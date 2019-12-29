<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//定义后台接口模块的域名路由
Route::domain('adminapi.pyg.com', function(){
    Route::get('/', 'adminapi/index/index');

    //获取验证码地址的接口
    Route::get('verify', 'adminapi/login/verify');
    //显示验证码图片的路由
    \think\Route::get('captcha/[:id]', "\\think\\captcha\\CaptchaController@index");

    //登录接口
    Route::post('login', 'adminapi/login/login');

    //退出接口
    Route::get('logout', 'adminapi/login/logout');

    //单图片上传
    Route::post('logo', 'adminapi/upload/logo');
    //多图片上传
    Route::post('images', 'adminapi/upload/images');

    //商品分类
    Route::resource('categorys', 'adminapi/category');
    //商品品牌
    Route::resource('brands', 'adminapi/brand');
    //商品模型
    Route::resource('types', 'adminapi/type');
    //商品
    Route::resource('goods', 'adminapi/goods');
    //删除相册图片接口
    Route::delete('delpics/:id', 'adminapi/goods/delpics');
    //权限
    Route::resource('auths', 'adminapi/auth');
    //权限菜单
    Route::get('nav', 'adminapi/auth/nav');
    //角色
    Route::resource('roles', 'adminapi/role');
    //角色
    Route::resource('admins', 'adminapi/admin');
});
