<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'mypage/changePassword') {
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
            'old_pass' => 'required|alpha_num_check|pass_verifi|min:8|different:password',
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
            'old_pass.required' => '入力必須です。',
            'old_pass.alpha_num_check' => '半角英数字にて入力してください。',
            'old_pass.pass_verifi' => '現在のパスワードが違います。',
            'old_pass.min' => '8桁以上にて入力してください。',
            'old_pass.different' => '新しいパスワードが現在のものと同じです。',
            'password.required' => '入力必須です。',
            'password.alpha_num_check' => '半角英数字にて入力してください。',
            'password.min' => '8桁以上にて入力してください。',
            'password.confirmed' => 'パスワードが確認欄と一致していません。'
        ];
    }
}
