<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام کاربری الزامی است',
            'name.string' => 'نام کاربری باید متن باشد',
            'name.max' => 'نام کاربری نمی‌تواند بیشتر از 255 کاراکتر باشد',
            
            'email.required' => 'ایمیل الزامی است',
            'email.string' => 'ایمیل باید متن باشد',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'email.max' => 'ایمیل نمی‌تواند بیشتر از 255 کاراکتر باشد',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است',
            
            'password.required' => 'رمز عبور الزامی است',
            'password.string' => 'رمز عبور باید متن باشد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'password.confirmed' => 'رمز عبور و تکرار آن یکسان نیستند',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام کاربری',
            'email' => 'ایمیل',
            'password' => 'رمز عبور',
        ];
    }
}
