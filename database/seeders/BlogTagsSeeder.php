<?php
// database/seeders/BlogTagsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Technology tags
            [
                'name' => 'PHP',
                'slug' => 'php',
                'description' => 'PHP programming language articles',
                'color' => '#777BB4',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'description' => 'Laravel framework tutorials and tips',
                'color' => '#FF2D20',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'JavaScript programming tutorials',
                'color' => '#F7DF1E',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'React',
                'slug' => 'react',
                'description' => 'React.js library tutorials',
                'color' => '#61DAFB',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vue-js',
                'description' => 'Vue.js framework articles',
                'color' => '#4FC08D',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'description' => 'Python programming language',
                'color' => '#3776AB',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Node.js',
                'slug' => 'node-js',
                'description' => 'Node.js runtime environment',
                'color' => '#339933',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'description' => 'MySQL database tutorials',
                'color' => '#4479A1',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'description' => 'Docker containerization platform',
                'color' => '#2496ED',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AWS',
                'slug' => 'aws',
                'description' => 'Amazon Web Services cloud platform',
                'color' => '#FF9900',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // General tags
            [
                'name' => 'Tutorial',
                'slug' => 'tutorial',
                'description' => 'Step-by-step tutorials',
                'color' => '#10B981',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tips',
                'slug' => 'tips',
                'description' => 'Helpful tips and tricks',
                'color' => '#3B82F6',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beginner',
                'slug' => 'beginner',
                'description' => 'Content for beginners',
                'color' => '#8B5CF6',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Advanced',
                'slug' => 'advanced',
                'description' => 'Advanced level content',
                'color' => '#EF4444',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Best Practices',
                'slug' => 'best-practices',
                'description' => 'Industry best practices',
                'color' => '#F59E0B',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Business tags
            [
                'name' => 'Entrepreneurship',
                'slug' => 'entrepreneurship',
                'description' => 'Entrepreneurship and business building',
                'color' => '#059669',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Productivity',
                'slug' => 'productivity',
                'description' => 'Productivity tips and tools',
                'color' => '#7C3AED',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Remote Work',
                'slug' => 'remote-work',
                'description' => 'Remote work strategies',
                'color' => '#0891B2',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Lifestyle tags
            [
                'name' => 'Health',
                'slug' => 'health',
                'description' => 'Health and wellness content',
                'color' => '#DC2626',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fitness',
                'slug' => 'fitness',
                'description' => 'Fitness and exercise content',
                'color' => '#B91C1C',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Travel guides and experiences',
                'color' => '#D97706',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Food and cooking content',
                'color' => '#EA580C',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Education tags
            [
                'name' => 'Learning',
                'slug' => 'learning',
                'description' => 'Learning resources and methods',
                'color' => '#7C2D12',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Career',
                'slug' => 'career',
                'description' => 'Career development and advice',
                'color' => '#92400E',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Skills',
                'slug' => 'skills',
                'description' => 'Skill development content',
                'color' => '#451A03',
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert all tags
        foreach ($tags as $tag) {
            DB::table('blog_tags')->insert($tag);
        }
    }
}
