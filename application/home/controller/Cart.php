<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Cart extends Base
{
    //加入购物车
    public function addcart(){
        //如果是get请求，直接跳转到首页
        if(request()->isGet()){
            $this->redirect('home/index/index');
        }
        //post请求  表单处理
        $params = input();
        $validate = $this->validate($params, [
            'goods_id' => 'require',
            //'spec_goods_id' => 'require',
            'number' => 'require'
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        //数据处理 调用封装的方法
        \app\home\logic\CartLogic::addCart($params['goods_id'], $params['spec_goods_id'], $params['number']);
        //展示 加入成功的页面
        //查询商品相关信息
        $goods = \app\home\logic\GoodsLogic::getGoodsWithSpecGoods($params['goods_id'], $params['spec_goods_id']);
        return view('addcart', ['goods'=>$goods, 'number'=>$params['number']]);

    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //dump(cookie('cart'));die;
        //查询购物记录信息
        $list = \app\home\logic\CartLogic::getAllCart();
        foreach($list as $k=>$v){
            $list[$k]['goods'] = \app\home\logic\GoodsLogic::getGoodsWithSpecGoods($v['goods_id'], $v['spec_goods_id']);
        }
//        dump($list);die;
        unset($v);
        return view('index', ['list'=>$list]);
    }

    //ajax修改购买数量
    public function changenum(){
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require',
            'number'=>'require|integer|gt:0'
        ]);
        if($validate !== true){
            $res = [
                'code' => 400,
                'msg'=>$validate
            ];
            return json($res);
        }
        //数据处理
        \app\home\logic\CartLogic::changeNum($params['id'], $params['number']);
        //返回数据
        $res = [
            'code' => 200,
            'msg'=>'success'
        ];
        return json($res);
    }

    //删除购物记录
    public function delcart($id)
    {
        if(empty($id)){
            $res = [
                'code' => 400,
                'msg'=>'参数错误'
            ];
            return json($res);
        }
        //删除记录 调用封装的方法
        \app\home\logic\CartLogic::delCart($id);
        //返回数据
        $res = [
            'code' => 200,
            'msg'=>'success'
        ];
        return json($res);
    }

    //修改选中状态
    public function changestatus()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require',
            'status' => 'require|in:0,1'
        ]);
        if($validate !== true){
            $res = [
                'code' => 400,
                'msg' => $validate
            ];
            return json($res);
        }
        //处理数据
        \app\home\logic\CartLogic::changeStatus($params['id'], $params['status']);
        //返回数据
        $res = [
            'code' => 200,
            'msg'=>'success'
        ];
        return json($res);
    }

}
