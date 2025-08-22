<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use Modules\Blog\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create super admin user
        $this->superAdmin = User::factory()->create([
            'role' => UserRole::SUPER_ADMIN
        ]);
        
        // Create regular user
        $this->regularUser = User::factory()->create([
            'role' => UserRole::USER
        ]);
    }

    #[Test]
    public function it_can_list_categories()
    {
        // Create some categories
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'result' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);
    }

    #[Test]
    public function it_can_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'result' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug
                    ]
                ]);
    }

    #[Test]
    public function super_admin_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category'
        ];

        $response = $this->actingAs($this->superAdmin, 'sanctum')
                        ->postJson('/api/v1/categories', $categoryData);

        $response->assertStatus(201)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'دسته‌بندی با موفقیت ایجاد شد'
                ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }

    #[Test]
    public function regular_user_cannot_create_category()
    {
        $categoryData = [
            'name' => 'Test Category'
        ];

        $response = $this->actingAs($this->regularUser, 'sanctum')
                        ->postJson('/api/v1/categories', $categoryData);

        $response->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_update_category()
    {
        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Updated Category'
        ];

        $response = $this->actingAs($this->superAdmin, 'sanctum')
                        ->putJson("/api/v1/categories/{$category->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'دسته‌بندی با موفقیت بروزرسانی شد'
                ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }

    #[Test]
    public function super_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->superAdmin, 'sanctum')
                        ->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'دسته‌بندی با موفقیت حذف شد'
                ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    #[Test]
    public function category_name_must_be_unique()
    {
        // Create first category
        Category::factory()->create(['name' => 'Test Category']);

        // Try to create duplicate
        $response = $this->actingAs($this->superAdmin, 'sanctum')
                        ->postJson('/api/v1/categories', [
                            'name' => 'Test Category'
                        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }
}
