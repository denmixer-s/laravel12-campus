<?php

// routes/blog-frontend.php - Frontend Blog Routes
use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\Blog\{
    PostsList,
    PostDetail,
    PostsByCategory,
    PostsByTag,
    FeaturedPosts,
    SearchResults,
    CommentSection,
    PostsFilter,
    PostLikes,
    PostShare,
    RelatedPosts,
    CategoryMenu,
    TagCloud,
    RecentPosts
};

// =============================================================================
// FRONTEND BLOG ROUTES (Public Blog)
// =============================================================================

Route::prefix('blog')->name('blog.')->group(function () {

    // ===============================
    // Main Blog Pages
    // ===============================
    // Blog Homepage
    Route::get('/', PostsList::class)->name('index');

    // Featured Posts
    Route::get('/featured', FeaturedPosts::class)->name('featured');

    // Recent Posts
    Route::get('/recent', RecentPosts::class)->name('recent');

    // ===============================
    // Search & Filter
    // ===============================
    // Search
    Route::get('/search', SearchResults::class)->name('search');

    // Filter & Browse
    Route::get('/filter', PostsFilter::class)->name('filter');

    // ===============================
    // Categories
    // ===============================
    // Category Listing
    Route::get('/categories', CategoryMenu::class)->name('categories.index');

    // Posts by Category
    Route::get('/category/{category:slug}', PostsByCategory::class)->name('category');

    // ===============================
    // Tags
    // ===============================
    // Tag Cloud
    Route::get('/tags', TagCloud::class)->name('tags.index');

    // Posts by Tag
    Route::get('/tag/{tag:slug}', PostsByTag::class)->name('tag');

    // ===============================
    // Individual Posts
    // ===============================
    // Post Detail
    Route::get('/{post:slug}', PostDetail::class)->name('show');

    // Related Posts
    Route::get('/{post:slug}/related', RelatedPosts::class)->name('related');

    // ===============================
    // Comments
    // ===============================
    Route::get('/{post:slug}/comments', CommentSection::class)->name('comments');

    // ===============================
    // Social Features
    // ===============================
    // Post Sharing
    Route::get('/{post:slug}/share', PostShare::class)->name('share');

    // Post Likes
    Route::get('/{post:slug}/like', PostLikes::class)->name('like');
    Route::get('/popular', PostLikes::class)->name('popular');
});

// SEO Friendly Redirects
Route::redirect('/articles', '/blog');
Route::redirect('/news', '/blog');
Route::redirect('/posts', '/blog');
