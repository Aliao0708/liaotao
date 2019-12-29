<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:70:"E:\pyg\public/../application/admin\view\category\product-category.html";i:1575797755;s:48:"E:\pyg\application\admin\view\layout\layout.html";i:1575797786;s:47:"E:\pyg\application\admin\view\layout\_meta.html";i:1575797788;s:49:"E:\pyg\application\admin\view\layout\_footer.html";i:1575797787;s:49:"E:\pyg\application\admin\view\layout\_header.html";i:1575797754;s:47:"E:\pyg\application\admin\view\layout\_menu.html";i:1575797788;}*/ ?>
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
<title>产品分类</title>
<link rel="stylesheet" href="/static/admin/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">

<section class="Hui-article-box">
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 商品管理 <span class="c-gray en">&gt;</span> 商品分类 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="Hui-article" style="overflow-y: scroll;">
		<article class="cl pd-20">
			<div class="cl pd-5 bg-1 bk-gray mt-20">
				<span class="l">
					<a href="javascript:;" id="tree_open" class="btn btn-danger radius tree_open"><i class="Hui-iconfont ">&#xfe3d;</i> <span>收缩分类</span></a>
					<a href="javascript:;" data-url="<?php echo url('create'); ?>" id="add" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加分类</a>
				</span>
			</div>
			<table class="table table-border table-bordered table-bg">
				<thead>
				<tr>
					<th scope="col" colspan="7">分类</th>
				</tr>
				<tr class="text-c">
					<th width="10%">[+/-]</th>
					<th width="10%">ID</th>
					<th width="20%">分类名称</th>
					<th width="10%">分类图片</th>
					<th width="10%">是否显示</th>
					<th width="10%">是否热门</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($list as $v): ?>
				<tr class="text-c level_<?php echo $v['level']; ?>">
					<td>
						<?php if(($v['level'] < 2)): ?>
						<?php echo str_repeat('&emsp;', $v['level']*2); ?>
						[<a href="javascript:;">-</a>]
						<?php endif; ?>
					</td>
					<td><?php echo $v['id']; ?></td>
					<td class="text-l"><?php echo str_repeat('&emsp;', $v['level']*2); ?><?php echo $v['cate_name']; ?></td>
					<td><img src="<?php echo $v['image_url']; ?>"></td>
					<td><?php echo $v['is_show']; ?></td>
					<td><?php echo $v['is_hot']; ?></td>
					<td>
						<?php if(($v['level'] < 2)): ?>
						<a title="添加下级" data-url="<?php echo url('create', ['pid'=>$v['id']]); ?>?pid=" href="javascript:;" class="ml-5 add_child" style="text-decoration:none"><i class="Hui-iconfont">&#xe600;</i></a>
						<?php endif; ?>
						<a title="编辑" data-url="<?php echo url('edit', ['id'=>$v['id']]); ?>" href="javascript:;" class="ml-5 edit" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
						<a title="删除" data-url="<?php echo url('delete', ['id'=>$v['id']]); ?>" href="javascript:;" class="ml-5 delete" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
					</td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</article>
	</div>
</section>

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="/static/admin/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="/static/admin/logic/curd.js"></script>
<script type="text/javascript">
/*var setting = {
	view: {
		dblClickExpand: false,
		showLine: false,
		selectedMulti: false
	},
	data: {
		simpleData: {
			enable:true,
			idKey: "id",
			pIdKey: "pId",
			rootPId: ""
		}
	},
	callback: {
		beforeClick: function(treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("tree");
			if (treeNode.isParent) {
				zTree.expandNode(treeNode);
				return false;
			} else {
				demoIframe.attr("src",treeNode.file + ".html");
				return true;
			}
		}
	}
};

var zNodes =[
	{ id:1, pId:0, name:"一级分类", open:true},
	{ id:11, pId:1, name:"二级分类"},
	{ id:111, pId:11, name:"三级分类"},
	{ id:112, pId:11, name:"三级分类"},
	{ id:113, pId:11, name:"三级分类"},
	{ id:114, pId:11, name:"三级分类"},
	{ id:115, pId:11, name:"三级分类"},
	{ id:12, pId:1, name:"二级分类 1-2"},
	{ id:121, pId:12, name:"三级分类 1-2-1"},
	{ id:122, pId:12, name:"三级分类 1-2-2"},
];

var code;

function showCode(str) {
	if (!code) code = $("#code");
	code.empty();
	code.append("<li>"+str+"</li>");
}

$(document).ready(function(){
	var t = $("#treeDemo");
	t = $.fn.zTree.init(t, setting, zNodes);
	demoIframe = $("#testIframe");
	demoIframe.bind("load", loadReady);
	var zTree = $.fn.zTree.getZTreeObj("tree");
	zTree.selectNode(zTree.getNodeByParam("id",'11'));
});*/
</script>
<script>

	$(function(){
		$('tr.level_0').find('td:first-child a').click(function(){
			var str = $(this).html() == '-';
			if(str){
				//收起
				$(this).html('+').closest('tr').nextUntil('.level_0').hide();
			}else{
				//展开
				$(this).html('-').closest('tr').nextUntil('.level_0').show();
			}
		});
		$('tr.level_1').find('td:first-child a').click(function(){
			var str = $(this).html() == '-';
			if(str){
				//收起
				$(this).html('+').closest('tr').nextUntil('.level_1,.level_0').hide();
			}else{
				//展开
				$(this).html('-').closest('tr').nextUntil('.level_1,.level_0').show();
			}
		});
		$('#tree_open').click(function(){
			var status = $(this).find('span').text() == '收缩分类';
			if(status){
				$(this).find('i').html('&#xfe3e;');
				$(this).find('span').text('展开分类');
				$('tr.level_0>td:first-child a').html('+');
				$('tbody tr').not('.level_0').hide();
			}else{
				$(this).find('i').html('&#xfe3d;');
				$(this).find('span').text('收缩分类');
				$('.level_0>td:first-child a').html('-');
				$('tbody tr').not('.level_0').show();
			}
		});
	});
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>

</body>
</html>