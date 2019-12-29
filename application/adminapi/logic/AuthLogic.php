<?php
namespace app\adminapi\logic;

class AuthLogic{
    //权限检测
    public static function check()
    {
        //特殊页面 不需要检测 （比如首页、退出）
        $controller = request()->controller(); //大驼峰命名法 比如 Auth
        $action = request()->action();//小写
        $path = strtolower($controller . '/' . $action);
        if(in_array($path, ['index/index', 'login/logout'])){
            //不需要检测
            return true;
        }
        //特殊情况 超级管理员 不需要检测
        $user_id = input('user_id');
        $admin = \app\common\model\Admin::find($user_id);
        $role_id = $admin['role_id'];
        if($role_id == 1){
            //不需要检测
            return true;
        }
        //其他情况 正常检测
        //查询当前角色有哪些权限
        $role = \app\common\model\Role::find($role_id);
        $role_auth_ids = explode(',', $role['role_auth_ids']);
        //查询当前访问的权限id
        $auth = \app\common\model\Auth::where('auth_c', $controller)->where('auth_a', $action)->find();
        $auth_id = $auth['id'];
        if(in_array($auth_id, $role_auth_ids)){
            //有权限
            return true;
        }
        //没有权限
        return false;
    }
}