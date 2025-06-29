<?php
namespace App\Livewire\Backend\Document;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.dashboard')]
#[Title('เพิ่มเอกสารใหม่')]
class DocumentCreate extends Component
{
    use WithFileUploads;

    // Basic document properties
    public $title            = '';
    public $description      = '';
    public $keywords         = '';
    public $reference_number = '';

    // Document metadata
    public $document_category_id = '';
    public $document_type_id     = '';
    public $department_id        = '';
    public $document_date        = '';

    // Document settings
    public $status             = 'draft';
    public $access_level       = 'public';
    public $is_featured        = false;
    public $is_new             = true;
    public $version            = '1.0';
    public $parent_document_id = '';

    // Tags (JSON array)
    public $tags   = [];
    public $newTag = '';

    // File upload
    public $documentFile;

    // Publishing options
    public $published_at = '';

    // UI state
    public $isProcessing       = false;
    public $isDraftSaving      = false;
    public $showSuccessMessage = false;
    public $successMessage     = '';
    public $lastSavedAt        = null;

    // Auto-save
    public $autoSaveEnabled  = true;
    public $autoSaveInterval = 30; // seconds

    // Computed properties for UI
    public $availableCategories;
    public $availableTypes;
    public $availableDepartments;
    public $availableParentDocuments;

    protected function rules()
    {
        $rules = [
            'title'                => 'required|string|max:500',
            'description'          => 'nullable|string',
            'keywords'             => 'nullable|string',
            'reference_number'     => 'nullable|string|max:100',
            'document_category_id' => 'required|exists:document_categories,id',
            'document_type_id'     => 'required|exists:document_types,id',
            'document_date'        => 'required|date',
            'status'               => 'required|in:draft,published,archived',
            'access_level'         => 'required|in:public,registered',
            'version'              => 'required|string|max:10',
            'parent_document_id'   => 'nullable|exists:documents,id',
            'tags'                 => 'array',
            'tags.*'               => 'string|max:50',
            'is_featured'          => 'boolean',
            'is_new'               => 'boolean',
            'documentFile'         => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:51200', // 50MB
        ];

        // Only validate department_id if user can select department
        if ($this->canSelectDepartment) {
            $rules['department_id'] = 'nullable|exists:departments,id';
        }

        // Published documents need published_at
        if ($this->status === 'published') {
            $rules['published_at'] = 'nullable|date';
        }

        return $rules;
    }

    protected $messages = [
        'title.required'                => 'กรุณากรอกชื่อเอกสาร',
        'title.max'                     => 'ชื่อเอกสารต้องไม่เกิน 500 ตัวอักษร',
        'document_category_id.required' => 'กรุณาเลือกหมวดหมู่เอกสาร',
        'document_category_id.exists'   => 'หมวดหมู่เอกสารที่เลือกไม่ถูกต้อง',
        'document_type_id.required'     => 'กรุณาเลือกประเภทเอกสาร',
        'document_type_id.exists'       => 'ประเภทเอกสารที่เลือกไม่ถูกต้อง',
        'department_id.exists'          => 'หน่วยงานที่เลือกไม่ถูกต้อง',
        'document_date.required'        => 'กรุณาระบุวันที่เอกสาร',
        'document_date.date'            => 'รูปแบบวันที่ไม่ถูกต้อง',
        'version.required'              => 'กรุณาระบุเวอร์ชันเอกสาร',
        'documentFile.required'         => 'กรุณาเลือกไฟล์เอกสาร',
        'documentFile.file'             => 'ไฟล์ที่อัพโหลดไม่ถูกต้อง',
        'documentFile.mimes'            => 'ไฟล์ต้องเป็นประเภท: PDF, Word, Excel, PowerPoint เท่านั้น',
        'documentFile.max'              => 'ไฟล์ต้องมีขนาดไม่เกิน 50 MB',
    ];

    // Real-time validation
    public function updatedTitle()
    {
        $this->validateOnly('title');
        $this->scheduleAutoSave();
    }

