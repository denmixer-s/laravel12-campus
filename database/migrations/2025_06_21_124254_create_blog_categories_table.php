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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color for UI
            $table->string('icon')->nullable();             // FontAwesome icon

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
