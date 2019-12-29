<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:54:"E:\pyg\public/../application/home\view\cart\index.html";i:1576747078;s:40:"E:\pyg\application\home\view\layout.html";i:1576152348;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

    <link rel="stylesheet" type="text/css" href="/static/home/css/all.css" />

    <script type="text/javascript" src="/static/home/js/all.js"></script>
</head>

<body>
<!-- 头部栏位 -->
<!--页面顶部-->
<div id="nav-bottom">
    <!--顶部-->
    <div class="nav-top">
        <div class="top">
            <div class="py-container">
                <div class="shortcut">
                    <ul class="fl">
                        <li class="f-item">品优购欢迎您！</li>
                        <?php if((\think\Session::get('user_info') == null)): ?>
                        <li class="f-item">请<a href="<?php echo url('home/login/login'); ?>">登录</a>　<span><a href="<?php echo url('home/login/register'); ?>">免费注册</a></span></li>
                        <?php else: ?>
                        <li class="f-item">Hi,<a href="javascript:;" ><?php echo \think\Session::get('user_info.nickname'); ?></a>　<span><a href="<?php echo url('home/login/logout'); ?>">退出</a></span></li>
                        <?php endif; ?>
                    </ul>
                    <ul class="fr">
                        <li class="f-item">我的订单</li>
                        <li class="f-item space"></li>
                        <li class="f-item"><a href="home.html" target="_blank">我的品优购</a></li>
                        <li class="f-item space"></li>
                        <li class="f-item">品优购会员</li>
                        <li class="f-item space"></li>
                        <li class="f-item">企业采购</li>
                        <li class="f-item space"></li>
                        <li class="f-item">关注品优购</li>
                        <li class="f-item space"></li>
                        <li class="f-item" id="service">
                            <span>客户服务</span>
                            <ul class="service">
                                <li><a href="cooperation.html" target="_blank">合作招商</a></li>
                                <li><a href="shoplogin.html" target="_blank">商家后台</a></li>
                            </ul>
                        </li>
                        <li class="f-item space"></li>
                        <li class="f-item">网站导航</li>
                    </ul>
                </div>
            </div>
        </div>

        <!--头部-->
        <div class="header">
            <div class="py-container">
                <div class="yui3-g Logo">
                    <div class="yui3-u Left logoArea">
                        <a class="logo-bd" title="品优购" href="JD-index.html" target="_blank"></a>
                    </div>
                    <div class="yui3-u Center searchArea">
                        <div class="search">
                            <form action="<?php echo url('home/goods/index'); ?>" method="get" class="sui-form form-inline">
                                <!--searchAutoComplete-->
                                <div class="input-append">
                                    <input type="text" id="autocomplete" class="input-error input-xxlarge" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>" />
                                    <button class="sui-btn btn-xlarge btn-danger" type="submit">搜索</button>
                                </div>
                            </form>
                        </div>
                        <div class="hotwords">
                            <ul>
                                <li class="f-item">品优购首发</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">每满99减30</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">办公用品</li>

                            </ul>
                        </div>
                    </div>
                    <div class="yui3-u Right shopArea">
                        <div class="fr shopcar">
                            <div class="show-shopcar" id="shopcar">
                                <span class="car"></span>
                                <a class="sui-btn btn-default btn-xlarge" href="<?php echo url('home/cart/index'); ?>" target="_blank">
                                    <span>我的购物车</span>
                                    <i class="shopnum">0</i>
                                </a>
                                <div class="clearfix shopcarlist" id="shopcarlist" style="display:none">
                                    <p>"啊哦，你的购物车还没有商品哦！"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="yui3-g NavList">
                    <div class="all-sorts-list">
                        <div class="yui3-u Left all-sort">
                            <h4>全部商品分类</h4>
                        </div>
                        <div class="sort">
                            <div class="all-sort-list2">
                                <?php foreach($category as $one): ?>
                                <div class="item">
                                    <h3><a href="javascript:;"><?php echo $one['cate_name']; ?></a></h3>
                                    <div class="item-list clearfix">
                                        <div class="subitem">
                                            <?php foreach($one['son'] as $two): ?>
                                            <dl class="fore1">
                                                <dt><a href="javascript:;"><?php echo $two['cate_name']; ?></a></dt>
                                                <dd>
                                                    <?php foreach($two['son'] as $three): ?>
                                                    <em><a href="<?php echo url('home/goods/index', ['id'=>$three['id']]); ?>"><?php echo $three['cate_name']; ?></a></em>
                                                    <?php endforeach; ?>
                                                </dd>
                                            </dl>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="yui3-u Center navArea">
                        <ul class="nav">
                            <li class="f-item">服装城</li>
                            <li class="f-item">美妆馆</li>
                            <li class="f-item">品优超市</li>
                            <li class="f-item">全球购</li>
                            <li class="f-item">闪购</li>
                            <li class="f-item">团购</li>
                            <li class="f-item">有趣</li>
                            <li class="f-item"><a href="seckill-index.html" target="_blank">秒杀</a></li>
                        </ul>
                    </div>
                    <div class="yui3-u Right"></div>
                </div>

            </div>
        </div>
    </div>
