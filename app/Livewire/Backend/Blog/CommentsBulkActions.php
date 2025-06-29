<?php

namespace App\Livewire\Backend\Blog;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

#[Layout('components.layouts.dashboard')]
#[Title('Comments Bulk Actions')]
class CommentsBulkActions extends Component
{
    use WithPagination, AuthorizesRequests;

    // Selection & Filters
    public $selectedComments = [];
    public $selectAll = false;
    public $selectAllPages = false;
    public $search = '';
    public $statusFilter = 'all';
    public $postFilter = 'all';
    public $authorFilter = 'all';
    public $dateRange = 'all';
    public $sortBy = 'created_at';
    public $sortDir = 'desc';
    public $perPage = 25;

    // Bulk Action States
    public $bulkAction = '';
    public $showBulkModal = false;
    public $bulkConfirmAction = '';
    public $bulkConfirmText = '';
    public $bulkActionCount = 0;

    // Advanced Bulk Options
    public $bulkChangeCategory = '';
    public $bulkAddTags = '';
    public $bulkRemoveTags = '';
    public $bulkReason = '';
    public $bulkNotifyAuthors = false;
    public $bulkSendEmail = false;

    // Modal & Preview States
    public $showPreviewModal = false;
    public $previewComments = [];
    public $showAdvancedOptions = false;

    // Available options
    public $statuses = [
        'all' => 'All Statuses',
        'pending' => 'Pending Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'spam' => 'Spam'
    ];

    public $dateRanges = [
        'all' => 'All Time',
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'this_week' => 'This Week',
        'last_week' => 'Last Week',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        'this_year' => 'This Year'
    ];

    public $bulkActions = [
        'approve' => 'Approve Comments',
        'reject' => 'Reject Comments',
        'spam' => 'Mark as Spam',
        'restore' => 'Restore Comments',
        'delete' => 'Delete Comments',
        'export' => 'Export Comments',
        'notify_authors' => 'Notify Authors'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'postFilter' => ['except' => 'all'],
        'authorFilter' => ['except' => 'all'],
        'dateRange' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDir' => ['except' => 'desc'],
        'page' => ['except' => 1]
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
            ->when($this->authorFilter !== 'all', function ($q) {
                if ($this->authorFilter === 'guest') {
                    $q->whereNull('user_id');
                } elseif ($this->authorFilter === 'registered') {
                    $q->whereNotNull('user_id');
                } else {
                    $q->where('user_id', $this->authorFilter);
                }
            })
            ->when($this->dateRange !== 'all', function ($q) {
                $this->applyDateFilter($q);
            })
            ->orderBy($this->sortBy, $this->sortDir);

        $comments = $query->paginate($this->perPage);

        // Get filter options
        $posts = BlogPost::select('id', 'title')
            ->whereHas('comments')
            ->orderBy('title')
            ->get();

        $authors = User::select('id', 'name', 'email')
            ->whereHas('blogComments')
            ->orderBy('name')
            ->get();

        // Get selection stats
        $stats = $this->getSelectionStats();

        return view('livewire.backend.blog.comments-bulk-actions', [
            'comments' => $comments,
            'posts' => $posts,
            'authors' => $authors,
            'stats' => $stats
        ]);
    }

