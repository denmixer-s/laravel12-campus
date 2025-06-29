<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SlidersSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user (Super Admin) as default author
        $user = User::first();

        if (!$user) {
            $this->command->error('No users found! Please run UsersSeeder first.');
            return;
        }

        $sliders = [
            [
                'heading' => 'ยินดีต้อนรับสู่ RSTC',
                'description' => 'ระบบจัดการเนื้อหาที่ทันสมัย ออกแบบมาเพื่อความง่ายในการใช้งานและมีประสิทธิภาพสูง พัฒนาด้วย Laravel Framework',
                'link' => '/about',
                'show' => 'home',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'heading' => 'บริการที่เหนือกว่า',
                'description' => 'เราให้บริการพัฒนาเว็บไซต์และระบบจัดการเนื้อหาด้วยความเป็นมืออาชีพ คุณภาพที่คุณไว้วางใจได้',
                'link' => '/services',
                'show' => 'frontend',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'heading' => 'ติดต่อเราได้ตลอดเวลา',
                'description' => 'ทีมงานของเราพร้อมให้การสนับสนุนและดูแลลูกค้าอย่างใกล้ชิด บริการลูกค้าที่เป็นเลิศ',
                'link' => '/contact',
                'show' => 'both',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'heading' => 'เทคโนโลยีล้ำสมัย',
                'description' => 'ใช้เทคโนโลยีล่าสุดและมาตรฐานสากลในการพัฒนาระบบ พัฒนาด้วย Laravel Framework และ Modern PHP',
                'link' => '/about',
                'show' => 'frontend',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'heading' => 'ระบบจัดการที่ครบครัน',
                'description' => 'ฟีเจอร์ครบครันสำหรับการจัดการเว็บไซต์ ตั้งแต่การจัดการผู้ใช้ เนื้อหา เมนู และเอกสาร',
                'link' => '/services',
                'show' => 'home',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($sliders as $sliderData) {
            \DB::table('sliders')->insert($sliderData);
            $this->command->info("Created slider: {$sliderData['heading']} (Show: {$sliderData['show']})");
        }

        $this->command->info('Sliders created successfully!');
    }
}
