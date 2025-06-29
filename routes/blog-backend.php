<?php

// routes/blog-backend.php - Backend Blog Routes
use Illuminate\Support\Facades\Route;
use App\Livewire\Backend\Blog\{
    Dashboard,
    PostsList,
    PostsCreate,
    PostsEdit,
    PostsBulkActions,
    CategoriesIndex,
    CategoriesForm,
    CategoriesReorder,
    TagsIndex,
    TagsForm,
    TagsMerge,
    CommentsModerate,
    CommentsBulkActions,
    CommentsReply,
    MediaUpload,
    MediaGallery,
    MediaManager,
    Analytics,
    Settings
};

// =============================================================================
// BACKEND BLOG ROUTES (Administrator Dashboard)
// =============================================================================

Route::middleware(['auth', 'verified'])->prefix('administrator')->name('administrator.')->group(function () {

    // Blog Management Dashboard
    Route::middleware('can:blog.posts.view')->prefix('blog')->name('blog.')->group(function () {

        // ===============================
        // Blog Dashboard
        // ===============================
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/dashboard', Dashboard::class)->name('dashboard.index');

        // ===============================
        // Posts Management
        // ===============================
        Route::middleware('can:blog.posts.view')->group(function () {
            // Posts Listing & Management
            Route::get('/posts', PostsList::class)->name('posts.index');
            Route::get('/posts/bulk-actions', PostsBulkActions::class)->name('posts.bulk-actions');

            // Posts CRUD
            Route::middleware('can:blog.posts.create')->group(function () {
                Route::get('/posts/create', PostsCreate::class)->name('posts.create');
            });

            Route::middleware('can:blog.posts.edit')->group(function () {
                Route::get('/posts/{post}/edit', PostsEdit::class)->name('posts.edit');
            });
        });

        // ===============================
        // Categories Management
        // ===============================
        Route::middleware('can:blog.categories.view')->group(function () {
            // Categories Listing
            Route::get('/categories', CategoriesIndex::class)->name('categories.index');
            Route::get('/categories/reorder', CategoriesReorder::class)->name('categories.reorder');

            // Categories CRUD
            Route::middleware('can:blog.categories.create')->group(function () {
                Route::get('/categories/create', CategoriesForm::class)->name('categories.create');
            });

            Route::middleware('can:blog.categories.edit')->group(function () {
                Route::get('/categories/{category}/edit', CategoriesForm::class)->name('categories.edit');
            });
        });

        // ===============================
        // Tags Management
        // ===============================
        Route::middleware('can:blog.tags.view')->group(function () {
            // Tags Listing
            Route::get('/tags', TagsIndex::class)->name('tags.index');
            Route::get('/tags/merge', TagsMerge::class)->name('tags.merge');

            // Tags CRUD
            Route::middleware('can:blog.tags.create')->group(function () {
                Route::get('/tags/create', TagsForm::class)->name('tags.create');
            });

            Route::middleware('can:blog.tags.edit')->group(function () {
                Route::get('/tags/{tag}/edit', TagsForm::class)->name('tags.edit');
            });
        });

        // ===============================
        // Comments Management
        // ===============================
        Route::middleware('can:blog.comments.view')->group(function () {
            // Comments Listing & Moderation
            Route::get('/comments', CommentsModerate::class)->name('comments.index');
            Route::get('/comments/moderate', CommentsModerate::class)->name('comments.moderate');
            Route::get('/comments/bulk-actions', CommentsBulkActions::class)->name('comments.bulk-actions');

            // Comments Actions
            Route::get('/comments/{comment}/reply', CommentsReply::class)->name('comments.reply');
        });

        // ===============================
        // Media Management
        // ===============================
        Route::middleware('can:blog.media.manage')->group(function () {
            // Media Library
            Route::get('/media', MediaManager::class)->name('media.index');
            Route::get('/media/upload', MediaUpload::class)->name('media.upload');
            Route::get('/media/gallery', MediaGallery::class)->name('media.gallery');
        });

        // ===============================
        // Analytics & Reports
        // ===============================
        Route::middleware('can:blog.analytics.view')->group(function () {
            Route::get('/analytics', Analytics::class)->name('analytics');
        });

        // ===============================
        // Blog Settings
        // ===============================
        Route::middleware('can:blog.settings.view')->group(function () {
            Route::get('/settings', Settings::class)->name('settings');
        });
    });
});

// Admin Shortcuts
Route::redirect('/admin/blog', '/administrator/blog');
Route::redirect('/admin/posts', '/administrator/blog/posts');
