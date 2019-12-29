<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class BaseApi extends Controller
{
    //无需登录检测的接口
    protected  $no_login = ['login/login', 'login/verify'];

    /*public function __construct(Request $request)
    {
        parent::__construct($request);
        //允许的源域名
        header("Access-Control-Allow-Origin: http://localhost:8080");
        //允许的请求头信息
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        //允许的请求类型
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        //允许携带证书式访问（携带cookie）
        header('Access-Control-Allow-Credentials:true');

        //登录检测
        $this->checkLogin();
    }*/

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        //允许的源域名
        header("Access-Control-Allow-Origin: http://localhost:8080");
        //允许的请求头信息
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        //允许的请求类型
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        //允许携带证书式访问（携带cookie）
        header('Access-Control-Allow-Credentials:true');
        //登录检测
        //$this->checkLogin();
        //权限检测
        $res = \app\adminapi\logic\AuthLogic::check();
        if(!$res){
            $this->fail('无权访问');
        }
    }

    public function checkLogin()
    {
        try{
            //获取当前访问的控制器和方法名称
            $controller = request()->controller();
            $action = request()->action();
            $path = strtolower($controller . '/' . $action);
            if(!in_array($path, $this->no_login)){
                //需要登录检测
                $user_id = \tools\jwt\Token::getUserId();
                if(empty($user_id)){
                    $this->fail('未登录或token无效', 400);
                }
                //可以将登录的用户id， 记录到当前的请求对象中去；后续需要使用用户id，直接从请求对象中获取
                request()->get(['user_id'=>$user_id]);
                request()->post(['user_id'=>$user_id]);
            }
        }catch(\Exception $e){
            /*$msg = $e->getMessage(); //获取异常中的提示信息
            $file = $e->getFile(); //获取报错的文件名称
            $line = $e->getLine(); //获取报错的行号
            $this->fail('错误信息：' . $msg . ';文件名：' . $file . ';行号：' . $line);*/
            $this->fail('token无效', 400);
        }
    }

    /**
     * 快速响应方法
     */
    public function response($code=200, $msg='success', $data=[])
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        //二选一
        echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);die;
        //return json($res)->send();
    }

    /**
     * 成功时的响应
     */
    public function ok($data=[], $code=200, $msg='success')
    {
        $this->response($code, $msg, $data);
    }

    /**
     * 失败时的响应
     */
    public function fail($msg='error', $code=500, $data=[])
    {
        $this->response($code, $msg, $data);
    }
}
