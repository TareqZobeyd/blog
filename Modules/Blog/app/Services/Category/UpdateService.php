<?php

namespace Modules\Blog\Services\Category;

use Modules\Blog\Http\Requests\UpdateCategoryRequest;
use Modules\Blog\Models\Category;
use Modules\Blog\Transformers\CategoryResource;

class UpdateService
{
    /**
     * Execute the update operation
     */
    public function update(UpdateCategoryRequest $request, Category $category): array
    {
        $validatedData = $request->validated();
        
        $success = $category->update($validatedData);

        if (!$success) {
            return [
                'status' => 'error',
                'message' => 'خطا در بروزرسانی دسته‌بندی',
                'result' => null
            ];
        }

        return [
            'status' => 'success',
            'result' => new CategoryResource($category->fresh()),
            'message' => 'دسته‌بندی با موفقیت بروزرسانی شد'
        ];
    }
}
