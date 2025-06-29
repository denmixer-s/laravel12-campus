<?php

namespace App\Livewire\Backend\Blog;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\BlogPost;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

#[Layout('components.layouts.dashboard')]
#[Title('Media Upload')]
class MediaUpload extends Component
{
    use WithFileUploads, AuthorizesRequests;

    // File Upload Properties
    public $files = [];
    public $uploadProgress = [];
    public $uploadedFiles = [];
    public $failedFiles = [];

    // Upload Settings
    public $collection = 'default';
    public $allowedTypes = ['image', 'video', 'document', 'audio'];
    public $maxFileSize = 10240; // 10MB in KB
    public $maxFiles = 10;
    public $generateThumbnails = true;
    public $optimizeImages = true;

    // File Organization
    public $folder = '';
    public $tags = '';
    public $altText = '';
    public $caption = '';
    public $customProperties = [];

    // Upload Modes
    public $uploadMode = 'multiple'; // 'single', 'multiple', 'bulk'
    public $autoUpload = false;
    public $showPreview = true;

    // Progress & Status
    public $isUploading = false;
    public $uploadComplete = false;
    public $totalFiles = 0;
    public $processedFiles = 0;
    public $currentFile = '';

    // Available Collections
    public $collections = [
        'default' => 'General Media',
        'featured' => 'Featured Images',
        'gallery' => 'Gallery Images',
        'thumbnails' => 'Thumbnails',
        'documents' => 'Documents',
        'videos' => 'Videos',
        'audio' => 'Audio Files'
    ];

    // Supported File Types
    public $supportedTypes = [
        'image' => [
            'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'mimes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'maxSize' => 5120 // 5MB
        ],
        'video' => [
            'extensions' => ['mp4', 'webm', 'avi', 'mov', 'wmv'],
            'mimes' => ['video/mp4', 'video/webm', 'video/avi', 'video/quicktime'],
            'maxSize' => 51200 // 50MB
        ],
        'document' => [
            'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
            'mimes' => ['application/pdf', 'application/msword', 'text/plain'],
            'maxSize' => 10240 // 10MB
        ],
        'audio' => [
            'extensions' => ['mp3', 'wav', 'ogg', 'flac'],
            'mimes' => ['audio/mpeg', 'audio/wav', 'audio/ogg'],
            'maxSize' => 10240 // 10MB
        ]
    ];

    protected $rules = [
        'files.*' => 'file|max:51200', // 50MB max
        'collection' => 'required|string',
        'folder' => 'nullable|string|max:255',
        'tags' => 'nullable|string|max:500',
        'altText' => 'nullable|string|max:255',
        'caption' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'files.*.file' => 'Each upload must be a valid file.',
        'files.*.max' => 'File size cannot exceed 50MB.',
        'collection.required' => 'Please select a collection for your media.',
    ];

    public function mount()
    {
        $this->authorize('blog.media.manage');
    }

    public function render()
    {
        $recentUploads = $this->getRecentUploads();
        $storageStats = $this->getStorageStats();

        return view('livewire.backend.blog.media-upload', [
            'recentUploads' => $recentUploads,
            'storageStats' => $storageStats
        ]);
    }

    // File Upload Handlers
    public function updatedFiles()
    {
        $this->validateFiles();

        if ($this->autoUpload && !empty($this->files)) {
            $this->startUpload();
        }
    }

    public function validateFiles()
    {
        $this->failedFiles = [];
        $validFiles = [];

        foreach ($this->files as $index => $file) {
            $validation = $this->validateSingleFile($file);

            if ($validation['valid']) {
                $validFiles[] = $file;
            } else {
                $this->failedFiles[] = [
                    'file' => $file,
                    'errors' => $validation['errors']
                ];
            }
        }

        $this->files = $validFiles;
        $this->totalFiles = count($this->files);
    }

