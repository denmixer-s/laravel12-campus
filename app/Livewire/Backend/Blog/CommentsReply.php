<?php
namespace App\Livewire\Backend\Blog;

use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
#[Title('Reply to Comment')]
class CommentsReply extends Component
{
    use WithPagination, AuthorizesRequests;

    // Comment Data
    public BlogComment $comment;
    public $commentId;

    // Reply Form
    public $replyContent = '';
    public $replyAsUser  = true;
    public $guestName    = '';
    public $guestEmail   = '';
    public $guestWebsite = '';
    public $notifyAuthor = true;
    public $autoApprove  = false;

    // Thread Management
    public $showThread    = true;
    public $threadSortBy  = 'created_at';
    public $threadSortDir = 'asc';
    public $perPage       = 10;

    // Modal States
    public $showQuickReplyModal = false;
    public $showAdvancedOptions = false;
    public $showPreview         = false;
    public $previewContent      = '';

    // Reply Status
    public $replyStatus  = 'pending';
    public $isSubmitting = false;

    protected function rules()
    {
        $rules = [
            'replyContent' => 'required|min:10|max:5000',
            'notifyAuthor' => 'boolean',
            'autoApprove'  => 'boolean',
            'replyStatus'  => ['required', Rule::in(['pending', 'approved'])],
        ];

        if (! $this->replyAsUser) {
            $rules['guestName']    = 'required|string|max:255';
            $rules['guestEmail']   = 'required|email|max:255';
            $rules['guestWebsite'] = 'nullable|url|max:255';
        }

        return $rules;
    }

    protected $messages = [
        'replyContent.required' => 'Reply content is required.',
        'replyContent.min'      => 'Reply must be at least 10 characters.',
        'replyContent.max'      => 'Reply cannot exceed 5000 characters.',
        'guestName.required'    => 'Name is required when replying as guest.',
        'guestEmail.required'   => 'Email is required when replying as guest.',
        'guestEmail.email'      => 'Please enter a valid email address.',
        'guestWebsite.url'      => 'Please enter a valid website URL.',
    ];

    public function mount($comment)
    {
        $this->authorize('blog.comments.view');

        $this->comment = BlogComment::with(['post', 'user', 'parent', 'replies', 'approvedBy'])
            ->findOrFail($comment);
        $this->commentId = $this->comment->id;

        // Set default reply status based on permissions
        if (Gate::allows('blog.comments.approve')) {
            $this->replyStatus = 'approved';
            $this->autoApprove = true;
        } else {
            $this->replyStatus = 'pending';
            $this->autoApprove = false;
        }
    }

    public function render()
    {
        // Get comment thread (parent + all replies)
        $thread = $this->getCommentThread();

        // Get comment statistics
        $stats = [
            'total_replies'    => $this->comment->replies()->count(),
            'approved_replies' => $this->comment->replies()->where('status', 'approved')->count(),
            'pending_replies'  => $this->comment->replies()->where('status', 'pending')->count(),
            'rejected_replies' => $this->comment->replies()->where('status', 'rejected')->count(),
        ];

        return view('livewire.backend.blog.comments-reply', [
            'thread' => $thread,
            'stats'  => $stats,
        ]);
    }

    private function getCommentThread()
    {
        // Get the main comment and all its replies
        $baseQuery = BlogComment::with(['user', 'approvedBy'])
            ->where(function ($query) {
                $query->where('id', $this->comment->id)
                    ->orWhere('parent_id', $this->comment->id);
            });

        if ($this->threadSortBy === 'created_at') {
            $baseQuery->orderBy('created_at', $this->threadSortDir);
        } elseif ($this->threadSortBy === 'status') {
            $baseQuery->orderBy('status', $this->threadSortDir)
                ->orderBy('created_at', 'asc');
        }

        return $baseQuery->paginate($this->perPage);
    }

    // Form Management
    public function updatedReplyAsUser()
    {
        if ($this->replyAsUser) {
            $this->reset(['guestName', 'guestEmail', 'guestWebsite']);
        }
    }

    public function updatedReplyContent()
    {
        $this->previewContent = $this->replyContent;
    }

    public function togglePreview()
    {
        $this->showPreview = ! $this->showPreview;
        if ($this->showPreview) {
            $this->previewContent = $this->replyContent;
        }
    }