</div>


<title>我的购物车</title>

<link rel="stylesheet" type="text/css" href="/static/home/css/pages-cart.css" />

<script type="text/javascript" src="/static/home/js/pages/index.js"></script>

<!--主内容-->
<div class="cart py-container">
	<!--All goods-->
	<div class="allgoods">
		<h4>全部商品<span>11</span></h4>
		<div class="cart-main">
			<div class="yui3-g cart-th">
				<div class="yui3-u-1-4"><input type="checkbox" name="" id="" value="" /> 全部</div>
				<div class="yui3-u-1-4">商品</div>
				<div class="yui3-u-1-8">单价（元）</div>
				<div class="yui3-u-1-8">数量</div>
				<div class="yui3-u-1-8">小计（元）</div>
				<div class="yui3-u-1-8">操作</div>
			</div>
			<div class="cart-item-list">
				<div class="cart-shop">
					<input type="checkbox" name="" id="" value="" />
					<span class="shopname self">传智自营</span>
				</div>
				<div class="cart-body">
					<?php foreach($list as $v): ?>
					<div class="cart-list">
						<ul class="goods-list yui3-g" cart_id="<?php echo $v['id']; ?>" number="<?php echo $v['number']; ?>">
							<li class="yui3-u-1-24">
								<input type="checkbox" class="row_check" <?php if(($v['is_selected'] == 1)): ?>checked="checked"<?php endif; ?> name="" id="" value="" />
							</li>
							<li class="yui3-u-6-24">
								<div class="good-item">
									<div class="item-img"><img src="<?php echo $v['goods']['goods_logo']; ?>" /></div>
									<div class="item-msg"><?php echo $v['goods']['goods_name']; ?></div>
								</div>
							</li>
							<li class="yui3-u-5-24">
								<div class="item-txt"><?php echo $v['goods']['value_names']; ?></div>
							</li>
							<li class="yui3-u-1-8"><span class="price"><?php echo $v['goods']['goods_price']; ?></span></li>
							<li class="yui3-u-1-8">
								<a href="javascript:void(0)" class="increment mins">-</a>
								<input autocomplete="off" type="text" value="<?php echo $v['number']; ?>" minnum="1" class="itxt current_number" />
								<a href="javascript:void(0)" class="increment plus">+</a>
							</li>
							<li class="yui3-u-1-8"><span class="sum"><?php echo $v['number'] * $v['goods']['goods_price']; ?></span></li>
							<li class="yui3-u-1-8">
								<a href="javascript:;" class="delete">删除</a><br />
								<a href="#none">移到我的关注</a>
							</li>
						</ul>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="cart-tool">
			<div class="select-all">
				<input type="checkbox" class="check_all" name="" value="" />
				<span>全选</span>
			</div>
			<div class="option">
				<a href="#none">删除选中的商品</a>
				<a href="#none">移到我的关注</a>
				<a href="#none">清除下柜商品</a>
			</div>
			<div class="money-box">
				<div class="chosed">已选择<span id="total_number">0</span>件商品</div>
				<div class="sumprice">
					<span><em>总价（不含运费） ：</em><i id="total_price" class="summoney">¥0</i></span>
					<span><em>已节省：</em><i>-¥0</i></span>
				</div>
				<div class="sumbtn">
					<a class="sum-btn" href="javascript:;">结算</a>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="deled">
			<span>已删除商品，您可以重新购买或加关注：</span>
			<div class="cart-list del">
				<ul class="goods-list yui3-g">
					<li class="yui3-u-1-2">
						<div class="good-item">
							<div class="item-msg">Apple Macbook Air 13.3英寸笔记本电脑 银色（Corei5）处理器/8GB内存</div>
						</div>
					</li>
					<li class="yui3-u-1-6"><span class="price">8848.00</span></li>
					<li class="yui3-u-1-6">
						<span class="number">1</span>
					</li>
					<li class="yui3-u-1-8">
						<a href="#none">重新购买</a>
						<a href="#none">移到我的关注</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="liked">
			<ul class="sui-nav nav-tabs">
				<li class="active">
					<a href="#index" data-toggle="tab">猜你喜欢</a>
				</li>
				<li>
					<a href="#profile" data-toggle="tab">特惠换购</a>
				</li>
			</ul>
			<div class="clearfix"></div>
			<div class="tab-content">
				<div id="index" class="tab-pane active">
					<div id="myCarousel" data-ride="carousel" data-interval="4000" class="sui-carousel slide">
						<div class="carousel-inner">
							<div class="active item">
								<ul>
									<li>
										<img src="/static/home/img/like1.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
									<li>
										<img src="/static/home/img/like2.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
									<li>
										<img src="/static/home/img/like3.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
									<li>
										<img src="/static/home/img/like4.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
								</ul>
							</div>
							<div class="item">
								<ul>
									<li>
										<img src="/static/home/img/like1.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
									<li>
										<img src="/static/home/img/like2.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
									<li>
										<img src="/static/home/img/like3.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
									<li>
										<img src="/static/home/img/like4.png" />
										<div class="intro">
											<i>Apple苹果iPhone 6s (A1699)</i>
										</div>
										<div class="money">
											<span>$29.00</span>
										</div>
										<div class="incar">
											<a href="#" class="sui-btn btn-bordered btn-xlarge btn-default"><i class="car"></i><span class="cartxt">加入购物车</span></a>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<a href="#myCarousel" data-slide="prev" class="carousel-control left">‹</a>
						<a href="#myCarousel" data-slide="next" class="carousel-control right">›</a>
					</div>
				</div>
				<div id="profile" class="tab-pane">
					<p>特惠选购</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		//计算已选商品数量和金额
		var change_total = function(){
			//获取到选中的行checkbox 遍历，获取对应行的数量和小计金额，进行累加
			var total_number = 0;
			var total_price = 0;
			$('.row_check:checked').each(function(i,v){
				//v是一个checkbox标签
				//累加每一行的数量
				total_number += parseInt( $(v).closest('ul').find('.current_number').val() );
				//累加每一行的小计金额
				total_price += parseFloat( $(v).closest('ul').find('.sum').html() );
			});
			//将计算好的数量和金额，显示到页面
			$('#total_number').html(total_number);
			$('#total_price').html('¥' + total_price);
		};
		//计算已选商品数量和金额
		change_total();

		//控制全选的选中状态
		function check_all(){
			var total = $('.row_check').length;
			var checked = $('.row_check:checked').length;
			//console.log(total, checked);
			var status = total == checked;
			$('.check_all').prop('checked', status);
			//相当于以下if else判断
			/*if(total == checked){
             $('.check_all').prop('checked', true);
             }else{
             $('.check_all').prop('checked', false);
             }*/
		}
		//控制全选的选中状态
		check_all();

		//修改购买数量
		var change_num = function(number, element){
			var data = {
				"id":$(element).closest('ul').attr('cart_id'),
				"number":number
			};
			//发送ajax请求
			$.ajax({
				"url":"<?php echo url('home/cart/changenum'); ?>",
				"type":"post",
				"data":data,
				"dataType":"json",
				"success":function(res){
					if(res.code != 200){
						alert(res.msg);return;
					}
					//修改成功后的处理
					//显示到当前行的input
					$(element).closest('ul').find('.current_number').val(number);
					//计算当前行的小计金额
					var price = parseFloat( $(element).closest('ul').find('.price').html() );
					var sum = price * number;
					$(element).closest('ul').find('.sum').html(sum);
					//修改ul标签上的number
					$(element).closest('ul').attr('number', number);
					//计算已选的数量和金额
					change_total();
				}
			});

		};
		//修改选中状态
		/*var change_status = function(id, status){
            var data = {
                "id":id,
                "status":status
            };
            $.ajax({
                "url":"<?php echo url('home/cart/changestatus'); ?>",
                "type":"post",
                "data":data,
                "dataType":"json",
                "success":function(res){
                    if(res.code != 200){
                        alert(res.msg);return;
                    }
                }
            });
        };*/
		var change_status = function(element){
			var data = {
				"id":$(element).hasClass('check_all') ? 'all' : $(element).closest('ul').attr('cart_id'),
				"status":$(element).prop('checked') ? 1 : 0
			};
			$.ajax({
				"url":"<?php echo url('home/cart/changestatus'); ?>",
				"type":"post",
				"data":data,
				"dataType":"json",
				"success":function(res){
					if(res.code != 200){
						alert(res.msg);return;
					}
				}
			});
		};
		//全选效果
		$('.check_all').change(function(){
			//获取全选的选中状态  checked属性值
			var status = $('.check_all').prop('checked');
			//console.log(status);
			//设置每一行的checkbox 和全选状态一致
			$('.row_check').prop('checked', status);
			//修改选中状态
			/*var id = 'all';
            var status = $(this).prop('checked') ? 1 : 0;
            change_status(id, status);*/
			change_status(this);
			//计算已选商品数量和金额
			change_total();
		});

		//每一行checkbox的效果
		$('.row_check').change(function(){
			//控制全选的选中状态
			check_all();
			//修改选中状态
			/*var id = $(this).closest('ul').attr('cart_id');
            var status = $(this).prop('checked') ? 1 : 0;
            change_status(id, status);*/
			change_status(this);
			//计算选中数量和金额
			change_total();
		});

		//+号修改数量
		$('.plus').click(function(){
			//取到当前行input的数量
			//var number = $(this).prev().val();
			var number = parseInt($(this).closest('ul').find('.current_number').val());
			//计算新的数量 +1
			number++;
			//number += 1;
			//发送ajax请求修改数量
			change_num(number, this);
		});

		//-号修改数量
		$('.mins').click(function(){
			//取到当前行input的数量
			//var number = $(this).next().val();
			var number = parseInt($(this).closest('ul').find('.current_number').val());
			if(number == 1) return;
			//计算新的数量 -1
			number--;
			//发送ajax请求修改数量
			change_num(number, this);
		});

		//input直接修改数量
		$('.current_number').change(function(){
			//获取到修改数量
			var number = $(this).val();
			//检测数量格式 略
			if(!/^\d+$/.test(number) || number == 0){
				alert('购买数量必须是正整数');
				//获取之前的数量
				var old_number = $(this).closest('ul').attr('number');
				$(this).val(old_number);
				return;
			}
			/*if(isNaN(number) || parseInt(number)!=number || number <= 0){
                alert('购买数量必须是正整数');return;
            }*/
			//发送ajax请求修改数量
			change_num(number, this);
		});

		//删除购物记录
		$('.delete').click(function(){
			var data = {
				'id':$(this).closest('ul').attr('cart_id')
			};
			var that = this;
			//发送ajax
			$.ajax({
				"url": "<?php echo url('home/cart/delcart'); ?>",
				"type": "post",
				"data": data,
				"dataType": "json",
				"success": function (res) {
					if (res.code != 200) {
						alert(res.msg);
						return;
					}
					//移除当前行记录
					$(that).closest('.cart-list').remove();
					//$(that).closest('ul').parent().remove();
					//计算选中数量和金额
					change_total();
				}
			});

		});

		//结算
		$('.sum-btn').click(function(){
			//判断是否有选中的记录
			if($('.row_check:checked').length == 0){
				alert('至少选中一条购物记录');return;
			}
			//跳转到结算页面
			location.href = "<?php echo url('home/order/create'); ?>";
		});
	})
