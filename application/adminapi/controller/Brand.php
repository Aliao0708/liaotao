<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Brand extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数 keyword page
        $params = input();
        if(!empty($params['cate_id'])){
            //查询分类下的品牌 返回普通列表
            $list = \app\common\model\Brand::field('id,name')->where('cate_id', $params['cate_id'])->select();
            $this->ok($list);
        }
        //搜索+分页
        $where = [];
        if(!empty($params['keyword'])){
            //搜索条件
            $keyword = $params['keyword'];
            $where['t1.name'] = ['like', "%{$keyword}%"];
        }
        //分页查询
        $list = \app\common\model\Brand::alias('t1')
            ->field('t1.*,t2.cate_name')
            ->join('pyg_category t2', 't1.cate_id=t2.id', 'left')
            ->where($where)
            ->order('t1.sort desc,t1.id desc')
            ->paginate(10);
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
            'name|品牌名称' => 'require',
            'cate_id|所属分类' => 'require|integer|gt:0',
            'is_hot|是否热门' => 'require|in:0,1',
            'sort|排序' => 'require|integer'
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        //品牌的logo处理 生成缩略图
        if(!empty($params['logo']) && is_file('.' . $params['logo'])){
            \think\Image::open('.' . $params['logo'])->thumb(100, 50)->save('.' . $params['logo']);
        }
        //添加数据
        \app\common\model\Brand::create($params, true);
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
        $info = \app\common\model\Brand::find($id);
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
            'name|品牌名称' => 'require',
            'cate_id|所属分类' => 'require|integer|gt:0',
            'is_hot|是否热门' => 'require|in:0,1',
            'sort|排序' => 'require|integer'
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        //品牌的logo处理 生成缩略图
        if(!empty($params['logo']) && is_file('.' . $params['logo'])){
            \think\Image::open('.' . $params['logo'])->thumb(100, 50)->save('.' . $params['logo']);
        }
        //添加数据
        \app\common\model\Brand::update($params, ['id'=>$id], true);
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
        //品牌下有商品，不能删除
        $total = \app\common\model\Goods::where('brand_id', $id)->count('id');
        if($total){
            $this->fail('品牌下有商品，不能删除');
        }
        //删除数据
        \app\common\model\Brand::destroy($id);
        $this->ok();
    }
}
