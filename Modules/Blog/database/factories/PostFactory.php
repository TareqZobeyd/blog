<?php

namespace Modules\Blog\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Blog\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        $status = $this->faker->randomElement([PostStatus::DRAFT, PostStatus::PUBLISHED]);
        
        return [
            'title' => $title,
            'img' => $this->faker->imageUrl(640, 480, 'nature'),
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraphs(5, true),
            'status' => $status,
            'published_at' => $status === PostStatus::PUBLISHED ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::PUBLISHED,
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the post is draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::DRAFT,
            'published_at' => null,
        ]);
    }
}

