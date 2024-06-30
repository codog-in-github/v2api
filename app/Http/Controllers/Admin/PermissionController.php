<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Request\Admin\PermissionRequest;
use App\Logic\BankLogic;
use App\Logic\PermissionLogic;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function roleList(Request $request)
    {
       $res = PermissionLogic::roleList($request);
       return $this->pageReturn($res->items(), $res->total());
    }

    public function saveRole(PermissionRequest $request)
    {
        return $this->success(PermissionLogic::saveRole($request));
    }
    public function delRole(PermissionRequest $request)
    {
        return $this->success(PermissionLogic::delRole($request));
    }

    public function permissionList(Request $request)
    {
        return $this->success(PermissionLogic::permissionList($request));
    }

    public function roleBindPermission(PermissionRequest $request)
    {
        return $this->success(PermissionLogic::roleBindPermission($request));
    }

    public function userPermission(PermissionRequest $request)
    {
        return $this->success(PermissionLogic::userPermission($request));
    }
}
