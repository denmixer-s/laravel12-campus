<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['staff', 'public'])->default('public');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();

            $table->index('user_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropIndex(['user_type']);
            $table->dropColumn([
                'user_type',
                'department_id'
            ]);
        });
    }
};
