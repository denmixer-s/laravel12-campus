<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user (Super Admin) as default author
        $user = User::first();

        if (!$user) {
            $this->command->error('No users found! Please run UsersSeeder first.');
            return;
        }

        // Optional: Clear existing pages (uncomment if you want fresh start)
        // \DB::table('pages')->delete();
        // $this->command->info('Cleared existing pages');

        $pages = [
            [
                'title' => 'หน้าแรก',
                'slug' => 'home',
                'content' => '<h1>ยินดีต้อนรับสู่ RSTC</h1><p>เนื้อหาหน้าแรกของเว็บไซต์ ระบบจัดการเนื้อหาที่ทันสมัยและใช้งานง่าย</p><p>เราพัฒนาด้วย Laravel Framework พร้อมฟีเจอร์ครบครันสำหรับการจัดการเว็บไซต์</p>',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'เกี่ยวกับเรา',
                'slug' => 'about',
                'content' => '<h1>เกี่ยวกับ RSTC</h1><p>RSTC เป็นระบบจัดการเนื้อหา (Content Management System) ที่พัฒนาด้วย Laravel Framework</p><p>ระบบนี้ออกแบบมาเพื่อความง่ายในการใช้งานและมีประสิทธิภาพสูง เหมาะสำหรับองค์กรทุกขนาด</p><h2>ฟีเจอร์หลัก</h2><ul><li>จัดการผู้ใช้งานและสิทธิ์</li><li>จัดการเนื้อหาและหน้าเพจ</li><li>ระบบเมนูที่ยืดหยุ่น</li><li>การจัดการเอกสาร</li></ul>',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'บริการของเรา',
                'slug' => 'services',
                'content' => '<h1>บริการของเรา</h1><p>เราให้บริการพัฒนาระบบจัดการเนื้อหาที่ครบครันและตอบโจทย์ความต้องการของลูกค้า</p><h2>บริการหลัก</h2><ul><li><strong>พัฒนาเว็บไซต์</strong> - ออกแบบและพัฒนาเว็บไซต์ตามความต้องการ</li><li><strong>ระบบจัดการเนื้อหา</strong> - CMS ที่ใช้งานง่ายและมีประสิทธิภาพ</li><li><strong>ปรับแต่งระบบ</strong> - Customize ตามความต้องการเฉพาะ</li><li><strong>ฝึกอบรม</strong> - อบรมการใช้งานระบบ</li></ul>',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'ติดต่อเรา',
                'slug' => 'contact',
                'content' => '<h1>ติดต่อเรา</h1><p>หากคุณสนใจบริการของเรา หรือมีคำถามใดๆ สามารถติดต่อเราได้ผ่านช่องทางต่างๆ</p><h2>ข้อมูลการติดต่อ</h2><p><strong>อีเมล:</strong> info@rstc.local<br><strong>โทรศัพท์:</strong> 02-xxx-xxxx<br><strong>ที่อยู่:</strong> กรุงเทพมหานคร</p><h2>เวลาทำการ</h2><p>จันทร์ - ศุกร์: 09:00 - 18:00<br>เสาร์ - อาทิตย์: ปิดทำการ</p>',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'นโยบายความเป็นส่วนตัว',
                'slug' => 'privacy-policy',
                'content' => '<h1>นโยบายความเป็นส่วนตัว</h1><p>เราให้ความสำคัญกับความเป็นส่วนตัวของข้อมูลของคุณ</p><h2>การเก็บรวบรวมข้อมูล</h2><p>เราเก็บรวบรวมข้อมูลเฉพาะที่จำเป็นสำหรับการให้บริการเท่านั้น</p><h2>การใช้ข้อมูล</h2><p>ข้อมูลของคุณจะถูกใช้เพื่อ:</p><ul><li>ให้บริการตามที่ร้องขอ</li><li>ปรับปรุงคุณภาพการบริการ</li><li>ติดต่อสื่อสารในเรื่องที่เกี่ยวข้อง</li></ul><p>เราจะไม่เปิดเผยข้อมูลของคุณต่อบุคคลที่สาม โดยไม่ได้รับความยินยอมจากคุณ</p>',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'เงื่อนไขการใช้งาน',
                'slug' => 'terms-of-service',
                'content' => '<h1>เงื่อนไขการใช้งาน</h1><p>การใช้บริการนี้ถือว่าคุณยอมรับเงื่อนไขต่อไปนี้</p><h2>การใช้งาน</h2><p>คุณสามารถใช้บริการนี้เพื่อวัตถุประสงค์ที่ถูกกฎหมายเท่านั้น</p><h2>ข้อจำกัดความรับผิดชอบ</h2><p>เราจะไม่รับผิดชอบต่อความเสียหายใดๆ ที่เกิดจากการใช้งานระบบ</p><h2>การยกเลิกบริการ</h2><p>เราขอสงวนสิทธิ์ในการยกเลิกหรือระงับบริการได้ตามความเหมาะสม</p>',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($pages as $pageData) {
            $existingPage = \DB::table('pages')->where('slug', $pageData['slug'])->first();

            if ($existingPage) {
                $this->command->warn("Page with slug '{$pageData['slug']}' already exists. Skipping...");
            } else {
                \DB::table('pages')->insert($pageData);
                $this->command->info("Created page: {$pageData['title']}");
            }
        }

        $this->command->info('Pages created successfully!');
    }
}
