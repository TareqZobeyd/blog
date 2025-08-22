<?php

namespace Modules\Blog\Services\Category;

use Illuminate\Http\Request;
use Modules\Blog\Models\Category;

class DeleteService
{
    /**
     * Handle the delete request and return structured data
     */
    public function request(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Execute the delete operation
     */
    public function delete(Category $category): array
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return [
                'status' => 'error',
                'message' => 'نمی‌توان دسته‌بندی دارای پست را حذف کرد',
                'result' => null
            ];
        }

        $success = $category->delete();

        if (!$success) {
            return [
                'status' => 'error',
                'message' => 'خطا در حذف دسته‌بندی',
                'result' => null
            ];
        }

        return [
            'status' => 'success',
            'message' => 'دسته‌بندی با موفقیت حذف شد',
            'result' => null
        ];
    }
}
