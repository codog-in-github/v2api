<?php

namespace App\Http\Request\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RequestBookRequest extends FormRequest
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
            case 'admin/request_book/save':
                return [
                    'type' => 'required',
                    'no' => 'required',
                    'date' => 'required|date',
                    'zip_code' => 'required',
                    'company_name' => 'required',
                    'company_address' => 'required',
                    'total_amount' => 'required|numeric',
                    'tax' => 'required|numeric',
                    'request_amount' => 'required|numeric',
                    'bank' => 'required',
                    'address' => 'required',
                    'is_stamp' => 'required|in:0,1',
                    'details' => 'required|array',
                    'extras' => 'required|array',
                    'counts' => 'required|array',
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
