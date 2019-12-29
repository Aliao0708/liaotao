<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Category extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数  pid可选，type 可选
        $params = input();
        if(empty($params['pid'])){
            //查询所有数据
            $list = \app\common\model\Category::field('id,cate_name,pid,pid_path_name,level,is_hot,is_show,image_url')->select();
        }else{
            //查询子分类
            $list = \app\common\model\Category::field('id,cate_name,pid')->where('pid', $params['pid'])->select();
        }
        if(empty($params['type']) || $params['type'] != 'list'){
            //将查询到的结果 转化为标准的二维数组
            $list = (new \think\Collection($list))->toArray();
            //转化为无限级分类列表
            $list = get_cate_list($list);
        }
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
            'cate_name|分类名称' => 'require',
            'pid|上级分类' => 'require|integer|egt:0',
            'is_show|是否显示' => 'require|in:0,1',
            'is_hot|是否热门' => 'require|in:0,1',
            'sort|排序' => 'require|integer',
            //'logo|logo图片' => 'require',
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        //添加数据
        if($params['pid'] == 0){
            $params['pid_path'] = 0;
            $params['pid_path_name'] = '';
            $params['level'] = 0;
        }else{
            $p_info = \app\common\model\Category::find($params['pid']);
            //if(empty($p_info)) $this->fail('数据异常');
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
            $params['pid_path_name'] = trim($p_info['pid_path_name'] . '_' . $p_info['cate_name'], '_');
            $params['level'] = $p_info['level'] + 1;
        }
        //分类logo图片处理
        if(!empty($params['logo']) && is_file('.' . $params['logo'])){
            $logo = dirname($params['logo']) . DS . 'thumb_' . basename($params['logo']);
            \think\Image::open('.' . $params['logo'])->thumb(50,50)->save('.' . $logo);
            $params['image_url'] = $logo;
        }
        $res = \app\common\model\Category::create($params, true);
        //返回数据
        //$this->ok();
        $info = \app\common\model\Category::find($res['id']);
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
        $info = \app\common\model\Category::field('id,cate_name,pid,pid_path_name,level,is_show,is_hot,image_url')->find($id);
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
            'cate_name|分类名称' => 'require',
            'pid|上级分类' => 'require|integer|egt:0',
            'is_show|是否显示' => 'require|in:0,1',
            'is_hot|是否热门' => 'require|in:0,1',
            'sort|排序' => 'require|integer',
            //'logo|logo图片' => 'require',
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        //添加数据
        if($params['pid'] == 0){
            $params['pid_path'] = 0;
            $params['pid_path_name'] = '';
            $params['level'] = 0;
        }else{
            $p_info = \app\common\model\Category::find($params['pid']);
            //if(empty($p_info)) $this->fail('数据异常');
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
            $params['pid_path_name'] = trim($p_info['pid_path_name'] . '_' . $p_info['cate_name'], '_');
            $params['level'] = $p_info['level'] + 1;
        }
        //不能降级
        $info = \app\common\model\Category::find($id);
        if($info['level'] < $params['level']){
            $this->fail('不能降级');
        }
        //分类logo图片处理
        if(!empty($params['logo']) && is_file('.' . $params['logo'])){
            $logo = dirname($params['logo']) . DS . 'thumb_' . basename($params['logo']);
            \think\Image::open('.' . $params['logo'])->thumb(50,50)->save('.' . $logo);
            $params['image_url'] = $logo;
        }
        \app\common\model\Category::update($params, ['id'=>$id], true);
        //返回数据
        $this->ok();
        /*$info = \app\common\model\Category::find($id);
        $this->ok($info);*/
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //分类下有子分类，不能删除
        $info = \app\common\model\Category::where('pid', $id)->find();
        if($info){
            $this->fail('分类下有子分类，不能删除');
        }
        //分类下有品牌，不能删除
        $total = \app\common\model\Brand::where('cate_id', $id)->count('id');
        if($total > 0){
            $this->fail('分类下有品牌，不能删除');
        }
        //分类下有商品，不能删除
        $total = \app\common\model\Goods::where('cate_id', $id)->count('id');
        if($total > 0){
            $this->fail('分类下有商品，不能删除');
        }
        //删除数据
        \app\common\model\Category::destroy($id);
        $this->ok();
    }
}
