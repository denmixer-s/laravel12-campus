<?php
namespace App\Livewire\Backend\Blog;

use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
#[Title('Comments Moderation')]
class CommentsModerate extends Component
{
    use WithPagination, AuthorizesRequests;

    // Filters & Search
    public $search       = '';
    public $statusFilter = 'all';
    public $postFilter   = 'all';
    public $sortBy       = 'created_at';
    public $sortDir      = 'desc';
    public $perPage      = 15;

    // Bulk Actions
    public $selectedComments = [];
    public $selectAll        = false;
    public $bulkAction       = '';

    // Modal States
    public $showBulkModal     = false;
    public $bulkConfirmAction = '';
    public $bulkConfirmText   = '';

    // Comment Details
    public $selectedComment  = null;
    public $showCommentModal = false;
    public $replyText        = '';

    // Available statuses
    public $statuses = [
        'all'      => 'All Comments',
        'pending'  => 'Pending Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'spam'     => 'Spam',
    ];

    protected $queryString = [
        'search'       => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'postFilter'   => ['except' => 'all'],
        'sortBy'       => ['except' => 'created_at'],
        'sortDir'      => ['except' => 'desc'],
        'page'         => ['except' => 1],
    ];

    public function mount()
    {
        $this->authorize('blog.comments.view');
    }

    public function render()
    {
        $query = BlogComment::with(['post', 'user', 'parent', 'approvedBy'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('content', 'like', '%' . $this->search . '%')
                        ->orWhere('guest_name', 'like', '%' . $this->search . '%')
                        ->orWhere('guest_email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('post', function ($q) {
                            $q->where('title', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->postFilter !== 'all', function ($q) {
                $q->where('blog_post_id', $this->postFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir);

        $comments = $query->paginate($this->perPage);

        // Get posts for filter dropdown
        $posts = BlogPost::select('id', 'title')
            ->whereHas('comments')
            ->orderBy('title')
            ->get();

        // Get stats
        $stats = [
            'total'    => BlogComment::count(),
            'pending'  => BlogComment::where('status', 'pending')->count(),
            'approved' => BlogComment::where('status', 'approved')->count(),
            'rejected' => BlogComment::where('status', 'rejected')->count(),
            'spam'     => BlogComment::where('status', 'spam')->count(),
        ];

        return view('livewire.backend.blog.comments-moderate', [
            'comments' => $comments,
            'posts'    => $posts,
            'stats'    => $stats,
        ]);
    }

    // Search & Filtering
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPostFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $field;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'postFilter']);
        $this->resetPage();
    }

    // Selection Management
    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedComments = $this->getCurrentPageCommentIds();
        } else {
            $this->selectedComments = [];
        }
    }

    public function updatedSelectedComments()
    {
        $currentPageIds  = $this->getCurrentPageCommentIds();
        $this->selectAll = count($this->selectedComments) === count($currentPageIds)
        && count(array_diff($currentPageIds, $this->selectedComments)) === 0;
    }