    private function validateSingleFile($file)
    {
        $errors = [];
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize() / 1024; // Convert to KB

        // Check file type
        $fileType = $this->getFileType($extension, $mimeType);
        if (!$fileType || !in_array($fileType, $this->allowedTypes)) {
            $errors[] = "File type '{$extension}' is not allowed.";
        }

        // Check file size
        if ($fileType && isset($this->supportedTypes[$fileType])) {
            $maxSize = $this->supportedTypes[$fileType]['maxSize'];
            if ($fileSize > $maxSize) {
                $errors[] = "File size ({$this->formatFileSize($fileSize * 1024)}) exceeds maximum allowed size ({$this->formatFileSize($maxSize * 1024)}).";
            }
        }

        // Check max files limit
        if (count($this->files) > $this->maxFiles) {
            $errors[] = "Maximum {$this->maxFiles} files allowed per upload.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'type' => $fileType
        ];
    }

    private function getFileType($extension, $mimeType)
    {
        foreach ($this->supportedTypes as $type => $config) {
            if (in_array($extension, $config['extensions']) ||
                in_array($mimeType, $config['mimes'])) {
                return $type;
            }
        }
        return null;
    }

    // Upload Process
    public function startUpload()
    {
        if (empty($this->files)) {
            $this->addError('files', 'Please select files to upload.');
            return;
        }

        $this->validate();
        $this->isUploading = true;
        $this->uploadComplete = false;
        $this->processedFiles = 0;
        $this->uploadedFiles = [];

        foreach ($this->files as $index => $file) {
            $this->currentFile = $file->getClientOriginalName();
            $this->uploadProgress[$index] = 0;

            try {
                $media = $this->processFile($file, $index);
                $this->uploadedFiles[] = $media;
                $this->uploadProgress[$index] = 100;
            } catch (\Exception $e) {
                $this->failedFiles[] = [
                    'file' => $file,
                    'errors' => [$e->getMessage()]
                ];
                $this->uploadProgress[$index] = 0;
            }

            $this->processedFiles++;
        }

        $this->isUploading = false;
        $this->uploadComplete = true;
        $this->currentFile = '';

        // Reset form if all files uploaded successfully
        if (empty($this->failedFiles)) {
            $this->resetUpload();
        }

        $this->dispatch('upload-completed', [
            'uploaded' => count($this->uploadedFiles),
            'failed' => count($this->failedFiles),
            'message' => $this->getUploadMessage()
        ]);
    }

    private function processFile($file, $index)
    {
        // Create a temporary model to attach media to
        // In a real application, you might attach to a specific model
        $model = new BlogPost(); // This is just for MediaLibrary compatibility

        // Store file
        $fileName = $this->generateFileName($file);
        $filePath = $file->storeAs('media', $fileName, 'public');

        // Create media record
        $mediaBuilder = $model->addMediaFromRequest('files.' . $index)
            ->setFileName($fileName)
            ->setName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        // Add custom properties
        $properties = [
            'alt_text' => $this->altText,
            'caption' => $this->caption,
            'tags' => $this->tags ? explode(',', $this->tags) : [],
            'folder' => $this->folder,
            'original_name' => $file->getClientOriginalName(),
            'file_type' => $this->getFileType(
                strtolower($file->getClientOriginalExtension()),
                $file->getMimeType()
            )
        ];

        $mediaBuilder->withCustomProperties($properties);

        // Add to specific collection
        $media = $mediaBuilder->toMediaCollection($this->collection);

        // Generate thumbnails for images
        if ($this->generateThumbnails && $this->isImage($file)) {
            $this->generateImageThumbnails($media);
        }

        // Optimize images
        if ($this->optimizeImages && $this->isImage($file)) {
            $this->optimizeImage($media);
        }

        return $media;
    }

    private function generateFileName($file)
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $random = Str::random(8);

