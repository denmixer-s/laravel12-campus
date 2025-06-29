<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['faculty', 'office'])->default('faculty');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'slug']);
            $table->index(['university_id', 'type']);
            $table->index(['university_id', 'code']);
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};