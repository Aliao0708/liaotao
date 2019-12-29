<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:76:"E:\phpStudy\WWW\pyg\public/../application/admin\view\goods\product-list.html";i:1563517535;s:61:"E:\phpStudy\WWW\pyg\application\admin\view\layout\layout.html";i:1557468795;s:60:"E:\phpStudy\WWW\pyg\application\admin\view\layout\_meta.html";i:1557469360;s:62:"E:\phpStudy\WWW\pyg\application\admin\view\layout\_footer.html";i:1557469363;s:62:"E:\phpStudy\WWW\pyg\application\admin\view\layout\_header.html";i:1559719521;s:60:"E:\phpStudy\WWW\pyg\application\admin\view\layout\_menu.html";i:1559037674;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="favicon.ico" >
<LINK rel="Shortcut Icon" href="favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/static/admin/lib/html5.js"></script>
<script type="text/javascript" src="/static/admin/lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />

<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script><![endif]-->
<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
 
<script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui.admin/js/H-ui.admin.page.js"></script>

<body>
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/">品优购后台管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/">品优购后台管理系统</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs">v3.0</span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
			<nav id="top_nav" class="nav navbar-nav">
				<ul class="cl">
					<?php foreach($nav as $k => $nav_one): ?>
					<li style="<?php if(($nav_one['id'] == $current_auth_ids[1])): ?>background-color:gray;<?php endif; ?>"> <a href="javascript:;" data-id="<?php echo $nav_one['id']; ?>"><?php echo $nav_one['auth_name']; ?></a> </li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
					<li>超级管理员</li>
					<li class="dropDown dropDown_hover"> <a href="javascript:;" class="dropDown_A"><?php echo \think\Session::get('manager_info.username'); ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;">个人信息</a></li>
							<li><a href="<?php echo url('login/logout'); ?>">切换账户</a></li>
							<li><a href="<?php echo url('login/logout'); ?>">退出</a></li>
						</ul>
					</li>
					<li id="Hui-msg"> <a href="javascript:;" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li>
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="橙色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<aside class="Hui-aside">
	<div class="menu_dropdown bk_2">
		<?php foreach($nav as $k => $nav_one): foreach($nav_one['son'] as $kk=>$nav_two): ?>
		<dl id="menu<?php echo $kk; ?>" class="sec_nav" data-pid="<?php echo $nav_one['id']; ?>" style="<?php if(($nav_one['id'] == $current_auth_ids[1])): ?>display:block;<?php else: ?>display:none;<?php endif; ?>">
			<dt class="<?php if(($nav_two['id'] == $current_auth_ids[2])): ?>selected<?php endif; ?>"><i class="Hui-iconfont">&#xe616;</i> <?php echo $nav_two['auth_name']; ?><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd style="<?php if(($nav_two['id'] == $current_auth_ids[2])): ?>display:block;<?php endif; ?>">
				<ul>
					<?php foreach($nav_two['son'] as $nav_three): ?>
					<li data-id="<?php echo $nav_three['id']; ?>" class="<?php if(($nav_three['id'] == $current_auth_ids[3])): ?>current<?php endif; ?>">
						<a href="<?php if(($nav_three['auth_c']&&$nav_three['auth_a'])): ?><?php echo url($nav_three['auth_c'] . '/' . $nav_three['auth_a']); else: ?>javascript:;<?php endif; ?>" title=""><?php echo $nav_three['auth_name']; ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</dd>
		</dl>
		<?php endforeach; endforeach; ?>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<script>
	$(function(){
		$('#top_nav').on('click', 'a', function(){
			var data_id = $(this).attr('data-id');
			$(this).parent().css('backgroundColor', 'gray');
			$(this).parent().siblings().css('backgroundColor', '');
			$('.sec_nav').filter('[data-pid=' + data_id + ']').show();
			$('.sec_nav').not('[data-pid=' + data_id + ']').hide();
		});
	});
</script>

