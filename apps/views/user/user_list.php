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
			<blockquote class="layui-elem-quote">
				<div class="layui-form-inline">
					<form action="<?=base_url('user/user_list')?>" method="post">
						<input type="hidden" name="now_page" value="<?=$now_page ?>">
						<input type="hidden" name="requery" value="yes">
						<div class="layui-input-inline">
							<input type="text" name="keyword" lay-verify="title" value="<?=isset($keyword)?$keyword:'' ?>" placeholder="用户名/手机号/姓名" autocomplete="off" class="layui-input">
						</div>&nbsp;
						<div class="layui-btn-group" style="float:right">
							<a href="<?=base_url('user/add_user')?>" class="layui-btn"><i class="layui-icon">&#xe608; <span style="font-size:14px">添加</span></i></a>
							<button class="layui-btn">
							<i class="layui-icon">&#xe615; <span style="font-size:14px">查询</span></i>
							</button>
						</div>
					</form>
				</div>
			</blockquote>
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
		<!-- 	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				<legend>用户管理</legend>
			</fieldset> -->
			<div class="layui-form">
				<table class="layui-table">
					<thead>
						<tr>
							<th style="width: 30px;">序号</th>
							<th>用户名</th>
							<th>姓名</th>
							<th>手机号</th>
							<th>身份证</th>
							<th>性别</th>
							<th>科目</th>
							<th>状态</th>
							<th>有效期至</th>
							<th>剩余次数</th>
							<th style="width: 15%">操作</th>
						</tr>
					</thead>
					<tbody id="content">
					<?php foreach($list as $key=>$li): ?>
						<tr id="<?=$li->id ?>">
							<td><?=$key+1 ?></td>
							<td><?=$li->username ?></td>
							<td><?=$li->relname ?></td>
							<td><?=$li->telephone ?></td>
							<td><?=$li->idcard ?></td>
							<td><?=config_item('sex_type')[$li->sex] ?></td>
							<td><?=config_item('subject')[$li->subject] ?></td>
							<td style="color:<?=config_item('user_state_color')[$li->status]?>"><?=config_item('user_state')[$li->status]?></td>
							<td><?=date('Y-m-d',$li->expire_time) ?></td>
							<td><?=$li->residue_num ?></td>
							<td>
								<select>
									<option value="">请选择操作</option>
									<option value="1">编辑</option>
									<?php if($li->status==2): ?>
									<option value="2">停用</option>
									<?php endif; ?>
									<?php if($li->status==4): ?>
									<option value="3">恢复</option>
									<?php endif; ?>
									<?php if($li->status==2): ?>
									<option value="4">设置剩余次数</option>
									<option value="5">设置有效期</option>
									<option value="6">删除</option>
									<?php endif; ?>
								</select>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div id="page1"></div>
		</div>
		<script type="text/javascript" src="public/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
		<script>
			layui.use(['form','layer','laypage'], function(){
				var form = layui.form();
				form.on('select',function(data){
					$(data.elem).val('');
					form.render('select');
					doWork($(data.elem).parent().parent().attr('id'), data.value);
				});

				var laypage = layui.laypage;
				laypage({
					cont:'page1',
					pages:'<?=$totalPage ?>',
					groups:6,
					curr:'<?=$now_page ?>',
					skip:true,
					jump:function(obj,first){
						if(!first){
							$(':hidden[name=now_page]').val(obj.curr);
							$(':hidden[name=requery]').val('no');
							$('form').submit();
						}
					}
				})
			});
			var layer = layui.layer;
			function doDeal(type,id){
				layer.open({
				  type: 2,
				  title: '审核',
				  area: ['500px', '350px'],
				  shadeClose: true, //点击遮罩关闭
				  content: '<?=base_url("user/setting/")?>'+type+'?id='+id
				  });
			}

			function doWork(id,value){
				switch(value){
					case '1':
						location.href = '<?=base_url("user/add_user/")?>'+id;
						break;
					case '2':
						layer.confirm('确定要停用该用户吗?',
						    {btn:['确定','取消']},
						    function(){
						    	setting_helper(id,4);
						    },
						    function(){
						    });
						break;
					case '3':
						layer.confirm('确定要恢复该用户吗?',
						    {btn:['确定','取消']},
						    function(){
						    	setting_helper(id,2);
						    },
						    function(){
						    });
						break;
					case '4':
						doDeal('t_residue',id);
						break;
					case '5':
						doDeal('t_expire',id);
						break;
					case '6':
						$.post('<?=base_url("user/delete_user")?>',
							{
								id:id
							},
							function(data){
								if(data=='success'){
									layer.msg('删除成功',{icon:1});
									setTimeout(function(){
										location.reload();
									},1000);
								}
								else
									layer.msg('删除失败',{icon:5});
							});
						break;
				}
			}
			function setting_helper(id, state){
				$.post('<?=base_url("user/save_setting")?>',
					{
						id:id,
						status:state
					},
					function(data){
						if(data=='success'){
							layer.msg('保存成功',{icon:1});
							setTimeout(function(){
								location.reload();
							},1000);
						}
						else
							layer.msg('保存失败',{icon:5});
					});
			}
		</script>
	</body>

</html>