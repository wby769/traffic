<!DOCTYPE html>
<html>
	<head>
		<base href="<?=base_url()?>" />
		<meta charset="utf-8">
		<title></title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">

		<link rel="stylesheet" href="public/layui/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" type="text/css" href="http://www.jq22.com/jquery/font-awesome.4.6.0.css">
		<script type="text/javascript" src="public/js/jquery-1.9.1.min.js"></script>
		<style type="text/css">
			form span.tip{
				color: red;
				margin-right: 3px;
			}
		</style>
	</head>

	<body>
		<div style="margin: 15px;">
			<span class="layui-breadcrumb">
			  <a href="javascript:;">首页</a>
			  <a href="<?=base_url("menu/list_menu")?>">父菜单管理</a>
			  <a><cite><?=isset($title)?$title:'添加' ?></cite></a>
			</span>
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
			</fieldset>

			<form class="layui-form" action="">
				<input type="hidden" name="id" value="<?=isset($id)?$id:null ?>">
				<div class="layui-form-item" pane="">
				    <label class="layui-form-label">科目</label>
				    <div class="layui-input-inline">
				    	<select name="subject" lay-verify="required">
				    		<option value="">请选择</option>
				    		<option value="1">科目一</option>
				    		<option value="2">科目四</option>
				    	</select>
				    </div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">名称</label>
					<div class="layui-input-inline">
						<input type="text" name="name" ovalue="<?=isset($name)?$name:null ?>" value="<?=isset($name)?$name:null ?>" lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item" pane="">
				    <label class="layui-form-label">车型</label>
				    <div class="layui-input-block">
				      <input type="checkbox" name="car_type[]" value="1" lay-skin="primary" title="C1C2C3C4">
				      <input type="checkbox" name="car_type[]" value="2" lay-skin="primary" title="A1A3B1">
				      <input type="checkbox" name="car_type[]" value="3" lay-skin="primary" title="A2B2">
				      <input type="checkbox" name="car_type[]" value="4" lay-skin="primary" title="DEF">
				    </div>
				</div>
				<?php if(isset($id)): ?>
					<div class="layui-form-item">
						<label class="layui-form-label">状态</label>
						<div class="layui-input-block">
							<input type="radio" name="status" value="1" title="有效">
							<input type="radio" name="status" value="0" title="无效">
						</div>
					</div>
					<script type="text/javascript">
						$(function(){
							$('select[name=subject]').val(['<?=$subject ?>']);
							$(':checkbox[name^=car_type]').val('<?=$car_type ?>'.split(','));
							$(':radio[name=status]').val(['<?=$status ?>']);
						});
					</script>

					<div class="layui-form-item">
						<div class="layui-input-block">
							<button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
							<button type="button" onclick="javascript:history.go(-1)" class="layui-btn layui-btn-primary">返回</button>
						</div>
					</div>
				<?php else: ?>
					<div class="layui-form-item">
						<div class="layui-input-block">
							<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
							<button type="reset" class="layui-btn layui-btn-primary">重置</button>
						</div>
					</div>
				<?php endif; ?>
			</form>
		</div>
		<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
		<script>
			layui.use(['form','element'], function(){
				var form = layui.form(),
					layer = layui.layer;

				form.on('submit(demo1)', function(data){
					if(!$(':checkbox[name^=car_type]:checked').val()){
						layer.msg('请选择车型',function(){});
						return false;
					}
					var name = $(':text[name=name]').val();
					if($(':text[name=name]').attr('ovalue') != name){
						$.post(
							'<?=base_url("menu/exists")?>',
							{
								type:'f',
								name: name
							},
							function(data){
								if(data==1){
									doDeal();
								}
								else layer.msg('该菜单已经存在',function(){});
							});
					}
					else doDeal();
					return false;
				});

				function doDeal(){
					$.post('<?=base_url("menu/save_menu")?>',
						$('form').serialize(),
						function(data){
							if(data=='success'){
								layer.msg('保存成功',{icon:1});
								setTimeout(function(){
									location.href='<?=base_url("menu/list_menu")?>';
								},1000);
							}
							else
								layer.msg('保存失败',{icon:5});
						});
				}
				
			});
		</script>
	</body>

</html>