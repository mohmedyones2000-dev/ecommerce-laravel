<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,strict',
                'regex:/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9][a-zA-Z0-9.-]*\.[a-zA-Z]{2,}$/',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.max' => 'الاسم طويل جداً (الحد الأقصى 255 حرف).',

            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صحيح.',
            'email.regex' => 'يجب إدخال بريد إلكتروني بصيغة صحيحة (مثل example@gmail.com).',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم من قبل.',
            'email.max' => 'البريد الإلكتروني طويل جداً.',

            'phone.max' => 'رقم الهاتف طويل جداً (الحد الأقصى 20 حرف).',

            'avatar.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'avatar.mimes' => 'الصورة يجب أن تكون بصيغة: jpg, jpeg, png, webp.',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}