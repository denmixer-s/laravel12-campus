<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('document_categories')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('documents_count')->default(0)->after('is_active');
            $table->timestamps();

            $table->index('parent_id');
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_categories');
    }
};
