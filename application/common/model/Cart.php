<?php

namespace app\common\model;

use think\Model;

class Cart extends Model
{
    //定义购物车 商品关联
    public function goods()
    {
        return $this->belongsTo('Goods','goods_id','id');
    }
    //定义购物车 sku规格商品关联
    public function specGoods()
    {
        return $this->belongsTo('SpecGoods','spec_goods_id','id');
    }
}
