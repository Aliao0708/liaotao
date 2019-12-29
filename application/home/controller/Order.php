<?php

namespace app\home\controller;

use think\Controller;

class Order extends Base
{
    //结算页面展示
    public function create()
    {
        //登录检测
        if(!session('?user_info')){
            //设置登录后的跳转地址
            session('back_url', 'home/cart/index');
            //跳转到登录
            $this->redirect('home/login/login');
        }
        //查询当前用户的收货地址信息
        $user_id = session('user_info.id');
        $address = \app\common\model\Address::where('user_id', $user_id)->select();
        //查询商品清单信息（选中的购物记录以及商品信息）
        $res = \app\home\logic\OrderLogic::getCartWithGoods();
        $res['address'] = $address;
        return view('create', $res);
    }

    //提交订单
    public function save()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params,[
           'address_id' => 'require|integer|gt:0'
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        //组装订单表需要的数据
        $user_id = session('user_info.id');
        //收货地址
        $address = \app\common\model\Address::find($params['address_id']);
        if(!$address){
            $this->error('数据异常');
        }
        //开启事务
        \think\Db::startTrans();
        try{
            //订单编号 纯数字
            $order_sn = time() . mt_rand(100000, 999999);
            //商品总价格
            //查询商品清单信息（选中的购物记录以及商品信息）
            $res = \app\home\logic\OrderLogic::getCartWithGoods();
            //库存检测
            foreach($res['data'] as $v){
                //$v['number']  $v['goods']['goods_number']
                if($v['number'] > $v['goods']['goods_number']){
                    //库存不足
                    throw new \Exception('订单中包含库存不足的商品');
                }
            }
            //向订单表添加一条数据
            $order_data = [
                'order_sn' => $order_sn,
                'user_id' => $user_id,
                'consignee' => $address['consignee'],
                'phone' => $address['phone'],
                'address' => $address['area'] . $address['address'],
                'goods_price' => $res['total_price'], //商品总价
                'shipping_price' => 0, //邮费
                'coupon_price' => 0,    //优惠金额
                'order_amount' => $res['total_price'], //应付款金额 = 商品总价 + 邮费 - 优惠金额
                'total_amount' => $res['total_price'], //订单总金额 = 商品总价 + 邮费
            ];
            $order = \app\common\model\Order::create($order_data, true);
            //$order['id']
            //向订单商品表添加多条数据
            $order_goods_data = [];
            foreach($res['data'] as $v){
                $order_goods_data[] = [
                    'order_id' => $order['id'],
                    'goods_id' => $v['goods_id'],
                    'spec_goods_id' => $v['spec_goods_id'],
                    'number' => $v['number'],
                    'goods_name' => $v['goods']['goods_name'],
                    'goods_logo' => $v['goods']['goods_logo'],
                    'goods_price' => $v['goods']['goods_price'],
                    'spec_value_names' => $v['spec_goods']['value_names'],
                ];
            }
            $order_goods = new \app\common\model\OrderGoods();
            $order_goods->saveAll($order_goods_data);
            //从购物车表删除对应记录
            //\app\common\model\Cart::destroy(['user_id'=>$user_id, 'is_selected'=>1]);
            //冻结库存
            $goods_data = [];
            $spec_goods_data = [];
            foreach($res['data'] as $v){
                if($v['spec_goods_id']){
                    //冻结 spec_goods表的库存
                    $spec_goods_data[] = [
                        'id' => $v['spec_goods_id'],
                        'store_count' => $v['goods']['goods_number'] - $v['number'],
                        'store_frozen' => $v['goods']['frozen_number'] + $v['number'],
                    ];
                }else{
                    //冻结 goods表的库存
                    $goods_data[] = [
                        'id' => $v['goods_id'],
                        'goods_number' => $v['goods']['goods_number'] - $v['number'],
                        'frozen_number' => $v['goods']['frozen_number'] + $v['number'],
                    ];
                }
            }
            //批量修改库存
            $goods_model = new \app\common\model\Goods();
            $goods_model->saveAll($goods_data);
            $spec_goods_model = new \app\common\model\SpecGoods();
            $spec_goods_model->saveAll($spec_goods_data);
            //提交事务
            \think\Db::commit();
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            $msg = $e->getMessage();
            $this->error($msg);
        }
       //跳转到选择支付方式的页面
        $this->redirect('home/order/pay',['id'=>$order['id']]);
    }

   /* public function pay($id)
    {
        //查询订单信息
        $order = \app\common\model\Order::find($id);
        //支付方式
        $pay_type = config('pay_type');
        return view('pay',['order'=>$order,'pay_type'=>$pay_type]);
    }*/

