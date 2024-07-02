<?php

namespace App\Http\Request\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            case 'admin/order/create':
                return [
                    'customer_id' => 'required',
                    'bkg_type' => 'nullable|between:1,8',
                    'node_ids' => 'required_if:bkg_type,8',
                ];

            default:
                return [];
        }
    }

//    public function attributes()
//    {
//        return [
//            'name' => 'ユーザー名',
//            'password' => 'パスワード'
//        ];
//    }
}
