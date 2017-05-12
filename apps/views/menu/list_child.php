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
				<div class="layui-form-inline">
					<form class="layui-form" action="<?=base_url('menu/list_menu_child')?>" method="post">
						<input type="hidden" name="now_page" value="<?=$now_page ?>">
						<input type="hidden" name="requery" value="yes">
						<div class="layui-input-inline">
							<input type="text" name="name" lay-verify="title" value="<?=isset($name)?$name:'' ?>" placeholder="子菜单名称" autocomplete="off" class="layui-input">
						</div>&nbsp;
						<div class="layui-input-inline">
							<select name="qsubject">
								<option value="0">全部</option>
								<option value="1">科目一</option>
								<option value="2">科目四</option>
							</select>
						</div>
						<div class="layui-btn-group" style="float:right">
							<a href="<?=base_url('menu/add_menu_child')?>" class="layui-btn"><i class="layui-icon">&#xe608; <span style="font-size:14px">添加</span></i></a>
							<button class="layui-btn">
							<i class="layui-icon">&#xe615; <span style="font-size:14px">查询</span></i>
							</button>
						</div>
					</form>
				</div>
			</blockquote>
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				<!-- <legend>菜单管理</legend> -->
			</fieldset>
			<div class="layui-form">
				<table class="layui-table">
					<thead>
						<tr>
							<th style="width: 30px;">序号</th>
							<th>科目</th>
							<th style="width: 26%">父菜单名称</th>
							<th style="width: 34%">子菜单菜单名称</th>
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
								<td><?=$li->mname ?></td>
								<td><?=$li->name ?></td>
								<td><?=date('Y-m-d H:i:s',$li->create_time) ?></td>
								<td <?php if(!$li->status) echo 'style="color:red"'?>><?=$li->status==1?'有效':'无效' ?></td>
								<td>
									<select lay-filter="doWork">
									<!-- <select lay-ignore="" onchange="doWork(this);" style="width:100%"> -->
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
		<?php if(isset($qsubject)): ?>
			<script>
				$('select[name=qsubject]').val('<?=$qsubject ?>');
			</script>
		<?php endif; ?>
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

				form.on('select(doWork)',function(data){
					$(data.elem).val('');
					form.render('select');
					doWork($(data.elem).parent().parent().attr('id'), data.value);
				});
			});

			function doWork(id, value){
				switch(value){
					case '1':
						location.href = '<?=base_url("menu/add_menu_child/")?>'+id;
						break;
					case '2':
						layer.confirm('确定要删除该子菜单吗?',
						    {btn:['确定','取消']},
						    function(){
								$.post('<?=base_url("menu/delete_menu_child/")?>'+id,{},
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