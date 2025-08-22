<?php

namespace Modules\Blog\Services\Category;

use Illuminate\Http\Request;
use Modules\Blog\Models\Category;
use Modules\Blog\Transformers\CategoryResource;

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
            'result' => new CategoryResource($category),
            'message' => 'دسته‌بندی با موفقیت دریافت شد'
        ];
    }


}
