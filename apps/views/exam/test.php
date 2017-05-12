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
			<div class="paper-l-item">考场号：001</div>
			<div class="paper-l-item">姓名：<?=$this->session->user->relname?></div>
			<div class="paper-l-item">科目：<?=config_item('subject')[$this->session->user->subject]?></div>
			<div class="paper-l-item">车型：<?=config_item('car_type')[$this->session->user->car]?></div>
			<div class="paper-l-item" id="timer">剩余时间：<em>0</em>时<em>0</em>分<em>0</em>秒</div>
			<div class="paper-l-item">
				<h1>提示选项</h1>
				<p>
					<label><input type="checkbox" name="showAn" checked="checked" onclick="showAnswer()">显示答案</label>
				</p>
				<p>
					<label><input type="checkbox" name="showEr" checked="checked" onclick="showError()">显示对错</label>
				</p>
				<p>
					<label><input type="checkbox" name="showEx" onclick="showExplain()">显示讲解</label>
				</p>
				<hr>
				<p>
					<label><input type="checkbox" name="audioEx" onclick="playExplain(this)">语音讲解</label>
				</p>
				<p>
					<label><input type="checkbox" checked="checked" name="audioTi">语音提示</label>
				</p>
				<p>
					<label><input type="checkbox" checked="checked" name="audioEE">答错时语音讲解</label>
				</p>
				<hr>
				<p>
					<label><input type="radio" checked="checked" name="fy" value="1">手动翻页</label>
				</p>
				<p>
					<label><input type="radio" name="fy" value="2">自动翻页</label>
				</p>
				<p>
					<label><input type="radio" name="fy" value="4">答对时翻页</label>
				</p>
				<audio src="public/audio/right.wav" id="audio_right" preload="none"></audio>
				<audio src="public/audio/wrong.wav" id="audio_wrong" preload="none"></audio>
				<audio src="public/audio/none.mp3" id="audio_none" preload="none"></audio>
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
			<div class="paper-btns">
				<a href="javascript:;" onclick="playAudio()">语音讲解</a>
				<a href="javascript:;" onclick="doError()">错题重做</a>
			</div>
			<div class="paper-result">
				<h1>练习结果</h1>
				<div class="list">抽取数量：<span>0</span>道</div>
				<div class="list">答对题目：<div class="green">绿</div><span>0</span>道</div>
				<div class="list">答错题目：<div class="red">红</div><span>0</span>道</div>
				<div class="list">未答题目：<div>白</div><span>0</span>道</div>
				<div class="list">首正率：<span>0</span>%</div>
				<div class="list">答题比成绩：<span>0</span></div>
			</div>
		</div>
	</div>
</body>
<div id="info" class="info none">
	<img src="public/imgs/icon8.jpg" alt="">尚有<span>0</span>道题未做，是否交卷?
	<div class="tk2-content-btm" style="padding-top: 0;">
		<a href="javascript:;" onclick="doSubmit()" title="">确认</a>
		<a href="javascript:;" onclick="layer.closeAll('page')" title="">取消</a>
	</div>
