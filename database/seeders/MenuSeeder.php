<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ลบข้อมูลเก่าก่อน (ถ้ามี)
        Menu::query()->delete();

        // เมนูหลัก - หน้าแรก
        $homeMenu = Menu::create([
            'name' => 'หน้าแรก',
            'url' => '/',
            'route_name' => 'home',
            'parent_id' => null,
            'sort_order' => 1,
            'icon' => 'fas fa-home',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หน้าแรกของเว็บไซต์',
            'meta_data' => null,
        ]);

        // เมนูหลัก - เกี่ยวกับเรา
        $aboutMenu = Menu::create([
            'name' => 'เกี่ยวกับเรา',
            'url' => '/about',
            'route_name' => 'about',
            'parent_id' => null,
            'sort_order' => 2,
            'icon' => 'fas fa-info-circle',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลเกี่ยวกับมหาวิทยาลัย',
            'meta_data' => [
                'mega_menu' => true,
                'columns' => 4
            ],
        ]);

        // เกี่ยวกับเรา - ประวัติความเป็นมา
        $historyMenu = Menu::create([
            'name' => 'ประวัติความเป็นมา',
            'url' => '/about/history',
            'route_name' => 'about.history',
            'parent_id' => $aboutMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-history',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ประวัติความเป็นมาของมหาวิทยาลัย',
            'meta_data' => ['group' => 'ประวัติความเป็นมา'],
        ]);

        // เกี่ยวกับเรา - ประวัติความเป็นมา - ย่อย
        Menu::create([
            'name' => 'ประวัติมหาวิทยาลัย',
            'url' => '/about/history/university',
            'route_name' => 'about.history.university',
            'parent_id' => $historyMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ประวัติของมหาวิทยาลัย',
        ]);

        Menu::create([
            'name' => 'วิสัยทัศน์ พันธกิจ',
            'url' => '/about/vision-mission',
            'route_name' => 'about.vision_mission',
            'parent_id' => $historyMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'วิสัยทัศน์และพันธกิจ',
        ]);

        Menu::create([
            'name' => 'ปรัชญา อุดมการณ์',
            'url' => '/about/philosophy',
            'route_name' => 'about.philosophy',
            'parent_id' => $historyMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ปรัชญาและอุดมการณ์',
        ]);

        // เกี่ยวกับเรา - ผู้บริหาร
        $managementMenu = Menu::create([
            'name' => 'ผู้บริหาร',
            'url' => '/about/management',
            'route_name' => 'about.management',
            'parent_id' => $aboutMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-users',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลผู้บริหาร',
            'meta_data' => ['group' => 'ผู้บริหาร'],
        ]);

        Menu::create([
            'name' => 'อธิการบดี',
            'url' => '/about/management/rector',
            'route_name' => 'about.management.rector',
            'parent_id' => $managementMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลอธิการบดี',
        ]);

        Menu::create([
            'name' => 'รองอธิการบดี',
            'url' => '/about/management/vice-rector',
            'route_name' => 'about.management.vice_rector',
            'parent_id' => $managementMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลรองอธิการบดี',
        ]);

        Menu::create([
            'name' => 'ผู้ช่วยอธิการบดี',
            'url' => '/about/management/assistant-rector',
            'route_name' => 'about.management.assistant_rector',
            'parent_id' => $managementMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลผู้ช่วยอธิการบดี',
        ]);

        // เกี่ยวกับเรา - โครงสร้าง
        $structureMenu = Menu::create([
            'name' => 'โครงสร้าง',
            'url' => '/about/structure',
            'route_name' => 'about.structure',
            'parent_id' => $aboutMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-sitemap',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'โครงสร้างองค์กร',
            'meta_data' => ['group' => 'โครงสร้าง'],
        ]);

        Menu::create([
            'name' => 'โครงสร้างองค์กร',
            'url' => '/about/structure/organization',
            'route_name' => 'about.structure.organization',
            'parent_id' => $structureMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'โครงสร้างองค์กร',
        ]);

        Menu::create([
            'name' => 'คณะและสำนักงาน',
            'url' => '/about/structure/faculties',
            'route_name' => 'about.structure.faculties',
            'parent_id' => $structureMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'คณะและสำนักงาน',
        ]);

        Menu::create([
            'name' => 'ศูนย์และหน่วยงาน',
            'url' => '/about/structure/centers',
            'route_name' => 'about.structure.centers',
            'parent_id' => $structureMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ศูนย์และหน่วยงาน',
        ]);

        // เกี่ยวกับเรา - ข้อมูลทั่วไป
        $generalInfoMenu = Menu::create([
            'name' => 'ข้อมูลทั่วไป',
            'url' => '/about/general',
            'route_name' => 'about.general',
            'parent_id' => $aboutMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-info',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลทั่วไป',
            'meta_data' => ['group' => 'ข้อมูลทั่วไป'],
        ]);

        Menu::create([
            'name' => 'ที่ตั้งและการเดินทาง',
            'url' => '/about/general/location',
            'route_name' => 'about.general.location',
            'parent_id' => $generalInfoMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ที่ตั้งและการเดินทาง',
        ]);

        Menu::create([
            'name' => 'ติดต่อเรา',
            'url' => '/contact',
            'route_name' => 'contact',
            'parent_id' => $generalInfoMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลติดต่อ',
        ]);

        Menu::create([
            'name' => 'แผนที่มหาวิทยาลัย',
            'url' => '/about/general/map',
            'route_name' => 'about.general.map',
            'parent_id' => $generalInfoMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'แผนที่มหาวิทยาลัย',
        ]);

        // เมนูหลัก - การศึกษา
        $academicMenu = Menu::create([
            'name' => 'การศึกษา',
            'url' => '/academics',
            'route_name' => 'academics',
            'parent_id' => null,
            'sort_order' => 3,
            'icon' => 'fas fa-graduation-cap',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลการศึกษา',
            'meta_data' => [
                'mega_menu' => true,
                'columns' => 3
            ],
        ]);

        // การศึกษา - หลักสูตรการศึกษา
        $curriculumMenu = Menu::create([
            'name' => 'หลักสูตรการศึกษา',
            'url' => '/academics/curriculum',
            'route_name' => 'academics.curriculum',
            'parent_id' => $academicMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-graduation-cap',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หลักสูตรการศึกษา',
            'meta_data' => ['group' => 'หลักสูตรการศึกษา'],
        ]);

        Menu::create([
            'name' => 'ปริญญาตรี',
            'url' => '/academics/curriculum/bachelor',
            'route_name' => 'academics.curriculum.bachelor',
            'parent_id' => $curriculumMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หลักสูตรปริญญาตรี',
        ]);

        Menu::create([
            'name' => 'ปริญญาโท',
            'url' => '/academics/curriculum/master',
            'route_name' => 'academics.curriculum.master',
            'parent_id' => $curriculumMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หลักสูตรปริญญาโท',
        ]);

        Menu::create([
            'name' => 'ปริญญาเอก',
            'url' => '/academics/curriculum/doctoral',
            'route_name' => 'academics.curriculum.doctoral',
            'parent_id' => $curriculumMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หลักสูตรปริญญาเอก',
        ]);

        Menu::create([
            'name' => 'หลักสูตรนานาชาติ',
            'url' => '/academics/curriculum/international',
            'route_name' => 'academics.curriculum.international',
            'parent_id' => $curriculumMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หลักสูตรนานาชาติ',
        ]);

        Menu::create([
            'name' => 'หลักสูตรระยะสั้น',
            'url' => '/academics/curriculum/short-course',
            'route_name' => 'academics.curriculum.short_course',
            'parent_id' => $curriculumMenu->id,
            'sort_order' => 5,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'หลักสูตรระยะสั้น',
        ]);

        // การศึกษา - คณะและสาขาวิชา
        $facultiesMenu = Menu::create([
            'name' => 'คณะและสาขาวิชา',
            'url' => '/academics/faculties',
            'route_name' => 'academics.faculties',
            'parent_id' => $academicMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-building',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'คณะและสาขาวิชา',
            'meta_data' => ['group' => 'คณะและสาขาวิชา'],
        ]);

        Menu::create([
            'name' => 'คณะวิศวกรรมศาสตร์',
            'url' => '/academics/faculties/engineering',
            'route_name' => 'academics.faculties.engineering',
            'parent_id' => $facultiesMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'คณะวิศวกรรมศาสตร์',
        ]);

        Menu::create([
            'name' => 'คณะเทคโนโลยีการเกษตร',
            'url' => '/academics/faculties/agricultural-technology',
            'route_name' => 'academics.faculties.agricultural_technology',
            'parent_id' => $facultiesMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'คณะเทคโนโลยีการเกษตร',
        ]);

        Menu::create([
            'name' => 'คณะบริหารธุรกิจ',
            'url' => '/academics/faculties/business-administration',
            'route_name' => 'academics.faculties.business_administration',
            'parent_id' => $facultiesMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'คณะบริหารธุรกิจ',
        ]);

        Menu::create([
            'name' => 'คณะศิลปศาสตร์',
            'url' => '/academics/faculties/liberal-arts',
            'route_name' => 'academics.faculties.liberal_arts',
            'parent_id' => $facultiesMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'คณะศิลปศาสตร์',
        ]);

        Menu::create([
            'name' => 'วิทยาลัยแพทยศาสตร์',
            'url' => '/academics/faculties/medical-science',
            'route_name' => 'academics.faculties.medical_science',
            'parent_id' => $facultiesMenu->id,
            'sort_order' => 5,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'วิทยาลัยแพทยศาสตร์',
        ]);

        // การศึกษา - บริการการศึกษา
        $servicesMenu = Menu::create([
            'name' => 'บริการการศึกษา',
            'url' => '/academics/services',
            'route_name' => 'academics.services',
            'parent_id' => $academicMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-calendar-alt',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการการศึกษา',
            'meta_data' => ['group' => 'บริการการศึกษา'],
        ]);

        Menu::create([
            'name' => 'ปฏิทินการศึกษา',
            'url' => '/academics/services/calendar',
            'route_name' => 'academics.services.calendar',
            'parent_id' => $servicesMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ปฏิทินการศึกษา',
        ]);

        Menu::create([
            'name' => 'ระเบียบการศึกษา',
            'url' => '/academics/services/regulations',
            'route_name' => 'academics.services.regulations',
            'parent_id' => $servicesMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ระเบียบการศึกษา',
        ]);

        Menu::create([
            'name' => 'การลงทะเบียน',
            'url' => '/academics/services/registration',
            'route_name' => 'academics.services.registration',
            'parent_id' => $servicesMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'การลงทะเบียน',
        ]);

        Menu::create([
            'name' => 'ระบบสารสนเทศ',
            'url' => '/academics/services/information-system',
            'route_name' => 'academics.services.information_system',
            'parent_id' => $servicesMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ระบบสารสนเทศ',
        ]);

        Menu::create([
            'name' => 'ใบปริญญา',
            'url' => '/academics/services/diploma',
            'route_name' => 'academics.services.diploma',
            'parent_id' => $servicesMenu->id,
            'sort_order' => 5,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ใบปริญญา',
        ]);

        // เมนูหลัก - วิจัย
        $researchMenu = Menu::create([
            'name' => 'วิจัย',
            'url' => '/research',
            'route_name' => 'research',
            'parent_id' => null,
            'sort_order' => 4,
            'icon' => 'fas fa-flask',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'งานวิจัยและนวัตกรรม',
            'meta_data' => [
                'mega_menu' => true,
                'columns' => 2
            ],
        ]);

        // วิจัย - งานวิจัยและนวัตกรรม
        $researchInnovationMenu = Menu::create([
            'name' => 'งานวิจัยและนวัตกรรม',
            'url' => '/research/innovation',
            'route_name' => 'research.innovation',
            'parent_id' => $researchMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-flask',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'งานวิจัยและนวัตกรรม',
            'meta_data' => ['group' => 'งานวิจัยและนวัตกรรม'],
        ]);

        Menu::create([
            'name' => 'นโยบายการวิจัย',
            'url' => '/research/innovation/policy',
            'route_name' => 'research.innovation.policy',
            'parent_id' => $researchInnovationMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'นโยบายการวิจัย',
        ]);

        Menu::create([
            'name' => 'ยุทธศาสตร์การวิจัย',
            'url' => '/research/innovation/strategy',
            'route_name' => 'research.innovation.strategy',
            'parent_id' => $researchInnovationMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ยุทธศาสตร์การวิจัย',
        ]);

        Menu::create([
            'name' => 'ผลงานวิจัยเด่น',
            'url' => '/research/innovation/outstanding',
            'route_name' => 'research.innovation.outstanding',
            'parent_id' => $researchInnovationMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ผลงานวิจัยเด่น',
        ]);

        Menu::create([
            'name' => 'ฐานข้อมูลงานวิจัย',
            'url' => '/research/innovation/database',
            'route_name' => 'research.innovation.database',
            'parent_id' => $researchInnovationMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ฐานข้อมูลงานวิจัย',
        ]);

        Menu::create([
            'name' => 'จรรยาบรรณการวิจัย',
            'url' => '/research/innovation/ethics',
            'route_name' => 'research.innovation.ethics',
            'parent_id' => $researchInnovationMenu->id,
            'sort_order' => 5,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'จรรยาบรรณการวิจัย',
        ]);

        Menu::create([
            'name' => 'สิทธิบัตรและนวัตกรรม',
            'url' => '/research/innovation/patent',
            'route_name' => 'research.innovation.patent',
            'parent_id' => $researchInnovationMenu->id,
            'sort_order' => 6,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'สิทธิบัตรและนวัตกรรม',
        ]);

        // วิจัย - ศูนย์และหน่วยงานวิจัย
        $researchCentersMenu = Menu::create([
            'name' => 'ศูนย์และหน่วยงานวิจัย',
            'url' => '/research/centers',
            'route_name' => 'research.centers',
            'parent_id' => $researchMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-users',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ศูนย์และหน่วยงานวิจัย',
            'meta_data' => ['group' => 'ศูนย์และหน่วยงานวิจัย'],
        ]);

        Menu::create([
            'name' => 'ศูนย์เครื่องมือวิทยาศาสตร์',
            'url' => '/research/centers/scientific-instruments',
            'route_name' => 'research.centers.scientific_instruments',
            'parent_id' => $researchCentersMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ศูนย์เครื่องมือวิทยาศาสตร์',
        ]);

        Menu::create([
            'name' => 'ศูนย์วิจัยเทคโนโลยี',
            'url' => '/research/centers/technology-research',
            'route_name' => 'research.centers.technology_research',
            'parent_id' => $researchCentersMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ศูนย์วิจัยเทคโนโลยี',
        ]);

        Menu::create([
            'name' => 'ศูนย์บ่มเพาะวิสาหกิจ',
            'url' => '/research/centers/business-incubator',
            'route_name' => 'research.centers.business_incubator',
            'parent_id' => $researchCentersMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ศูนย์บ่มเพาะวิสาหกิจ',
        ]);

        Menu::create([
            'name' => 'งานทรัพย์สินทางปัญญา',
            'url' => '/research/centers/intellectual-property',
            'route_name' => 'research.centers.intellectual_property',
            'parent_id' => $researchCentersMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'งานทรัพย์สินทางปัญญา',
        ]);

        Menu::create([
            'name' => 'ห้องปฏิบัติการวิจัย',
            'url' => '/research/centers/research-labs',
            'route_name' => 'research.centers.research_labs',
            'parent_id' => $researchCentersMenu->id,
            'sort_order' => 5,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ห้องปฏิบัติการวิจัย',
        ]);

        Menu::create([
            'name' => 'เครือข่ายความร่วมมือ',
            'url' => '/research/centers/cooperation-network',
            'route_name' => 'research.centers.cooperation_network',
            'parent_id' => $researchCentersMenu->id,
            'sort_order' => 6,
            'icon' => 'fas fa-chevron-right',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'เครือข่ายความร่วมมือ',
        ]);

        // เมนูหลัก - กิจการนักศึกษา
        $studentAffairsMenu = Menu::create([
            'name' => 'กิจการนักศึกษา',
            'url' => '/student-affairs',
            'route_name' => 'student_affairs',
            'parent_id' => null,
            'sort_order' => 5,
            'icon' => 'fas fa-users',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'กิจการนักศึกษา',
            'meta_data' => [
                'mega_menu' => false,
                'dropdown' => true
            ],
        ]);

        Menu::create([
            'name' => 'ชมรมและองค์กร',
            'url' => '/student-affairs/clubs',
            'route_name' => 'student_affairs.clubs',
            'parent_id' => $studentAffairsMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ชมรมและองค์กรนักศึกษา',
        ]);

        Menu::create([
            'name' => 'ทุนการศึกษา',
            'url' => '/student-affairs/scholarships',
            'route_name' => 'student_affairs.scholarships',
            'parent_id' => $studentAffairsMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ทุนการศึกษา',
        ]);

        Menu::create([
            'name' => 'กิจกรรมนักศึกษา',
            'url' => '/student-affairs/activities',
            'route_name' => 'student_affairs.activities',
            'parent_id' => $studentAffairsMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'กิจกรรมนักศึกษา',
        ]);

        Menu::create([
            'name' => 'บริการนักศึกษา',
            'url' => '/student-affairs/services',
            'route_name' => 'student_affairs.services',
            'parent_id' => $studentAffairsMenu->id,
            'sort_order' => 4,
            'icon' => null,
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการนักศึกษา',
        ]);

        // เมนูหลัก - ข่าวสาร
        $newsMenu = Menu::create([
            'name' => 'ข่าวสาร',
            'url' => '/news',
            'route_name' => 'news',
            'parent_id' => null,
            'sort_order' => 6,
            'icon' => 'fas fa-newspaper',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข่าวสารและประชาสัมพันธ์',
            'meta_data' => null,
        ]);

        // เมนูหลัก - ติดต่อเรา (ใช้ร่วมกับเมนูย่อยใน About)
        Menu::create([
            'name' => 'ติดต่อเรา',
            'url' => '/contact',
            'route_name' => 'contact',
            'parent_id' => null,
            'sort_order' => 7,
            'icon' => 'fas fa-envelope',
            'show' => 'header',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลติดต่อ',
            'meta_data' => null,
        ]);

        // เมนู Footer
        // บริการด่วน
        $quickServicesMenu = Menu::create([
            'name' => 'บริการด่วน',
            'url' => '/quick-services',
            'route_name' => 'quick_services',
            'parent_id' => null,
            'sort_order' => 1,
            'icon' => 'fas fa-tachometer-alt',
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการด่วนต่างๆ',
            'meta_data' => null,
        ]);

        Menu::create([
            'name' => 'ระบบนักศึกษา',
            'url' => '/student-system',
            'route_name' => 'student_system',
            'parent_id' => $quickServicesMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-graduation-cap',
            'show' => 'footer',
            'target' => '_blank',
            'permission' => null,
            'is_active' => true,
            'description' => 'ระบบจัดการข้อมูลนักศึกษา',
        ]);

        Menu::create([
            'name' => 'ห้องสมุด',
            'url' => '/library',
            'route_name' => 'library',
            'parent_id' => $quickServicesMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-book',
            'show' => 'footer',
            'target' => '_blank',
            'permission' => null,
            'is_active' => true,
            'description' => 'ระบบห้องสมุด',
        ]);

        Menu::create([
            'name' => 'ปฏิทินการศึกษา',
            'url' => '/academic-calendar',
            'route_name' => 'academic_calendar',
            'parent_id' => $quickServicesMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-calendar',
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ปฏิทินการศึกษา',
        ]);

        Menu::create([
            'name' => 'อีเมล',
            'url' => '/email',
            'route_name' => 'email',
            'parent_id' => $quickServicesMenu->id,
            'sort_order' => 4,
            'icon' => 'fas fa-envelope',
            'show' => 'footer',
            'target' => '_blank',
            'permission' => null,
            'is_active' => true,
            'description' => 'ระบบอีเมล',
        ]);

        // ลิงก์ด่วน Footer
        $quickLinksMenu = Menu::create([
            'name' => 'ลิงก์ด่วน',
            'url' => '/quick-links',
            'route_name' => 'quick_links',
            'parent_id' => null,
            'sort_order' => 2,
            'icon' => 'fas fa-link',
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ลิงก์ด่วนต่างๆ',
            'meta_data' => null,
        ]);

        Menu::create([
            'name' => 'รับเข้าศึกษา',
            'url' => '/admission',
            'route_name' => 'admission',
            'parent_id' => $quickLinksMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลการรับเข้าศึกษา',
        ]);

        Menu::create([
            'name' => 'ระบบสารสนเทศ',
            'url' => '/mis',
            'route_name' => 'mis',
            'parent_id' => $quickLinksMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'footer',
            'target' => '_blank',
            'permission' => null,
            'is_active' => true,
            'description' => 'ระบบสารสนเทศ',
        ]);

        Menu::create([
            'name' => 'บุคลากร',
            'url' => '/personnel',
            'route_name' => 'personnel',
            'parent_id' => $quickLinksMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลบุคลากร',
        ]);

        Menu::create([
            'name' => 'หลักสูตร',
            'url' => '/curriculum-info',
            'route_name' => 'curriculum_info',
            'parent_id' => $quickLinksMenu->id,
            'sort_order' => 4,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'ข้อมูลหลักสูตร',
        ]);

        // บริการ Footer
        $servicesFooterMenu = Menu::create([
            'name' => 'บริการ',
            'url' => '/services',
            'route_name' => 'services',
            'parent_id' => null,
            'sort_order' => 3,
            'icon' => 'fas fa-cogs',
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการต่างๆ',
            'meta_data' => null,
        ]);

        Menu::create([
            'name' => 'ห้องสมุด',
            'url' => '/library-services',
            'route_name' => 'library_services',
            'parent_id' => $servicesFooterMenu->id,
            'sort_order' => 1,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการห้องสมุด',
        ]);

        Menu::create([
            'name' => 'ศูนย์คอมพิวเตอร์',
            'url' => '/computer-center',
            'route_name' => 'computer_center',
            'parent_id' => $servicesFooterMenu->id,
            'sort_order' => 2,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการศูนย์คอมพิวเตอร์',
        ]);

        Menu::create([
            'name' => 'งานทะเบียน',
            'url' => '/registrar',
            'route_name' => 'registrar',
            'parent_id' => $servicesFooterMenu->id,
            'sort_order' => 3,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการงานทะเบียน',
        ]);

        Menu::create([
            'name' => 'กิจการนักศึกษา',
            'url' => '/student-affairs-services',
            'route_name' => 'student_affairs_services',
            'parent_id' => $servicesFooterMenu->id,
            'sort_order' => 4,
            'icon' => null,
            'show' => 'footer',
            'target' => '_self',
            'permission' => null,
            'is_active' => true,
            'description' => 'บริการกิจการนักศึกษา',
        ]);

        // เมนูสำหรับ Admin (จะแสดงเฉพาะผู้ที่มีสิทธิ์)
        $adminMenu = Menu::create([
            'name' => 'จัดการระบบ',
            'url' => '/admin',
            'route_name' => 'admin.dashboard',
            'parent_id' => null,
            'sort_order' => 100,
            'icon' => 'fas fa-cogs',
            'show' => 'sidebar',
            'target' => '_self',
            'permission' => 'admin.access',
            'is_active' => true,
            'description' => 'จัดการระบบงาน',
            'meta_data' => null,
        ]);

        Menu::create([
            'name' => 'จัดการเมนู',
            'url' => '/admin/menus',
            'route_name' => 'admin.menus.index',
            'parent_id' => $adminMenu->id,
            'sort_order' => 1,
            'icon' => 'fas fa-bars',
            'show' => 'sidebar',
            'target' => '_self',
            'permission' => 'menus.manage',
            'is_active' => true,
            'description' => 'จัดการเมนูเว็บไซต์',
        ]);

        Menu::create([
            'name' => 'จัดการข่าวสาร',
            'url' => '/admin/news',
            'route_name' => 'admin.news.index',
            'parent_id' => $adminMenu->id,
            'sort_order' => 2,
            'icon' => 'fas fa-newspaper',
            'show' => 'sidebar',
            'target' => '_self',
            'permission' => 'news.manage',
            'is_active' => true,
            'description' => 'จัดการข่าวสารและประชาสัมพันธ์',
        ]);

        Menu::create([
            'name' => 'จัดการผู้ใช้งาน',
            'url' => '/admin/users',
            'route_name' => 'admin.users.index',
            'parent_id' => $adminMenu->id,
            'sort_order' => 3,
            'icon' => 'fas fa-users',
            'show' => 'sidebar',
            'target' => '_self',
            'permission' => 'users.manage',
            'is_active' => true,
            'description' => 'จัดการผู้ใช้งานระบบ',
        ]);

        $this->command->info('Menu seeder completed successfully!');
    }
}