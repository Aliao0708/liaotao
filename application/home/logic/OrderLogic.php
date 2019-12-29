<?php
namespace app\home\logic;

class OrderLogic{
    //查询当前用户 选中的购物记录以及商品、SKU信息
    public static function getCartWithGoods()
    {
        //用户id
        $user_id = session('user_info.id');
        //关联查询
        $data = \app\common\model\Cart::with('goods,spec_goods')
            ->where('is_selected', 1)
            ->where('user_id', $user_id)
            ->select();
        //使用sku 价格和库存， 覆盖商品spu的价格和库存
        $data = (new \think\Collection($data))->toArray();
        //累加总数量和价格
        $total_number = 0;
        $total_price = 0;
        foreach($data as $k=>&$v){
            if(!empty($v['spec_goods'])){
                //$v['goods'] : goods_number, frozen_number ,goods_price, cost_price
                //$v['spec_goods'] : store_count, store_frozen ,price, cost_price
                $v['goods']['goods_number'] = $v['spec_goods']['store_count'];
                //$data[$k]['goods']['goods_number'] = $v['spec_goods']['store_count'];
                $v['goods']['frozen_number'] = $v['spec_goods']['store_frozen'];
                $v['goods']['goods_price'] = $v['spec_goods']['price'];
                $v['goods']['cost_price'] = $v['spec_goods']['cost_price'];
            }
            //累加
            $total_number += $v['number'];
            $total_price += $v['number'] * $v['goods']['goods_price'];
        }
        $res = [
            'data' => $data,
            'total_price' => $total_price,
            'total_number' => $total_number
        ];
        return $res;
    }
}