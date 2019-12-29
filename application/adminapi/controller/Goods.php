<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Goods extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //分页+搜索
        $params = input();
        $where = [];
        if(!empty($params['keyword'])){
            $keyword = $params['keyword'];
            $where['goods_name'] = ['like', "%{$keyword}%"];
        }
        //分页查询
        $list = \app\common\model\Goods::with('type_bind,brand_bind,category_bind')->where($where)->order('id desc')->paginate(10);
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
            'goods_name|商品名称' => 'require|max:100',
            'goods_price|商品价格' => 'require|float|gt:0',
            'goods_number|商品库存' => 'requrie|integer|gt:0',
            //此处省略很多字段
            'goods_logo|商品logo' => 'require',
            'goods_images|商品相册' => 'require|array',
            'item|规格值' => 'require|array',
            'attr|属性值' => 'require|array',
            'type_id|商品模型' => 'require',
            'brand_id|商品品牌' => 'require',
            'cate_id|商品分类' => 'require',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //防范xss攻击 对富文本编辑器字段 做过滤处理
        $params['goods_desc'] = input('goods_desc', '', 'remove_xss');
        //添加数据
        //开启事务
        \think\Db::startTrans();
        try{
            //商品表数据（SPU表）
            //logo图片生成缩略图
            if(is_file('.' . $params['goods_logo'])){
                //\think\Image::open('.' . $params['goods_logo'])->thumb(200,240)->save('.' . $params['goods_logo']);
                //如果重新取名
                $goods_logo = dirname($params['goods_logo']) . DS . 'thumb_' . basename($params['goods_logo']);
                \think\Image::open('.' . $params['goods_logo'])->thumb(200,240)->save('.' . $goods_logo);
                $params['goods_logo'] = $goods_logo;
            }else{
                $this->fail('商品logo图片不存在');
            }
            //商品属性转化为json格式字符串
            $params['goods_attr'] = json_encode($params['attr'], JSON_UNESCAPED_UNICODE);
            //$params['goods_attr'] = json_encode(array_values($params['attr']), JSON_UNESCAPED_UNICODE);
            //添加商品表数据
            $goods = \app\common\model\Goods::create($params, true);
            //商品相册表数据
            $goods_images_data = [];
            foreach($params['goods_images'] as $k=>$v){
                if(!is_file('.' . $v)){
                    continue;
                }
                //$v 就是一个图片地址 需要生成两张不同尺寸的缩略图，组装成一条数据
                $pics_big = dirname($v) . DS . 'thumb_800_' . basename($v);
                $pics_sma = dirname($v) . DS . 'thumb_400_' . basename($v);
                //生成缩略图
                $image = \think\Image::open('.' . $v);
                $image->thumb(800, 800)->save('.' . $pics_big);
                $image->thumb(400, 400)->save('.' . $pics_sma);
                $goods_images_data[] = [
                    'goods_id' => $goods['id'],
                    'pics_big' => $pics_big,
                    'pics_sma' => $pics_sma,
                ];
                //unlink('.' . $v);
            }
            $goods_images = new \app\common\model\GoodsImages();
            $goods_images->saveAll($goods_images_data);
            //规格商品表数据（SKU表）
            $spec_goods_data = [];
            foreach($params['item'] as $v){
                //$v 和数据表字段对比，缺少goods_id
                $v['goods_id'] = $goods['id'];
                $spec_goods_data[] = $v;
            }
            $spec_goods_model = new \app\common\model\SpecGoods();
            $spec_goods_model->allowField(true)->saveAll($spec_goods_data);
            //提交事务
            \think\Db::commit();
            $info = \app\common\model\Goods::with('type_bind,brand_bind,category_bind')->find($goods['id']);
            $this->ok($info);
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //记录日志写法
            $msg = $e->getMessage();
            $this->fail($msg);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询商品一条数据  相册图片、规格商品sku、所属分类、所属品牌
        $info = \app\common\model\Goods::with('goods_images,spec_goods,category,brand')->find($id);
        //关联模型查询，不允许多个嵌套关联，只能有一个生效
        $info['type'] = \app\common\model\Type::with('attrs,specs,specs.spec_values')->find($info['type_id']);
        $this->ok($info);
    }

    /**
     * 显示编辑资源表单页.
     *  get  域名/goods/66/edit
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //查询商品相关数据 相册图片、规格商品sku、所属分类、所属品牌
        $goods = \app\common\model\Goods::with('goods_images,category,brand,spec_goods')->find($id);
        //关联模型查询，不允许多个嵌套关联，只能有一个生效,查询所属模型及规格属性
        $goods['type'] = \app\common\model\Type::with('attrs,specs,specs.spec_values')->find($goods['type_id']);
        //查询所有的模型列表 type
        $type = \app\common\model\Type::select();
        //查询商品分类 用于三级联动中三个下拉列表显示
        //查询所有的一级分类
        $cate_one = \app\common\model\Category::where('pid', 0)->select();
        //找到 商品所属的一级分类id  和二级分类id
        $pid_path = $goods['category']['pid_path']; // [0,3,124]
        //分类模型中设置过获取器 ，$pid_path = explode('_', $pid_path); // $pid_path[1]  $pid_path[2]
        //查询商品所属的一级分类下的 所有二级分类
        $cate_two = \app\common\model\Category::where('pid', $pid_path[1])->select();
        //查询商品所属的二级分类下的 所有三级分类
        $cate_three = \app\common\model\Category::where('pid', $pid_path[2])->select();

        //返回数据
        $data = [
            'goods' => $goods,
            'type' => $type,
            'category' => [
                'cate_one' => $cate_one,
                'cate_two' => $cate_two,
                'cate_three' => $cate_three
            ]
        ];
        $this->ok($data);
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
            'goods_name|商品名称' => 'require|max:100',
            'goods_price|商品价格' => 'require|float|gt:0',
            'goods_number|商品库存' => 'requrie|integer|gt:0',
            //此处省略很多字段
            //'goods_logo|商品logo' => 'require',
            'goods_images|商品相册' => 'array',
            'item|规格值' => 'require|array',
            'attr|属性值' => 'require|array',
            'type_id|商品模型' => 'require',
            'brand_id|商品品牌' => 'require',
            'cate_id|商品分类' => 'require',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //防范xss攻击 对富文本编辑器字段 做过滤处理
        $params['goods_desc'] = input('goods_desc', '', 'remove_xss');
        //添加数据
        //开启事务
        \think\Db::startTrans();
        try{
            //商品表数据（SPU表）
            //logo图片生成缩略图
            if(!empty($params['goods_logo']) && is_file('.' . $params['goods_logo'])){
                //\think\Image::open('.' . $params['goods_logo'])->thumb(200,240)->save('.' . $params['goods_logo']);
                //如果重新取名
                $goods_logo = dirname($params['goods_logo']) . DS . 'thumb_' . basename($params['goods_logo']);
                \think\Image::open('.' . $params['goods_logo'])->thumb(200,240)->save('.' . $goods_logo);
                $params['goods_logo'] = $goods_logo;
            }
            //商品属性转化为json格式字符串
            $params['goods_attr'] = json_encode($params['attr'], JSON_UNESCAPED_UNICODE);
            //$params['goods_attr'] = json_encode(array_values($params['attr']), JSON_UNESCAPED_UNICODE);
            //修改商品表数据
            \app\common\model\Goods::update($params, ['id'=>$id], true);
            //商品相册表数据
            if(!empty($params['goods_images'])){
                $goods_images_data = [];
                foreach($params['goods_images'] as $k=>$v){
                    if(!is_file('.' . $v)){
                        continue;
                    }
                    //$v 就是一个图片地址 需要生成两张不同尺寸的缩略图，组装成一条数据
                    $pics_big = dirname($v) . DS . 'thumb_800_' . basename($v);
                    $pics_sma = dirname($v) . DS . 'thumb_400_' . basename($v);
                    //生成缩略图
                    $image = \think\Image::open('.' . $v);
                    $image->thumb(800, 800)->save('.' . $pics_big);
                    $image->thumb(400, 400)->save('.' . $pics_sma);
                    $goods_images_data[] = [
                        'goods_id' => $id,
                        'pics_big' => $pics_big,
                        'pics_sma' => $pics_sma,
                    ];
                    //unlink('.' . $v);
                }
                $goods_images = new \app\common\model\GoodsImages();
                $goods_images->saveAll($goods_images_data);
            }

            //规格商品表数据（SKU表）
            //先删除原来的数据再添加新数据
            \app\common\model\SpecGoods::destroy(['goods_id'=>$id]);
            $spec_goods_data = [];
            foreach($params['item'] as $v){
                //$v 和数据表字段对比，缺少goods_id
                $v['goods_id'] = $id;
                $spec_goods_data[] = $v;
            }
            $spec_goods_model = new \app\common\model\SpecGoods();
            $spec_goods_model->allowField(true)->saveAll($spec_goods_data);
            //提交事务
            \think\Db::commit();
            //返回数据
            //$this->ok();
            $info = \app\common\model\Goods::with('type_bind,brand_bind,category_bind')->find($id);
            $this->ok($info);
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //记录日志写法
            $msg = $e->getMessage();
            /* trace('adminapi/goods/save:'.$msg, 'error');
             $this->fail('添加失败');*/
            $this->fail($msg);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //删除商品数据
        $is_on_sale = \app\common\model\Goods::where('id', $id)->value('is_on_sale');
        if($is_on_sale){
            $this->fail('商品已上架，不能删除');
        }
        \app\common\model\Goods::destroy($id);
        //删除相册图片（从数据表删除、从硬盘删除）
        //查询相册图片
        $goods_images = \app\common\model\GoodsImages::where('goods_id', $id)->select();
        //从数据表删除相册图片
        \app\common\model\GoodsImages::destroy(['goods_id'=>$id]);
        $images = [];
        foreach($goods_images as $v){
            $images[] = $v['pics_big'];
            $images[] = $v['pics_sma'];
        }
        foreach($images as $v){
            if(is_file('.' . $v)){
                unlink('.' . $v);
            }
        }
        $this->ok();
    }

    //删除相册图片接口
    public function delpics($id)
    {
        //\app\common\model\GoodsImages::destroy($id);
        //从数据表删除数据
        $data = \app\common\model\GoodsImages::find($id);
        if(!$data){
            $this->ok();
        }
        $data->delete();
        //从硬盘删除数据
        //$data['pics_big']  $data['pics_sma']
        if(is_file('.' . $data['pics_big'])){
            unlink('.' . $data['pics_big']);
        }
        if(is_file('.' . $data['pics_sma'])){
            unlink('.' . $data['pics_sma']);
        }
        $this->ok();
    }
}
