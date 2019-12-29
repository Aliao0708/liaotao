<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if(!function_exists('encrypt_password')){
    function encrypt_password($password)
    {
        $salt = 'dfnsaefwu344q2qnwd';
        return md5($salt . md5($password));
    }
}

if (!function_exists('get_cate_list')) {
    //递归函数 实现无限级分类列表
    function get_cate_list($list,$pid=0,$level=0) {
        static $tree = array();
        foreach($list as $row) {
            if($row['pid']==$pid) {
                $row['level'] = $level;
                $tree[] = $row;
                get_cate_list($list, $row['id'], $level + 1);
            }
        }
        return $tree;
    }
}

if(!function_exists('get_tree_list')){
    //引用方式实现 父子级树状结构
    function get_tree_list($list){
        //将每条数据中的id值作为其下标
        $temp = [];
        foreach($list as $v){
            $v['son'] = [];
            $temp[$v['id']] = $v;
        }
        //获取分类树
        foreach($temp as $k=>$v){
            $temp[$v['pid']]['son'][] = &$temp[$v['id']];
        }
        return isset($temp[0]['son']) ? $temp[0]['son'] : [];
    }
}

if (!function_exists('remove_xss')) {
    //使用htmlpurifier防范xss攻击
    function remove_xss($string){
        //composer安装的，不需要此步骤。相对index.php入口文件，引入HTMLPurifier.auto.php核心文件
//         require_once './plugins/htmlpurifier/HTMLPurifier.auto.php';
        // 生成配置对象
        $cfg = HTMLPurifier_Config::createDefault();
        // 以下就是配置：
        $cfg -> set('Core.Encoding', 'UTF-8');
        // 设置允许使用的HTML标签
        $cfg -> set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,br,p[style],span[style],img[width|height|alt|src]');
        // 设置允许出现的CSS样式属性
        $cfg -> set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置a标签上是否允许使用target="_blank"
        $cfg -> set('HTML.TargetBlank', TRUE);
        // 使用配置生成过滤用的对象
        $obj = new HTMLPurifier($cfg);
        // 过滤字符串
        return $obj -> purify($string);
    }
}

if(!function_exists('send_email')){
    function send_email($email,$subject,$body)
    {
        $mailto=$email;
        $mailsubject=$subject;
        $mailbody=$body;
        $smtpserver     = "smtpdm.aliyun.com";
        $smtpserverport = 25;
        $smtpusermail   = "pyg01@mail.tbyue.com";
        // 发件人的账号，填写控制台配置的发信地址,比如xxx@xxx.com
        $smtpuser       = "pyg01@mail.tbyue.com";
        // 访问SMTP服务时需要提供的密码(在控制台选择发信地址进行设置)
        $smtppass       = "PinYouGou01";
        $mailsubject    = "=?UTF-8?B?" . base64_encode($mailsubject) . "?=";
        $mailtype       = "HTML";
        //可选，设置回信地址
        $smtpreplyto    = "***";
        $smtp           = new \Smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);
        $smtp->debug    = false;
        $cc   ="";
        $bcc  = "";
        $additional_headers = "";
        //设置发件人名称，名称用户可以自定义填写。
        $sender  = "品优购";
        return $smtp->sendmail($mailto,$smtpusermail, $mailsubject, $mailbody, $mailtype, $cc, $bcc, $additional_headers, $sender, $smtpreplyto);
    }
}

if(!function_exists('encrypt_phone'))
{
    // 13312345678  => 133****5678
    function encrypt_phone($phone){
        return substr($phone,0,3) . '****' . substr($phone,7,4);
    }
}

if(!function_exists('curl_request')){
    //使用curl函数库 发送请求
    function curl_request($url, $post=false, $params=[], $https=false){
        //初始化请求会话
        $ch = curl_init($url);
        //设置请求选项
        //请求方式 默认curl发送get请求
        if($post){
            //设置发送post请求
            curl_setopt($ch, CURLOPT_POST, true);
            //设置post请求参数
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        //https协议是否验证证书
        if($https){
            //禁止从服务器端验证客户端本地的整数
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        //发送请求
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if($res === false){
            $msg = curl_error($ch);
            return [$msg];
        }
        //关闭请求
        curl_close($ch);
        return $res;
    }
}

//调用短信接口发送短信
if(!function_exists('send_msg')){
    //短信内容模板： 【创信】你的验证码是：5873，3分钟内有效！
    function send_msg($phone, $msg){
        //接口地址
        $url = config('msg.gateway');
        $appkey = config('msg.appkey');

        //拼接请求参数
        $url .= '?appkey=' . $appkey . '&mobile=' . $phone . '&content=' . $msg;
        //发送请求
        $res = curl_request($url, false, [], true);
        if(is_array($res)){
            //请求没有发出
            return '请求失败';
        }
        //解析结果字符串
        $arr = json_decode($res, true);
        if(!isset($arr['code']) || $arr['code'] != 10000){
            return '短信接口请求失败';
            //return !isset($arr['msg']) ? '短信接口请求失败' : $arr['msg'];
        }
        if(!isset($arr['result']['ReturnStatus']) || $arr['result']['ReturnStatus'] != 'Success'){
            return '短信发送失败';
            //return !isset($arr['result']['Message']) ? '短信发送失败' : $arr['msg'];
        }
        return true;
    }
}