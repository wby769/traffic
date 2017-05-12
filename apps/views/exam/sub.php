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
				<div class="treeace-l-content" style="border-bottom:0;">
					<p>姓名：<?=$this->session->user->relname?></p>
					<p>身份证号码：<?=$this->session->user->idcard?></p>
					<p>考试车型：<?=config_item('car_type')[$this->session->user->car]?></p>
					<p>考试科目：<?=config_item('subject')[$this->session->user->subject]?></p>
				</div>
			</div>
			<!-- 内容右 培训项目 -->
			<div class="treeace-r left">
				<h1 class="tt">培训项目</h1>
				<div class="treeace-r-content treeace-r-content1">
					<?php if(empty($list)): ?>
						<p style="font-size:14px">无数据...</p>
					<?php endif; ?>
					<?php foreach($list as $key=>$li): ?>
						<p>
							<label><input type="checkbox" value="<?=$li->id ?>" name="ids[]"><?=$li->name ?></label>
						</p>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- 按钮 -->
			<div class="back-box back-box1">
				<?php if(!empty($list)): ?>
				<a href="javascript:;" title="" onclick="doDeal(1)" class="back-btn">顺序练习</a>
				<a href="javascript:;" title="" onclick="doDeal(2)" class="back-btn g-color">随机练习</a>
				<?php endif; ?>
				<a href="javascript:history.go(-1)" title="" class="back-btn">返 回</a>
			</div>
			<script type="text/javascript">
				layer.config({
					extend:'lly/style.css',
				});
				function doDeal(num){
					if($(':checkbox:checked').length==0){
						layer.alert('请选择需要练习的项目',{skin:'lly'});
						return false;
					}
					var ids = new Array();
					$(':checkbox:checked').each(function(){
						ids.push($(this).val());
					});
					ids = ids.join('-');
					switch(num){
						case 1:
							location.href = '<?=base_url("exam/sub_test/$fid/")?>'+ids;
							break;
						case 2:
							location.href = '<?=base_url("exam/sub_test/$fid/")?>'+ids+'/1';
							break;
					}
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
			</script>
		</div>
	</div>
</body>
</html>