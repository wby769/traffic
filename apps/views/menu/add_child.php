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
		.question_fix{
			background-color:#fbfbfb;
			position:fixed;
			width:100%;
			height: 45px;
			top:0px;
			left:0;
			z-index:2
		}
		</style>
	</head>

	<body>
		<div style="margin: 15px;">
			<span class="layui-breadcrumb">
			  <a href="javascript:;">首页</a>
			  <a href="<?=base_url("menu/list_menu_child")?>">子菜单管理</a>
			  <a><cite><?=isset($title)?$title:'添加' ?></cite></a>
			</span>
			<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
			</fieldset>

			<form class="layui-form">
				<input type="hidden" name="id" value="<?=isset($id)?$id:null ?>">
				<div class="layui-form-item" style="padding-top:2px">
					<div class="layui-form-inline" pane="">
					    <label class="layui-form-label">父菜单</label>
					    <div class="layui-input-inline">
					    	<select name="fid" lay-filter="fid" lay-verify="required">
					    		<option value="">请选择</option>
							    <?php foreach($opt as $li): ?>
					    		<option svalue="<?=$li->subject ?>" value="<?=$li->id ?>"><?=$li->name ?></option>
							    <?php endforeach; ?>
					    	</select>
					    </div>
					</div>

					<div class="layui-form-inline">
						<label class="layui-form-label">名称</label>
						<div class="layui-input-inline">
							<input type="text" name="name" value="<?=isset($name)?$name:null ?>" ovalue="<?=isset($name)?$name:null ?>" lay-verify="required" autocomplete="off" class="layui-input">
						</div>
					</div>
					<?php if(isset($id)): ?>
						<div class="layui-form-inline">
						  <label class="layui-form-label">状态</label>
						  <div class="layui-input-inline">
						    <input type="checkbox" name="status" <?php if($status) echo 'checked' ?> lay-skin="switch" lay-text="有效|无效">
						  </div>
						</div>
						<div class="layui-form-inline">
							<div class="layui-input-inline">
								<button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
								<button type="button" onclick="javascript:history.go(-1)" class="layui-btn layui-btn-primary">返回</button>
							</div>
						</div>
					<?php else: ?>
						<div class="layui-form-inline">
							<div class="layui-input-inline">
								<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
								<button type="reset" class="layui-btn layui-btn-primary">重置</button>
							</div>
						</div>
					<?php endif; ?>
					<div class="layui-btn layui-btn-primary" onclick="window.scrollTo(0,0)" style="float:right;display:none;margin-right:5px" id="top"><i class="layui-icon">&#xe619;</i></div>
				</div>
				
				<div style="width:60%; margin-left:55px; display:none" id="content">
					<table class="layui-table">
						<thead>
							<tr>
							<th width="5%">选择</th>
								<th>序号</th>
								<th>题目编号</th>
								<th>题目简述</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
		<script>
			var eleHeight = $('.layui-form-item').outerHeight();
			$(window).scroll(function(){
				if($(window).scrollTop()>eleHeight){
					$('.layui-form-item').addClass('question_fix');
					$('#top').slideDown();
				}
				else{
					$('.layui-form-item').removeClass('question_fix');
					$('#top').hide();
				}
			});
			layui.use(['form','element'], function() {
				var form = layui.form(),
					layer = layui.layer;

				form.on('select(fid)',function(data){
					getQuestion($(data.elem).children(':selected').attr('svalue'));
					form.render('checkbox');
				});

				var select_state = true;
				$(window).keyup(function(event){
				    if(event.which == 13){
				    	if($('tbody .layui-form-checked').length>=2){
				    		var start = $('tbody .layui-form-checked:first').parent().next().text();
				    		var end = $('tbody .layui-form-checked:last').parent().next().text();
				    		if(select_state){
					    		$('tbody tr').slice(start,end-1).each(function(){
					    			$(this).find('i').click();
					    		});
				    			select_state = false;
				    		}
				    		else{
				    			$('tbody tr').slice(start-1,end).each(function(){
					    			$(this).find('i').click();
					    		});
				    			select_state = true;
				    		}
							form.render('checkbox');
				    	}
				    }
				});

				form.on('submit(demo1)', function(data){
					var name = $(':text[name=name]').val();
					if($(':text[name=name]').attr('ovalue')!= name){
						$.ajaxSetup({async:false});
						var result = true;
						$.post(
							'<?=base_url("menu/exists")?>',
							{
								type:'c',
								name: $(':text[name=name]').val(),
								fid : $('select[name=fid]').val()
							},
							function(data){
								if(!data){
									result = false;
									layer.msg('该菜单已经存在',function(){});
								}
						});
						if(!result) return false;
					}
					$.post('<?=base_url("menu/save_menu_child")?>',
						$('form').serialize(),
						function(data){
							if(data=='success'){
								layer.msg('保存成功',{icon:1});
								setTimeout(function(){
									location.href='<?=base_url("menu/list_menu_child")?>';
								},1000);
							}
							else
								layer.msg('保存失败',{icon:5});
						});
					return false;
				});
				
			});
			function getQuestion(subject){
				$.ajaxSetup({async:false});
				$.post('<?=base_url("question/question_opt")?>',
					{subject:subject},
					function(data){
						var content = '';
						for(var i=0; i<data.length; i++){
							content += 
								'<tr>'+
									'<td><input type="checkbox"  name="qid[]" value="'+data[i].id+'" lay-skin="primary"></td>'+
									'<td>'+(i+1)+'</td>'+
									'<td>'+data[i].id+'</td>'+
									'<td>'+data[i].question_content+'</td>'+
								'</tr>';
						}
						$('#content').show().find('tbody').html(content);
					},'json');
			}
		</script>
		<?php if(isset($id)): ?>
			<script type="text/javascript">
				$(function(){
					$('select[name=fid]').val(['<?=$fid ?>']);
					var subject = $('select[name=fid]').children('option[value=<?=$fid ?>]').attr('svalue');
					getQuestion(subject);
					$(':checkbox[name^=qid]').val('<?=$ids?>'.split(','));
				});
			</script>
		<?php endif; ?>
	</body>

</html>