    public function updatedDescription()
    {
        $this->validateOnly('description');
        $this->scheduleAutoSave();
    }

    public function updatedDocumentCategoryId()
    {
        $this->validateOnly('document_category_id');
        $this->scheduleAutoSave();
    }

    public function updatedDocumentTypeId()
    {
        $this->validateOnly('document_type_id');
        $this->scheduleAutoSave();
    }

    public function updatedDepartmentId()
    {
        // Only validate if user can select department
        if ($this->canSelectDepartment()) {
            $this->validateOnly('department_id');
        }
        $this->scheduleAutoSave();
    }

    public function updatedDocumentDate()
    {
        $this->validateOnly('document_date');
        $this->scheduleAutoSave();
    }

    public function updatedStatus()
    {
        $this->validateOnly('status');

        // Set published_at when publishing
        if ($this->status === 'published' && empty($this->published_at)) {
            $this->published_at = now()->format('Y-m-d H:i');
        }
    }

    public function updatedDocumentFile()
    {
        $this->validateOnly('documentFile');
    }

    // Toggle methods
    public function toggleFeatured()
    {
        $this->is_featured = ! $this->is_featured;
        $this->scheduleAutoSave();
    }

    public function toggleNew()
    {
        $this->is_new = ! $this->is_new;
        $this->scheduleAutoSave();
    }

    public function toggleAutoSave()
    {
        $this->autoSaveEnabled = ! $this->autoSaveEnabled;
    }

    // Tag management
    public function addTag()
    {
        if (empty(trim($this->newTag))) {
            return;
        }

        $tagName = trim($this->newTag);

        // Add to tags array if not already there
        if (! in_array($tagName, $this->tags)) {
            $this->tags[] = $tagName;
        }

        $this->newTag = '';
        $this->scheduleAutoSave();
    }

    public function removeTag($index)
    {
        if (isset($this->tags[$index])) {
            unset($this->tags[$index]);
            $this->tags = array_values($this->tags);
            $this->scheduleAutoSave();
        }
    }

    // File management
    public function removeDocumentFile()
    {
        $this->documentFile = null;
        $this->resetValidation('documentFile');
    }

    // Auto-save functionality
    public function scheduleAutoSave()
    {
        if (! $this->autoSaveEnabled) {
            return;
        }

        $this->dispatch('scheduleAutoSave', [
            'interval' => $this->autoSaveInterval * 1000, // Convert to milliseconds
        ]);
    }

    public function autoSaveDraft()
    {
        if ($this->isDraftSaving || $this->isProcessing) {
            return;
        }

        // Don't auto-save if no meaningful content
        if (empty(trim($this->title))) {
            return;
        }

        $this->isDraftSaving = true;

        try {
            // Basic validation for auto-save
            $this->validate([
                'title'       => 'nullable|string|max:500',
                'description' => 'nullable|string',
            ]);

            Log::info('Auto-save draft attempted', [
                'title'              => $this->title,
                'description_length' => strlen($this->description),
            ]);

            $this->lastSavedAt = now();
            $this->dispatch('autoSaveSuccess');

        } catch (\Exception $e) {
            Log::error('Auto-save failed', ['error' => $e->getMessage()]);
            $this->dispatch('autoSaveFailed');
        } finally {
            $this->isDraftSaving = false;
        }
    }

