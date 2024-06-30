<?php

namespace App\Http\Request\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
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
        $uri = $this->route()->uri();
        switch ($uri) {
            case 'admin/login':
                return [
                    'username' => 'required',
                    'password' => 'required',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザー名',
            'password' => 'パスワード'
        ];
    }
}
