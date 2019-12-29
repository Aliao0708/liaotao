<?php

namespace app\home\logic;

class CartLogic{
    //加入购物车
    public static function addCart($goods_id, $spec_goods_id, $number, $is_selected=1)
    {
        //判断登录状态 已登录：添加到数据表；未登录：添加到cookie
        if(session('?user_info')){
            //已登录：添加到数据表
            //判断是否存在相同记录：user_id、goods_id、spec_goods_id都相同
            $user_id = session('user_info.id');
            $where = [
                'user_id' => $user_id,
                'goods_id' => $goods_id,
                'spec_goods_id' => $spec_goods_id
            ];
            //$where = compact('user_id', 'goods_id', 'spec_goods_id');
            //查询购物车表
            $info = \app\common\model\Cart::where($where)->find();
            if($info){
                //存在相同记录 累加数量
                $info->number += $number;
                $info->is_selected = $is_selected;
                $info->save();
            }else{
                //不存在相同记录，添加新记录
                $where['number'] = $number;
                $where['is_selected'] = $is_selected;
                //$where = compact('user_id', 'goods_id', 'spec_goods_id', 'number', 'is_selected');
                \app\common\model\Cart::create($where, true);
            }
        }else{
            //未登录：添加到cookie
            //从cookie中取出所有购物车数据
            $data = cookie('cart') ?: [];
            //拼接下标
            $key = $goods_id . '_' . $spec_goods_id;
            //判断是否存在相同记录
            if(isset($data[$key])){
                //存在相同记录 累加数量
                $data[$key]['number'] += $number;
                $data[$key]['is_selected'] = $is_selected;
            }else{
                //不存在相同记录，添加新记录
                $data[$key] = [
                    'id' => $key,
                    'goods_id' => $goods_id,
                    'spec_goods_id' => $spec_goods_id,
                    'number' => $number,
                    'is_selected' => $is_selected
                ];
            }
            //重新保存到cookie
            cookie('cart', $data, 86400*7);
        }
    }

    //查询所有购物记录
    public static function getAllCart()
    {
        //判断登录状态：已登录：查询数据表；未登录：取cookie
        if(session('?user_info')){
            //已登录：查询数据表
            $user_id = session('user_info.id');
            $data = \app\common\model\Cart::field('id,goods_id,spec_goods_id,number,is_selected')->where('user_id', $user_id)->select();
            //转化为标准二维数组
            $data = (new \think\Collection($data))->toArray();
        }else{
            //未登录：取cookie
            $data = cookie('cart') ?: [];
            //转化为 和查询数据表后 一样的数组格式（去除外层的下标）
            $data = array_values($data);
        }
        return $data;
    }

    //登录后迁移cookie购物车到数据表
    public static function cookieTodb()
    {
        //从cookie中取数据
        $data = cookie('cart') ?: [];
        //逐条加入购物车
        foreach($data as $v){
            // $v['goods_id']  $v['spec_goods_id']  $v['number']
            self::addCart($v['goods_id'], $v['spec_goods_id'], $v['number']);
        }
        //从cookie中删除购物车数据
        cookie('cart', null);
    }

    //修改购买数量
    public static function changeNum($id, $number)
    {
        //判断登录状态
        if(session('?user_info')){
            //登录，修改数据表
            $user_id = session('user_info.id');
            \app\common\model\Cart::update(['number'=>$number], ['id'=>$id, 'user_id'=>$user_id], true);
        }else{
            //未登录，修改cookie
            //取所有数据
            $data = cookie('cart') ?: [];
            //修改数量
            $data[$id]['number'] = $number;
            //重新保存
            cookie('cart', $data, 86400*7);
        }
    }

    //删除购物记录
    public static function delCart($id)
    {
        //判断登录状态
        if(session('?user_info')){
            //从数据表删除
            $user_id = session('user_info.id');
            \app\common\model\Cart::destroy(['id'=>$id,'user_id'=>$user_id]);
        }else{
            //从cookie删除
            $data = cookie('cart') ?: [];
            unset($data[$id]);
            //重新保存
            cookie('cart', $data, 86400*7);
        }
    }

    //修改选中状态
    public static function changeStatus($id, $is_selected)
    {
        //判断登录状态
        if(session('?user_info')){
            //登录，修改数据表
            $user_id = session('user_info.id');
            $where['user_id'] = $user_id;
            if($id != 'all'){
                //修改一条
                $where['id'] = $id;
            }
            //修改
            \app\common\model\Cart::update(['is_selected'=>$is_selected], $where, true);
        }else{
            //修改cookie
            $data = cookie('cart') ?: [];
            if($id != 'all'){
                //修改一条
                $data[$id]['is_selected'] = $is_selected;
            }else{
                //修改所有
                foreach($data as $k=>$v){
                    $data[$k]['is_selected'] = $is_selected;
                }
            }
            //重新保存
            cookie('cart', $data, 86400*7);
        }
    }
}