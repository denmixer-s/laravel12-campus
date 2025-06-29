<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
