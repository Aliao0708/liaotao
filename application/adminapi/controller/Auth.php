<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Auth extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数
        $params = input();
        //查询数据
        $list = \app\common\model\Auth::select();
        //将结果转化为标准的二维数组
        $list = (new \think\Collection($list))->toArray();
        if(!empty($params['type']) && $params['type'] == 'tree'){
            //树状结构
            $list = get_tree_list($list);
        }else{
            //无限级分类
            $list = get_cate_list($list);
        }
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
            'auth_name|权限名称' => "require",
            'pid|上级权限' => 'require|integer|egt:0',
            'is_nav|是否菜单' => 'require|in:0,1'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //处理level和pid_path两个字段
        if($params['pid'] == 0){
            //顶级权限
            $params['level'] = 0;
            $params['pid_path'] = 0;
        }else{
            //查询上级权限
            $p_info = \app\common\model\Auth::find($params['pid']);
            if(!$p_info){
                $this->fail('数据异常');
            }
            $params['level'] = $p_info['level'] + 1;
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
        }
        //添加功能
        $res = \app\common\model\Auth::create($params, true);
        $info = \app\common\model\Auth::find($res['id']);
        $this->ok($info);
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
        $info = \app\common\model\Auth::field('id,auth_name,pid,pid_path,level,is_nav,auth_c,auth_a')->find($id);
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
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'auth_name|权限名称' => "require",
            'pid|上级权限' => 'require|integer|egt:0',
            'is_nav|是否菜单' => 'require|in:0,1'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //处理level和pid_path两个字段
        if($params['pid'] == 0){
            //顶级权限
            $params['level'] = 0;
            $params['pid_path'] = 0;
        }else{
            //查询上级权限
            $p_info = \app\common\model\Auth::find($params['pid']);
            if(!$p_info){
                $this->fail('数据异常');
            }
            $params['level'] = $p_info['level'] + 1;
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
            //不能降级 0 1 2 3
            $info = \app\common\model\Auth::find($id);
            if(!$info){
                $this->fail('数据异常');
            }
            if($info['level'] < $params['level']){
                $this->fail('不能降级');
            }
        }
        //修改功能
        \app\common\model\Auth::update($params, ['id'=>$id], true);
        $info = \app\common\model\Auth::find($id);
        $this->ok($info);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //如果权限下有子权限，不能删除
        $total = \app\common\model\Auth::where('pid', $id)->count('id');
        if($total){
            $this->fail('权限下有子权限，不能删除');
        }
        //删除数据
        \app\common\model\Auth::destroy($id);
        $this->ok();
    }

    //菜单权限接口
    public function nav()
    {
        //获取登录的管理员id
        $admin_id = input('user_id');
        //查询管理员表 获取角色id
        $admin = \app\common\model\Admin::find($admin_id);
        $role_id = $admin['role_id'];
        if($role_id == 1){
            //超级管理员，直接查询权限表的菜单权限
            $data = \app\common\model\Auth::where('is_nav', 1)->select();
        }else{
            //其他管理员
            //查询角色表，获取拥有的权限ids
            $role = \app\common\model\Role::find($role_id);
            $role_auth_ids = $role['role_auth_ids'];
            //再查询权限表，拥有的菜单权限
            $data = \app\common\model\Auth::where('id', 'in', $role_auth_ids)->where('is_nav', 1)->select();
            //$data = \app\common\model\Auth::where(['id'=>['in', $role_auth_ids], 'is_nav'=>1])->select();
        }
        //转化数组结构
        $data = (new \think\Collection($data))->toArray();
        $data = get_tree_list($data);
        $this->ok($data);

    }
}
