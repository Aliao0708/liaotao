<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Test extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //cookie中存储购物车数据 分析
        /*$data = [
            '67_100' => ['id' => '67_100', 'goods_id' => 67, 'number'=>10, 'spec_goods_id'=>100, 'is_seleted' => 1],
            '68_102' => ['id' => '68_102', 'goods_id' => 68, 'number'=>10, 'spec_goods_id'=>102, 'is_seleted' => 1],
        ];
        cookie('cart', $data, 86400*7);*/

        //列表页
        //$list = cookie('cart');

        //加入购物车
        $row = ['goods_id' => 67, 'number'=>10, 'spec_goods_id'=>100, 'is_seleted' => 1];
        //先从cookie中取所有数据 得到数组
        $data = cookie('cart') ?: [];
        //向数组中加入一条数据
        //判断 是否已存在相同记录 存在则累加购买数量，不存在则添加新记录
        /*$flag = 0;
        foreach($data as $k=>$v){
            if($v['goods_id'] == $row['goods_id'] && $v['spec_goods_id'] == $row['spec_goods_id']){
                $data[$k]['number'] += $row['number'];
                $flag = 1;
            }
        }
        if($flag == 0){
            $data[] = $row;
        }*/
        $key = $row['goods_id'] . '_' . $row['spec_goods_id'];
        if(isset($data[$key])){
            //存在则累加购买数量
            $data[$key]['number'] += $row['number'];
        }else{
            //不存在则添加新记录
            $data[$key] = $row;
        }
        //将数组重新保存到cookie
        cookie('cart', $data, 86400*7);


        //修改购买数量
        $row = ['goods_id'=>67, 'spec_goods_id'=>100, 'number'=>30];
        $key = $row['goods_id'] . '_' . $row['spec_goods_id'];
        /*$row = ['id'=>'67_100', 'number'=>30];
        $key = $row['id'];*/
        $data = cookie('cart') ?: [];
        $data[$key]['number'] = $row['number'];
        cookie('cart', $data, 86400*7);

        //删除
        $row = ['goods_id'=>67, 'spec_goods_id'=>100];
        $key = $row['goods_id'] . '_' . $row['spec_goods_id'];
        /*$row = ['id'=>'67_100'];
        $key = $row['id'];*/
        $data = cookie('cart') ?: [];
        unset($data[$key]);
        cookie('cart', $data, 86400*7);


    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //创建索引
        /*$es = \Elasticsearch\ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => 'test_index'
        ];
        $r = $es->indices()->create($params);
        dump($r);die;*/

        //添加文档
        /*$es = \Elasticsearch\ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => 'test_index',
            'type' => 'test_type',
            'id' => 100,
            'body' => ['id'=>100, 'title'=>'PHP从入门到精通', 'author' => '张三']
        ];

        $r = $es->index($params);
        dump($r);die;*/

        //修改文档
        /*$es = \Elasticsearch\ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => 'test_index',
            'type' => 'test_type',
            'id' => 100,
            'body' => [
                'doc' => ['id'=>100, 'title'=>'ES从入门到精通', 'author' => '张三']
            ]
        ];

        $r = $es->update($params);
        dump($r);die;*/

        //删除文档
        /*$es = \Elasticsearch\ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => 'test_index',
            'type' => 'test_type',
            'id' => 100,
        ];

        $r = $es->delete($params);
        dump($r);die;*/
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
