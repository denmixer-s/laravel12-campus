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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tags');
    }
};
