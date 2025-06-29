<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->index(['status', 'access_level', 'published_at'], 'idx_documents_public');
            $table->index(['is_featured', 'status', 'published_at'], 'idx_documents_featured');
            $table->index(['is_new', 'status', 'published_at'], 'idx_documents_new');
            $table->index(['status', 'published_at'], 'idx_documents_published');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('idx_documents_public');
            $table->dropIndex('idx_documents_featured');
            $table->dropIndex('idx_documents_new');
            $table->dropIndex('idx_documents_published');
        });
    }
};