</div>
<div  id="info2" class="none">
	<p>考生姓名：<?=$this->session->user->relname?></p>
	<p>所属科目：<?=config_item('subject')[$this->session->user->subject]?></p>
	<p>所属车型：<?=config_item('car_type')[$this->session->user->car]?></p>
	<p>学习章节：<?=$qtitle?></p>
	<div class="nums">
		<p>总共：<span style="color:#0001fe;">100</span> 道题</p>
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

	function doDeal(){
	  	var index = layer.open({
	  		type: 1,
	  		skin:'lly',
	  		area: ['300px', '147px'],
	  		icon: 1,
		  	title:'注意', 
		 	content:$("#info")
		});  
		reStyle(index);
	}
	var layer_index;
	function doError(){
	  	layer_index = layer.open({
	  		type: 1,
	  		skin:'lly',
	  		area: ['300px', '147px'],
	  		icon: 1,
		  	title:'注意', 
		 	content:$("#info3")
		});  
		reStyle(layer_index);
	}
	function doSubmit(){
		layer.closeAll('page');
	  	var index = layer.open({
	  		type: 1,
	  		area: ['600px', '422px'],
		  	title:'注意', 
		 	content:$("#info2")
		}); 
		reStyle(index);
	}
	function setTimer(){
		var hour = 0;
		var minute = 0;
		var second = 1;
		setInterval(function(){
			if(second==60){
				minute+=1;
				second = 0;
			}
			if(minute==60){
				hour+=1;
				minute=0;
			}
			if(hour==60){
				hour=0;
			}
			$('#timer em').eq(0).text(hour);
			$('#timer em').eq(1).text(minute);
			$('#timer em').eq(2).text(second++);
		},1000);
	}
	function analyse(){
		var count = $('.subjects-list li').length;
		var right = $('.subjects-list li').filter('.green').length;
		var error = $('.subjects-list li').filter('.red').length;
		var none = count-right-error;
		var scale = Math.round(right*100/count);
		$('.paper-result div').filter('.list').eq(0).find('span').text(count);
		$('.paper-result div').filter('.list').eq(1).find('span').text(right);
		$('.paper-result div').filter('.list').eq(2).find('span').text(error);

		$('#info2 span').eq(0).text(count);
		$('#info2 span').eq(1).text(right);
		$('#info2 span').eq(2).text(error);
		$('#info2 span').eq(3).text(scale);
		$('#info2 span').eq(3).parent().next().text(scale>=90?'祝贺您已通过考试':'考试成绩不合格');

		$('.paper-result div').filter('.list').eq(3).find('span').text(none);
		$('.paper-result div').filter('.list').eq(4).find('span').text(scale);
		$('.paper-result div').filter('.list').eq(5).find('span').text(right);

		$('#info').find('span').text(none);
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
	function show_img(){
		layer.photos({
		    photos:'.photos',
		    shift:0
		});
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
				$(':checkbox[name=showEx]').is(':checked') && $('#qexplain').show();
				$(':checkbox[name=audioEx]').is(':checked') && $('audio:last')[0].play();
				$(':hidden[name=question_select]').val(data.selected.split(''));
				showAnswer();
				showError();
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
	function showAnswer(){
		if($(':hidden[name=question_select]').val()){
			if($(':checkbox[name=showAn]').is(':checked'))
				$('#qanswer').show();
			else $('#qanswer').hide();
		}
	}
	function showError(){
		if($(':hidden[name=question_select]').val()){
			if($(':checkbox[name=showEr]').is(':checked'))
				$('#qerror').show();
			else $('#qerror').hide();
		}
		var sign = $(':hidden[name=question_select]').val() == $(':hidden[name=question_answer]').val()?'yes':'no';
		$('#qerror').html('<img src="public/imgs/'+sign+'.jpg">');
	}
	function showExplain(){
		$('#qexplain').toggle();
	}
	function playExplain(src){
		$(src).is(':checked') && $('audio:last')[0].play();

	}
	function setSingle(src,index,is_judge){
		$('#qsanswer div').hide().eq(index).show();
		var selected = $(src).attr('h-val');
		$(':hidden[name=question_select]').val(selected);
		setCommon(selected,is_judge);
	}
	function setCommon(selected,is_judge){
		var qid = $(':hidden[name=qid]').val();
		$(':checkbox[name=showAn]').is(':checked') && $('#qanswer').show();
		if($(':hidden[name=question_select]').val() == $(':hidden[name=question_answer]').val()){
			var sign = 'yes';
			var color = 'green';
			$(':checkbox[name=audioTi]').is(':checked') && $('#audio_right')[0].play();
		}
		else{
			var sign = 'no';
			var color = 'red';
			$(':checkbox[name=audioTi]').is(':checked') && $('#audio_wrong')[0].play();
			$(':checkbox[name=audioEE]').is(':checked') && $(':radio[name=fy]:checked').val()!=2 && $('audio:last')[0].play();
		}
		$('#qerror').html('<img src="public/imgs/'+sign+'.jpg">');
		$(':checkbox[name=showEr]').is(':checked') && $('#qerror').show();
		if($(':radio[name=fy]:checked').val()==4 && sign=='yes' || $(':radio[name=fy]:checked').val()==2){
			$('#qnext').click();
		}
		if(is_judge){
			if(selected=='A') selected = '√';
			if(selected=='B') selected = '×';
		}
		$('.subjects-list li').filter('#'+qid).removeClass().addClass(color).find('span:last').text(selected);
		analyse();
		// return color;
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
	function setMulti(){
		var selected = new Array();
		$(':checkbox[name=mul-a]:checked').each(function(){
			selected.push($(this).val());
		});
		$('#qsanswer').text(selected.join(''));
		$(':hidden[name=question_select]').val(selected);
		setCommon(selected.join(''));
	}
	function next(qid){
		$('#'+qid).next().click();
	}
	function prev(qid){
		$('#'+qid).prev().click();
	}
	function playAudio(){
		$('audio:last')[0].play();
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
					'<button onclick="setMulti()">确定</button>';
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
		var audio_url= data.audio_url==''?'public/audio/none.mp3':data.audio_url;
		var tmp = 
				'<div  class="paper-content-item" style="min-height: 300px;">'+
					'<span class="bt">考试题目-<?=$qtitle?>-<span>'+data.id+'</span></span>'+
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
					'<div class="right">'+
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
				'<audio src="'+audio_url+'" preload="none"></audio>'+
				'</div>';
		return tmp;
	}
</script>
</html>