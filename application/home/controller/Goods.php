<?php

namespace app\home\controller;

use think\Controller;

class Goods extends Base
{
    //商品列表
    public function index($id=0)
    {
        //接收参数
        $keywords = input('keywords');
        //dump($keywords);die;
        if(empty($keywords)){
            //获取指定分类下商品列表
            if(!preg_match('/^\d+$/', $id)){
                $this->error('参数错误');
            }
            //查询分类下的商品
            $list = \app\common\model\Goods::where('cate_id', $id)->order('id desc')->paginate(10);
            //查询分类名称
            $category_info = \app\common\model\Category::find($id);
            $cate_name = $category_info['cate_name'];
        }else{
            try{
                //从ES中搜索
                $list = \app\home\logic\GoodsLogic::search();
                $cate_name = $keywords;
            }catch (\Exception $e){
                $this->error('服务器异常');
            }
        }
        return view('index', ['list' => $list, 'cate_name' => $cate_name]);
    }

    //商品详情
    public function detail($id)
    {
        //查询商品数据 查询商品下所有的sku（规格商品spec_goods）
        $goods = \app\common\model\Goods::with('goods_images,spec_goods')->find($id);
        if($goods['spec_goods']){
            $goods['goods_price'] = $goods['spec_goods'][0]['price'];
        }
        //从所有sku中取出所有相关的规格值id  （value_ids）
        //$goods['spec_goods'] 二维数组结构
        $value_ids = array_column($goods['spec_goods'], 'value_ids'); // ['28_32', '28_33', '29_32', '29_33']
        $value_ids = array_unique(explode('_', implode('_', $value_ids)));
        //implode 28_32_28_33_29_32_29_33
        //explode ['28', '32', '28', '33', '29', '32', '29','33']
        //array_unique ['28', '32', '33', '29']

        //查询规格值表 连表规格名称表
        $spec_values = \app\common\model\SpecValue::alias('t1')
            ->join('pyg_spec t2', 't1.spec_id=t2.id', 'left')
            ->field('t1.*, t2.spec_name')
            ->where('t1.id', 'in', $value_ids)
            ->select();
        //$spec_values = \app\common\model\SpecValue::with('spec_bind')->where('id', 'in', $value_ids)->select();
        //转化为需要的数组结构
        //理想规格信息 数组结构
        //需要从数据表查询的数据结构
        /*$spec_values = [
                ['id'=>200, 'spec_value'=>'黑色', 'spec_id'=>100, 'spec_name'=>'颜色'],
                ['id'=>202, 'spec_value'=>'金色', 'spec_id'=>100, 'spec_name'=>'颜色'],
                ['id'=>203, 'spec_value'=>'128G', 'spec_id'=>101, 'spec_name'=>'内存'],
                ['id'=>204, 'spec_value'=>'256G', 'spec_id'=>101, 'spec_name'=>'内存'],
        ];*/
        $specs = [];
        //组装规格名称数组
        foreach($spec_values as $k=>$v){
            $specs[$v['spec_id']] = [
                'id' => $v['spec_id'],
                'spec_name' => $v['spec_name'],
                'spec_values' => []
            ];
        }
        /*$specs = [
            '100' => ['id'=>100, 'spec_name'=>'颜色', 'spec_values' => []],
            '101' => ['id'=>101, 'spec_name'=>'内存', 'spec_values' => []],
        ];*/
        //组装规格值
        foreach($spec_values as $k=>$v){
            $specs[$v['spec_id']]['spec_values'][] = [
                'id' => $v['id'],
                'spec_value' => $v['spec_value']
            ];
        }
        //最终的目标结构
        /*$specs = [
            '100' => ['id'=>100, 'spec_name'=>'颜色', 'spec_values' => [
                ['id'=>200, 'spec_value'=>'黑色'],
                ['id'=>202, 'spec_value'=>'金色'],
            ]],
            '101' => ['id'=>101, 'spec_name'=>'内存', 'spec_values' => [
                ['id'=>203, 'spec_value'=>'128G'],
                ['id'=>204, 'spec_value'=>'256G'],
            ]],
        ];*/
        // 切换规格值改变价格 ，预期数组结构
        //$goods['spec_goods']
        // ['28_33' => ['id' => 827, 'price'=>'3500'], '28_32'=>['id' => 827, 'price'=>'3500']]
        $value_ids_map = [];
        foreach($goods['spec_goods'] as $v){
            $value_ids_map[$v['value_ids']] = [
                'id' => $v['id'],
                'price' => $v['price']
            ];
        }
        //将数据放在js中使用，需要转化为json格式
        $value_ids_map = json_encode($value_ids_map);
        //echo $value_ids_map;die;
        return view('detail', ['goods'=>$goods, 'specs' => $specs, 'value_ids_map' => $value_ids_map]);
    }
}
