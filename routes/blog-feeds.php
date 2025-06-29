<?php


use Illuminate\Support\Facades\Route;
// routes/blog-feeds.php - Blog RSS & Sitemap Routes

// =============================================================================
// BLOG RSS & SITEMAP ROUTES
// =============================================================================

Route::prefix('blog')->name('blog.')->group(function () {

    // ===============================
    // RSS Feeds
    // ===============================
    Route::get('/feed', [App\Http\Controllers\BlogFeedController::class, 'rss'])->name('feed.rss');
    Route::get('/rss', [App\Http\Controllers\BlogFeedController::class, 'rss'])->name('rss');
    Route::get('/feed.xml', [App\Http\Controllers\BlogFeedController::class, 'rss'])->name('feed.xml');

    // Category/Tag Feeds
    Route::get('/category/{category:slug}/feed', [App\Http\Controllers\BlogFeedController::class, 'categoryRss'])->name('category.feed');
    Route::get('/tag/{tag:slug}/feed', [App\Http\Controllers\BlogFeedController::class, 'tagRss'])->name('tag.feed');

    // ===============================
    // Sitemap
    // ===============================
    Route::get('/sitemap.xml', [App\Http\Controllers\BlogSitemapController::class, 'index'])->name('sitemap');
    Route::get('/sitemap-posts.xml', [App\Http\Controllers\BlogSitemapController::class, 'posts'])->name('sitemap.posts');
    Route::get('/sitemap-categories.xml', [App\Http\Controllers\BlogSitemapController::class, 'categories'])->name('sitemap.categories');
    Route::get('/sitemap-tags.xml', [App\Http\Controllers\BlogSitemapController::class, 'tags'])->name('sitemap.tags');
});