    //去支付
    public function topay($id)
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params,[
           'id' => 'require|integer|gt:0',
           'pay_type|支付方式' => 'require'
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        //查询订单信息
        $user_id = session('user_info.id');
        $order = \app\common\model\Order::where('user_id',$user_id)
            ->where('id',$id)
            ->where('order_status',0)
            ->find();
        if(!$order){
            $this->error('订单信息有误，无法支付');
        }
        //记录用户选择的支付方式，也可以在支付成功的异步通知中记录
        $pay_type = config('pay_type');
        $order->pay_code = $params['pay_type'];
        $order->pay_name = $pay_type[$params['pay_type']]['pay_name'];
        $order->save();

        switch($params['pay_type']){
            case 'wechat':
                //微信支付
                echo '订单创建成功，微信支付尚未开通';
                break;
            case 'union':
                //银联支付
                echo '订单创建成功，银联支付尚未开通';
                break;
            case 'alipay':
            default:
            //默认支付宝
            $html = "<form id='alipayment' action='/plugins/alipay/pagepay/pagepay.php' method='post' style='display:none'>
        <input id='WIDout_trade_no' name='WIDout_trade_no' value='{$order['order_sn']}'/>
        <input id='WIDsubject' name='WIDsubject' value='品优购商城商品' />
        <input id='WIDtotal_amount' name='WIDtotal_amount' value='{$order['order_amount']}' />
        <input id='WIDbody' name='WIDbody' value='品优购商城订单' />
    </form><script>document.getElementById('alipayment').submit();</script>";
            echo $html;
            break;
        }
    }

    public function pay($id)
    {
        //查询订单信息
        $order = \app\common\model\Order::find($id);
        //支付方式
        $pay_type = config('pay_type');
        //二维码图片中的支付链接（本地项目自定义链接，传递订单id参数）
        //用于测试的线上项目域名 http://pyg.tbyue.com
        $url = url('/home/order/qrpay', ['id'=>$order->order_sn, 'debug'=>'true'], true, "http://pyg.tbyue.com");
        //生成支付二维码
        $qrCode = new \Endroid\QrCode\QrCode($url);
        //二维码图片保存路径（请先将对应目录结构创建出来，需要具有写权限）
        $qr_path = '/uploads/qrcode/'.uniqid(mt_rand(100000,999999), true).'.png';
        //将二维码图片信息保存到文件中
        $qrCode->writeFile('.' . $qr_path);
        $this->assign('qr_path', $qr_path);
        return view('pay', ['order'=>$order, 'pay_type'=>$pay_type]);
    }

    //扫码支付
    public function qrpay()
    {
        //$_SERVER['HTTP_USER_AGENT']
        $agent = request()->server('HTTP_USER_AGENT');
        //判断扫码支付方式
        if ( strpos($agent, 'MicroMessenger') !== false ) {
            //微信扫码
            $pay_code = 'wx_pub_qr';
        }else if (strpos($agent, 'AlipayClient') !== false) {
            //支付宝扫码
            $pay_code = 'alipay_qr';
        }else{
            //默认为支付宝扫码支付
            $pay_code = 'alipay_qr';
        }
        //接收订单id参数
        $order_sn = input('id');
        //创建支付请求
        $this->pingpp($order_sn,$pay_code);
    }

    //发起ping++支付请求
    public function pingpp($order_sn,$pay_code)
    {
        //查询订单信息
        $order = \app\common\model\Order::where('order_sn', $order_sn)->find();
        //ping++聚合支付
        \Pingpp\Pingpp::setApiKey(config('pingpp.api_key'));// 设置 API Key
        \Pingpp\Pingpp::setPrivateKeyPath(config('pingpp.private_key_path'));// 设置私钥
        \Pingpp\Pingpp::setAppId(config('pingpp.app_id'));
        $params = [
            'order_no'  => $order['order_sn'],
            'app'       => ['id' => config('pingpp.app_id')],
            'channel'   => $pay_code,
            'amount'    => $order['order_amount'] * 100,
            'client_ip' => '127.0.0.1', //request()->ip()
            'currency'  => 'cny',
            'subject'   => 'Your Subject',//自定义标题
            'body'      => 'Your Body',//自定义内容
            'extra'     => [],
        ];
        if($pay_code == 'wx_pub_qr'){
            $params['extra']['product_id'] = $order['id'];
        }
        //创建Charge对象
        $ch = \Pingpp\Charge::create($params);
        //跳转到对应第三方支付链接
        $this->redirect($ch->credential->$pay_code);die;
    }

