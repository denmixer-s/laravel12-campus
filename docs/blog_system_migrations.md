<?php

// 1. Blog Categories Migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color for UI
            $table->string('icon')->nullable(); // FontAwesome icon
            //$table->string('image')->nullable(); // Category image
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Hierarchy support
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            
            // Management
            $table->boolean('is_active')->default(true);
            $table->integer('posts_count')->default(0); // Cache counter
            
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('parent_id')->references('id')->on('blog_categories')->onDelete('cascade');
            
            // Indexes
            $table->index(['parent_id', 'sort_order']);
            $table->index('is_active');
            $table->index('posts_count');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_categories');
    }
}

// 2. Blog Tags Migration
class CreateBlogTagsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#10B981'); // Hex color
            
            // Management
            $table->boolean('is_active')->default(true);
            $table->integer('posts_count')->default(0); // Cache counter
            
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('posts_count');
            $table->fullText(['name', 'description']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_tags');
    }
}

// 3. Blog Posts Migration
class CreateBlogPostsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            
            // Basic fields
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Short description
            $table->longText('content');
            
            // Media - Will use Spatie MediaLibrary instead
            // Removed: $table->string('featured_image')->nullable();
            // Removed: $table->json('gallery')->nullable(); // Array of images
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            // Removed: $table->string('og_image')->nullable(); // Will use MediaLibrary
            
            // Relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Author
            $table->foreignId('blog_category_id')->constrained()->onDelete('cascade');
            
            // Publishing
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable(); // For scheduled posts
            
            // Features
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_sticky')->default(false); // Pin to top
            $table->boolean('allow_comments')->default(true);
            
            // Statistics (cached)
            $table->integer('views_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('shares_count')->default(0);
            
            // Reading time estimation
            $table->integer('reading_time')->nullable(); // in minutes
            
            // Admin fields
            $table->text('admin_notes')->nullable(); // Internal notes
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['status', 'published_at']);
            $table->index(['blog_category_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['is_featured', 'status']);
            $table->index(['is_sticky', 'status']);
            $table->index('views_count');
            $table->index('published_at');
            
            // Full-text search
            $table->fullText(['title', 'excerpt', 'content']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}

// 4. Blog Post Tags (Many-to-Many) Migration
class CreateBlogPostTagsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_post_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('blog_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Unique constraint
            $table->unique(['blog_post_id', 'blog_tag_id']);
            
            // Indexes
            $table->index('blog_post_id');
            $table->index('blog_tag_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_tags');
    }
}

// 5. Blog Comments Migration
class CreateBlogCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Registered user
            $table->unsignedBigInteger('parent_id')->nullable(); // For nested comments
            
            // Guest user fields (if user_id is null)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_website')->nullable();
            
            // Content
            $table->text('content');
            $table->string('user_agent')->nullable();
            $table->ipAddress('ip_address')->nullable();
            
            // Moderation
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Features
            $table->integer('likes_count')->default(0);
            $table->integer('replies_count')->default(0); // Cache counter for child comments
            
            $table->timestamps();
            $table->softDeletes();

            // Foreign key for nested comments
            $table->foreign('parent_id')->references('id')->on('blog_comments')->onDelete('cascade');
            
            // Indexes
            $table->index(['blog_post_id', 'status']);
            $table->index(['parent_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('approved_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
}

// 6. Blog Post Views (for analytics) Migration
class CreateBlogPostViewsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // null for guests
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->timestamp('viewed_at');
            
            // Indexes
            $table->index(['blog_post_id', 'viewed_at']);
            $table->index(['ip_address', 'blog_post_id', 'viewed_at']); // Prevent spam views
            $table->index('viewed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_views');
    }
}

// 7. Blog Post Likes Migration
class CreateBlogPostLikesTable extends Migration
{
    public function up()
    {
        Schema::create('blog_post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['like', 'dislike'])->default('like');
            $table->timestamps();

            // Unique constraint - one like per user per post
            $table->unique(['blog_post_id', 'user_id']);
            
            // Indexes
            $table->index(['blog_post_id', 'type']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_likes');
    }
}

// 8. Blog Settings Migration
class CreateBlogSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Can be accessed via API
            $table->timestamps();

            // Index
            $table->index('key');
            $table->index('is_public');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_settings');
    }
}