    private function getCurrentPageCommentIds()
    {
        return BlogComment::with(['post', 'user', 'parent'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('content', 'like', '%' . $this->search . '%')
                        ->orWhere('guest_name', 'like', '%' . $this->search . '%')
                        ->orWhere('guest_email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->postFilter !== 'all', function ($q) {
                $q->where('blog_post_id', $this->postFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->forPage($this->getPage(), $this->perPage)
            ->pluck('id')
            ->toArray();
    }

    // Individual Comment Actions
    public function approveComment($commentId)
    {
        $this->authorize('blog.comments.approve');

        $comment = BlogComment::findOrFail($commentId);
        $comment->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        $this->dispatch('comment-updated', [
            'message' => 'Comment approved successfully',
            'type'    => 'success',
        ]);
    }

    public function rejectComment($commentId)
    {
        $this->authorize('blog.comments.approve');

        $comment = BlogComment::findOrFail($commentId);
        $comment->update([
            'status'      => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
        ]);

        $this->dispatch('comment-updated', [
            'message' => 'Comment rejected',
            'type'    => 'warning',
        ]);
    }

    public function markAsSpam($commentId)
    {
        $this->authorize('blog.comments.approve');

        $comment = BlogComment::findOrFail($commentId);
        $comment->update([
            'status'      => 'spam',
            'approved_at' => null,
            'approved_by' => null,
        ]);

        $this->dispatch('comment-updated', [
            'message' => 'Comment marked as spam',
            'type'    => 'warning',
        ]);
    }

    public function deleteComment($commentId)
    {
        $this->authorize('blog.comments.delete');

        $comment = BlogComment::findOrFail($commentId);
        $comment->delete();

        $this->dispatch('comment-updated', [
            'message' => 'Comment deleted',
            'type'    => 'success',
        ]);
    }

    // Comment Detail Modal
    public function viewComment($commentId)
    {
        $this->selectedComment = BlogComment::with(['post', 'user', 'parent', 'replies', 'approvedBy'])
            ->findOrFail($commentId);
        $this->showCommentModal = true;
    }

    public function closeCommentModal()
    {
        $this->showCommentModal = false;
        $this->selectedComment  = null;
        $this->replyText        = '';
    }

    // Bulk Actions
    public function initiateBulkAction($action)
    {
        if (empty($this->selectedComments)) {
            $this->dispatch('show-alert', [
                'message' => 'Please select comments first',
                'type'    => 'warning',
            ]);
            return;
        }

        $this->bulkAction = $action;
        $selectedCount    = count($this->selectedComments);

        switch ($action) {
            case 'approve':
                if (! Gate::allows('blog.comments.approve')) {
                    $this->dispatch('show-alert', [
                        'message' => 'You do not have permission to approve comments',
                        'type'    => 'error',
                    ]);
                    return;
                }
                $this->bulkConfirmAction = 'Approve Comments';
                $this->bulkConfirmText   = "Are you sure you want to approve {$selectedCount} selected comment(s)?";
                break;

            case 'reject':
                if (! Gate::allows('blog.comments.approve')) {
                    $this->dispatch('show-alert', [
                        'message' => 'You do not have permission to reject comments',
                        'type'    => 'error',
                    ]);
                    return;
                }
                $this->bulkConfirmAction = 'Reject Comments';
                $this->bulkConfirmText   = "Are you sure you want to reject {$selectedCount} selected comment(s)?";
                break;

            case 'spam':
                if (! Gate::allows('blog.comments.approve')) {
                    $this->dispatch('show-alert', [
                        'message' => 'You do not have permission to mark comments as spam',
                        'type'    => 'error',
                    ]);
                    return;
                }
                $this->bulkConfirmAction = 'Mark as Spam';
                $this->bulkConfirmText   = "Are you sure you want to mark {$selectedCount} selected comment(s) as spam?";
                break;

            case 'delete':
                if (! Gate::allows('blog.comments.delete')) {
                    $this->dispatch('show-alert', [
                        'message' => 'You do not have permission to delete comments',
                        'type'    => 'error',
                    ]);
                    return;
                }
                $this->bulkConfirmAction = 'Delete Comments';
                $this->bulkConfirmText   = "Are you sure you want to permanently delete {$selectedCount} selected comment(s)? This action cannot be undone.";
                break;
        }

        $this->showBulkModal = true;
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedComments) || empty($this->bulkAction)) {
            return;
        }

        $count    = 0;
        $comments = BlogComment::whereIn('id', $this->selectedComments)->get();

        foreach ($comments as $comment) {
            switch ($this->bulkAction) {
                case 'approve':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status'      => 'approved',
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                        ]);
                        $count++;
                    }
                    break;

                case 'reject':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status'      => 'rejected',
                            'approved_at' => null,
                            'approved_by' => null,
                        ]);
                        $count++;
                    }
                    break;

                case 'spam':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status'      => 'spam',
                            'approved_at' => null,
                            'approved_by' => null,
                        ]);
                        $count++;
                    }
                    break;

                case 'delete':
                    if (Gate::allows('blog.comments.delete')) {
                        $comment->delete();
                        $count++;
                    }
                    break;
            }
        }

        $this->closeBulkModal();
        $this->selectedComments = [];
        $this->selectAll        = false;

        $actionName = ucfirst($this->bulkAction);
        if ($this->bulkAction === 'delete') {
            $actionName = 'Deleted';
        } elseif ($this->bulkAction === 'approve') {
            $actionName = 'Approved';
        } elseif ($this->bulkAction === 'reject') {
            $actionName = 'Rejected';
        } elseif ($this->bulkAction === 'spam') {
            $actionName = 'Marked as spam';
        }

        $this->dispatch('bulk-action-completed', [
            'message' => "{$actionName} {$count} comment(s) successfully",
            'type'    => 'success',
        ]);
    }

    public function closeBulkModal()
    {
        $this->showBulkModal     = false;
        $this->bulkAction        = '';
        $this->bulkConfirmAction = '';
        $this->bulkConfirmText   = '';
    }
}
