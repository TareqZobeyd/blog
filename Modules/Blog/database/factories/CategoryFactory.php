<?php

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}

