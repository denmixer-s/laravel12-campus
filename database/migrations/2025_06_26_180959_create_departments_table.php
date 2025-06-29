<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['division_id', 'slug']);
            $table->index(['division_id', 'is_active']);
            $table->index(['division_id', 'code']);
            $table->index('sort_order');
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};