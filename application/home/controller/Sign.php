<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Sign extends Controller
{
    //生成签名
    public function getSign($params)
    {
        unset($params['sign']);
        unset($params['sign_type']);
        //将数组中的空值去掉
        foreach($params as $k=>$v){
            if(empty($v)){
                unset($params[$k]);
            }
        }
        //将其他参数 按照一定顺序排列
        ksort($params);
        //去除最后的符号
        $str = http_build_query($params);
        //生成签名
        return encrypt_password($str);
    }

    //验证签名
    public function checkSign($params)
    {
        $sign = $params['sign'];
        $new_sign = $this->getSign($params);
        return $sign == $new_sign;
    }

    //模拟支付宝服务端 发请求
    public function alipay()
    {
        $url = 'http://www.pyg.com/home/sign/notify';
        $params = ['total_amount' => '100','out_trade_no' => '12345646565'];
        $sign = $this->getSign($params);
        $params['sign'] = $sign;
        $params['sign_type'] = 'md5';
        //发请求
        $res = curl_request($url,true,$params);
        dump($res);
    }

    //模拟商城服务端 异步接口
    public function notify()
    {
        $params = input();
        $params['total_amount'] = 100;
        //验证签名
        $result = $this->checkSign($params);
        if($result){
            echo 'success';die;
        }else{
            echo 'fail';die;
        }
    }
}
