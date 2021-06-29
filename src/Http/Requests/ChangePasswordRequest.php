<?php

namespace Dizatech\Identifier\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_password' => ['required', 'min:6'],
            'password_confirm' => ['required', 'same:new_password', 'min:6'],
        ];
    }

    public function messages()
    {
        return [
            'new_password.required' => 'لطفا رمز عبور جدید را وارد نمایید.',
            'new_password.min' => 'حداقل ۶ کاراکتر را وارد نمایید',
            'password_confirm.min' => 'حداقل ۶ کاراکتر را وارد نمایید',
            'password_confirm.required' => 'لطفا تکرار رمز عبور جدید را وارد نمایید.',
            'password_confirm.same' => 'رمز عبورهای وارد شده یکسان نمی‌باشند.'
        ];
    }
}