﻿
<title>商品管理</title>
<style type="text/css">
    .pagination li{list-style:none;float:left;margin-left:10px;
        padding:0 10px;
        background-color:#5a98de;
        border:1px solid #ccc;
        height:26px;
        line-height:26px;
        cursor:pointer;color:#fff;
    }
    .pagination li a{color:white;padding: 0;line-height: inherit;border: none;}
    .pagination li a:hover{background-color: #5a98de;}
    .pagination li.active{background-color:white;color:gray;}
    .pagination li.disabled{background-color:white;color:gray;}
</style>
<section class="Hui-article-box">
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 产品管理 <span class="c-gray en">&gt;</span> 商品管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

	<div class="Hui-article">
		<article class="cl pd-20">
			<div class="text-c">
				<form class="Huiform" method="get" action="<?php echo url('index'); ?>" target="_self">
					<input type="text" placeholder="商品名称" name="keyword" value="<?php echo \think\Request::instance()->param('keyword'); ?>" class="input-text" style="width:120px">
					<button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜商品</button>
				</form>
			</div>
			<div class="cl pd-5 bg-1 bk-gray mt-20">
				<span class="l">
					<a href="javascript:;" data-url="<?php echo url('delete'); ?>" id="patch_delete" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
					<a href="javascript:;" data-url="<?php echo url('create'); ?>" data-type="full" id="add" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加商品</a>
				</span>
				<span class="r">共有数据：<strong><?php echo $list->total(); ?></strong> 条</span> </div>
			<div class="mt-10">
				<table class="table table-border table-bordered table-bg table-sort">
					<thead>
					<tr class="text-c">
						<th width="3%"><input type="checkbox" name="" value=""></th>
						<th width="5%">ID</th>
						<th width="15%">商品名称</th>
						<th width="8%">商品单价</th>
						<th width="7%">库存</th>
						<th width="8%">LOGO</th>
						<th width="8%">分类</th>
						<th width="7%">销量</th>
						<th width="5%">上/下架</th>
						<th width="5%">包邮</th>
						<th width="5%">推荐</th>
						<th width="5%">新品</th>
						<th width="5%">热卖</th>
						<th width="8%">排序</th>
						<th width="">操作</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($list as $v): ?>
					<tr class="text-c">
						<td><input name="" type="checkbox" value=""></td>
						<td><?php echo $v['id']; ?></td>
						<td><?php echo $v['goods_name']; ?></td>
						<td class="text-l"><?php echo $v['goods_price']; ?></td>
						<td class="text-l"><?php echo $v['goods_number']; ?></td>
						<td><img src="<?php echo $v['goods_logo']; ?>"></td>
						<td class="text-l"><?php echo $v['category']['pid_path_name']; ?></td>
						<td class="text-l"><?php echo $v['sales_num']; ?></td>
						<td class="text-l"><?php echo $v['is_on_sale']; ?></td>
						<td class="text-l"><?php echo $v['is_free_shipping']; ?></td>
						<td class="text-l"><?php echo $v['is_recommend']; ?></td>
						<td class="text-l"><?php echo $v['is_new']; ?></td>
						<td class="text-l"><?php echo $v['is_hot']; ?></td>
						<td class="text-l"><?php echo $v['sort']; ?></td>
						<td class="f-14 product-brand-manage">
							<a title="编辑" data-url="<?php echo url('edit', ['id'=>$v['id']]); ?>" href="javascript:;" class="ml-5 edit" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
							<a title="删除" data-url="<?php echo url('delete', ['id'=>$v['id']]); ?>" href="javascript:;" class="ml-5 delete" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
						</td>
					</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div><?php echo $list->render(); ?></div>
		</article>
	</div>
</section>

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="/static/admin/logic/curd.js"></script>
<script type="text/javascript">
	// $('.table-sort').dataTable({
	// 	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	// 	"bStateSave": true,//状态保存
	// 	"aoColumnDefs": [
	// 		//{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	// 		{"orderable":false,"aTargets":[0,6]}// 制定列不参与排序
	// 	]
	// });
</script>
<!--/请在上方写此页面业务相关的脚本-->


</body>
</html>