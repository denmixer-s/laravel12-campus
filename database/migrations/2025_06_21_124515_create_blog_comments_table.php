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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
