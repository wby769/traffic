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

			<form class="layui-form" action="">
				<div class="layui-form-item">
					<label class="layui-form-label">原密码</label>
					<div class="layui-input-inline">
						<input type="password" name="opwd" lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">新密码</label>
					<div class="layui-input-inline">
						<input type="password" name="pwd" lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">确认密码</label>
					<div class="layui-input-inline">
						<input type="password" name="repwd" lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>
				

				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="public/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
		<script>
			layui.use(['form'], function() {
				var form = layui.form(),
					layer = layui.layer;

				form.on('submit(demo1)', function(data){
					var password = $(':password[name=pwd]').val();
					if($(':password[name=repwd]').val()!=password){
						layer.msg('确认密码不一致',function(){});
						return false;
					}
					$.post(
						'<?=base_url("user/check_old_pwd")?>',
						{pwd:$(':password[name=opwd]').val()},
						function(data){
							if(data==1){
								$.post('<?=base_url("user/save_pwd")?>',
									{pwd:password},
									function(data){
										if(data=='success'){
											time = 3;
											info = '修改成功! 系统会在'+time+'秒后自动跳转...'
											var index = layer.msg(info, {icon: 1,time:0});
											s = setInterval(function(){
											    setStatus(index);
											},1000);
										}
										else
											layer.msg('保存失败',{icon:5});
									});
							}
							else layer.msg('原始密码错误',function(){});
						});
					return false;
				});
			});
			function setStatus(index){
			    time--;
			    var info = '修改成功! 系统会在'+time+'秒后自动跳转...';
			    var content = '<i class="layui-layer-ico layui-layer-ico1"></i>'+info;
			    $('#layui-layer'+index).children('div').html(content);
			    if(time<=0){
			        clearInterval(s);
			        parent.location.href = '<?=base_url("nav/logout/1")?>';
			    }
			}
		</script>
	</body>

</html>