</script>


<!-- 底部栏位 -->
<!--页面底部-->
<div class="clearfix footer">
    <div class="py-container">
        <div class="footlink">
            <div class="Mod-service">
                <ul class="Mod-Service-list">
                    <li class="grid-service-item intro  intro1">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro2">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro  intro3">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro4">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro intro5">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                </ul>
            </div>
            <div class="clearfix Mod-list">
                <div class="yui3-g">
                    <div class="yui3-u-1-6">
                        <h4>购物指南</h4>
                        <ul class="unstyled">
                            <li>购物流程</li>
                            <li>会员介绍</li>
                            <li>生活旅行/团购</li>
                            <li>常见问题</li>
                            <li>购物指南</li>
                        </ul>

                    </div>
                    <div class="yui3-u-1-6">
                        <h4>配送方式</h4>
                        <ul class="unstyled">
                            <li>上门自提</li>
                            <li>211限时达</li>
                            <li>配送服务查询</li>
                            <li>配送费收取标准</li>
                            <li>海外配送</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>支付方式</h4>
                        <ul class="unstyled">
                            <li>货到付款</li>
                            <li>在线支付</li>
                            <li>分期付款</li>
                            <li>邮局汇款</li>
                            <li>公司转账</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>售后服务</h4>
                        <ul class="unstyled">
                            <li>售后政策</li>
                            <li>价格保护</li>
                            <li>退款说明</li>
                            <li>返修/退换货</li>
                            <li>取消订单</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>特色服务</h4>
                        <ul class="unstyled">
                            <li>夺宝岛</li>
                            <li>DIY装机</li>
                            <li>延保服务</li>
                            <li>品优购E卡</li>
                            <li>品优购通信</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>帮助中心</h4>
                        <img src="/static/home/img/wx_cz.jpg">
                    </div>
                </div>
            </div>
            <div class="Mod-copyright">
                <ul class="helpLink">
                    <li>关于我们<span class="space"></span></li>
                    <li>联系我们<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>商家入驻<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们</li>
                </ul>
                <p>地址：北京市昌平区建材城西路金燕龙办公楼一层 邮编：100096 电话：400-618-4000 传真：010-82935100</p>
                <p>京ICP备08001421号京公网安备110108007702</p>
            </div>
        </div>
    </div>