        return "{$timestamp}-{$random}.{$extension}";
    }

    private function isImage($file)
    {
        $imageTypes = $this->supportedTypes['image']['mimes'];
        return in_array($file->getMimeType(), $imageTypes);
    }

    private function generateImageThumbnails($media)
    {
        try {
            // Generate different thumbnail sizes
            $media->addMediaConversion('thumb')
                  ->width(300)
                  ->height(300)
                  ->sharpen(10);

            $media->addMediaConversion('medium')
                  ->width(600)
                  ->height(600)
                  ->quality(80);

            $media->addMediaConversion('large')
                  ->width(1200)
                  ->height(1200)
                  ->quality(85);
        } catch (\Exception $e) {
            logger()->error('Failed to generate thumbnails: ' . $e->getMessage());
        }
    }

    private function optimizeImage($media)
    {
        try {
            $path = $media->getPath();
            if (file_exists($path)) {
                $image = Image::make($path);

                // Optimize quality based on file size
                $quality = $image->filesize() > 1000000 ? 75 : 85; // 1MB threshold

                $image->save($path, $quality);
            }
        } catch (\Exception $e) {
            logger()->error('Failed to optimize image: ' . $e->getMessage());
        }
    }

    // UI Actions
    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            unset($this->uploadProgress[$index]);
            $this->files = array_values($this->files); // Reindex array
            $this->totalFiles = count($this->files);
        }
    }

    public function clearAll()
    {
        $this->reset([
            'files', 'uploadProgress', 'uploadedFiles', 'failedFiles',
            'isUploading', 'uploadComplete', 'processedFiles', 'currentFile'
        ]);
    }

    public function resetUpload()
    {
        $this->reset([
            'files', 'uploadProgress', 'totalFiles', 'processedFiles',
            'currentFile', 'tags', 'altText', 'caption'
        ]);
    }

    public function retryFailed()
    {
        $failedFiles = $this->failedFiles;
        $this->failedFiles = [];

        // Re-add failed files to upload queue
        foreach ($failedFiles as $failed) {
            $this->files[] = $failed['file'];
        }

        $this->validateFiles();

        if (!empty($this->files)) {
            $this->startUpload();
        }
    }

    // Settings Updates
    public function updatedCollection()
    {
        // Update max file settings based on collection
        switch ($this->collection) {
            case 'featured':
                $this->uploadMode = 'single';
                $this->allowedTypes = ['image'];
                break;
            case 'documents':
                $this->allowedTypes = ['document'];
                break;
            case 'videos':
                $this->allowedTypes = ['video'];
                break;
            case 'audio':
                $this->allowedTypes = ['audio'];
                break;
            default:
                $this->allowedTypes = ['image', 'video', 'document', 'audio'];
        }
    }

    public function toggleAutoUpload()
    {
        $this->autoUpload = !$this->autoUpload;
    }

    public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
    }

    public function toggleImageOptimization()
    {
        $this->optimizeImages = !$this->optimizeImages;
    }

    public function toggleThumbnailGeneration()
    {
        $this->generateThumbnails = !$this->generateThumbnails;
    }

    // Helper Methods
    private function getUploadMessage()
    {
        $uploaded = count($this->uploadedFiles);
        $failed = count($this->failedFiles);

        if ($failed === 0) {
            return "Successfully uploaded {$uploaded} file(s).";
        } elseif ($uploaded === 0) {
            return "Failed to upload {$failed} file(s).";
        } else {
            return "Uploaded {$uploaded} file(s), {$failed} failed.";
        }
    }

    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function getRecentUploads()
    {
        // Return recent media uploads
        // This would typically query your media table
        return collect(); // Placeholder
    }

    private function getStorageStats()
    {
        // Calculate storage usage statistics
        return [
            'total_files' => 0,
            'total_size' => 0,
            'by_type' => [
                'images' => 0,
                'videos' => 0,
                'documents' => 0,
                'audio' => 0
            ]
        ];
    }

    public function getFilePreview($file)
    {
        try {
            if ($this->isImage($file)) {
                return $file->temporaryUrl();
            }
        } catch (\Exception $e) {
            // If temporary URL fails, return null
            return null;
        }

        return null;
    }

    public function getFileIcon($file)
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());
            $fileType = $this->getFileType($extension, $file->getMimeType());

            $icons = [
                'image' => 'photo',
                'video' => 'video-camera',
                'document' => 'document-text',
                'audio' => 'music-note'
            ];

            return $icons[$fileType] ?? 'document';
        } catch (\Exception $e) {
            return 'document';
        }
    }
}
