<?php

namespace App\Livewire\Sakon\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Pages')]
class ListPage extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $imageFilter = '';
    public $userFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $confirmingPageDeletion = false;
    public $pageToDelete = null;
    public $pageToDeleteTitle = '';

    // Real-time listeners
    protected $listeners = [
        'pageCreated' => 'handlePageCreated',
        'pageUpdated' => 'handlePageUpdated',
        'pageDeleted' => 'handlePageDeleted',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    // Search and filter updates
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedImageFilter()
    {
        $this->resetPage();
    }

    public function updatedUserFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Sorting functionality
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // Real-time event handlers
    public function handlePageCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Page created successfully!');
    }

    public function handlePageUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Page updated successfully!');
    }

    public function handlePageDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Page deleted successfully!');
    }

    // Navigation methods
    public function createPage()
    {
        return $this->redirect(route('administrator.pages.create'), navigate: true);
    }

    public function viewPage($pageId)
    {
        return $this->redirect(route('administrator.pages.show', $pageId), navigate: true);
    }

    public function editPage($pageId)
    {
        return $this->redirect(route('administrator.pages.edit', $pageId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($pageId)
    {
        Log::info('confirmDelete called with pageId: ' . $pageId);

        try {
            $page = Page::findOrFail($pageId);
            Log::info('Page found: ' . $page->title);

            // Set the properties for the modal
            $this->pageToDelete = $pageId;
            $this->pageToDeleteTitle = $page->title;
            $this->confirmingPageDeletion = true;

            Log::info('Modal should open now', [
                'pageToDelete' => $this->pageToDelete,
                'pageToDeleteTitle' => $this->pageToDeleteTitle,
                'confirmingPageDeletion' => $this->confirmingPageDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        Log::info('delete method called');

        if (!$this->pageToDelete) {
            session()->flash('error', 'No page selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $page = Page::findOrFail($this->pageToDelete);
            $pageTitle = $page->title;

            Log::info('Deleting page: ' . $pageTitle);

            // Delete the page with media
            DB::transaction(function () use ($page) {
                // Delete associated media from all collections
                $page->clearMediaCollection('featured_image');
                $page->clearMediaCollection('gallery_images');

                // Delete the page
                $page->delete();
            });

            Log::info('Page deleted successfully: ' . $pageTitle);

            session()->flash('success', "Page '{$pageTitle}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting page: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete page: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingPageDeletion = false;
        $this->pageToDelete = null;
        $this->pageToDeleteTitle = '';
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->imageFilter = '';
        $this->userFilter = '';
        $this->resetPage();
    }

    // Helper method to check if user can delete page
    public function canUserDeletePage($page)
    {
        // Check if user has permission (you can customize this logic)
        return auth()->user()->hasRole('Super Admin') ||
               auth()->user()->can('delete-page') ||
               $page->user_id === auth()->id();
    }

    // Data fetching
    public function getPagesProperty()
    {
        try {
            return Page::with(['user', 'media'])
                ->when($this->search, function ($query) {
                    $query->search($this->search);
                })
                ->when($this->imageFilter === 'with_featured', function ($query) {
                    $query->withFeaturedImage();
                })
                ->when($this->imageFilter === 'with_gallery', function ($query) {
                    $query->withGalleryImages();
                })
                ->when($this->imageFilter === 'no_images', function ($query) {
                    $query->whereDoesntHave('media');
                })
                ->when($this->userFilter, function ($query) {
                    $query->byUser($this->userFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading pages', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading pages. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    // Get filter options
    public function getImageFilterOptionsProperty()
    {
        return [
            '' => 'All Pages',
            'with_featured' => 'With Featured Image',
            'with_gallery' => 'With Gallery Images',
            'no_images' => 'No Images',
        ];
    }

    public function getUserFilterOptionsProperty()
    {
        try {
            $users = \App\Models\User::select('id', 'name')
                ->whereHas('pages')
                ->orderBy('name')
                ->get();

            $options = ['' => 'All Users'];
            foreach ($users as $user) {
                $options[$user->id] = $user->name;
            }
            return $options;
        } catch (\Exception $e) {
            Log::error('Error loading user filter options', ['error' => $e->getMessage()]);
            return ['' => 'All Users'];
        }
    }

    // Get statistics
    public function getStatsProperty()
    {
        try {
            $totalPages = Page::count();
            $myPages = Page::where('user_id', auth()->id())->count();
            $pagesWithFeatured = Page::withFeaturedImage()->count();
            $pagesWithGallery = Page::withGalleryImages()->count();

            return [
                'total' => $totalPages,
                'mine' => $myPages,
                'with_featured' => $pagesWithFeatured,
                'with_gallery' => $pagesWithGallery,
            ];
        } catch (\Exception $e) {
            Log::error('Error loading stats', ['error' => $e->getMessage()]);
            return [
                'total' => 0,
                'mine' => 0,
                'with_featured' => 0,
                'with_gallery' => 0,
            ];
        }
    }

    // Get sort icon for table headers
    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4'; // Sort icon
        }

        return $this->sortDirection === 'asc'
            ? 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12' // Sort up
            : 'M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4'; // Sort down
    }

    // Preview page content
    public function getContentPreview($content, $limit = 150)
    {
        $stripped = strip_tags($content);
        return strlen($stripped) > $limit ? substr($stripped, 0, $limit) . '...' : $stripped;
    }

    // Format page URL
    public function getPageUrl($slug)
    {
        return url('/' . $slug);
    }

    public function render()
    {
        return view('livewire.sakon.pages.list-page', [
            'pages' => $this->pages,
            'imageFilterOptions' => $this->imageFilterOptions,
            'userFilterOptions' => $this->userFilterOptions,
            'stats' => $this->stats,
        ]);
    }
}
