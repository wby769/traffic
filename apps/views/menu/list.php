<!DOCTYPE html>
<html>
	<head>
		<base href="<?=base_url()?>" />
		<meta charset="utf-8">
		<title>录入题目</title>
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
				<a href="<?=base_url("menu/add_menu")?>" class="layui-btn layui-btn-small" id="add">
					<i class="layui-icon">&#xe608;</i> 添加
				</a>
			</blockquote>
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				<!-- <legend>菜单管理</legend> -->
			</fieldset>
			<form action="<?=base_url('menu/list_menu')?>" method="post">
				<input type="hidden" name="now_page" value="<?=$now_page ?>">
				<input type="hidden" name="requery" value="yes">
			</form>
			<div class="layui-form">
				<table class="layui-table">
					<thead>
						<tr>
							<th style="width: 30px;">序号</th>
							<th>科目</th>
							<th style="width: 35%">菜单名称</th>
							<th>创建时间</th>
							<th>状态</th>
							<th style="width: 10%">操作</th>
						</tr>
					</thead>
					<tbody id="content">
						<?php foreach($list as $key=>$li): ?>
							<tr id="<?=$li->id ?>">
								<td><?=$key+1 ?></td>
								<td><?=config_item('subject')[$li->subject] ?></td>
								<td><?=$li->name ?></td>
								<td><?=date('Y-m-d H:i:s',$li->create_time) ?></td>
								<td <?php if(!$li->status) echo 'style="color:red"'?>><?=$li->status==1?'有效':'无效' ?></td>
								<td>
									<select>
										<option value="">请选择操作</option>
										<option value="1">编辑</option>
										<?php if($li->status): ?>
										<option value="2">删除</option>
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
				var laypage = layui.laypage;
				var form = layui.form();
			
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

				form.on('select',function(data){
					$(data.elem).val('');
					form.render('select');
					doWork($(data.elem).parent().parent().attr('id'), data.value);
				});
			});

			function doWork(id,value){
				switch(value){
					case '1':
						location.href = '<?=base_url("menu/add_menu/")?>'+id;
						break;
					case '2':
						layer.confirm('确定要删除该菜单吗?',
						    {btn:['确定','取消']},
						    function(){
								$.post('<?=base_url("menu/delete_menu/")?>'+id,{},
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
						    },
						    function(){
						    });
						break;
				}
			}
			
		</script>
	</body>

</html>