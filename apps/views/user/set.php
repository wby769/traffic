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
	</head>

	<body>
		<div style="margin: 15px;">
			<form class="layui-form">
			<input type="hidden" name="id" value="<?=$id ?>">
			<?php if($sign=='t_residue'): ?>
				<div class="layui-form-item">
				    <label class="layui-form-label">使用次数</label>
				    <div class="layui-input-inline">
				      <input type="text" name="residue_num" value="<?=$residue_num ?>"  autocomplete="off" class="layui-input">
				    </div>
				</div>
			<?php endif; ?>
			<?php if($sign=='t_expire'): ?>
				<div class="layui-form-item">
					<label class="layui-form-label">有效期至</label>
					<div class="layui-inline">
					  <input class="layui-input" name="expire_time" value="<?=date('Y-m-d',$expire_time) ?>" onclick="layui.laydate({elem: this, festival: true})">
					</div>
				</div>
			<?php endif; ?>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="public/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
		<script>
			layui.use(['form', 'laydate'], function(){
				var form = layui.form();
				form.on('submit(demo1)',function(data){
					var index = parent.layer.getFrameIndex(window.name);
					$.post('<?=base_url("user/save_setting")?>',
						$('form').serialize(),
						function(data){
							if(data=='success'){
								layer.msg('保存成功',{icon:1});
								setTimeout(function(){
									parent.layer.close(index);
									parent.location.reload();
								},1000);
							}
							else
								layer.msg('保存失败',{icon:5});
						});
					return false;
				});
			});
		</script>
	</body>

</html>