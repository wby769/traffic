<!DOCTYPE html>
<html>
<head>
	<base href="<?=base_url()?>" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>同创理论强化服务加盟平台</title>
	<link rel="stylesheet" href="public/css/default.css">
	<link rel="stylesheet" href="public/css/style.css">
	<script src="public/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="public/layer/layer.js" type="text/javascript"></script>
</head>
<body>
	<?php $this->load->view('nav/header') ?>
	<!-- 平台内容 -->
	<div class="treeaceWrap">
		<div class="boxWrap clearfix">
			<!-- 内容左 学员资料 -->
			<div class="treeace-l left">
				<h1 class="tt">学员资料</h1>
				<div class="treeace-l-content">
					<p>姓名：<?=$this->session->user->relname?></p>
					<p>身份证号码：<?=$this->session->user->idcard?></p>
					<p>考试车型：<?=config_item('car_type')[$this->session->user->car]?></p>
					<p>考试科目：<?=config_item('subject')[$this->session->user->subject]?></p>
				</div>
				<div  class="treeace-btm">
					<p>最后一次测试时间：<?=date('Y-m-d H:i:s',$last_time)?></p>
					<div class="treeace-btn">
						<a href="<?=base_url($last_url)?>">继续上次</a>
						<a href="javascript:;" onclick="exam()">仿真考试</a><br/>
						<a href="<?=base_url('exam/wrong')?>">我的错题</a>
						<a href="javascript:;" onclick="clearWrong()">清空错题</a>
					</div>
				</div>
			</div>
			<!-- 内容右 培训项目 -->
			<div class="treeace-r left">
				<h1 class="tt">培训项目</h1>
				<div class="treeace-r-content">
				<?php foreach($list as $key=>$li): ?>
					<div class="item-list">
						<span onclick="show_sub('<?=$li->id ?>')"><?=$li->name ?></span>
						<a href="<?=base_url('exam/test/').$li->id?>">学习测试</a>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</body>
<!-- 考试须知 -->
<div id="tk1" class="none">
	<img src="public/imgs/bg.jpg" alt="">
	<div class="tk1-content">
		<h1>考试须知</h1>
		<p>1、遵守考场纪律，服从监考人员指挥。</p>
		<p>2、进入考场，手机、拷机关机。禁止抽烟，禁止吃零食。</p>
		<p>3、未经工作人员允许，考生禁止随意走动。</p>
		<p>4、考场内禁止大声喧哗，禁止随意走动。</p>
		<p>5、考试中认真答题，不准交头接耳。</p>
		<p>6、考生中不准冒名顶替，不准弄虚作假。</p>
		<p>7、注意考场卫生</p>
		<p>8、爱护公务</p>
		<div class="tk1-content-btm" style="text-align: right;">
			<a href="javascript:;" title="" class="" onclick="step(1)">确定</a>
		</div>
	</div>
</div>
<!-- 考生信息确认 -->
<div id="tk2" class="none">
	<img src="public/imgs/bg.jpg" alt="">
	<div class="tk2-content">
		<table>
			<tbody>
				<tr>
					<td style="width:70%">姓名：<?=$this->session->user->relname?></td>
					<td rowspan="8"></td>
				</tr>
				<tr>
					<td>性别：<?=config_item('sex_type')[$this->session->user->sex]?></td>
				</tr>
				<tr>
					<td>出生日期：</td>
				</tr>
				<tr>
					<td>身份证号：<?=$this->session->user->idcard?></td>
				</tr>
				<tr>
					<td>报考车型：<?=config_item('car_type')[$this->session->user->car]?></td>
				</tr>
				<tr>
					<td>考试原因：</td>
				</tr>
				<tr>
					<td>考试日期：</td>
				</tr>
				<tr>
					<td>考试次数：</td>
				</tr>
			</tbody>
		</table>
		<div class="tk2-content-btm">
			<h2>以上信息确认无误后点击“开始考试”按钮开始考试</h2>
			<a href="<?=base_url('exam/simulate')?>" title="" >开始考试</a>
			<a href="javascript:;" title="" onclick="step(1)">重新登录</a>
		</div>
	</div>
