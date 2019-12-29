<?php
namespace app\adminapi\controller;

class Index extends BaseApi
{
    public function index()
    {
        //echo encrypt_password('123456');die;

        //一对一关联
        //查询管理员信息 以及 档案信息
        /*$info = \app\common\model\Admin::find(1);
        $this->ok($info);
        $this->ok($info->profile);
        $this->ok($info->profile->idnum);*/

        //关联预载入
        /*//$info = \app\common\model\Admin::with('profile')->find(1);
        $info = \app\common\model\Admin::with('profile_bind')->find(1);
        $this->ok($info);*/

        //查询档案信息 以及管理员信息
        //$info = \app\common\model\Profile::find(1);
        //$this->ok($info);
        //$this->ok($info->admin);
        //$info = \app\common\model\Profile::with('admin')->find(1);
        //$info = \app\common\model\Profile::with('admin_bind')->find(1);
        //$info = \app\common\model\Profile::with('admin')->select();
        /*$info = \app\common\model\Profile::with('admin_bind')->select();
        $this->ok($info);*/

        //查询品牌信息以及分类
        //$info = \app\common\model\Brand::find(1);
        //$this->ok($info);
        //$this->ok($info->category);

        //$info = \app\common\model\Brand::with('category')->find(1);
        /*$info = \app\common\model\Brand::with('category_bind')->find(1);
        $this->ok($info);*/
        //查询分类 以及 分类下的品牌
        /*$info = \app\common\model\Category::with('brands')->find(72);
        $this->ok($info);*/

        /*$user_id = \tools\jwt\Token::getUserId();
        //$user_id = input('user_id');
        $this->ok($user_id);
        //加密密码
        $password = encrypt_password('123456');
        $this->ok($password);*/
        //生成token
        /*$token = \tools\jwt\Token::getToken(100);
        $this->ok($token);*/

        /*$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjNmMmc1N2E5MmFhIn0.eyJpc3MiOiJodHRwOlwvXC9hZG1pbmFwaS5weWcuY29tIiwiYXVkIjoiaHR0cDpcL1wvd3d3LnB5Zy5jb20iLCJqdGkiOiIzZjJnNTdhOTJhYSIsImlhdCI6MTU3NDgzODc2MCwibmJmIjoxNTc0ODM4NzU5LCJleHAiOjE1NzQ5MjUxNjAsInVzZXJfaWQiOjEwMH0.oiJOHEi96pVc4Sp440Bg3M4E9KsItE57cZ6Qi1DYSTM';
        $user_id = \tools\jwt\Token::getUserId($token);
        $this->ok($user_id);*/

        //从请求头获取token
        /*$token = \tools\jwt\Token::getRequestToken();
        $this->ok($token);*/

        //echo 'hello,adminapi';die;
        /*$goods = \think\Db::table('pyg_goods')->find();
        dump($goods);die;*/
        //返回数据
        //返回 code 200,msg success, data []
        //$this->response();
        /*$this->ok();
        //返回 具体的数据
        $data = \think\Db::table('pyg_goods')->select();
        $this->response(200, 'success', $data);
        $this->ok($data);
        //返回 失败提示
        //$this->response(400, '参数错误');
        $this->fail('参数错误');
        $this->fail('参数错误', 400);

        $this->ok(['token' => '12345.dsagfkdslafjdsaklagda']);*/

        //12345.dsagfkdslafjdsaklagda
        //123456.dsagfkdslafjdsaklagda
    }
}
