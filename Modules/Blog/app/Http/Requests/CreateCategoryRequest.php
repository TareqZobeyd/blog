<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Check if user has super admin role
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()->toArray(),
                'result' => null
            ], 422)
        );
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام دسته‌بندی الزامی است',
            'name.string' => 'نام دسته‌بندی باید متن باشد',
            'name.max' => 'نام دسته‌بندی نمی‌تواند بیشتر از 255 کاراکتر باشد',
            'name.unique' => 'این نام دسته‌بندی قبلاً ثبت شده است',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام دسته‌بندی',
        ];
    }
}

