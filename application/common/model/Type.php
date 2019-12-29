<?php

namespace app\common\model;

use think\Model;

class Type extends Model
{
    //$hidden 和 $visible 二选一
    protected $hidden = ['create_time', 'update_time', 'delete_time']; //隐藏
    //protected $visible = ['id', 'type_name']; //可见
    //定义 type模型 和 规格名称spec的关联关系  一个type下有多个spec
    public function specs()
    {
        return $this->hasMany('Spec', 'type_id', 'id');
    }

    //定义 type模型 和 属性attribute的关联  一个type有多个属性attribute
    public function attrs()
    {
        return $this->hasMany('Attribute', 'type_id', 'id');
    }
}
