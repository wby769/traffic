<!DOCTYPE html>
<html>
<head>
	<base href="<?=base_url()?>" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>模拟试卷</title>
	<link rel="stylesheet" href="public/css/default.css">
	<link rel="stylesheet" href="public/css/style.css">
	<script src="public/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="public/layer/layer.js" type="text/javascript"></script>
	<style type="text/css">
		.layui-layer-imgbar{
		    display: none !important;
		  }
	</style>
</head>
<body>
	<div class="paperWrap">
		<div class="paper-l">
			<div class="paper-content-item paper-content-item1">
				<span class="bt">信息提示</span>
				<p>考场号：001</p>
			</div>
			<div class="paper-content-item paper-content-item1">
				<span class="bt" style="left:70px;">考生信息</span>
				<div class="temp-box"></div>
				<p>姓名：<?=$this->session->user->relname?></p>
				<p>科目：<?=config_item('subject')[$this->session->user->subject]?></p>
				<p>车型：<?=config_item('car_type')[$this->session->user->car]?></p>
			</div>
			<div class="paper-content-item paper-content-item1" id="timer">
				<span class="bt">剩余时间</span>
				<p><em><?=$minute?></em>分<em>0</em>秒</p>
			</div>
		</div>

		<div class="paper-content">
		<img src="public/imgs/load.gif" width="20px">&nbsp;loading...
		</div>

		<div class="paper-r">
			<ul class="subjects-list">
				<?php foreach($list as $key=>$li): ?>
					<li id="<?=$li['question_id'] ?>">
						<em><?=$key+1 ?></em><span></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</body>
<div id="info1" class="none">
	<h1>考试确认窗口</h1>
	<p>1.尚有<span></span>道题未做，是否交卷？</p>
	<p>2.点击【确认交卷】，将提交考试成绩，结束考试！</p>
	<p>3.点击【继续交卷】，将关闭提示窗口，继续考试！</p>
	<div class="tk2-content-btm" style="padding:0 0 10px 0;background: #fff;">
		<a href="javascript:;" title="" onclick="doSubmit()">确认交卷</a>
		<a href="javascript:;" title="" onclick="layer.closeAll('page')">继续考试</a>
	</div>
</div>

<div  id="info2" class="none">
	<p>考生姓名：<?=$this->session->user->relname?></p>
	<p>所属科目：<?=config_item('subject')[$this->session->user->subject]?></p>
	<p>所属车型：<?=config_item('car_type')[$this->session->user->car]?></p>
	<div class="nums">
		<p>总共：<span style="color:#0001fe;">0</span> 道题</p>
		<p>正确：<span style="color:#008100;">0</span> 道题</p>
		<p>错误：<span style="color:#fa0003;">0</span> 道题</p>
	</div>
	<div class="tk2-content-btm" style="padding-top:50px;">
		<a href="javascript:;" onclick="doError()" title="">我的错题</a>
		<a href="<?=base_url()?>" title="">退出测试</a>
	</div>
	<div class="score">
		<p>本次成绩为</p>
		<p><span>0</span> 分</p>
		<p></p>
	</div>
</div>
<div id="info3" class="info none">
	<img src="public/imgs/icon8.jpg" alt="">本操作只会保存错误题目数据
	<div class="tk2-content-btm" style="padding-top: 0;">
		<a href="javascript:;" onclick="saveError()" title="">确认</a>
		<a href="javascript:;" onclick="saveError(1)" title="">取消</a>
	</div>
