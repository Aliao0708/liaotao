<?php

namespace app\common\model;

use think\Model;

class Brand extends Model
{
    //定义关联关系 id cate_id 一个品牌属于一个分类
    public function category()
    {
        return $this->belongsTo('Category', 'cate_id', 'id');
    }
    //定义关联关系 id cate_id 一个品牌属于一个分类
    public function categoryBind()
    {
        /*return $this->belongsTo('Category', 'cate_id', 'id')->bind('cate_name');
        return $this->belongsTo('Category', 'cate_id', 'id')->bind('cate_name,pid');
        return $this->belongsTo('Category', 'cate_id', 'id')->bind(['cate_name','pid']);*/
        //支持给字段取别名 bind方法传递数组参数，下标是别名，值是数据表字段名。
        return $this->belongsTo('Category', 'cate_id', 'id')->bind(['catename'=>'cate_name','pid']);
    }
}
