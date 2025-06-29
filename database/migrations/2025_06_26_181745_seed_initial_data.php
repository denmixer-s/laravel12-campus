<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seed Document Categories
        DB::table('document_categories')->insert([
            [
                'name' => 'ประกาศมหาวิทยาลัยฯ',
                'slug' => 'announcements',
                'description' => 'ประกาศมหาวิทยาลัยฯ สำหรับทุกคน',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'แบบฟอร์มบริการ',
                'slug' => 'forms',
                'description' => 'แบบฟอร์มบริการต่างๆ ของแผนกงาน',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'คู่มือการบริการ',
                'slug' => 'manuals',
                'description' => 'คู่มือและแนวทางปฏิบัติสำหรับให้บริการต่างๆ',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'เอกสารอ้างอิง',
                'slug' => 'references',
                'description' => 'เอกสารอ้างอิงต่างๆ',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Seed Document Types
        DB::table('document_types')->insert([
            [
                'name' => 'เอกสาร PDF',
                'slug' => 'pdf',
                'description' => 'เอกสารในรูปแบบ PDF',
                'allowed_extensions' => json_encode(['pdf']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'เอกสาร Word',
                'slug' => 'word',
                'description' => 'เอกสาร Microsoft Word',
                'allowed_extensions' => json_encode(['doc', 'docx']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'สเปรดชีต',
                'slug' => 'excel',
                'description' => 'ไฟล์ Excel',
                'allowed_extensions' => json_encode(['xls', 'xlsx']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'งานนำเสนอ',
                'slug' => 'powerpoint',
                'description' => 'ไฟล์ PowerPoint',
                'allowed_extensions' => json_encode(['ppt', 'pptx']),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        DB::table('document_types')->truncate();
        DB::table('document_categories')->truncate();
    }
};
