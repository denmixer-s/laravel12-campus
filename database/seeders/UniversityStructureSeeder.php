<?php

namespace Database\Seeders;

use App\Models\University;
use App\Models\Faculty;
use App\Models\Division;
use App\Models\Department;
use Illuminate\Database\Seeder;

class UniversityStructureSeeder extends Seeder
{
    public function run(): void
    {
        // University
        $university = University::create([
            'name' => 'มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน วิทยาเขตสกลนคร',
            'slug' => 'Rajamangala University of Technology Isan Sakonnakhon Campus.',
            'code' => 'SK',
            'description' => 'มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน วิทยาเขตสกลนคร สร้างคนสู่งาน เชี่ยวชาญเทคโนโลยี',
            'is_active' => true
        ]);

        // สำนักงานวิทยาเขตสกลนคร
        $campusOffice = Faculty::create([
            'university_id' => $university->id,
            'name' => 'สำนักงานวิทยาเขตสกลนคร',
            'slug' => 'sakon-nakhon-campus-office',
            'code' => 'SKCO',
            'type' => 'office',
            'sort_order' => 1,
            'is_active' => true
        ]);

        // งานบริการการศึกษา
        $educationService = Division::create([
            'faculty_id' => $campusOffice->id,
            'name' => 'งานบริการการศึกษา',
            'slug' => 'education-service-division',
            'code' => 'ESD',
            'sort_order' => 1,
            'is_active' => true
        ]);

        // แผนกงานวิทยบริการและเทคโนโลยีสารสนเทศ
        Department::create([
            'division_id' => $educationService->id,
            'name' => 'แผนกงานวิทยบริการและเทคโนโลยีสารสนเทศ',
            'slug' => 'academic-service-it-department',
            'code' => 'ASIT',
            'sort_order' => 1,
            'is_active' => true
        ]);

        // คณะทรัพยากรธรรมชาติ
        $naturalResourcesFaculty = Faculty::create([
            'university_id' => $university->id,
            'name' => 'คณะทรัพยากรธรรมชาติ',
            'slug' => 'natural-resources-faculty',
            'code' => 'NRF',
            'type' => 'faculty',
            'sort_order' => 2,
            'is_active' => true
        ]);

        // คณะอุตสาหกรรมและเทคโนโลยี
        $industryTechFaculty = Faculty::create([
            'university_id' => $university->id,
            'name' => 'คณะอุตสาหกรรมและเทคโนโลยี',
            'slug' => 'industry-technology-faculty',
            'code' => 'ITF',
            'type' => 'faculty',
            'sort_order' => 3,
            'is_active' => true
        ]);

        // เพิ่มตัวอย่าง Division และ Department สำหรับคณะต่างๆ
        $this->createSampleDivisionsAndDepartments($naturalResourcesFaculty);
        $this->createSampleDivisionsAndDepartments($industryTechFaculty);
    }

    private function createSampleDivisionsAndDepartments(Faculty $faculty): void
    {
        $division = Division::create([
            'faculty_id' => $faculty->id,
            'name' => 'งานบริหารทั่วไป',
            'slug' => 'general-administration-' . $faculty->slug,
            'code' => $faculty->code . '-GA',
            'sort_order' => 1,
            'is_active' => true
        ]);

        Department::create([
            'division_id' => $division->id,
            'name' => 'แผนกธุรการ',
            'slug' => 'administration-department-' . $faculty->slug,
            'code' => $faculty->code . '-AD',
            'sort_order' => 1,
            'is_active' => true
        ]);

        Department::create([
            'division_id' => $division->id,
            'name' => 'แผนกการเงิน',
            'slug' => 'finance-department-' . $faculty->slug,
            'code' => $faculty->code . '-FD',
            'sort_order' => 2,
            'is_active' => true
        ]);
    }
}
