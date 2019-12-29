<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    //定义商品goods - type关联关系 一个商品属于一个type模型
    public function typeBind(){
        return $this->belongsTo('Type', 'type_id', 'id')->bind('type_name');
    }
    public function type(){
        return $this->belongsTo('Type', 'type_id', 'id');
    }
    //定义商品goods - 品牌brand关联关系 一个商品属于一个brand品牌
    public function brandBind(){
        return $this->belongsTo('Brand', 'brand_id', 'id')->bind(['brand_name'=>'name']);
    }
    //定义商品goods - 品牌brand关联关系 一个商品属于一个brand品牌
    public function brand(){
        return $this->belongsTo('Brand', 'brand_id', 'id');
    }
    //定义商品goods - 分类关联关系 一个商品属于一个category分类
    public function categoryBind(){
        return $this->belongsTo('Category', 'cate_id', 'id')->bind('cate_name');
    }
    //定义商品goods - 分类关联关系 一个商品属于一个category分类
    public function category(){
        return $this->belongsTo('Category', 'cate_id', 'id');
    }
    //定义商品-相册关联  一个商品有多个相册图片
    public function goodsImages(){
        return $this->hasMany('GoodsImages', 'goods_id', 'id');
    }
    //定义商品-规格商品SKU关联  一个商品SPU有多个SKU
    public function specGoods(){
        return $this->hasMany('SpecGoods', 'goods_id', 'id');
    }

    //获取器 对goods_attr字段进行转化
    public function getGoodsAttrAttr($value){
        return $value ? json_decode($value, true) : [];
    }


}
