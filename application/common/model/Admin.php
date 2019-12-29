<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    //定义关联关系 一个管理员有一份档案 id uid   id是管理员表主键
    public function profile()
    {
        //参数： 模型名，关联外键（默认取admin_id），关联主键(默认id)
        return $this->hasOne('Profile', 'uid', 'id');
    }

    public function profileBind()
    {
        //参数： 模型名，关联外键（默认取admin_id），关联主键(默认id)
        //绑定指定字段属性到父模型
        return $this->hasOne('Profile', 'uid', 'id')->bind('idnum,card');
    }

    //定义管理员和角色对应关系
    public function roleBind(){
        return $this->belongsTo('Role', 'role_id', 'id')->bind('role_name');
    }

    //定义修改器
    /*public function setPasswordAttr($value){
        return encrypt_password($value);
    }*/
}
