<?php
// database/seeders/BlogCategoriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Parent Categories
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest news and trends in technology',
                'color' => '#3B82F6',
                'icon' => 'fas fa-laptop-code',
                'meta_title' => 'Technology News & Articles',
                'meta_description' => 'Stay updated with the latest technology news, trends, and innovations.',
                'meta_keywords' => 'technology, tech news, innovation, gadgets',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Tips and ideas for better living',
                'color' => '#EC4899',
                'icon' => 'fas fa-heart',
                'meta_title' => 'Lifestyle Tips & Ideas',
                'meta_description' => 'Discover lifestyle tips, health advice, and wellness ideas.',
                'meta_keywords' => 'lifestyle, health, wellness, tips',
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business insights and entrepreneurship',
                'color' => '#059669',
                'icon' => 'fas fa-briefcase',
                'meta_title' => 'Business & Entrepreneurship',
                'meta_description' => 'Business news, startup advice, and entrepreneurship insights.',
                'meta_keywords' => 'business, entrepreneurship, startup, finance',
                'parent_id' => null,
                'sort_order' => 3,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Travel guides and destination reviews',
                'color' => '#F59E0B',
                'icon' => 'fas fa-plane',
                'meta_title' => 'Travel Guides & Tips',
                'meta_description' => 'Explore amazing destinations with our travel guides and tips.',
                'meta_keywords' => 'travel, destinations, guides, tourism',
                'parent_id' => null,
                'sort_order' => 4,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Learning resources and educational content',
                'color' => '#8B5CF6',
                'icon' => 'fas fa-graduation-cap',
                'meta_title' => 'Educational Resources & Learning',
                'meta_description' => 'Educational articles, learning resources, and academic insights.',
                'meta_keywords' => 'education, learning, academic, resources',
                'parent_id' => null,
                'sort_order' => 5,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert parent categories first
        foreach ($categories as $category) {
            DB::table('blog_categories')->insert($category);
        }

        // Get parent category IDs for subcategories
        $techId = DB::table('blog_categories')->where('slug', 'technology')->first()->id;
        $lifestyleId = DB::table('blog_categories')->where('slug', 'lifestyle')->first()->id;
        $businessId = DB::table('blog_categories')->where('slug', 'business')->first()->id;

        // Subcategories
        $subcategories = [
            // Technology subcategories
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Web development tutorials and frameworks',
                'color' => '#1E40AF',
                'icon' => 'fas fa-code',
                'meta_title' => 'Web Development Tutorials',
                'meta_description' => 'Learn web development with our tutorials and guides.',
                'meta_keywords' => 'web development, programming, HTML, CSS, JavaScript',
                'parent_id' => $techId,
                'sort_order' => 1,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mobile Apps',
                'slug' => 'mobile-apps',
                'description' => 'Mobile app development and reviews',
                'color' => '#3730A3',
                'icon' => 'fas fa-mobile-alt',
                'meta_title' => 'Mobile App Development',
                'meta_description' => 'Mobile app development tutorials and app reviews.',
                'meta_keywords' => 'mobile apps, iOS, Android, app development',
                'parent_id' => $techId,
                'sort_order' => 2,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AI & Machine Learning',
                'slug' => 'ai-machine-learning',
                'description' => 'Artificial intelligence and ML content',
                'color' => '#1E3A8A',
                'icon' => 'fas fa-robot',
                'meta_title' => 'AI & Machine Learning',
                'meta_description' => 'Explore artificial intelligence and machine learning topics.',
                'meta_keywords' => 'AI, artificial intelligence, machine learning, ML',
                'parent_id' => $techId,
                'sort_order' => 3,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Lifestyle subcategories
            [
                'name' => 'Health & Fitness',
                'slug' => 'health-fitness',
                'description' => 'Health tips and fitness routines',
                'color' => '#BE185D',
                'icon' => 'fas fa-dumbbell',
                'meta_title' => 'Health & Fitness Tips',
                'meta_description' => 'Get healthy with our fitness routines and health tips.',
                'meta_keywords' => 'health, fitness, exercise, wellness',
                'parent_id' => $lifestyleId,
                'sort_order' => 1,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Food & Cooking',
                'slug' => 'food-cooking',
                'description' => 'Recipes and cooking tips',
                'color' => '#BE123C',
                'icon' => 'fas fa-utensils',
                'meta_title' => 'Food & Cooking Recipes',
                'meta_description' => 'Delicious recipes and cooking tips for every occasion.',
                'meta_keywords' => 'food, cooking, recipes, cuisine',
                'parent_id' => $lifestyleId,
                'sort_order' => 2,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Business subcategories
            [
                'name' => 'Startups',
                'slug' => 'startups',
                'description' => 'Startup advice and success stories',
                'color' => '#047857',
                'icon' => 'fas fa-rocket',
                'meta_title' => 'Startup Advice & Stories',
                'meta_description' => 'Learn from startup success stories and expert advice.',
                'meta_keywords' => 'startups, entrepreneurship, business advice',
                'parent_id' => $businessId,
                'sort_order' => 1,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing strategies and tips',
                'color' => '#065F46',
                'icon' => 'fas fa-bullhorn',
                'meta_title' => 'Digital Marketing Strategies',
                'meta_description' => 'Effective digital marketing strategies and tips.',
                'meta_keywords' => 'marketing, digital marketing, SEO, social media',
                'parent_id' => $businessId,
                'sort_order' => 2,
                'is_active' => true,
                'posts_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert subcategories
        foreach ($subcategories as $subcategory) {
            DB::table('blog_categories')->insert($subcategory);
        }
    }
}
