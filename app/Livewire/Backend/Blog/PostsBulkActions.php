<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.dashboard')]
class PostsBulkActions extends Component
{
    use WithPagination;

    // Bulk action properties
    public array $selectedPosts = [];
    public bool $selectAll = false;
    public string $bulkAction = '';

    // Bulk action data
    #[Validate('nullable|exists:blog_categories,id')]
    public ?int $bulkCategoryId = null;

    public array $bulkTagIds = [];

    #[Validate('nullable|in:draft,published,scheduled,archived')]
    public string $bulkStatus = '';

    public string $bulkDeleteReason = '';

    // Filter & Search
    public string $search = '';
    public string $filterStatus = '';
    public ?int $filterCategoryId = null;
    public ?int $filterAuthorId = null;
    public array $filterTagIds = [];
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    // UI state
    public bool $showBulkPanel = false;
    public bool $confirmingBulkAction = false;
    public string $confirmMessage = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterCategoryId' => ['except' => null],
        'filterAuthorId' => ['except' => null],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->authorize('blog.posts.view');
    }

    #[Computed]
    public function posts()
    {
        return BlogPost::query()
            ->with(['category', 'tags', 'author', 'media'])
            ->when($this->search, fn($query) =>
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->filterStatus, fn($query) =>
                $query->where('status', $this->filterStatus)
            )
            ->when($this->filterCategoryId, fn($query) =>
                $query->where('category_id', $this->filterCategoryId)
            )
            ->when($this->filterAuthorId, fn($query) =>
                $query->where('author_id', $this->filterAuthorId)
            )
            ->when($this->filterTagIds, fn($query) =>
                $query->whereHas('tags', fn($q) =>
                    $q->whereIn('blog_tags.id', $this->filterTagIds)
                )
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function categories()
    {
        return BlogCategory::orderBy('name')->get();
    }

    #[Computed]
    public function tags()
    {
        return BlogTag::orderBy('name')->get();
    }

    #[Computed]
    public function selectedPostsCount()
    {
        return count($this->selectedPosts);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPosts = $this->posts->pluck('id')->toArray();
        } else {
            $this->selectedPosts = [];
        }
        $this->updateBulkPanelVisibility();
    }

    public function updatedSelectedPosts()
    {
        $this->selectAll = count($this->selectedPosts) === $this->posts->count();
        $this->updateBulkPanelVisibility();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->selectedPosts = [];
        $this->selectAll = false;
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
        $this->selectedPosts = [];
        $this->selectAll = false;
    }

    public function updatedFilterCategoryId()
    {
        $this->resetPage();
        $this->selectedPosts = [];
        $this->selectAll = false;
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    private function updateBulkPanelVisibility()
    {
        $this->showBulkPanel = count($this->selectedPosts) > 0;
    }

    public function setBulkAction($action)
    {
        $this->bulkAction = $action;

        // Set confirmation message
        $this->confirmMessage = match($action) {
            'delete' => "คุณแน่ใจหรือไม่ที่จะลบโพสต์ {$this->selectedPostsCount} รายการ?",
            'publish' => "เผยแพร่โพสต์ {$this->selectedPostsCount} รายการ?",
            'unpublish' => "ยกเลิกเผยแพร่โพสต์ {$this->selectedPostsCount} รายการ?",
            'archive' => "เก็บโพสต์ {$this->selectedPostsCount} รายการเข้าคลัง?",
            'restore' => "กู้คืนโพสต์ {$this->selectedPostsCount} รายการ?",
            'change_category' => "เปลี่ยนหมวดหมู่สำหรับโพสต์ {$this->selectedPostsCount} รายการ?",
            'add_tags' => "เพิ่มแท็กให้โพสต์ {$this->selectedPostsCount} รายการ?",
            'remove_tags' => "ลบแท็กจากโพสต์ {$this->selectedPostsCount} รายการ?",
            default => "ดำเนินการกับโพสต์ {$this->selectedPostsCount} รายการ?"
        };

        $this->confirmingBulkAction = true;
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedPosts) || empty($this->bulkAction)) {
            return;
        }

        $posts = BlogPost::whereIn('id', $this->selectedPosts);

        try {
            switch ($this->bulkAction) {
                case 'delete':
                    $this->authorize('blog.posts.delete');
                    $posts->delete();
                    $message = "ลบโพสต์ {$this->selectedPostsCount} รายการเรียบร้อยแล้ว";
                    break;

                case 'publish':
                    $this->authorize('blog.posts.edit');
                    $posts->update(['status' => 'published', 'published_at' => now()]);
                    $message = "เผยแพร่โพสต์ {$this->selectedPostsCount} รายการเรียบร้อยแล้ว";
                    break;

                case 'unpublish':
                    $this->authorize('blog.posts.edit');
                    $posts->update(['status' => 'draft']);
                    $message = "ยกเลิกเผยแพร่โพสต์ {$this->selectedPostsCount} รายการเรียบร้อยแล้ว";
                    break;

                case 'archive':
                    $this->authorize('blog.posts.edit');
                    $posts->update(['status' => 'archived']);
                    $message = "เก็บโพสต์ {$this->selectedPostsCount} รายการเข้าคลังเรียบร้อยแล้ว";
                    break;

                case 'change_category':
                    $this->authorize('blog.posts.edit');
                    $this->validate(['bulkCategoryId' => 'required|exists:blog_categories,id']);
                    $posts->update(['category_id' => $this->bulkCategoryId]);
                    $message = "เปลี่ยนหมวดหมู่โพสต์ {$this->selectedPostsCount} รายการเรียบร้อยแล้ว";
                    break;

                case 'add_tags':
                    $this->authorize('blog.posts.edit');
                    if (!empty($this->bulkTagIds)) {
                        foreach ($posts->get() as $post) {
                            $post->tags()->syncWithoutDetaching($this->bulkTagIds);
                        }
                        $message = "เพิ่มแท็กให้โพสต์ {$this->selectedPostsCount} รายการเรียบร้อยแล้ว";
                    }
                    break;

                case 'remove_tags':
                    $this->authorize('blog.posts.edit');
                    if (!empty($this->bulkTagIds)) {
                        foreach ($posts->get() as $post) {
                            $post->tags()->detach($this->bulkTagIds);
                        }
                        $message = "ลบแท็กจากโพสต์ {$this->selectedPostsCount} รายการเรียบร้อยแล้ว";
                    }
                    break;

                default:
                    throw new \InvalidArgumentException('Invalid bulk action');
            }

            session()->flash('success', $message);

        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }

        $this->resetBulkAction();
    }

    public function cancelBulkAction()
    {
        $this->confirmingBulkAction = false;
        $this->bulkAction = '';
    }

    private function resetBulkAction()
    {
        $this->selectedPosts = [];
        $this->selectAll = false;
        $this->showBulkPanel = false;
        $this->confirmingBulkAction = false;
        $this->bulkAction = '';
        $this->bulkCategoryId = null;
        $this->bulkTagIds = [];
        $this->bulkStatus = '';
        $this->bulkDeleteReason = '';
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterCategoryId = null;
        $this->filterAuthorId = null;
        $this->filterTagIds = [];
        $this->resetPage();
    }

    #[On('post-updated')]
    public function refreshPosts()
    {
        $this->resetBulkAction();
    }

    public function render()
    {
        return view('livewire.backend.blog.posts-bulk-actions');
    }
}
