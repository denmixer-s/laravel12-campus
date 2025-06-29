<?php

declare(strict_types=1);

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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('icon')->nullable();
            $table->enum('show', ['header', 'footer', 'sidebar', 'both', 'mobile'])->default('header');
            $table->enum('target', ['_self', '_blank', '_parent', '_top'])->default('_self');
            $table->string('permission')->nullable(); // Fixed: Added this missing column
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->json('meta_data')->nullable(); // For storing additional data like badges, etc.
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');

            // Indexes for performance
            $table->index(['parent_id', 'sort_order']);
            $table->index('show');
            $table->index('permission');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};