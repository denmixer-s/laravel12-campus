<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->string('action', 50);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->constrained('users');
            $table->timestamp('created_at')->nullable();

            $table->index('document_id');
            $table->index('action');
            $table->index('performed_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_histories');
    }
};
