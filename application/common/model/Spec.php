<?php

namespace app\common\model;

use think\Model;

class Spec extends Model
{
    //定义spec和 spec_value关联关系  一个spec名称有多个spec_value值
    public function specValues()
    {
        return $this->hasMany('SpecValue', 'spec_id', 'id');
    }
}