</div>
<!--页面底部END-->
<!--侧栏面板开始-->
<div class="J-global-toolbar">
    <div class="toolbar-wrap J-wrap">
        <div class="toolbar">
            <div class="toolbar-panels J-panel">

                <!-- 购物车 -->
                <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-cart toolbar-animate-out">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="" class="title"><i></i><em class="title">购物车</em></a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('cart');" ></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div id="J-cart-tips" class="tbar-tipbox hide">
                                <div class="tip-inner">
                                    <span class="tip-text">还没有登录，登录后商品将被保存</span>
                                    <a href="#none" class="tip-btn J-login">登录</a>
                                </div>
                            </div>
                            <div id="J-cart-render">
                                <!-- 列表 -->
                                <div id="cart-list" class="tbar-cart-list">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 小计 -->
                    <div id="cart-footer" class="tbar-panel-footer J-panel-footer">
                        <div class="tbar-checkout">
                            <div class="jtc-number"> <strong class="J-count" id="cart-number">0</strong>件商品 </div>
                            <div class="jtc-sum"> 共计：<strong class="J-total" id="cart-sum">¥0</strong> </div>
                            <a class="jtc-btn J-btn" href="#none" target="_blank">去购物车结算</a>
                        </div>
                    </div>
                </div>

                <!-- 我的关注 -->
                <div style="visibility: hidden;" data-name="follow" class="J-content toolbar-panel tbar-panel-follow">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="#" target="_blank" class="title"> <i></i> <em class="title">我的关注</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('follow');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="tbar-tipbox2">
                                <div class="tip-inner"> <i class="i-loading"></i> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tbar-panel-footer J-panel-footer"></div>
                </div>

                <!-- 我的足迹 -->
                <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-history toolbar-animate-in">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="#" target="_blank" class="title"> <i></i> <em class="title">我的足迹</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('history');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="jt-history-wrap">
                                <ul>
                                    <!--<li class="jth-item">
                                        <a href="#" class="img-wrap"> <img src="/static/home//static/home/./static/home/portal/img/like_03.png" height="100" width="100" /> </a>
                                        <a class="add-cart-button" href="#" target="_blank">加入购物车</a>
                                        <a href="#" target="_blank" class="price">￥498.00</a>
                                    </li>
                                    <li class="jth-item">
                                        <a href="#" class="img-wrap"> <img src="/static/home//static/home//static/home/portal/img/like_02.png" height="100" width="100" /></a>
                                        <a class="add-cart-button" href="#" target="_blank">加入购物车</a>
                                        <a href="#" target="_blank" class="price">￥498.00</a>
                                    </li>-->
                                </ul>
                                <a href="#" class="history-bottom-more" target="_blank">查看更多足迹商品 &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                    <div class="tbar-panel-footer J-panel-footer"></div>
                </div>

            </div>

            <div class="toolbar-header"></div>

            <!-- 侧栏按钮 -->
            <div class="toolbar-tabs J-tab">
                <div onclick="cartPanelView.tabItemClick('cart')" class="toolbar-tab tbar-tab-cart" data="购物车" tag="cart" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count " id="tab-sub-cart-count">0</span>
                </div>
                <div onclick="cartPanelView.tabItemClick('follow')" class="toolbar-tab tbar-tab-follow" data="我的关注" tag="follow" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count hide">0</span>
                </div>
                <div onclick="cartPanelView.tabItemClick('history')" class="toolbar-tab tbar-tab-history" data="我的足迹" tag="history" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count hide">0</span>
                </div>
            </div>

            <div class="toolbar-footer">
                <div class="toolbar-tab tbar-tab-top" > <a href="#"> <i class="tab-ico  "></i> <em class="footer-tab-text">顶部</em> </a> </div>
                <div class="toolbar-tab tbar-tab-feedback" > <a href="#" target="_blank"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
            </div>

            <div class="toolbar-mini"></div>

        </div>

        <div id="J-toolbar-load-hook"></div>

    </div>
</div>
<!--购物车单元格 模板-->
<script type="text/template" id="tbar-cart-item-template">
    <div class="tbar-cart-item" >
        <div class="jtc-item-promo">
            <em class="promo-tag promo-mz">满赠<i class="arrow"></i></em>
            <div class="promo-text">已购满600元，您可领赠品</div>
        </div>
        <div class="jtc-item-goods">
            <span class="p-img"><a href="#" target="_blank"><img src="{2}" alt="{1}" height="50" width="50" /></a></span>
            <div class="p-name">
                <a href="#">{1}</a>
            </div>
            <div class="p-price"><strong>¥{3}</strong>×{4} </div>
            <a href="#none" class="p-del J-del">删除</a>
        </div>
    </div>
</script>
<!--侧栏面板结束-->

</body>

</html>