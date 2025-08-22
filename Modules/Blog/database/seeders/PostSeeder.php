<?php

namespace Modules\Blog\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Post;
use Modules\Blog\Enums\PostStatus;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a user
        $user = User::first() ?? User::factory()->create();
        
        // Get all categories
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        // Create sample posts
        $posts = [
            [
                'title' => 'آموزش Laravel برای مبتدیان',
                'content' => 'Laravel یکی از محبوب‌ترین فریم‌ورک‌های PHP است که برای توسعه وب‌اپلیکیشن‌ها استفاده می‌شود. در این مقاله، ما اصول اولیه Laravel را بررسی می‌کنیم.',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(5),
                'categories' => ['برنامه‌نویسی', 'تکنولوژی'],
            ],
            [
                'title' => 'مقدمه‌ای بر هوش مصنوعی',
                'content' => 'هوش مصنوعی (AI) یکی از هیجان‌انگیزترین حوزه‌های تکنولوژی است که در حال تغییر جهان ما است. بیایید ببینیم AI چیست و چگونه کار می‌کند.',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
                'categories' => ['هوش مصنوعی', 'تکنولوژی'],
            ],
            [
                'title' => 'طراحی رابط کاربری مدرن',
                'content' => 'طراحی رابط کاربری (UI) یکی از مهم‌ترین جنبه‌های توسعه نرم‌افزار است. در این مقاله، اصول طراحی UI مدرن را بررسی می‌کنیم.',
                'status' => PostStatus::DRAFT,
                'published_at' => null,
                'categories' => ['طراحی وب', 'نرم‌افزار'],
            ],
            [
                'title' => 'امنیت در توسعه وب',
                'content' => 'امنیت یکی از مهم‌ترین جنبه‌های توسعه وب است. در این مقاله، تهدیدات امنیتی رایج و راه‌های محافظت در برابر آنها را بررسی می‌کنیم.',
                'status' => PostStatus::PUBLISHED,
                'published_at' => now()->subDays(1),
                'categories' => ['امنیت سایبری', 'برنامه‌نویسی'],
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'img' => 'https://picsum.photos/800/400?random=' . rand(1, 1000),
                'slug' => \Illuminate\Support\Str::slug($postData['title']),
                'content' => $postData['content'],
                'status' => $postData['status'],
                'published_at' => $postData['published_at'],
                'user_id' => $user->id,
            ]);

            // Attach categories
            $categoryIds = $categories->whereIn('name', $postData['categories'])->pluck('id');
            $post->categories()->attach($categoryIds);
        }
    }
}

