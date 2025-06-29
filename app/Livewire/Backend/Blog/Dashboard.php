<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogComment;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('components.layouts.dashboard')]
class Dashboard extends Component
{
    public function mount()
    {
        $this->authorize('blog.posts.view');
    }

    #[Computed]
    public function stats()
    {
        return [
            'total_posts' => BlogPost::count(),
            'published_posts' => BlogPost::where('status', 'published')->count(),
            'draft_posts' => BlogPost::where('status', 'draft')->count(),
            'scheduled_posts' => BlogPost::where('status', 'scheduled')->count(),
            'archived_posts' => BlogPost::where('status', 'archived')->count(),
            'total_categories' => BlogCategory::count(),
            'total_tags' => BlogTag::count(),
            'total_comments' => BlogComment::count(),
            'pending_comments' => BlogComment::where('status', 'pending')->count(),
            'approved_comments' => BlogComment::where('status', 'approved')->count(),
            'total_views' => BlogPost::sum('views_count'),
            'this_month_posts' => BlogPost::whereMonth('created_at', now()->month)->count(),
            'this_week_posts' => BlogPost::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }

    #[Computed]
    public function recentPosts()
    {
        return BlogPost::with(['category', 'author'])
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function popularPosts()
    {
        return BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function recentComments()
    {
        return BlogComment::with(['post', 'user'])
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function chartData()
    {
        // Posts created in the last 7 days
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days->push([
                'date' => $date->format('M d'),
                'posts' => BlogPost::whereDate('created_at', $date)->count(),
                'comments' => BlogComment::whereDate('created_at', $date)->count(),
            ]);
        }
        return $days;
    }

    public function quickAction($action, $postId = null)
    {
        switch ($action) {
            case 'create_post':
                return redirect()->route('administrator.blog.posts.create');

            case 'bulk_actions':
                return redirect()->route('administrator.blog.posts.bulk-actions');

            case 'manage_categories':
                return redirect()->route('administrator.blog.categories.index');

            case 'manage_comments':
                return redirect()->route('administrator.blog.comments.index');

            case 'view_analytics':
                return redirect()->route('administrator.blog.analytics');
        }
    }

    public function render()
    {
        return view('livewire.backend.blog.dashboard');
    }
}
