<?php

namespace App\Http\Request\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CustomerRequest extends FormRequest
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
            case 'admin/customer/save':
                return [
                    'company_name' => 'required',
                    'short_name' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'header' => 'required',
                    'mobile' => 'required',
                    'legal_number' => 'required',
                ];
            case 'admin/customer/delete':
                return [
                    'id' => 'required'
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
