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
		<style type="text/css">
		form span.tip{
			color: red;
			margin-right: 3px;
		}
		</style>
	</head>

	<body>
		<div style="margin: 15px;">
			<?php if(!empty($qid)): ?>
				<span class="layui-breadcrumb">
				  <a href="javascript:;">首页</a>
				  <a href="<?=base_url("question/list_question")?>">题目列表</a>
				  <a><cite>编辑</cite></a>
				</span>
				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
				</fieldset>
			<?php endif; ?>
			<form class="layui-form" method="post" action="<?=base_url('question/save_question')?>">
				<input type="hidden" name="qid" value="<?=isset($qid)?$qid:null ?>">
				<div class="layui-form-item">
					<label class="layui-form-label"><span class="tip">*</span>编号</label>
					<div class="layui-input-block">
						<input type="text" name="q[id]" autocomplete="off" onblur="sn_check(this)" class="layui-input" value="<?=isset($qid)?$qid:null ?>">
					</div>
				</div>
				<div id="main" style="display:none">
					<div class="layui-form-item" pane="">
					    <label class="layui-form-label"><span class="tip">*</span>科目</label>
					    <div class="layui-input-block">
					      <input type="checkbox" value="1" name="q[question_subject][]" lay-skin="primary" lay-filter="question_subject" title="科目一">
					      <input type="checkbox" value="2" name="q[question_subject][]" lay-skin="primary" lay-filter="question_subject" title="科目四">
					    </div>
					</div>
					<div class="layui-form-item" pane="">
					    <label class="layui-form-label"><span class="tip">*</span>车型</label>
					    <div class="layui-input-block">
					      <input type="checkbox" name="q[question_car][]" value="1" lay-skin="primary" title="C1C2C3C4">
					      <input type="checkbox" name="q[question_car][]" value="2" lay-skin="primary" title="A1A3B1">
					      <input type="checkbox" name="q[question_car][]" value="3" lay-skin="primary" title="A2B2">
					      <input type="checkbox" name="q[question_car][]" value="4" lay-skin="primary" title="DEF">
					    </div>
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label"><span class="tip">*</span>题型</label>
						<div class="layui-input-block">
							<input type="radio" name="q[question_type]" value="2" lay-filter="question_type" title="单选题">
							<input type="radio" name="q[question_type]" value="1" lay-filter="question_type" title="判断题">
							<input type="radio" name="q[question_type]" value="3" lay-filter="question_type" title="多选题">
						</div>
					</div>
					<?php if(!empty($qid)): ?>
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>状态</label>
							<div class="layui-input-block">
								<input type="radio" name="q[status]" value="1" title="有效">
								<input type="radio" name="q[status]" value="0" title="无效">
							</div>
						</div>
					<?php endif; ?>
					<div class="layui-form-item">
						<label class="layui-form-label"><span class="tip">*</span>问题</label>
						<div class="layui-input-block">
							<textarea class="layui-textarea" name="q[question_content]"><?=isset($question_content)?$question_content:null ?></textarea>
						</div>
					</div>
					<div id="part3_common" style="display:none">
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>选项A</label>
							<div class="layui-input-block">
								<input type="text" name="q[opt_a]" autocomplete="off" class="layui-input" value="<?=isset($opt_a)?$opt_a:null ?>">
							</div>
						</div>
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>选项B</label>
							<div class="layui-input-block">
								<input type="text" name="q[opt_b]" autocomplete="off" class="layui-input" value="<?=isset($opt_b)?$opt_b:null ?>">
							</div>
						</div>
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>选项C</label>
							<div class="layui-input-block">
								<input type="text" name="q[opt_c]" autocomplete="off" class="layui-input" value="<?=isset($opt_c)?$opt_c:null ?>">
							</div>
						</div>
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>选项D</label>
							<div class="layui-input-block">
								<input type="text" name="q[opt_d]" autocomplete="off" class="layui-input" value="<?=isset($opt_d)?$opt_d:null ?>">
							</div>
						</div>
					</div>
					<div id="part3" style="display:none">
						<div class="layui-form-item" pane="">
						    <label class="layui-form-label"><span class="tip">*</span>答案</label>
						    <div class="layui-input-block">
								<input type="checkbox" name="question_answer_multi[]" value="A" lay-skin="primary" title="A">
								<input type="checkbox" name="question_answer_multi[]" value="B" lay-skin="primary" title="B">
								<input type="checkbox" name="question_answer_multi[]" value="C" lay-skin="primary" title="C">
								<input type="checkbox" name="question_answer_multi[]" value="D" lay-skin="primary" title="D">
						    </div>
						</div>
					</div>
					<div id="part1" style="display:none">
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>答案</label>
							<div class="layui-input-block">
								<input type="radio" name="question_answer_op" value="A" title="正确">
								<input type="radio" name="question_answer_op" value="B" title="错误">
							</div>
						</div>
					</div>
					<div id="part2" style="display:none">
						<div class="layui-form-item">
							<label class="layui-form-label"><span class="tip">*</span>答案</label>
							<div class="layui-input-block">
								<input type="radio" name="question_answer_single" value="A" title="A">
								<input type="radio" name="question_answer_single" value="B" title="B">
								<input type="radio" name="question_answer_single" value="C" title="C">
								<input type="radio" name="question_answer_single" value="D" title="D">
							</div>
						</div>
					</div>
					
					<div class="layui-form-item">
						<label class="layui-form-label">讲解</label>
						<div class="layui-input-block">
							<textarea class="layui-textarea" name="q[question_explain]"><?=isset($question_explain)?$question_explain:null ?></textarea>
						</div>
					</div>

					<div class="layui-form-item">
						<label class="layui-form-label">题目图片</label>
						<div class="layui-input-block">
							<input type="hidden" name="q[img_url]" value="<?=isset($img_url)?$img_url:null ?>">
							<input type="file" id="file1" lay-type="images" lay-ext="jpg|png|gif" name="file" class="layui-upload-file"> 
						</div>
					</div>
					<div style="margin:10px 110px;" id="qimg" >
					</div>
					<div class="layui-form-item">
						<label class="layui-form-label">语音讲解</label>
						<div class="layui-input-block">
							<input type="hidden" name="q[audio_url]" value="<?=isset($audio_url)?$audio_url:null ?>">
							<input type="file" id="file2" lay-type="audio" lay-ext="wav|mp3" name="file" lay-type="audio" class="layui-upload-file">
							<span id="qaudio"><?=isset($audio_url)?$audio_url.'':null ?>&nbsp;<?php if(!empty($audio_url)): ?><b onclick="doDelete(this,2)" style="color:red">X</b>&nbsp;<em onclick="playAudio(this)">播放</em><audio src="<?=$audio_url ?>" preload="none"></audio><?php endif; ?></span>
						</div>
					</div>

					<?php if(empty($qid)): ?>
						<div class="layui-form-item">
							<div class="layui-input-block">
								<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
								<button type="reset" class="layui-btn layui-btn-primary">重置</button>
							</div>
						</div>
					<?php else: ?>
						<div class="layui-form-item">
							<div class="layui-input-block">
								<button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
								<button type="button" onclick="javascript:history.go(-1)" class="layui-btn layui-btn-primary">返回</button>
							</div>
						</div>
					<?php endif; ?>
					
				</div>
			</form>
		</div>
		<script type="text/javascript" src="public/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="public/layui/plugins/layui/layui.js"></script>
		<?php if(!empty($qid)): ?>
			<script>
				$(function(){
					$('#main').show();
					$(':text[name*=id]').removeAttr('onblur').attr('readonly','');
					$(':checkbox[name*=question_subject]').val('<?=$question_subject ?>'.split(','));
					$(':checkbox[name*=question_car]').val('<?=$question_car ?>'.split(','));
					$(':radio[name*=question_type]').val('<?=$question_type ?>'.split(','));
					$(':radio[name*=status]').val(['<?=$status ?>']);

					toggle_deal('<?=$question_type ?>');
					$('#qimg').append('<img src="<?=$img_url ?>" style="max-width:200px">');
					'<?=$img_url?>'!='' && $('#qimg').append('&nbsp;<span class="tip"><b onclick="doDelete(this,1)">X</b></span>');

					switch('<?=$question_type ?>'){
						case '1':
							$(':radio[name=question_answer_op]').val(['<?=$question_answer ?>']);
							break;
						case '2':
							$(':radio[name=question_answer_single]').val(['<?=$question_answer ?>']);
							break;
						case '3':
							$(':checkbox[name*=question_answer_multi]').val('<?=$question_answer ?>'.split(','));
							break;
					}
				});
			</script>
		<?php endif; ?>
		<script>
			var reg = /^\d{5,9}$/;
			function sn_check(src){
				if($(src).val()==''){
					$(src).focus();
					return false;
				}
				else{
					if(!reg.test($(src).val())){
						layer.msg('不合法的编号',function(){});
						return false;
					}
					$.post(
						'<?=base_url("question/question_exists")?>',
						{qid:$(src).val()},
						function(data){
							if(data=='1'){
								$('#main').show();
							}
							else{
								$('#main').hide();
								layer.msg('该题目编号已经存在',function(){});
							}
						});
				}

			}
			// question_type click
			function toggle_deal(num){
				switch(num){
					case '1':
						$('#part2,#part3,#part3_common').hide();
						$('#part1').slideDown();
						break;
					case '2':
						$('#part1,#part3').hide();
						$('#part3_common,#part2').slideDown();
						break;
					case '3':
						$('#part2,#part1').hide();
						$('#part3_common,#part3').slideDown();
						break;
				}
			}
			// delete img or audio
			function doDelete(src,num){
				if(num==1){
					$('#qimg').html('').prev().find(':input:hidden[name*=img_url]').val('');
				}
				else{
					$(src).parent().html('').siblings(':first').val('');
				}
			}

			function playAudio(src){
				$(src).next()[0].play();
			}
			layui.use(['form', 'upload','element'], function(){
				var form = layui.form(),
					layer = layui.layer;

				layui.upload({
				    url: 'question/upload',
				    elem: '#file1'
				    ,success: function(res){
				    	$('#qimg').html('<img src="'+res.pic+'" style="max-width:200px">&nbsp;<span class="tip"><b onclick="doDelete(this,1)">X</b></span>');
				    	$(':hidden[name*=img_url]').val(res.pic);
				    }
				});

				layui.upload({
				    url: 'question/upload/audio',
				    elem: '#file2'
				    ,success: function(res){
				    	$('#qaudio').html(res.pic+'&nbsp;<b onclick="doDelete(this,2)" style="color:red">X</b>&nbsp;<em onclick="playAudio(this)">播放</em><audio src="'+res.pic+'" preload="none"></audio>');
				    	$(':hidden[name*=audio_url]').val(res.pic);
				    }
				});
				
				form.on('radio(question_type)',function(data){
					toggle_deal(data.value);
				});

				form.on('submit(demo1)', function(data){
					if(!$(':text[name*=id]').val()){
						layer.msg('请输入题目编号',function(){});
						return false;
					}
					else if(!reg.test($(':text[name*=id]').val())){
						layer.msg('不合法的编号',function(){});
						return false;

					}
					if(!$(':checkbox[name*=question_subject]:checked').val()){
						layer.msg('请选择科目',function(){});
						return false;
					}
					if(!$(':checkbox[name*=question_car]:checked').val()){
						layer.msg('请选择车型',function(){});
						return false;
					}
					var q_type = $(':radio[name*=question_type]:checked').val();
					if(!q_type){
						layer.msg('请选择题型',function(){});
						return false;
					}
					if(!$(':input[name*=question_content]').val()){
						layer.msg('请输入问题',function(){});
						return false;
					}
					if(q_type==1){
						if(!$(':radio[name*=question_answer_op]:checked').val()){
							layer.msg('请选择答案',function(){});
							return false;
						}
					}
					else{
						if(!$(':text[name*=opt_a]').val()){
							layer.msg('请输入选项A',function(){});
							return false;
						}
						if(!$(':text[name*=opt_b]').val()){
							layer.msg('请输入选项B',function(){});
							return false;
						}
						if(!$(':text[name*=opt_c]').val()){
							layer.msg('请输入选项C',function(){});
							return false;
						}
						if(!$(':text[name*=opt_d]').val()){
							layer.msg('请输入选项D',function(){});
							return false;
						}
						if(!$(':radio[name*=question_answer_single]:checked').val() && q_type==2){
							layer.msg('请选择答案',function(){});
							return false;
						}
						if(!$(':checkbox[name*=question_answer_multi]:checked').val() && q_type==3){
							layer.msg('请选择答案',function(){});
							return false;
						}
					}
					
					$.post('<?=base_url("question/save_question")?>',
						$('form').serialize(),
						function(data){
							if(data=='success'){
								layer.msg('保存成功',{icon:1});
								setTimeout(function(){
									location.href = '<?=base_url("question/list_question")?>';
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