</div>
<script>
	layer.config({
		extend:'lly/style.css',
	});

	function doDeal(){
		  	var index = layer.open({
		  		type: 1,
		  		skin:'lly',
		  		area: ['400px', '207px'],
			  	title:'提示', 
			 	content:$("#info1")
			});
		reStyle(index);
	}

	function doSubmit(){
		layer.closeAll('page');
	  	var index = layer.open({
	  		type: 1,
	  		skin:'lly',
	  		area: ['600px', '385px'],
		  	title:'提示', 
		 	content:$("#info2")
		}); 
		reStyle(index);
	}
	function setTimer(){
		var minute = '<?=$minute-1?>';
		var second = 59;
		var timer = setInterval(function(){
			if(minute==0 && second==0){
				clearInterval(timer);
				doSubmit();
				return false;
			}
			if(second==0){
				minute-=1;
				second = 59;
			}
			$('#timer em').eq(0).text(minute);
			$('#timer em').eq(1).text(second--);
		},1000);
	}

	// 错题
	var layer_index;
	function doError(){
	  	layer_index = layer.open({
	  		type: 1,
	  		skin:'lly',
	  		area: ['300px', '147px'],
	  		icon: 1,
		  	title:'提示', 
		 	content:$("#info3")
		});  
		reStyle(layer_index);
	}
	function saveError(cancle){
		var qids = new Array();
		$('.subjects-list li').filter('.red').each(function(){
			qids.push($(this).attr('id'));
		});
		layer.close(layer_index);
		if(qids.length){
			if(!cancle){
				$.post('<?=base_url("exam/save_wrong")?>',
					{ids:qids},
					function(data){
						layer.close(layer_index);
						if(data.state=='success')
							layer.alert('保存成功',{skin:'lly'});
						else 
							layer.alert('保存失败',{skin:'lly'});
					},'json').error(function(){
						location.href = '<?=base_url("login")?>';
					});
			}
		}
		else{
			layer.alert('无错题',{skin:'lly'});
		}
	}

	function analyse(){
		var count = $('.subjects-list li').length;
		var right = $('.subjects-list li').filter('.green').length;
		var error = $('.subjects-list li').filter('.red').length;
		var none = count-right-error;
		var scale = Math.round(right*100/count);

		$('#info1 span').eq(0).text(none);
		$('#info2 span').eq(0).text(count);
		$('#info2 span').eq(1).text(right);
		$('#info2 span').eq(2).text(error);
		$('#info2 span').eq(3).text(scale);
		$('#info2 p:last').text(scale>=90?'祝贺您已通过考试':'考试成绩不合格');
	}
	$(function(){
		setTimer();
		$('.subjects-list li').click(function(){
			$(this).siblings('.blue').removeClass('blue');
			$(this).addClass('blue');
			var selected = $(this).children('span').text();
			var index = $(this).children('em').text();
			switch(selected){
				case '√':
					selected = 'A';
					break;
				case '×':
					selected = 'B';
					break;
			}
			load_question($(this).attr('id'),index,selected);
		});
		if($('.subjects-list li').length > 0){
			load_question($('.subjects-list li:first').addClass('blue').attr('id'),1,'');
		}
		else
			layer.alert('暂无数据',{skin:'lly',closeBtn:0},function(){
				history.go(-1);
			});
		analyse();
	});
	function reStyle(index){
		layer.style(index,{
			'border':'1px solid #7baaea',
			'background': '#edf4ff',
			'border-radius':'5px',
			'overflow': 'hidden',
			'padding': '6px'
		});
	}
	function load_question(qid,index,selected){
		$('.paper-content').html('<img src="public/imgs/load.gif" width="20px">&nbsp;loading...');
		$.post('<?=base_url("exam/load_question_json")?>',
			{
				qid:qid,
				index:index,
				selected:selected
			},
			function(data){
				if(!data.data){
					return false;
				}
				$('.paper-content').html(buildContent(qid, data.index, data.data));
				$(':hidden[name=question_select]').val(data.selected.split(''));

				if($(':hidden[name=question_select]').val()){
					var sign = $(':hidden[name=question_select]').val() == $(':hidden[name=question_answer]').val()?'yes':'no';
					$('#qerror').html('<img src="public/imgs/'+sign+'.jpg">').show();
					$('#qanswer').show();
					$('#btn_select').find('div.btn').attr('onclick','javascript:;');
					$('#mul_btn').attr('onclick','javascript:;');
				}

				var selected = data.selected;
				var index;
				if(data.data.question_type==1){
					if(data.selected=='A') index = 4;
					if(data.selected=='B') index = 5;
					$('#qsanswer div').hide().eq(index).show();
				}
				else if(data.data.question_type==2){
					switch(data.selected){
						case 'A':
							index = 0;
							break;
						case 'B':
							index = 1;
							break;
						case 'C':
							index = 2;
							break;
						case 'D':
							index = 3;
							break;
					}
					$('#qsanswer div').hide().eq(index).show();
				}
				else{
					$('#qsanswer').text(selected);
				}
			},'json').error(function(){
				location.href = '<?=base_url("login")?>';
			});
	}
	
	function setSingle(src,index,is_judge){
		$(src).siblings().andSelf().attr('onclick','javascript:;');
		$('#qsanswer div').hide().eq(index).show();
		var selected = $(src).attr('h-val');
		$(':hidden[name=question_select]').val(selected);
		setCommon(selected,is_judge);
	}
	function setCommon(selected,is_judge){
		var qid = $(':hidden[name=qid]').val();
		$('#qanswer').show();
		if($(':hidden[name=question_select]').val() == $(':hidden[name=question_answer]').val()){
			var sign = 'yes';
			var color = 'green';
		}
		else{
			var sign = 'no';
			var color = 'red';
		}
		$('#qerror').html('<img src="public/imgs/'+sign+'.jpg">');
		$('#qerror').show();
		if($(':radio[name=fy]:checked').val()==4 && sign=='yes' || $(':radio[name=fy]:checked').val()==2){
			$('#qnext').click();
		}
		if(is_judge){
			if(selected=='A') selected = '√';
			if(selected=='B') selected = '×';
		}
		$('.subjects-list li').filter('#'+qid).removeClass().addClass(color).find('span:last').text(selected);
		analyse();
	}
	function selectMulti(index){
		$(':checkbox[name=mul-a]').eq(index).click();
	}
	function doSel(src){
		if($(src).is(':checked'))
			$(src).attr('checked','checked');
		else
			$(src).removeAttr('checked');
	}
	function setMulti(src){
		var selected = new Array();
		$(':checkbox[name=mul-a]:checked').each(function(){
			selected.push($(this).val());
		});
		if(!selected.length){
			alert('请选择答案');
			return false;
		}
		$(src).attr('onclick','javascript:;');
		$('#qsanswer').text(selected.join(''));
		$(':hidden[name=question_select]').val(selected);
		setCommon(selected.join(''));
	}
	function show_img(){
		layer.photos({
		    photos:'.photos',
		    shift:0
		});
	}
	function next(qid){
		$('#'+qid).next().click();
	}
	function prev(qid){
		$('#'+qid).prev().click();
	}
	function buildContent(qid, index, data){
		var opt_tmp = '';
		var qtype = '判断题';
		var answer = '<div class="btn">'+data.question_answer+'</div>';
		var qselect =
				'<div class="btn" h-val="A" onclick="setSingle(this,0)">A</div>'+
				'<div class="btn" h-val="B" onclick="setSingle(this,1)">B</div>'+
				'<div class="btn" h-val="C" onclick="setSingle(this,2)">C</div>'+
				'<div class="btn" h-val="D" onclick="setSingle(this,3)">D</div>';
		switch(data.question_type){
			case '2':
				opt_tmp = 
					'<p>A. '+data.opt_a+'</p>'+
					'<p>B. '+data.opt_b+'</p>'+
					'<p>C. '+data.opt_c+'</p>'+
					'<p>D. '+data.opt_d+'</p>';
				qtype = '单选题';
				break;
			case '3':
				answer = data.question_answer.split(',').join('');
				opt_tmp = 
					'<p><label><input type="checkbox" name="mul-a" onclick="doSel(this)" value="A">A.'+data.opt_a+'</label></p>'+
					'<p><label><input type="checkbox" name="mul-a" onclick="doSel(this)" value="B">B.'+data.opt_b+'</label></p>'+
					'<p><label><input type="checkbox" name="mul-a" onclick="doSel(this)" value="C">C.'+data.opt_c+'</label></p>'+
					'<p><label><input type="checkbox" name="mul-a" onclick="doSel(this)" value="D">D.'+data.opt_d+'</label></p>'+
					'<button onclick="setMulti(this)" id="mul_btn">确定</button>';
				qtype = '多选题';
				qselect =
					'<div class="btn" h-val="A" onclick="selectMulti(0)">A</div>'+
					'<div class="btn" h-val="B" onclick="selectMulti(1)">B</div>'+
					'<div class="btn" h-val="C" onclick="selectMulti(2)">C</div>'+
					'<div class="btn" h-val="D" onclick="selectMulti(3)">D</div>';
				break;
			case '1':
				answer = '<div class="btn"><img src="public/imgs/'+(data.question_answer=='A'?'yes1':'no1')+'.png" alt=""></div>';
				qselect =
					'<div class="btn" h-val="A" onclick="setSingle(this,4,1)"><img src="public/imgs/yes1.png" alt=""></div>'+
					'<div class="btn" h-val="B" onclick="setSingle(this,5,1)"><img src="public/imgs/no1.png" alt=""></div>';
				break;

		}
		var tmp = 
				'<div  class="paper-content-item" style="min-height: 300px;">'+
					'<span class="bt">考试题目</span>'+
					'<h1><em>'+index+'</em>-'+data.question_content+'</h1>'+
					opt_tmp+
				'</div>'+
				'<div  class="paper-content-item" id="qexplain" style="display:none">'+
					'<span class="bt">讲解</span>'+
					'<p class="jj-content">'+data.question_explain+'&nbsp;</p>'+
				'</div>'+
				'<div  class="paper-content-item clearfix">'+
					'<span class="bt">答题选项-多项选择完成后请点击确定</span>'+
					'<h1 style="display: inline-block;*zoom:1;*display: inline;">'+
						'您的答案：'+
						'<span id="qsanswer">'+
							'<div class="btn" style="display:none">A</div>'+
							'<div class="btn" style="display:none">B</div>'+
							'<div class="btn" style="display:none">C</div>'+
							'<div class="btn" style="display:none">D</div>'+
							'<div class="btn" style="display:none"><img src="public/imgs/yes1.png" alt=""></div>'+
							'<div class="btn" style="display:none"><img src="public/imgs/no1.png" alt=""></div>'+
						'</span>'+
						'<span id="qerror" style="display:none">'+
						'</span>'+
						'<span id="qanswer" style="display:none">'+
							'正确答案：'+
							'<input type="hidden" name="question_answer" value="'+data.question_answer+'">'+
							'<input type="hidden" name="qid" value="'+data.id+'">'+
							'<input type="hidden" name="question_select">'+
							answer+
						'</span>'+
					'</h1>'+
					'<div class="right" id="btn_select">'+
						'<h1>'+
							'选择：'+
							qselect +
						'</h1>'+
					'</div>'+
					'<div style="clear: both;"></div>'+
				'</div>'+
				'<div  class="paper-content-item clearfix">'+
					'<span class="bt">提示信息</span>'+
					'<p class="jj-content">'+qtype+'：请选择您认为正确的答案</p>'+
					'<div class="paper-btn-box right">'+
						'<a href="javascript:;" title="" onclick="prev('+data.id+')" class="btn">上一题-</a>'+
						'<a href="javascript:;" title="" id="qnext" onclick="next('+data.id+')" class="btn">下一题+</a>'+
						'<a href="javascript:;" title="" class="btn" style="width: 74px;text-align: center;margin-left: 20px;" onclick="doDeal()">交卷</a>'+
					'</div>'+
					'<div style="clear: both;"></div>'+
				'</div>'+
				'<div class="photos">'+
				(data.img_url==''?'':'<img onclick="show_img()" style="max-height:300px" src="'+data.img_url+'" alt="">')+
				'</div>';
		return tmp;
	}
</script>
</html>