    //查询订单状态
    public function status()
    {
        //接收订单编号
        $order_sn = input('order_sn');
        //查询订单状态
        /*$order_status = \app\common\model\Order::where('order_sn', $order_sn)->value('order_status');
        return json(['code' => 200, 'msg' => 'success', 'data'=>$order_status]);*/
        //通过线上测试
        $res = curl_request("http://pyg.tbyue.com/home/order/status/order_sn/{$order_sn}");
        echo $res;die;
    }

    public function payresult()
    {
        $order_sn = input('order_sn');
        $order = \app\common\model\Order::where('order_sn', $order_sn)->find();
        if(empty($order)){
            return view('payfail', ['msg' => '订单编号错误']);
        }else{
            return view('paysuccess', ['pay_name' => $order->pay_name, 'total_amount'=>$order['total_amount']]);
        }
    }

    //支付宝同步跳转地址
    public function callback(){
        //接收参数
        $params = input();
        //展示页面
        //return view('paysuccess',['pay_name'=>'支付宝','total_amount'=>$params['total_amount']]);

        //验证签名
        require_once("./plugins/alipay/config.php");
        require_once './plugins/alipay/pagepay/service/AlipayTradeService.php';

        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($params);
        if($result){
            //验证成功
            return view('paysuccess', ['pay_name'=>'支付宝', 'total_amount'=>$params['total_amount']]);
        }else{
            //验证失败
            $msg = '支付结果验证失败';
            return view('payfail', ['msg' => $msg]);
        }
    }

    //支付宝异步通知
    public function notify()
    {
        //接收参数
        $params = input();
        //验证签名
        require_once './plugins/alipay/config.php';
        require_once './plugins/alipay/pagepay/service/AlipayTradeService.php';
        $alipaySevice = new \AlipayTradeService($config);

        //记录日志
        trace('/home/order/notify:接收参数：' . json_encode($params,JSON_UNESCAPED_UNICODE),'debug');
        $result = $alipaySevice->check($params);
        if(!$result){
            //验签失败
            trace('/home/order/notify:验签失败：' . $result,'error');
            echo 'fail';die;
        }
        //验签成功
        $trade_status = $params['trade_status'];
        if($trade_status == 'TRADE_FINISHED'){
            //超出可退款期限，触发此通知，交易已完成
            trace('/home/order/notify:交易已完成：' . $trade_status,'debug');
            echo 'success';die;
        }

        if($trade_status == 'TRADE_SUCCESS'){
            //查询并检测订单
            $order_sn = $params['out_trade_no'];
            $order = \app\common\model\Order::where('order_sn',$order_sn)->find();
            if(!$order){
                trace('/home/order/notify:订单不存在' . $order_sn,'error');
                echo 'fail';die;
            }
            //检测订单支付金额
            if($order['order_amount'] != $params['total_amount']){
                trace('/home/order/notify:顶单支付金额不正确' . $order['order_amount'] . '实付款金额:' . $params['total_amount'], 'error');
                echo 'fail';die;
            }
            //检测订单支付状态
            if($order['order_status'] != 0){
                trace('/home/order/notify:订单状态不是待付款' . $order['order_status'],'debug');
                echo 'success';die;
            }
            //修改订单状态
            $order->order_status = 1;  //状态为 已付款 或者 待发货
            $order->pay_code = 'alipay';
            $order->pay_name = '支付宝';
            $order->save();
            //记录支付信息
            \app\common\model\PayLog::create([
               'order_sn' => $order_sn,
               'json'     => json_encode($params,JSON_UNESCAPED_UNICODE)
            ],true);
            //扣减库存
            //查询订单下的商品信息
            $order_goods = \app\common\model\OrderGoods::with('goods,spec_goods')->where('order_id', $order['id'])->select();
            $goods_data = [];
            $spec_goods_data = [];
            foreach($order_goods as $v){
                if($v['spec_goods_id']){
                    //修改SKU表
                    $spec_goods_data[] = [
                        'id' => $v['spec_goods_id'],
                        'store_frozen' => $v['spec_goods']['store_frozen'] - $v['number']
                    ];
                }else{
                    //修改商品表SPU表
                    $goods_data[] = [
                        'id' => $v['goods_id'],
                        'frozen_number' => $v['goods']['frozen_number'] - $v['number']
                    ];
                }
            }
            //批量修改
            $goods_model = new \app\common\model\Goods();
            $goods_model->saveAll($goods_data);
            $spec_goods_model = new \app\common\model\SpecGoods();
            $spec_goods_model->saveAll($spec_goods_data);

            trace('/home/order/notify:订单状态已修改', 'debug');
            echo 'success';die;
        }
        trace('/home/order/notify:其他交易状态','debug');
        echo 'success';die;
    }
}