</div>
<!-- 考生信息确认 -->
<div id="tk3" class="none">
	<img src="public/imgs/bg.jpg" alt="">
	<div class="tk3-content clearfix" style="height:360px;">
		<div class="left tk3-content-l" style="margin-right:2%;">
			<p>学员姓名：<?=$this->session->user->relname?></p>
			<p>证件类型：身份证</p>
			<p>证件号码：<input type="text" readonly="" name="idcard"></p>
			<p>驾照类型：<?=config_item('car_type')[$this->session->user->car]?></p>
			<div class="tk2-content-btm">
				<a href="javascript:;" title="" onclick="step(2)">确定</a>
				<a href="javascript:;" title="" onclick="cancle()">取消</a>
			</div>
		</div>
		<div class="left tk3-content-r">
			<ul id="key-type">
				<li>7</li>
				<li>8</li>
				<li>9</li>
				<li>4</li>
				<li>5</li>
				<li>6</li>
				<li>1</li>
				<li>2</li>
				<li>3</li>
				<li>0</li>
				<li>X</li>
				<li></li>
				<li>清除</li>
				<li>退格</li>
			</ul>
		</div>
	</div>
</div>
<script>
	layer.config({
		extend:'lly/style.css',
	});
	function exam(){
		var index = layer.open({
	  		type: 1,
	  		skin: 'lly',
	  		area: ['755px', '452px'],
		  	title:'考试系统',
		 	content: $("#tk1")
		});  
		reStyle(index);
	}
	function reStyle(index){
		layer.style(index,{
			'border':'1px solid #7baaea',
			'background': '#edf4ff',
			'border-radius':'5px',
			'overflow': 'hidden',
			'padding': '6px'
		});
	}
	function step(sign){
		switch(sign){
			case 1:
				layer.closeAll('page');
			  	var index = layer.open({
			  		type: 1,
			  		skin: 'lly',
			  		area: ['755px', '500px'],
				  	title:'考试系统', 
				 	content: $("#tk3")
				});  
				reStyle(index);
				$('#key-type li').unbind('click');
				$('#key-type li').click(function(){
					var idcard = $('#tk3 :text[name=idcard]').val();
					var num = $(this).text();
					if(num=='退格'){
						idcard = idcard.substr(0,idcard.length-1);
					}
					else if(num=='清除'){
						idcard = '';
					}
					else{
						idcard += num;
					}
					$('#tk3 :text[name=idcard]').val(idcard);

				});
				break;
			case 2:
				if(!$('#tk3 :text[name=idcard]').val()){
					$('#tk3 :text[name=idcard]').focus();
				}
				else{
					if($('#tk3 :text[name=idcard]').val()=='<?=$this->session->user->idcard ?>'){
						layer.closeAll('page');
					  	var index = layer.open({
					  		skin: 'lly',
					  		type: 1,
					  		area: ['755px', '480px'],
						  	title:'考试系统', 
						 	content: $("#tk2")
						});  
						reStyle(index);
					}
					else{
						alert('身份证号码与登记记录不符，请重新输入。');
					}
				}
				break;
		}
	}
	function cancle(){
		layer.closeAll('page');
	}
	function logout(){
		layer.confirm('确定要退出系统吗?',
		    {btn:['确定','取消']},
		    function(){
				location.href='<?=base_url("nav/logout")?>';
		    },
		    function(){
		    });
	}
	function show_sub(id){
		window.location.href = '<?=base_url("exam/show_sub/")?>'+id;
	}
	function clearWrong(){
		$.post('<?=base_url("exam/clear_wrong")?>',
			{},
			function(data){
				if(data=='success')
					layer.alert('清空成功',{skin:'lly'});
				else 
					layer.alert('清空失败',{skin:'lly'});
			});
	}
</script>
</html>