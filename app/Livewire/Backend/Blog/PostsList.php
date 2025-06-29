<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

#[Layout('components.layouts.dashboard')]
#[Title('Blog Posts Management')]

class PostsList extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $status = '';

    #[Url]
    public $category = '';

    #[Url]
    public $author = '';

    #[Url]
    public $dateRange = '';

    #[Url]
    public $sortBy = 'created_at';

    #[Url]
    public $sortDirection = 'desc';

    #[Url]
    public $perPage = 10;

    public $selectedPosts = [];
    public $selectAll = false;
    public $bulkAction = '';
    public $showingFilters = false;

    // Confirmation states
    public $confirmingDeletion = false;
    public $postToDelete = null;
    public $confirmingBulkAction = false;

    // Quick edit states
    public $quickEditingPost = null;
    public $quickEditData = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'category' => ['except' => ''],
        'author' => ['except' => ''],
        'dateRange' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    public function mount()
    {
        // Check if user has permission to view posts
        if (!Gate::allows('blog.posts.view')) {
            abort(403, 'Unauthorized to view blog posts');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedAuthor()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // แก้ไข: ใช้ getPosts() แทน $this->posts
            $posts = $this->getPosts();
            $this->selectedPosts = $posts->pluck('id')->toArray();
        } else {
            $this->selectedPosts = [];
        }
    }

    public function updatedSelectedPosts()
    {
        // แก้ไข: ใช้ getPosts() แทน $this->posts
        $posts = $this->getPosts();
        $this->selectAll = count($this->selectedPosts) === $posts->count();
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

    public function toggleFilters()
    {
        $this->showingFilters = !$this->showingFilters;
    }

    public function clearFilters()
    {
        $this->reset([
            'search', 'status', 'category', 'author',
            'dateRange', 'sortBy', 'sortDirection'
        ]);
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function confirmDelete($postId)
    {
        if (!Gate::allows('blog.posts.delete')) {
            $this->addError('permission', 'You do not have permission to delete posts.');
            return;
        }

        $this->postToDelete = $postId;
        $this->confirmingDeletion = true;
    }

    public function deletePost()
    {
        if (!Gate::allows('blog.posts.delete') || !$this->postToDelete) {
            return;
        }

        $post = BlogPost::find($this->postToDelete);
        if ($post) {
            $post->delete();
            $this->dispatch('post-deleted', ['message' => 'Post deleted successfully']);
        }

        $this->confirmingDeletion = false;
        $this->postToDelete = null;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->postToDelete = null;
    }

    public function confirmBulkAction()
    {
        if (empty($this->selectedPosts) || empty($this->bulkAction)) {
            $this->addError('bulk', 'Please select posts and an action.');
            return;
        }

        $this->confirmingBulkAction = true;
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedPosts) || empty($this->bulkAction)) {
            return;
        }

        $posts = BlogPost::whereIn('id', $this->selectedPosts);
        $count = count($this->selectedPosts);

        switch ($this->bulkAction) {
            case 'delete':
                if (!Gate::allows('blog.posts.delete')) {
                    $this->addError('permission', 'You do not have permission to delete posts.');
                    return;
                }
                $posts->delete();
                $message = "$count posts deleted successfully";
                break;

            case 'publish':
                if (!Gate::allows('blog.posts.edit')) {
                    $this->addError('permission', 'You do not have permission to edit posts.');
                    return;
                }
                $posts->update(['status' => 'published', 'published_at' => now()]);
                $message = "$count posts published successfully";
                break;

            case 'draft':
                if (!Gate::allows('blog.posts.edit')) {
                    $this->addError('permission', 'You do not have permission to edit posts.');
                    return;
                }
                $posts->update(['status' => 'draft']);
                $message = "$count posts moved to draft";
                break;

            case 'archived':
                if (!Gate::allows('blog.posts.delete')) {
                    $this->addError('permission', 'You do not have permission to archive posts.');
                    return;
                }
                $posts->update(['status' => 'archived']);
                $message = "$count posts moved to archive";
                break;

            case 'trash':
                if (!Gate::allows('blog.posts.delete')) {
                    $this->addError('permission', 'You do not have permission to trash posts.');
                    return;
                }
                $posts->update(['status' => 'trashed']);
                $message = "$count posts moved to trash";
                break;

            default:
                $this->addError('bulk', 'Invalid bulk action.');
                return;
        }

        $this->selectedPosts = [];
        $this->selectAll = false;
        $this->bulkAction = '';
        $this->confirmingBulkAction = false;

        $this->dispatch('posts-updated', ['message' => $message]);
    }

    public function cancelBulkAction()
    {
        $this->confirmingBulkAction = false;
        $this->bulkAction = '';
    }

    public function duplicatePost($postId)
    {
        if (!Gate::allows('blog.posts.create')) {
            $this->addError('permission', 'You do not have permission to create posts.');
            return;
        }

        $post = BlogPost::find($postId);
        if ($post) {
            $newPost = $post->replicate();
            $newPost->title = $post->title . ' (Copy)';
            $newPost->slug = $post->slug . '-copy-' . time();
            $newPost->status = 'draft';
            $newPost->published_at = null;
            $newPost->save();

            // แก้ไข: ใช้ tags() แทน categories()
            $newPost->tags()->sync($post->tags->pluck('id'));

            $this->dispatch('post-duplicated', [
                'message' => 'Post duplicated successfully',
                'postId' => $newPost->id
            ]);
        }
    }

    public function toggleFeatured($postId)
    {
        if (!Gate::allows('blog.posts.edit')) {
            $this->addError('permission', 'You do not have permission to edit posts.');
            return;
        }

        $post = BlogPost::find($postId);
        if ($post) {
            $post->update(['is_featured' => !$post->is_featured]);
            $message = $post->is_featured ? 'Post marked as featured' : 'Post unmarked as featured';
            $this->dispatch('post-updated', ['message' => $message]);
        }
    }

    public function quickEdit($postId)
    {
        // แก้ไข: ใช้ category แทน categories
        $post = BlogPost::with('category')->find($postId);
        if ($post && Gate::allows('blog.posts.edit')) {
            $this->quickEditingPost = $postId;
            $this->quickEditData = [
                'title' => $post->title,
                'status' => $post->status,
                'is_featured' => $post->is_featured,
                'category_id' => $post->blog_category_id,
            ];
        }
    }

    public function saveQuickEdit()
    {
        if (!$this->quickEditingPost || !Gate::allows('blog.posts.edit')) {
            return;
        }

        $this->validate([
            'quickEditData.title' => 'required|string|max:255',
            'quickEditData.status' => 'required|in:draft,published,scheduled,trashed',
        ]);

        $post = BlogPost::find($this->quickEditingPost);
        if ($post) {
            $post->update([
                'title' => $this->quickEditData['title'],
                'status' => $this->quickEditData['status'],
                'is_featured' => $this->quickEditData['is_featured'] ?? false,
                'blog_category_id' => $this->quickEditData['category_id'] ?? null,
            ]);

            $this->dispatch('post-updated', ['message' => 'Post updated successfully']);
        }

        $this->cancelQuickEdit();
    }

    public function cancelQuickEdit()
    {
        $this->quickEditingPost = null;
        $this->quickEditData = [];
    }

    #[On('post-created')]
    #[On('post-updated')]
    #[On('post-deleted')]
    public function refreshPosts()
    {
        // This will trigger a re-render and refresh the posts list
        $this->resetPage();
    }

    // เพิ่ม method สำหรับดึง posts ที่ไม่ใช่ paginated
    private function getPosts()
    {
        return $this->getPostsQuery()->get();
    }

    public function render()
    {
        // ใช้ method แยกสำหรับ paginated results
        $posts = $this->getPostsQuery()->paginate($this->perPage);

        $categories = BlogCategory::select('id', 'name')->where('is_active', true)->get();

        // แก้ไข: ใช้ relationship ที่ถูกต้อง
        $authors = User::whereHas('blogPosts')->select('id', 'name')->get();

        $statusCounts = [
            'all' => BlogPost::count(),
            'published' => BlogPost::where('status', 'published')->count(),
            'draft' => BlogPost::where('status', 'draft')->count(),
            'scheduled' => BlogPost::where('status', 'scheduled')->count(),
            'trashed' => BlogPost::where('status', 'trashed')->count(),
        ];

        return view('livewire.backend.blog.posts-list', compact(
            'posts', 'categories', 'authors', 'statusCounts'
        ));
    }

    // แก้ไข: เปลี่ยนชื่อ method และ relationships
    protected function getPostsQuery()
    {
        $query = BlogPost::query()
            ->with([
                'user:id,name',  // แก้ไขจาก 'author' เป็น 'user'
                'category:id,name,slug,color',  // แก้ไขจาก 'categories' เป็น 'category'
                'tags:id,name,slug,color'  // เพิ่ม tags
            ])
            ->withCount(['comments'])  // ลบ views, likes ออกถ้าไม่มี relationship
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('content', 'like', '%' . $this->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function (Builder $query) {
                $query->where('status', $this->status);
            })
            ->when($this->category, function (Builder $query) {
                $query->where('blog_category_id', $this->category);  // แก้ไขให้ใช้ foreign key โดยตรง
            })
            ->when($this->author, function (Builder $query) {
                $query->where('user_id', $this->author);  // แก้ไขจาก 'author_id' เป็น 'user_id'
            })
            ->when($this->dateRange, function (Builder $query) {
                $dates = explode(' to ', $this->dateRange);
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        $dates[0] . ' 00:00:00',
                        $dates[1] . ' 23:59:59'
                    ]);
                }
            });

        return $query->orderBy($this->sortBy, $this->sortDirection);
    }
}
