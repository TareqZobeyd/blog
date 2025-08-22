<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'تکنولوژی',
            'برنامه‌نویسی',
            'طراحی وب',
            'هوش مصنوعی',
            'بلاکچین',
            'موبایل',
            'بازی',
            'سخت‌افزار',
            'نرم‌افزار',
            'امنیت سایبری',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => \Illuminate\Support\Str::slug($categoryName),
            ]);
        }
    }
}

