<?php

namespace App\Http\Validators;

use Illuminate\Validation\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;

class CustomValidator extends Validator
{
    /**
     * Twitterアカウント @チェック
     * @param string $value
     * @return boolean
     */
    public function validateAtSignCheck($attribute, $value, $parameters) 
    {
        return !preg_match('/^@/', $value);
    }



    /**
     * 半角英数字チェック
     * @param stirng $value
     * @return boolean
     */
    public function validateAlphaNumCheck($attribute, $value, $parameters)
    {
        return preg_match('/^[A-Za-z\d]+$/', $value);
    }

    /**
     * 現在パスワードチェック
     * @param string $value
     * @return boolean
     */
    public function validatePassVerifi($attribute, $value, $parameters)
    {
        $user = User::where('id', Auth::id())->first();
        if (Auth::attempt(['email' => $user->email, 'password' => $value])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * メールアドレス既存チェック
     * @param string $value
     * @return boolean
     */
    public function validateSameEmailVerifi($attribute, $value, $parameters)
    {
        $user = User::where('email', $value)->where('delete_flg', false)->count();
        if ($user == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 認証キー同値チェック
     * @param string $value
     * @return boolean
     */
    public function validateSameAuthKeyVerifi($attribute, $value, $parameters)
    {
        $auth_key = session()->get('auth_key');
        if ($auth_key !== $value) {
            return false;
        } else {
            return true;
        }
    }
}
