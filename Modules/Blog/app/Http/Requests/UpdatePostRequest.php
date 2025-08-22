<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Blog\Enums\PostStatus;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($this->post),
            ],
            'content' => 'sometimes|string|min:10',
            'status' => 'sometimes|in:draft,published',
            'published_at' => 'nullable|date|after_or_equal:now',
            'img' => 'nullable|string|max:500',
            'category_ids' => 'sometimes|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.max' => 'عنوان پست نمی‌تواند بیشتر از 255 کاراکتر باشد',
            'title.unique' => 'این عنوان قبلاً استفاده شده است',
            
            'content.min' => 'محتوای پست باید حداقل 10 کاراکتر باشد',
            
            'published_at.after_or_equal' => 'تاریخ انتشار نمی‌تواند در گذشته باشد',
            
            'img.max' => 'آدرس تصویر نمی‌تواند بیشتر از 500 کاراکتر باشد',
            
            'category_ids.min' => 'حداقل یک دسته‌بندی باید انتخاب شود',
            'category_ids.*.exists' => 'دسته‌بندی انتخاب شده معتبر نیست',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'عنوان پست',
            'content' => 'محتوای پست',
            'status' => 'وضعیت پست',
            'published_at' => 'تاریخ انتشار',
            'img' => 'تصویر',
            'category_ids' => 'دسته‌بندی‌ها',
        ];
    }
}
