<?php

namespace Modules\Blog\Services\Category;

use Illuminate\Http\Request;
use Modules\Blog\Models\Category;

class ShowService
{
    /**
     * Handle the show request and return structured data
     */
    public function request(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Execute the show operation by ID
     */
    public function show(int $id): array
    {
        $category = Category::withCount('posts')->find($id);

        if (!$category) {
            return [
                'status' => 'error',
                'message' => 'دسته‌بندی یافت نشد',
                'result' => null
            ];
        }

        return [
            'status' => 'success',
            'result' => $category,
            'message' => 'دسته‌بندی با موفقیت دریافت شد'
        ];
    }

    /**
     * Execute the show operation by slug
     */
    public function showBySlug(string $slug): array
    {
        $category = Category::withCount('posts')->where('slug', $slug)->first();

        if (!$category) {
            return [
                'status' => 'error',
                'message' => 'دسته‌بندی یافت نشد',
                'result' => null
            ];
        }

        return [
            'status' => 'success',
            'result' => $category,
            'message' => 'دسته‌بندی با موفقیت دریافت شد'
        ];
    }
}