    public function toggleAdvancedOptions()
    {
        $this->showAdvancedOptions = ! $this->showAdvancedOptions;
    }

    // Thread Management
    public function sortThread($field)
    {
        if ($this->threadSortBy === $field) {
            $this->threadSortDir = $this->threadSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->threadSortBy  = $field;
            $this->threadSortDir = 'asc';
        }
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Reply Actions
    public function submitReply()
    {
        if (! Gate::allows('blog.comments.approve') && ! Gate::allows('blog.comments.create')) {
            $this->addError('general', 'You do not have permission to reply to comments.');
            return;
        }

        $this->validate();
        $this->isSubmitting = true;

        try {
            // Create the reply
            $replyData = [
                'blog_post_id' => $this->comment->blog_post_id,
                'parent_id'    => $this->comment->id,
                'content'      => $this->replyContent,
                'status'       => $this->replyStatus,
                'user_agent'   => request()->userAgent(),
                'ip_address'   => request()->ip(),
            ];

            if ($this->replyAsUser) {
                $replyData['user_id'] = auth()->id();
            } else {
                $replyData['guest_name']    = $this->guestName;
                $replyData['guest_email']   = $this->guestEmail;
                $replyData['guest_website'] = $this->guestWebsite;
            }

            if ($this->replyStatus === 'approved') {
                $replyData['approved_at'] = now();
                $replyData['approved_by'] = auth()->id();
            }

            $reply = BlogComment::create($replyData);

            // Update parent comment's reply count
            $this->comment->increment('replies_count');

            // Send notification if requested
            if ($this->notifyAuthor && $this->comment->user && $this->comment->user->email) {
                $this->sendReplyNotification($reply);
            }

            // Reset form
            $this->resetForm();

            // Refresh the thread
            $this->resetPage();

            $this->dispatch('reply-submitted', [
                'message' => 'Reply submitted successfully!',
                'type'    => 'success',
            ]);

        } catch (\Exception $e) {
            $this->addError('general', 'An error occurred while submitting your reply. Please try again.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function quickReply($content = '')
    {
        $this->replyContent        = $content;
        $this->showQuickReplyModal = true;
    }

    public function submitQuickReply()
    {
        $this->validate(['replyContent' => 'required|min:10|max:1000']);

        $this->submitReply();
        $this->showQuickReplyModal = false;
    }

    private function sendReplyNotification($reply)
    {
        // Implementation for sending email notification
        // This would typically use Laravel's notification system
        try {
            // Example notification logic
            // $this->comment->user->notify(new CommentReplyNotification($reply, $this->comment));
        } catch (\Exception $e) {
            // Log the error but don't fail the reply submission
            logger()->error('Failed to send reply notification: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset([
            'replyContent', 'guestName', 'guestEmail', 'guestWebsite',
            'showPreview', 'previewContent', 'showAdvancedOptions',
        ]);

        // Reset to default status
        if (Gate::allows('blog.comments.approve')) {
            $this->replyStatus = 'approved';
        } else {
            $this->replyStatus = 'pending';
        }
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

    public function deleteComment($commentId)
    {
        $this->authorize('blog.comments.delete');

        $comment = BlogComment::findOrFail($commentId);

        // Update parent's reply count if this is a reply
        if ($comment->parent_id) {
            $comment->parent->decrement('replies_count');
        }

        $comment->delete();

        $this->dispatch('comment-updated', [
            'message' => 'Comment deleted',
            'type'    => 'success',
        ]);

        $this->resetPage();
    }

    // Modal Management
    public function closeQuickReplyModal()
    {
        $this->showQuickReplyModal = false;
        $this->reset(['replyContent']);
    }

    // Helper Methods
    public function getCommentAuthorName($comment)
    {
        return $comment->user ? $comment->user->name : $comment->guest_name;
    }

    public function getCommentAuthorEmail($comment)
    {
        return $comment->user ? $comment->user->email : $comment->guest_email;
    }

    public function canModerateComment($comment)
    {
        return Gate::allows('blog.comments.approve') ||
            (auth()->check() && $comment->user_id === auth()->id());
    }

    public function getReplyDepthClass($comment)
    {
        return $comment->parent_id ? 'ml-8 border-l-2 border-gray-200 dark:border-gray-700 pl-4' : '';
    }
}