    // Main create method
    public function create()
    {
        Log::info('=== DocumentCreate: START ===');

        $this->isProcessing = true;

        try {
            // Validate all form data
            $this->validate();

            Log::info('DocumentCreate: Validation passed', [
                'title'       => $this->title,
                'status'      => $this->status,
                'category_id' => $this->document_category_id,
                'type_id'     => $this->document_type_id,
                'has_file'    => $this->documentFile !== null,
            ]);

            $document = null;

            // Create document and handle file upload in transaction
            DB::transaction(function () use (&$document) {
                // Generate document number
                $documentNumber = $this->generateDocumentNumber();

                // Prepare document data
                $documentData = [
                    'document_number'      => $documentNumber,
                    'title'                => trim($this->title),
                    'description'          => $this->description,
                    'keywords'             => $this->keywords,
                    'reference_number'     => $this->reference_number,
                    'document_category_id' => $this->document_category_id,
                    'document_type_id'     => $this->document_type_id,
                    'department_id'        => $this->canSelectDepartment ?
                    ($this->department_id ?: auth()->user()->department_id):
                    auth()->user()->department_id,
                    'created_by'           => auth()->id(),
                    'document_date'        => Carbon::parse($this->document_date),
                    'status'               => $this->status,
                    'access_level'         => $this->access_level,
                    'version'              => $this->version,
                    'parent_document_id'   => $this->parent_document_id ?: null,
                    'tags'                 => $this->tags,
                    'is_featured'          => $this->is_featured,
                    'is_new'               => $this->is_new,
                    'download_count'       => 0,
                    'view_count'           => 0,
                ];

                // Set publishing dates
                if ($this->status === 'published') {
                    $documentData['published_at'] = $this->published_at ?
                    Carbon::parse($this->published_at) : now();
                }

                // Handle file metadata
                if ($this->documentFile) {
                    $documentData['file_size']         = $this->documentFile->getSize();
                    $documentData['mime_type']         = $this->documentFile->getMimeType();
                    $documentData['original_filename'] = $this->documentFile->getClientOriginalName();
                }

                // Create the document
                $document = Document::create($documentData);

                Log::info('DocumentCreate: Document created in database', [
                    'document_id'     => $document->id,
                    'document_number' => $document->document_number,
                    'title'           => $document->title,
                    'status'          => $document->status,
                ]);

                // Handle file upload
                if ($this->documentFile) {
                    $this->uploadDocumentFile($document);
                }
            });

            // Success
            $this->showSuccessMessage = true;
            $this->successMessage     = "เอกสาร '{$this->title}' ถูกสร้างเรียบร้อยแล้ว!";
            session()->flash('success', "เอกสาร '{$this->title}' ถูกสร้างเรียบร้อยแล้ว!");

            Log::info('=== DocumentCreate: SUCCESS ===', [
                'document_id' => $document->id,
                'status'      => $document->status,
            ]);

            return $this->redirect(route('administrator.documents.index'), navigate: true);

        } catch (\Exception $e) {
            Log::error('=== DocumentCreate: FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'เกิดข้อผิดพลาดในการสร้างเอกสาร: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Save as draft method
    public function saveDraft()
    {
        $originalStatus = $this->status;
        $this->status   = 'draft';

        $result = $this->create();

        if (! $result) {
            $this->status = $originalStatus;
        }

        return $result;
    }

    // Generate document number
    private function generateDocumentNumber()
    {
        $prefix = 'DOC';
        $year   = date('Y');
        $month  = date('m');

                                                // ใช้ timestamp เพื่อให้ unique
        $timestamp = now()->format('His');      // HHMMSS
        $micro     = substr(microtime(), 2, 2); // เอา 2 หลักจาก microseconds

        // รูปแบบ: DOC + ปี + เดือน + ชั่วโมง + นาที + วินาที + microsec (2 หลัก)
        return sprintf('%s%s%s%s%s', $prefix, $year, $month, $timestamp, $micro);
    }

    // File upload helper
    private function uploadDocumentFile($document = null)
    {
        try {
            Log::info('Uploading document file');

            // ใช้ $this->document สำหรับ Edit หรือ $document สำหรับ Create
            $targetDocument = $document ?? $this->document;

            // สร้าง hash filename
            $originalName = $this->documentFile->getClientOriginalName();
            $extension    = $this->documentFile->getClientOriginalExtension();
            $hashName     = md5($originalName . time() . uniqid()) . '.' . $extension;

            $media = $targetDocument->addMedia($this->documentFile->getRealPath())
                ->usingName($this->title)
                ->usingFileName($hashName)
                ->withCustomProperties([
                    'original_filename' => $originalName,
                ])
                ->toMediaCollection('documents');

            Log::info('Document file uploaded successfully', [
                'media_id'          => $media->id,
                'hash_filename'     => $media->file_name,
                'original_filename' => $originalName,
            ]);

        } catch (\Exception $e) {
            Log::error('Document file upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->title                = '';
        $this->description          = '';
        $this->keywords             = '';
        $this->reference_number     = '';
        $this->document_category_id = '';
        $this->document_type_id     = '';
        $this->department_id        = '';
        $this->document_date        = '';
        $this->status               = 'draft';
        $this->access_level         = 'public';
        $this->version              = '1.0';
        $this->parent_document_id   = '';
        $this->tags                 = [];
        $this->documentFile         = null;
        $this->is_featured          = false;
        $this->is_new               = true;
        $this->published_at         = '';
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.documents.index'), navigate: true);
    }

    // Computed properties
    public function getCanCreateProperty()
    {
        return ! $this->isProcessing &&
        ! empty(trim($this->title)) &&
        ! empty($this->document_category_id) &&
        ! empty($this->document_type_id) &&
        ! empty($this->document_date) &&
        $this->documentFile !== null;
    }

    public function getCanSelectDepartmentProperty()
    {
        // Only admin can select department
        return auth()->user() && auth()->user()->can('documents.manage-all-departments');
    }

    public function canSelectDepartment()
    {
        return $this->canSelectDepartment;
    }

    public function getCurrentUserDepartmentProperty()
    {
        return auth()->user()?->department;
    }

    public function getProgressProperty()
    {
        $progress = 0;
        $fields   = [
            'title'                => 20,
            'document_category_id' => 15,
            'document_type_id'     => 15,
            'document_date'        => 10,
            'documentFile'         => 25,
            'description'          => 10,
            'tags'                 => 5,
        ];

        foreach ($fields as $field => $weight) {
            if ($field === 'tags' && ! empty($this->tags)) {
                $progress += $weight;
            } elseif ($field === 'documentFile' && $this->documentFile) {
                $progress += $weight;
            } elseif (in_array($field, ['document_category_id', 'document_type_id']) && ! empty($this->$field)) {
                $progress += $weight;
            } elseif (is_string($this->$field) && ! empty(trim($this->$field))) {
                $progress += $weight;
            }
        }

        return $progress;
    }

    public function getFileInfoProperty()
    {
        if (! $this->documentFile) {
            return null;
        }

        return [
            'name' => $this->documentFile->getClientOriginalName(),
            'size' => $this->formatFileSize($this->documentFile->getSize()),
            'type' => $this->documentFile->getMimeType(),
        ];
    }

    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    // Data loading
    private function loadCategories()
    {
        $this->availableCategories = DocumentCategory::active()
            ->ordered()
            ->get();
    }

    private function loadTypes()
    {
        $this->availableTypes = DocumentType::active()
            ->orderBy('name')
            ->get();
    }

    private function loadDepartments()
    {
        $this->availableDepartments = Department::active()
            ->orderBy('name')
            ->get();
    }

    private function loadParentDocuments()
    {
        $this->availableParentDocuments = Document::published()
            ->orderBy('title')
            ->get();
    }

    // Component lifecycle
    public function mount()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('documents.create');

        $this->loadCategories();
        $this->loadTypes();
        $this->loadDepartments();
        $this->loadParentDocuments();

        // Set defaults
        $this->document_date = now()->format('Y-m-d');
        $this->published_at  = now()->format('Y-m-d H:i');

        // Set department based on user role
        if ($this->canSelectDepartment) {
            // Admin can leave it empty to select later
            $this->department_id = '';
        } else {
            // Non-admin gets their department automatically
            $this->department_id = auth()->user()->department_id;
        }
    }

    public function render()
    {
        return view('livewire.backend.document.document-create', [
            'categories'      => $this->availableCategories,
            'types'           => $this->availableTypes,
            'departments'     => $this->availableDepartments,
            'parentDocuments' => $this->availableParentDocuments,
        ]);
    }
}
