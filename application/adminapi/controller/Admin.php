<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Admin extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //分页+搜索 keyword  page
        $params = input();
        $where = [];
        if(!empty($params['keyword'])){
            $keyword = $params['keyword'];
            $where['username'] = ['like', "%{$keyword}%"];
        }
        //如果要支持 前端自定义 每页显示条数，则使用以下代码
        $size = !empty($params['size']) ? $params['size'] : 10;
        //关联模型写法 （先定义关联关系）
        $list = \app\common\model\Admin::with('role_bind')->where($where)->paginate($size);
        //返回数据
        $this->ok($list);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'username|用户名' => 'require|unique:admin,username',
            'email|邮箱' => 'require|email',
            'role_id|所属角色' => 'require|integer|gt:0'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //密码要加密
        if(empty($params['password'])){
            $params['password'] = '123456';
        }
        //手动加密或者 使用模型的修改器加密
        $params['password'] = encrypt_password($params['password']);
        //添加数据
        \app\common\model\Admin::create($params, true);
        $this->ok();
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询一条数据
        $info = \app\common\model\Admin::find($id);
        //返回数据
        $this->ok($info);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //超级管理员不能修改
        if($id == 1){
            $this->fail('无权修改此管理员');
        }
        //如果超级管理员有多个，用role_id == 1来判断
        //接收参数
        $params = input();
        if(!empty($params['type']) && $params['type'] == 'reset_pwd'){
            //重置密码操作
            $params = ['password' => encrypt_password('123456')];
        }else{
            //修改其他信息
            $validate = $this->validate($params, [
                'nickname|昵称' => 'max:100',
                'role_id|所属角色' => 'integer|gt:0',
                'email|邮箱' => 'email'
            ]);
            if($validate !== true){
                $this->fail($validate);
            }
            //安全起见的处理 将参数中可能会有的密码字段和用户名字段删除
            if(isset($params['password'])) unset($params['password']);
            if(isset($params['username'])) unset($params['username']);
        }
        //修改数据
        \app\common\model\Admin::update($params, ['id'=>$id], true);
        //返回数据
        $this->ok();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //超级管理员 id=1 不能删除
        if($id == 1){
            $this->fail('无权删除此管理员');
        }
        //不能删除自己
        $user_id = input('user_id');
        if($user_id == $id){
            $this->fail('不能删除自己');
        }
        //删除数据
        \app\common\model\Admin::destroy($id);
        $this->ok();
    }
}
