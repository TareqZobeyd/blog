<?php

namespace Modules\Blog\Services\Category;

use Modules\Blog\Http\Requests\CreateCategoryRequest;
use Modules\Blog\Models\Category;

class CreateService
{
    /**
     * Execute the create operation
     */
    public function create(CreateCategoryRequest $request): array
    {
        $validatedData = $request->validated();
        
        $category = Category::create($validatedData);

        return [
            'status' => 'success',
            'result' => $category,
            'message' => 'دسته‌بندی با موفقیت ایجاد شد'
        ];
    }
}
