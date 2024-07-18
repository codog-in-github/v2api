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
            case 'admin/order/detail':
                return [
                    'keyword' => 'required'
                ];
            case 'admin/order/edit_order':
                return [
                    'id' => 'required',
                    'bkg_type' => 'nullable|between:1,8',
                    'node_ids' => 'required_if:bkg_type,8',
                    'containers' => 'nullable|array',
                    'containers.*.id' => 'nullable|integer',
                    'containers.*.common' => 'integer',
                    'containers.*.container_type' => 'integer',
                    'containers.*.quantity' => 'integer',
                    'containers.*.details' => 'array',
                    'containers.*.details.*.van_place' => 'required',
                    'containers.*.details.*.van_type' => 'required',
                    'containers.*.details.*.bearing_type' => 'required',
                    'containers.*.details.*.deliver_day' => 'required',
                    'containers.*.details.*.deliver_time' => 'required',
                    'containers.*.details.*.trans_com' => 'required',
                    'containers.*.details.*.driver' => 'required',
                    'containers.*.details.*.tel' => 'required',
                    'containers.*.details.*.car' => 'required',
                    'containers.*.details.*.container' => 'required',
                    'containers.*.details.*.seal' => 'required',
                    'containers.*.details.*.tare' => 'required',
                ];
            case 'admin/order/save_file':
                return [
                    'order_id' => 'required',
                    'file_path' => 'required',
                    'type' => 'required|in:1,2,3,4'
                ];
            case 'admin/order/del_file':
                return [
                    'id' => 'required',
                ];
            case 'admin/order/send_message':
                return [
                    'order_id' => 'required',
                    'content' => 'required',
                ];
            case 'admin/order/update_ship_schedule':
                return [
                    'ids' => 'required|array',
                    'eta' => 'required|date',
                    'etd' => 'required|date',
                ];
            case 'admin/order/send_email':
                return [
                    'order_id'  => 'required',
                    'subject'   => 'required|string',
                    'content'   => 'required|string',
                    'to'        => 'required',
                ];
            case 'admin/order/change_node_status':
                return [
                    'id'            => 'required',
                    'is_enable'     => 'required|in:0,1',
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
