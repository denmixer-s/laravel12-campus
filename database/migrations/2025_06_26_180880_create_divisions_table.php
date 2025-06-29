<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['faculty_id', 'slug']);
            $table->index(['faculty_id', 'is_active']);
            $table->index(['faculty_id', 'code']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};