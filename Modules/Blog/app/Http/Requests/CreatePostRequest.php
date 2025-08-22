<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Blog\Enums\PostStatus;

class CreatePostRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:posts',
            'content' => 'required|string|min:10',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable|date|after_or_equal:now',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // تغییر شد
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان پست الزامی است',
            'title.max' => 'عنوان پست نمی‌تواند بیشتر از 255 کاراکتر باشد',
            'title.unique' => 'این عنوان قبلاً استفاده شده است',
            
            'content.required' => 'محتوای پست الزامی است',
            'content.min' => 'محتوای پست باید حداقل 10 کاراکتر باشد',
            
            'status.required' => 'وضعیت پست الزامی است',
            
            'published_at.after_or_equal' => 'تاریخ انتشار نمی‌تواند در گذشته باشد',
            
            'img.image' => 'فایل باید تصویر باشد',
            'img.mimes' => 'فرمت تصویر باید jpeg, png, jpg یا gif باشد',
            'img.max' => 'حجم تصویر نمی‌تواند بیشتر از 2 مگابایت باشد',
            
            'category_ids.required' => 'انتخاب دسته‌بندی الزامی است',
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
