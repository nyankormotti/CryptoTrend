<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       if($this->path() == 'signup') {
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
            'screen_name' => 'required|at_sign_check|alpha_num_check|between:0,15|unique:users',
            'email' => 'required|email|between:0,255|unique:users',
            'password' => 'required|alpha_num_check|min:8|confirmed'
        ];
    }

    /**
     * エラーメッセージ
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'screen_name.required' => '入力必須です。',
            'screen_name.between' => '15文字以内で入力してください。',
            'screen_name.alpha_num_check' => '半角英数字にて入力してください。',
            'screen_name.at_sign_check' => '@の後ろの文字を入力してください。',
            'screen_name.unique' => 'このTwitterアカウントはすでに使用されています。',
            'email.required' => '入力必須です。',
            'email.email' => 'メールアドレスの形式で入力してください。',
            'email.between' => '255文字以内で入力してください。',
            'email.unique' => 'このメールアドレスはすでに使用されています。',
            'password.required' => '入力必須です。',
            'password.alpha_num_check' => '半角英数字にて入力してください。',
            'password.min' => '8桁以上にて入力してください。',
            'password.confirmed' => 'パスワードが確認欄と一致していません。'
        ];
    }
}
