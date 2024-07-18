<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Request\Admin\UserRequest;
use App\Mail\Test;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    public function login(UserRequest $request)
    {
        //665544
        //root123
        //boos321
        //test
//        dd(bcrypt('boos321'));
        $credentials['username']= $request->username;
        $credentials['password'] = $request->password;
        $credentials['enable'] = 1;
        if (!$token = Auth::guard('user')->attempt($credentials)) {
            // errorUnauthorized 返回响应码401 token失效也是401 不利于前端区分 增加额外错误码作为更详细的区分
            // return $this->response->errorUnauthorized('用户名或密码错误');
            throw new ErrorException('用户名或密码错误');
        }
        // 假设具体的业务场景中，管理员登录后会有一系列业务操作 例如发送邮件通知 记录登录ip等等
        // event(new AdminLogin(Auth::guard('admin')->user(), ['login_ip' => $request->getClientIp(), 'login_time' => Carbon::now()->toDateTimeString()]));

        return $this->success($this->respondWithToken($token));
    }
    // 退出,删除token
    public function destroy()
    {
        try {
            Redis::del('admins:' . Auth::guard('user')->user()->id . ':login');
            Auth::guard('user')->logout();
        } catch (\Exception $e) {
            return $this->errorResponse(401);
        }
        return $this->success('success');
    }
    // 返回数据
    private function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('user')->factory()->getTTL()
        ];
    }

    public function userList(Request $request)
    {
        $query = User::query();
        if ($request['role_id']){
            $query->where('role_id', $request['role_id']);
        }
        if (is_numeric($request['enable'])){
            $query->where('enable', $request['enable']);
        }
        return $query->select('id', 'username', 'role_id', 'name', 'tag', 'enable')->get();
    }
}