    private function applyDateFilter($query)
    {
        $now = Carbon::now();

        switch ($this->dateRange) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'yesterday':
                $query->whereDate('created_at', $now->subDay()->toDateString());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                break;
            case 'last_week':
                $start = $now->subWeek()->startOfWeek();
                $end = $now->endOfWeek();
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'this_month':
                $query->whereYear('created_at', $now->year)
                      ->whereMonth('created_at', $now->month);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereYear('created_at', $lastMonth->year)
                      ->whereMonth('created_at', $lastMonth->month);
                break;
            case 'this_year':
                $query->whereYear('created_at', $now->year);
                break;
        }
    }

    private function getSelectionStats()
    {
        if (empty($this->selectedComments)) {
            return [
                'total' => 0,
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'spam' => 0
            ];
        }

        $selected = BlogComment::whereIn('id', $this->selectedComments)->get();

        return [
            'total' => $selected->count(),
            'pending' => $selected->where('status', 'pending')->count(),
            'approved' => $selected->where('status', 'approved')->count(),
            'rejected' => $selected->where('status', 'rejected')->count(),
            'spam' => $selected->where('status', 'spam')->count()
        ];
    }

    // Filter & Search Methods
    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedPostFilter()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedAuthorFilter()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedDateRange()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search', 'statusFilter', 'postFilter',
            'authorFilter', 'dateRange'
        ]);
        $this->resetPage();
        $this->resetSelection();
    }

    // Selection Management
    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedComments = $this->getCurrentPageCommentIds();
        } else {
            $this->selectedComments = [];
            $this->selectAllPages = false;
        }
    }

    public function updatedSelectedComments()
    {
        $currentPageIds = $this->getCurrentPageCommentIds();
        $this->selectAll = count($this->selectedComments) === count($currentPageIds)
            && count(array_diff($currentPageIds, $this->selectedComments)) === 0;

        if (!$this->selectAll) {
            $this->selectAllPages = false;
        }
    }

    public function selectAllPages()
    {
        $this->selectAllPages = true;
        $this->selectedComments = $this->getAllFilteredCommentIds();
        $this->selectAll = true;
    }

    public function clearSelection()
    {
        $this->resetSelection();
    }

    private function resetSelection()
    {
        $this->selectedComments = [];
        $this->selectAll = false;
        $this->selectAllPages = false;
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
            ->when($this->authorFilter !== 'all', function ($q) {
                if ($this->authorFilter === 'guest') {
                    $q->whereNull('user_id');
                } elseif ($this->authorFilter === 'registered') {
                    $q->whereNotNull('user_id');
                } else {
                    $q->where('user_id', $this->authorFilter);
                }
            })
            ->when($this->dateRange !== 'all', function ($q) {
                $this->applyDateFilter($q);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->forPage($this->getPage(), $this->perPage)
            ->pluck('id')
            ->toArray();
    }

    private function getAllFilteredCommentIds()
    {
        return BlogComment::when($this->search, function ($q) {
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
            ->when($this->authorFilter !== 'all', function ($q) {
                if ($this->authorFilter === 'guest') {
                    $q->whereNull('user_id');
                } elseif ($this->authorFilter === 'registered') {
                    $q->whereNotNull('user_id');
                } else {
                    $q->where('user_id', $this->authorFilter);
                }
            })
            ->when($this->dateRange !== 'all', function ($q) {
                $this->applyDateFilter($q);
            })
            ->pluck('id')
            ->toArray();
    }

    // Bulk Actions
    public function initiateBulkAction($action)
    {
        if (empty($this->selectedComments)) {
            $this->dispatch('show-alert', [
                'message' => 'Please select comments first',
                'type' => 'warning'
            ]);
            return;
        }

        $this->bulkAction = $action;
        $this->bulkActionCount = count($this->selectedComments);

        // Check permissions
        if (!$this->canPerformBulkAction($action)) {
            $this->dispatch('show-alert', [
                'message' => 'You do not have permission to perform this action',
                'type' => 'error'
            ]);
            return;
        }

        $this->prepareBulkActionModal($action);
        $this->showBulkModal = true;
    }

    private function canPerformBulkAction($action)
    {
        switch ($action) {
            case 'approve':
            case 'reject':
            case 'spam':
            case 'restore':
                return Gate::allows('blog.comments.approve');
            case 'delete':
                return Gate::allows('blog.comments.delete');
            case 'export':
                return Gate::allows('blog.comments.view');
            case 'notify_authors':
                return Gate::allows('blog.comments.approve');
            default:
                return false;
        }
    }

    private function prepareBulkActionModal($action)
    {
        switch ($action) {
            case 'approve':
                $this->bulkConfirmAction = 'Approve Comments';
                $this->bulkConfirmText = "Approve {$this->bulkActionCount} selected comment(s)?";
                break;
            case 'reject':
                $this->bulkConfirmAction = 'Reject Comments';
                $this->bulkConfirmText = "Reject {$this->bulkActionCount} selected comment(s)?";
                break;
            case 'spam':
                $this->bulkConfirmAction = 'Mark as Spam';
                $this->bulkConfirmText = "Mark {$this->bulkActionCount} selected comment(s) as spam?";
                break;
            case 'restore':
                $this->bulkConfirmAction = 'Restore Comments';
                $this->bulkConfirmText = "Restore {$this->bulkActionCount} selected comment(s)?";
                break;
            case 'delete':
                $this->bulkConfirmAction = 'Delete Comments';
                $this->bulkConfirmText = "Permanently delete {$this->bulkActionCount} selected comment(s)? This cannot be undone.";
                break;
            case 'export':
                $this->bulkConfirmAction = 'Export Comments';
                $this->bulkConfirmText = "Export {$this->bulkActionCount} selected comment(s) to CSV?";
                break;
            case 'notify_authors':
                $this->bulkConfirmAction = 'Notify Authors';
                $this->bulkConfirmText = "Send notification emails to authors of {$this->bulkActionCount} selected comment(s)?";
                break;
        }
    }

    public function previewBulkAction()
    {
        $this->previewComments = BlogComment::with(['post', 'user', 'parent'])
            ->whereIn('id', array_slice($this->selectedComments, 0, 10))
            ->get();
        $this->showPreviewModal = true;
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedComments) || empty($this->bulkAction)) {
            return;
        }

        $count = 0;
        $comments = BlogComment::whereIn('id', $this->selectedComments)->get();

        foreach ($comments as $comment) {
            switch ($this->bulkAction) {
                case 'approve':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => auth()->id()
                        ]);
                        $count++;
                    }
                    break;

                case 'reject':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status' => 'rejected',
                            'approved_at' => null,
                            'approved_by' => null
                        ]);
                        $count++;
                    }
                    break;

                case 'spam':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status' => 'spam',
                            'approved_at' => null,
                            'approved_by' => null
                        ]);
                        $count++;
                    }
                    break;

                case 'restore':
                    if (Gate::allows('blog.comments.approve')) {
                        $comment->update([
                            'status' => 'pending',
                            'approved_at' => null,
                            'approved_by' => null
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

                case 'export':
                    // Export functionality will be handled in the method
                    if (Gate::allows('blog.comments.view')) {
                        $count++;
                    }
                    break;

                case 'notify_authors':
                    // Notification functionality
                    if (Gate::allows('blog.comments.approve')) {
                        // Send notification logic here
                        $count++;
                    }
                    break;
            }
        }

        // Handle export action separately
        if ($this->bulkAction === 'export') {
            $this->exportComments();
            $this->closeBulkModal();
            return;
        }

        $this->closeBulkModal();
        $this->resetSelection();

        $actionName = $this->getActionName($this->bulkAction);
        $this->dispatch('bulk-action-completed', [
            'message' => "{$actionName} {$count} comment(s) successfully",
            'type' => 'success'
        ]);
    }

    private function getActionName($action)
    {
        $names = [
            'approve' => 'Approved',
            'reject' => 'Rejected',
            'spam' => 'Marked as spam',
            'restore' => 'Restored',
            'delete' => 'Deleted',
            'export' => 'Exported',
            'notify_authors' => 'Notified authors of'
        ];

        return $names[$action] ?? ucfirst($action);
    }

    public function exportComments()
    {
        $comments = BlogComment::with(['post', 'user', 'parent'])
            ->whereIn('id', $this->selectedComments)
            ->get();

        $csv = "ID,Content,Author Name,Author Email,Post Title,Status,Created At,Approved At,IP Address\n";

        foreach ($comments as $comment) {
            $csv .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",%s,%s,%s,%s\n",
                $comment->id,
                str_replace('"', '""', $comment->content),
                str_replace('"', '""', $comment->author_name),
                str_replace('"', '""', $comment->author_email),
                str_replace('"', '""', $comment->post->title),
                $comment->status,
                $comment->created_at->format('Y-m-d H:i:s'),
                $comment->approved_at ? $comment->approved_at->format('Y-m-d H:i:s') : '',
                $comment->ip_address ?? ''
            );
        }

        $this->dispatch('download-csv', [
            'filename' => 'comments-export-' . now()->format('Y-m-d-H-i-s') . '.csv',
            'content' => $csv
        ]);
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->bulkAction = '';
        $this->bulkConfirmAction = '';
        $this->bulkConfirmText = '';
        $this->bulkActionCount = 0;
        $this->reset([
            'bulkReason', 'bulkNotifyAuthors', 'bulkSendEmail'
        ]);
    }

    public function closePreviewModal()
    {
        $this->showPreviewModal = false;
        $this->previewComments = [];
    }

    public function toggleAdvancedOptions()
    {
        $this->showAdvancedOptions = !$this->showAdvancedOptions;
    }
}
