<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeTwitterAccount extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'changeTwitterAccount') {
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
            'screen_name' => 'required|at_sign_check|alpha_num_check|between:0,15'
        ];
    }

    /**
     * validation errmessage
     * @return array
     */
    public function messages()
    {
        return [
            'screen_name.required' => '入力必須です。',
            'screen_name.between' => '15文字以内で入力してください。',
            'screen_name.alpha_num_check' => '半角英数字にて入力してください。',
            'screen_name.at_sign_check' => '@の後ろの文字を入力してください。'
        ];
    }
}
