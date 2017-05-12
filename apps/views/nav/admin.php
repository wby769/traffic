<!DOCTYPE html>
<html>
	<head>
		<base href="<?=base_url()?>" />
		<meta charset="utf-8">
		<title>管理中心</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">

		<link rel="stylesheet" href="public/layui/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" href="public/layui/css/global.css" media="all">
		<link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">

	</head>

	<body>
		<div class="layui-layout layui-layout-admin" style="border-bottom: solid 5px #1aa094;">
			<div class="layui-header header header-demo">
				<div class="layui-main">
					<div class="admin-login-box">
						<a class="logo" style="left: 0;" href="javascript:;">
							<span style="font-size: 22px;">同创交规管理中心</span>
						</a>
						<!-- <div class="admin-side-toggle">
							<i class="fa fa-bars" aria-hidden="true"></i>
						</div>
						<div class="admin-side-full">
							<i class="fa fa-life-bouy" aria-hidden="true"></i>
						</div> -->
					</div>
					<ul class="layui-nav admin-header-item">
						<li class="layui-nav-item">
							<a href="javascript:;" class="admin-header-user">
								<span><?=$this->session->uname?></span>
							</a>
							<dl class="layui-nav-child">
								<dd>
									<a href="javascript:logout()"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
								</dd>
							</dl>
						</li>
					</ul>
					<ul class="layui-nav admin-header-item-mobile">
						<li class="layui-nav-item">
							<a href="javascript:logout();"><i class="fa fa-sign-out" aria-hidden="true"></i> 退出</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="layui-side layui-bg-black" id="admin-side">
				<div class="layui-side-scroll" id="admin-navbar-side" lay-filter="side"></div>
			</div>
			<div class="layui-body" style="bottom: 0;border-left: solid 2px #1AA094;" id="admin-body">
				<div class="layui-tab admin-nav-card layui-tab-brief" lay-filter="admin-tab">
					<ul class="layui-tab-title">
						<li class="layui-this">
							<i class="fa fa-dashboard" aria-hidden="true"></i>
							<cite>欢迎</cite>
						</li>
					</ul>
					<div class="layui-tab-content" style="min-height: 150px; padding: 5px 0 0 0;">
						<div class="layui-tab-item layui-show">
						<iframe src="nav/wel"></iframe>
						</div>
					</div>
				</div>
			</div>
			<div class="layui-footer footer footer-demo" id="admin-footer">
				<div class="layui-main">
					<p>技术支持：徐州溜溜云网络科技有限公司
					</p>
				</div>
			</div>
			<div class="site-tree-mobile layui-hide">
				<i class="layui-icon">&#xe602;</i>
			</div>
			<div class="site-mobile-shade"></div>
			
			
			<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
			<script type="text/javascript" src="public/layui/datas/nav.js"></script>
			<script src="public/layui/js/index.js"></script>
			<script>
				layui.use('layer', function() {
					var layer = layui.layer;
				});
				function logout(){
					layer.confirm('确定要退出系统吗?',
					    {btn:['确定','取消']},
					    function(){
							location.href='<?=base_url("nav/logout/1")?>';
					    },
					    function(){
					    });
				}
			</script>
		</div>
	</body>

</html>