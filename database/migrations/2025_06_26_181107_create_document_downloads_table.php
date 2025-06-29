<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('user_type', ['staff', 'public', 'anonymous'])->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();

            $table->timestamp('downloaded_at')->useCurrent();

            $table->index('document_id');
            $table->index('user_id');
            $table->index('user_type');
            $table->index('downloaded_at');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_downloads');
    }
};
