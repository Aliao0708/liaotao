<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Login extends BaseApi
{
    //验证码图片地址接口
    public function verify()
    {
        //处理数据
        $uniqid = uniqid('login', true); //获取唯一的标识
        $data = [
            'url' => captcha_src($uniqid),    //验证码地址
            'uniqid' => $uniqid //验证码标识
        ];
        //返回数据
        $this->ok($data);
    }

    //登录接口
    public function login()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'username|用户名' => 'require',
            'password|密码' => 'require',
            'uniqid|验证码编号' => 'require',
            'code|验证码' => 'require',
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        if(!captcha_check($params['code'], $params['uniqid'])){
            $this->fail('验证码错误', 400);
        }
        //处理数据
        //根据用户名密码查询管理员表
        $password = encrypt_password($params['password']);
        $info = \app\common\model\Admin::where('username', $params['username'])->where('password', $password)->find();
        if($info){
            //登录成功 生成token令牌
            $token = \tools\jwt\Token::getToken($info->id);
            $data = [
                'token' => $token,
                'user_id' => $info->id,
                'username' => $info->username,
                'nickname' => $info->nickname,
                'email' => $info->email
            ];
            //返回数据
            $this->ok($data);
        }else{
            $this->fail('用户名或密码错误', 401);
        }

    }

    //退出接口
    public function logout()
    {
        //将token令牌 保存起来，作为退出过的token
        $token = \tools\jwt\Token::getRequestToken();
        //将要退出的token， 存储到缓存中
        $delete_token = cache('delete_token') ?: [];
        $delete_token[] = $token;
        cache('delete_token', $delete_token, 86400);
        //返回数据
        $this->ok();
    }
}
