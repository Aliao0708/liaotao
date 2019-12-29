<?php

namespace app\home\controller;

use think\config\driver\Json;
use think\Controller;

class Login extends Controller
{
    //登录
    public function login()
    {
        //临时关闭模板布局
        $this->view->engine->layout(false);
        return view();
    }

    //注册页面展示
    public function register()
    {
        //临时关闭模板布局
        $this->view->engine->layout(false);
        return view();
    }

    //手机号注册表单提交
    public function phone()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params,[
            'phone|手机号' => 'require|regex:1[3-9]\d{9}|unique:user,phone',
            'password|密码' => 'require|length:6,18|confirm:repassword',
            'code|短信验证码' => 'require'
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        //短信验证码校验
        $code = cache('register_code_' . $params['phone']);
        if($params['code'] != $code){
            $this->error('短信验证码错误');
        }
        //验证成功后失效
        cache('register_code_' . $params['phone'], null);
        cache('register_time_' . $params['phone'], null);
        //添加数据到用户表
        //可选 让手机号作为 用户名和昵称
        $params['username'] = $params['phone'];
        $params['nickname'] = encrypt_phone($params['phone']);
        //密码加密
        $params['password'] = encrypt_password($params['password']);
        //注册成功 手动登录
        //添加数据
        \app\common\model\User::create($params, true);
        //页面跳转 跳转到登录页
        $this->success('注册成功', 'home/login/login');
    }

    //发送短信验证码接口
    public function sendcode()
    {
        //接收参数
        //$params = input();
        $phone = input('phone');
        //参数检测
        if(!preg_match('/^1[3-9]\d{9}$/', $phone)){
            $res = [
                'code' => 400,
                'msg' => '手机号格式不正确'
            ];
            return json($res);
        }
        //检测发送频率
        $last_time = cache('register_time_' . $phone) ?: 0;
        if(time() - $last_time < 60){
            $res = [
                'code' => 400,
                'msg' => '发送太频繁'
            ];
            return json($res);
        }
        //处理数据 发送短信
        //生成4位随机数
        $code = mt_rand(1000, 9999);
        $msg = '【创信】你的验证码是：' . $code . '，3分钟内有效！';
        //测试注册流程，短信验证码可以不用真正发送
        //$result = send_msg($phone, $msg);
        $result = true;
        if($result === true){
            //发送成功
            //将验证码记录到缓存，用于后续校验
            cache('register_code_' . $phone, $code, 180);
            //记录发送时间，用于下次发送前 做频率检测
            cache('register_time_' . $phone, time(), 180);
            $res = [
                'code' => 200,
                'msg' => '发送成功',
                'data' => $code  //测试过程
            ];
            return json($res);
        }else{
            //发送失败
            $res = [
                'code' => 401,
                'msg' => '发送失败'
            ];
            return json($res);
        }
    }

