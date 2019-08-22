<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * パスワードリマインダー(パスワード再発行)処理のフォームリクエスト
 */
class PasswordRemindRecieveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'passwordRemindRecieve') {
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
            'auth_key' => 'required|same_auth_key_verifi'
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
            'auth_key.required' => '入力必須です。',
            'auth_key.same_auth_key_verifi' => '認証キーが違います。'
        ];
    }
}
