<?php

namespace App\Logic;
use App\Exceptions\ErrorException;
use App\Models\Bank;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class PermissionLogic extends Logic
{
    public static function roleList($request)
    {
        return Role::query()->orderBy('sort')->paginate($request['page_size'] ?? 10);
    }

    public static function saveRole($request)
    {
        $exists = Role::query()->where('name', $request['name'])
            ->where('id', "!=", $request['id'] ?? 0)
            ->exists();
        if ($exists){
            throw new ErrorException('角色名重复');
        }
        return Role::query()->updateOrCreate([
            'id' => $request['id']
        ],[
            'name' => $request['name'],
            'sort' => $request['sort'],
        ]);
    }

    public static function delRole($request)
    {
        return Role::query()->where('id', $request['role_id'])->delete();
    }

    //----------permission---------------
    public static function permissionList($request)
    {
        $query = Permission::query();
        if ($request['pid']){
            $query->where('pid', $request['pid']);
        }
        $list = $query->orderBy('sort')->get()->keyBy('id')->toArray();
        return getTree($list);
    }

    public static function roleBindPermission($request)
    {
        $role = Role::query()->find($request['role_id']);
        return $role->permissions()->sync($request['permission_ids']);
    }

    public function userPermission($request)
    {
        $user = User::query()->find($request['user_id']);
        if (!$user || !$user->role){
            throw new ErrorException('ユーザーに権限がない');
        }
        //collect($user->role->permissions)->only()
        return $user->role->permissions;
    }
}
