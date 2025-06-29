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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_views');
    }
};
