<?php

namespace app\common\model;

use think\Model;

class Profile extends Model
{
    //定义相对的关联  id uid  一个档案属于一个管理员
    public function admin()
    {
        return $this->belongsTo('Admin', 'uid', 'id');
    }
    //定义相对的关联  id uid  一个档案属于一个管理员
    public function adminBind()
    {
        return $this->belongsTo('Admin', 'uid', 'id')->bind('username');
    }
}
