<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRemindSendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'passwordRemindSend') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|between:0,255|same_email_verifi'
        ];
    }

    /**
     * validation errmessage
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => '入力必須です。',
            'email.email' => 'メールアドレスの形式で入力してください。',
            'email.between' => '255文字以内で入力してください。',
            'email.same_email_verifi' => '登録されていないメールアドレスです。'
        ];
    }
}
