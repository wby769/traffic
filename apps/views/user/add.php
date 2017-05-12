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
			  <a href="<?=base_url("user/user_list")?>">用户管理</a>
			  <a><cite><?=isset($title)?$title:'添加' ?></cite></a>
			</span>
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
			</fieldset>
			<form class="layui-form">
				<input type="hidden" name="id" value="<?=isset($id)?$id:null ?>">
				<div class="layui-form-item">
					<label class="layui-form-label"><span class="tip">*</span>用户名</label>
					<div class="layui-input-inline">
						<input type="text" name="username" lay-verify="required" ovalue="<?=isset($username)?$username:'' ?>" value="<?=isset($username)?$username:'' ?>" autocomplete="off" class="layui-input" >
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label"><span class="tip">*</span>密码</label>
					<div class="layui-input-inline">
						<input type="password" name="password" lay-verify="required" ovalue="<?=isset($password)?$password:'' ?>" value="<?=isset($password)?$password:'' ?>" autocomplete="off" class="layui-input" >
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">姓名</label>
					<div class="layui-input-inline">
						<input type="text" name="relname" value="<?=isset($relname)?$relname:'' ?>" autocomplete="off" class="layui-input" >
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">性别</label>
					<div class="layui-input-block">
						<input type="radio" name="sex" value="1" title="男">
						<input type="radio" name="sex" value="2" title="女">
						<input type="radio" name="sex" checked="" value="0" title="保密">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">手机</label>
					<div class="layui-input-inline">
						<input type="text" name="telephone" value="<?=isset($telephone)?$telephone:'' ?>" lay-verify="reg_tel" autocomplete="off" class="layui-input" >
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label"><span class="tip">*</span>身份证</label>
					<div class="layui-input-inline">
						<input type="text" name="idcard" value="<?=isset($idcard)?$idcard:'' ?>" lay-verify="required" autocomplete="off" class="layui-input" >
					</div>
				</div>
				<div class="layui-form-item" pane="">
				    <label class="layui-form-label"><span class="tip">*</span>科目</label>
				    <div class="layui-input-inline">
				    	<select name="subject" lay-verify="required">
				    		<option value="">请选择</option>
				    		<option value="1">科目一</option>
				    		<option value="2">科目四</option>
				    	</select>
				    </div>
				</div>
				<div class="layui-form-item" pane="">
				    <label class="layui-form-label"><span class="tip">*</span>车型</label>
				    <div class="layui-input-inline">
				    	<select name="car" lay-verify="required">
				    		<option value="">请选择</option>
					    	<?php foreach(config_item('car_type') as $key=>$li): ?>
					    		<option value="<?=$key ?>"><?=$li?></option>
					    	<?php endforeach; ?>
				    	</select>
				    </div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label"><span class="tip">*</span>使用次数</label>
					<div class="layui-input-inline">
						<input type="text" name="residue_num" value="<?=isset($residue_num)?$residue_num:'' ?>" lay-verify="number" autocomplete="off" class="layui-input" >
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label"><span class="tip">*</span>有效期至</label>
					<div class="layui-input-inline">
					<input class="layui-input" name="expire_time" value="<?=isset($expire_time)?date('Y-m-d',$expire_time):'' ?>" lay-verify="required" onclick="layui.laydate({elem: this, festival: true})">
					</div>
				</div>
				<?php if(isset($id)): ?>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
						<a href="<?=base_url('user/user_list')?>" class="layui-btn layui-btn-primary"><span style="font-size:14px">返回</span></a>
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
			layui.use(['form','element','laydate'], function(){
				var form = layui.form(),
					layer = layui.layer;

				form.on('submit(demo1)', function(data){
					var uname = $(':text[name=username]').val();
					if($(':text[name=username]').attr('ovalue') != uname){
						$.post(
							'<?=base_url("user/exists")?>',
							{
								username: uname
							},
							function(data){
								if(data==1){
									doDeal();
								}
								else layer.msg('该用户已经存在',function(){});
							});
					}
					else doDeal();
					return false;
				});
				form.verify({
					reg_tel:function(value){
						if(value){
							if(!/^1[34578]{1}[0-9]{9}$/.test(value)){
								return '请输入正确的手机号';
							}
						}
					},
					reg_card:function(value){
						if(value){
							if(!/^\d{14}\d{3}?\w$/.test(value)){
								return '请输入正确的身份证';
							}
						}
					}

				});

				function doDeal(){
					var param = $('form').serialize();
					if($(':password').val()!=$(':password').attr('ovalue')){
						param+='&repwd=y';
					} 
					$.post('<?=base_url("user/save_user")?>',
						param,
						function(data){
							if(data=='success'){
								layer.msg('保存成功',{icon:1});
								setTimeout(function(){
									location.href='<?=base_url("user/user_list")?>';
								},1000);
							}
							else
								layer.msg('保存失败',{icon:5});
						});
				}
				
			});
		</script>
		<?php if(isset($id)): ?>
			<script>
				$('select[name=subject]').val('<?=$subject ?>');
				$('select[name=car]').val('<?=$car ?>');
				$(':radio[name=sex]').val(['<?=$sex ?>']);
			</script>
		<?php endif; ?>
	</body>

</html>