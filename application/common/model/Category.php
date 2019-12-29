<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    //定义关联关系 一个分类有多个品牌
    public function brands()
    {
        //注 hasMany方法后，不能调用bind方法绑定属性到父模型
        return $this->hasMany('Brand', 'cate_id', 'id');
    }

    //获取器 对pid_path进行转化
    public function getPidPathAttr($value)
    {
        return $value ? explode('_', $value) : [];
    }
}
