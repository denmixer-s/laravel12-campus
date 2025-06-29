<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number', 50)->unique();
            $table->string('title', 500);
            $table->text('description')->nullable();
            $table->foreignId('document_category_id')->constrained();
            $table->foreignId('document_type_id')->constrained();

            // ข้อมูลผู้สร้าง
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();

            // สถานะเอกสาร
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            // ระดับการเข้าถึง
            $table->enum('access_level', ['public', 'registered'])->default('public');

            // วันที่สำคัญ
            $table->date('document_date');
            $table->timestamp('published_at')->nullable();

            // ข้อมูลการเข้าถึง
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);

            // ข้อมูลเวอร์ชัน
            $table->string('version', 10)->default('1.0');
            $table->foreignId('parent_document_id')->nullable()->constrained('documents')->nullOnDelete();

            // ข้อมูลเพิ่มเติม
            $table->json('tags')->nullable();
            $table->text('keywords')->nullable();
            $table->string('reference_number', 100)->nullable();

            // การแสดงผล
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);

            // Metadata
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('original_filename')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('document_number');
            $table->index('title');
            $table->index('status');
            $table->index('created_by');
            $table->index('document_date');
            $table->index('document_category_id');
            $table->index('document_type_id');
            $table->index('access_level');
            $table->index('published_at');
            $table->index('is_featured');
            $table->index('is_new');
            $table->index('department_id');

            // Full-text search
            $table->fullText(['title', 'description', 'keywords']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
