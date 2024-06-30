<?php

namespace App\Http\Request\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PermissionRequest extends FormRequest
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
            case 'admin/permission/save_role':
                return [
                    'name' => 'required',
                    'sort' => 'required',
                ];
            case 'admin/permission/role_bind_permission':
                return [
                    'role_id' => 'required|integer',
                    'permission_ids' => 'required|array',
                    'permission_ids.*' => 'required|integer',
                ];
            case 'admin/permission/del_role':
                return [
                    'role_id' => 'required|integer',
                ];
                case 'admin/permission/user_permission':
                return [
                    'user_id' => 'required|integer',
                ];
            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザー名',
            'sort' => 'ソート',
            "permission_ids" => "権限のリスト",
            "user_id" => "ユーザーID",
        ];
    }

    public function messages()
    {
        return [
            'name.*' => "ユーザー名は必須です",
            'sort.*' => "ソートは必須です",
            'permission_ids.*' => "権限のリスト形式が正しくありません",
        ];
    }
}
