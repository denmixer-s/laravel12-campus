<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW public_documents AS
            SELECT
                d.id,
                d.document_number,
                d.title,
                d.description,
                d.document_date,
                d.published_at,
                d.download_count,
                d.view_count,
                d.is_featured,
                d.is_new,
                d.version,
                d.file_size,
                d.original_filename,
                dc.name as category_name,
                dc.slug as category_slug,
                dt.name as type_name,
                dt.slug as type_slug,
                dept.name as department_name
            FROM documents d
            JOIN document_categories dc ON d.document_category_id = dc.id
            JOIN document_types dt ON d.document_type_id = dt.id
            LEFT JOIN departments dept ON d.department_id = dept.id
            WHERE d.status = 'published'
              AND d.access_level = 'public'
              AND dc.is_active = TRUE
              AND dt.is_active = TRUE
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS public_documents');
    }
};
