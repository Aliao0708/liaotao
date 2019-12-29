<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Type extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询所有数据
        $list = \app\common\model\Type::field('id,type_name')->select();
        //返回
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
            'type_name|模型名称' => 'require',
            'spec|规格' => 'require|array',
            'attr|属性' => 'require|array'
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        //添加数据 4个添加操作
        //使用事务
        //开启事务
        \think\Db::startTrans();
        try{
            //处理数据
            //添加type数据
            $type = \app\common\model\Type::create($params, true);
            //$type = \app\common\model\Type::create(['type_name' => $params['type_name']]);
            //检测商品规格信息
            foreach($params['spec'] as $k=>$v){
                //如果规格名称为空，则删除当前整条数据
                if(empty($v['name'])){
                    unset($params['spec'][$k]);
                    continue;
                }
                //如果规格值不是数组，则删除当前整条数据
                if(!is_array($v['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
                //删除规格值数组中的空值
                foreach($v['value'] as $key=>$value){
                    if(empty($value)){
                        unset($params['spec'][$k]['value'][$key]);
                        continue;
                    }
                }
                //如果规格值数组为空数组，删除整条数据
                if(empty($params['spec'][$k]['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
            }
            //添加商品规格名称
            /*$spec_data = [
                ['type_id' => 100, 'spec_name' => '颜色', 'sort' => 50],
                ['type_id' => 100, 'spec_name' => '内存', 'sort' => 50],
            ];*/
            $spec_data = [];
            foreach($params['spec'] as $k=>$v){
                $spec_data[] = [
                    'type_id' => $type['id'],
                    'spec_name' => $v['name'],
                    'sort' => $v['sort']
                ];
            }
            $spec_model = new \app\common\model\Spec();
            $spec_res = $spec_model->saveAll($spec_data);
            //添加商品规格值
            $spec_value_data = [];
            foreach($params['spec'] as $k=>$v){
                //$v['value']  $v['name']
                foreach($v['value'] as $value){
                    //$value 就是一个规格值 比如黑色
                    $spec_value_data[] = [
                        'spec_id' => $spec_res[$k]['id'],
                        'spec_value' => $value,
                        'type_id' => $type['id']
                    ];
                }
            }
            $spec_value_model = new \app\common\model\SpecValue();
            $spec_value_model->saveAll($spec_value_data);
            //检测商品属性信息
            foreach($params['attr'] as $k=>$v){
                //如果属性名称为空，则删除整条数据
                if(empty($v['name'])){
                    unset($params['attr'][$k]);
                    continue;
                }
                //如果属性值不是数组，则设置为空数组
                if(!is_array($v['value'])){
                    $params['attr'][$k]['value'] = [];
                    continue;
                }
                //如果属性值数组中有空值，则删除空值
                foreach($v['value'] as $key=>$value){
                    if(empty($value)){
                        unset($params['attr'][$k]['value'][$key]);
                        continue;
                    }
                }
            }
            //添加商品属性信息
            $attr_data = [];
            foreach($params['attr'] as $k=>$v){
                $attr_data[] = [
                    'attr_name' => $v['name'],
                    'type_id' => $type['id'],
                    'attr_values' => implode(',', $v['value']),
                    'sort' => $v['sort']
                ];
            }
            $attr_model = new \app\common\model\Attribute();
            $attr_model->saveAll($attr_data);
            //提交事务
            \think\Db::commit();
            $this->ok();
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //$this->fail('操作失败');
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('msg:' . $msg . ';file:' . $file . ';line:' . $line);
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
        //嵌套关联
        $info = \app\common\model\Type::with('specs,specs.spec_values,attrs')->find($id);
        $this->ok($info);
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
        //接收参数
        $params = input();
        //参数数组参考：
        /*$params = [
            'type_name' => '手机',
            'spec' => [
                ['name' => '颜色', 'sort' => 50, 'value'=>['黑色', '白色', '红色']],
                ['name' => '内存', 'sort' => 50, 'value'=>['64G', '128G', '256G']],
            ],
            'attr' => [
                ['name' => '毛重', 'sort'=>50, 'value' => []],
                ['name' => '产地', 'sort'=>50, 'value' => ['进口', '国产']],
            ]
        ];*/
        //参数检测
        $validate = $this->validate($params, [
            'type_name|模型名称' => 'require',
            'spec|规格' => 'require|array',
            'attr|属性' => 'require|array'
        ]);
        if($validate !== true){
            $this->fail($validate, 400);
        }
        //添加数据 4个添加操作
        //使用事务
        //开启事务
        \think\Db::startTrans();
        try{
            //处理数据
            //修改type数据
            \app\common\model\Type::update($params, ['id'=>$id], true);
            //检测商品规格信息
            foreach($params['spec'] as $k=>$v){
                //如果规格名称为空，则删除当前整条数据
                if(empty($v['name'])){
                    unset($params['spec'][$k]);
                    continue;
                }
                //如果规格值不是数组，则删除当前整条数据
                if(!is_array($v['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
                //删除规格值数组中的空值
                foreach($v['value'] as $key=>$value){
                    if(empty($value)){
                        unset($params['spec'][$k]['value'][$key]);
                        continue;
                    }
                }
                //如果规格值数组为空数组，删除整条数据
                if(empty($params['spec'][$k]['value'])){
                    unset($params['spec'][$k]);
                    continue;
                }
            }
            //添加商品规格名称
            //先删除原来的，再添加新的
            \app\common\model\Spec::destroy(['type_id'=>$id]);
            $spec_data = [];
            foreach($params['spec'] as $k=>$v){
                $spec_data[] = [
                    'type_id' => $id,
                    'spec_name' => $v['name'],
                    'sort' => $v['sort']
                ];
            }
            $spec_model = new \app\common\model\Spec();
            $spec_res = $spec_model->saveAll($spec_data);

            //添加商品规格值
            //先删除原来的，再添加新的
            \app\common\model\SpecValue::destroy(['type_id'=>$id]);
            $spec_value_data = [];
            foreach($params['spec'] as $k=>$v){
                //$v['value']  $v['name']
                foreach($v['value'] as $value){
                    //$value 就是一个规格值 比如黑色
                    $spec_value_data[] = [
                        'spec_id' => $spec_res[$k]['id'],
                        'spec_value' => $value,
                        'type_id' => $id
                    ];
                }
            }
            $spec_value_model = new \app\common\model\SpecValue();
            $spec_value_model->saveAll($spec_value_data);
            //检测商品属性信息
            foreach($params['attr'] as $k=>$v){
                //如果属性名称为空，则删除整条数据
                if(empty($v['name'])){
                    unset($params['attr'][$k]);
                    continue;
                }
                //如果属性值不是数组，则设置为空数组
                if(!is_array($v['value'])){
                    $params['attr'][$k]['value'] = [];
                    continue;
                }
                //如果属性值数组中有空值，则删除空值
                foreach($v['value'] as $key=>$value){
                    if(empty($value)){
                        unset($params['attr'][$k]['value'][$key]);
                        continue;
                    }
                }
            }
            //添加商品属性信息
            //先删除原来的，再添加新的
            \app\common\model\Attribute::destroy(['type_id'=>$id]);
            $attr_data = [];
            foreach($params['attr'] as $k=>$v){
                $attr_data[] = [
                    'attr_name' => $v['name'],
                    'type_id' => $id,
                    'attr_values' => implode(',', $v['value']),
                    'sort' => $v['sort']
                ];
            }
            $attr_model = new \app\common\model\Attribute();
            $attr_model->saveAll($attr_data);
            //提交事务
            \think\Db::commit();
            $this->ok();
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //$this->fail('操作失败');
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('msg:' . $msg . ';file:' . $file . ';line:' . $line);
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
        //删除4张表
        //开启事务
        \think\Db::startTrans();
        try{
            //删除type表
            \app\common\model\Type::destroy($id);
            //删除规格名称表数据
            \app\common\model\Spec::destroy(['type_id'=>$id]);
            //删除规格值表数据
            \app\common\model\SpecValue::destroy(['type_id'=>$id]);
            //删除属性表数据
            \app\common\model\Attribute::destroy(['type_id'=>$id]);
            //提交事务
            \think\Db::commit();
            $this->ok();
        }catch (\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //$this->fail('操作失败');
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('msg:' . $msg . ';file:' . $file . ';line:' . $line);
        }
    }
}