    //登录表单提交
    public function dologin()
    {
        //接收参数
        $params = input();
        $validate = $this->validate($params, [
            'username|用户名' => 'require',
            'password|密码' => 'require'
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        //查询用户表进行登录认证
        $password = encrypt_password($params['password']);
        //手机号字段和邮箱字段，同时查询
        //用户名和密码一起查询表
        //SELECT * FROM `pyg_user` where (phone='15313139033' or email = '15313139033') and password ='a23d3b988fe7828cc43ab4ad0ce4cc37';
        $user = \app\common\model\User::where(function($query)use($params){
            $query->where('phone', $params['username'])->whereOr('email', $params['username']);
        })->where('password', $password)->find();
        if($user){
            //设置登录标识
            session('user_info', $user->toArray());
            //迁移cookie购物车
            \app\home\logic\CartLogic::cookieTodb();
            //关联第三方用户
            if(session('open_type') && session('open_id')){
                $open_user = \app\common\model\OpenUser::where('open_type', session('open_type'))->where('openid', session('open_id'))->find();
                $open_user->user_id = $user['id'];
                $open_user->save();
            }
            if(session('open_nickname')){
                \app\common\model\User::update(['nickname'=>session('open_nickname')], ['id'=>$user['id']], true);
            }
            //从session获取跳转地址
            $back_url = session('back_url') ?: 'home/index/index';
            $this->redirect($back_url);
        }else{
            //用户名或密码错误
            $this->error('用户名或密码错误');
        }
    }

    public function logout()
    {
        //清空session
        //session('user_info', null);
        session(null);
        $this->redirect('home/login/login');
    }

    //qq登录回调地址
    public function qqcallback()
    {
        //参考 plugins/qq/example/oauth/callback.php
        require_once("./plugins/qq/API/qqConnectAPI.php");
        $qc = new \QC();
        $access_token = $qc->qq_callback();
        $open_id = $qc->get_openid();
        //获取用户信息（昵称）
        $qc = new \QC($access_token, $open_id);
        //dump($qc);die;
        $info = $qc->get_user_info();
        //dump($info);

        //关联用户
        $open_user = \app\common\model\OpenUser::where('open_type', 'qq')->where('openid', $open_id)->find();
        if($open_user && !empty($open_user['user_id'])){
            //已经关联过  同步用户信息（昵称）
            $user = \app\common\model\User::find($open_user['user_id']);
            $user->nickname = $info['nickname'];
            $user->save();
            //登录成功
            session('user_info', $user->toArray());
            //迁移cookie购物车
            \app\home\logic\CartLogic::cookieTodb();
            //从session获取跳转地址
            $back_url = session('back_url') ?: 'home/index/index';
            $this->redirect($back_url);
        }else{
            //给用户显示一个选择页面：没有账号则跳转注册页面；已有账号则跳转登录页面
            //添加记录到open_user表
            if(!$open_user){
                \app\common\model\OpenUser::create([
                    'open_type' => 'qq',
                    'openid' => $open_id
                ]);
            }
            //第三方账号信息放到session， 用于后续登录后关联用户
            session('open_type', 'qq');
            session('open_id', $open_id);
            session('open_nickname', $info['nickname']);
            //这里直接跳转到登录
            $this->redirect('home/login/login');
        }
    }

    public function alicallback()
    {
        //引入必要的文件
        require_once './plugins/alipay/oauth/config.php';
        require_once './plugins/alipay/oauth/service/AlipayOauthService.php';
        //实例化AlipayOauthService
        $obj = new \AlipayOauthService($config);
        //获取auth_code
        $auth_code = $obj->auth_code();
        //获取access_token
        $access_token = $obj->get_token($auth_code);
        //获取用户信息
        $info = $obj->get_user_info($access_token);
        //如果支付宝用户信息中没有昵称，则将昵称设置为空或者设置为支付宝用户id
        if(empty($info['nick_name'])){
            $info['nick_name'] = '';
            //$info['nick_name'] = $info['user_id'];
        }
        //关联已有用户
        $open_user = \app\common\model\OpenUser::where('open_type', 'alipay')->where('openid', $info['user_id'])->find();
        if($open_user && !empty($open_user['user_id'])){
            //同步昵称
            $user = \app\common\model\User::find($open_user['user_id']);
            $user->nickname = $info['nick_name'];
            $user->save();
            //设置登录标识
            session('user_info', $user->toArray());
            //迁移cookie购物车
            \app\home\logic\CartLogic::cookieTodb();
            //从session获取跳转地址
            $back_url = session('back_url') ?: 'home/index/index';
            $this->redirect($back_url);
        }
        if(!$open_user){
            //添加第三方账号
            \app\common\model\OpenUser::create([
                'open_type' => 'alipay',
                'openid' => $info['user_id'],
            ]);
        }
        //关联用户的页面（比如跳转登录页）
        session('open_type', 'alipay');
        session('open_id', $info['user_id']);
        session('open_nickname', $info['nick_name']);
        $this->redirect('home/login/login');

    }
    public function test()
    {
        $phone = '15313139033';
        $msg = '【创信】你的验证码是：5455，3分钟内有效！';
        $res = send_msg($phone, $msg);
        dump($res);die;
    }
}
