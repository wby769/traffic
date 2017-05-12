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
	<script src="public/js/jquery.js" type="text/javascript"></script>
	<script src="public/layer/layer.js" type="text/javascript"></script>
	<style type="text/css">
	.loginWrap{
		height: 360px;
	}
	</style>
</head>
<body>
	<!-- 头部顶部 -->
	<div class="head-top-box">
		<div class="boxWrap">
			<div class="right head-btn-box">
				<!-- <a href="javascript:;" class="login-btn">登录</a>
				<a href="javascript:;">注册</a> -->
			</div>
			<span>欢迎登陆同创理论强化服务加盟平台</span>
		</div>
	</div>
	<!-- logo部分 -->
	<div class="head-content-box boxWrap">
		<div class="right">
			<span><img src="public/imgs/icon1.jpg" alt="">品质更高</span>
			<span><img src="public/imgs/icon2.jpg" alt="">价格更优</span>
			<span><img src="public/imgs/icon3.jpg" alt="">服务更好</span>
		</div>
		<img src="public/imgs/logo.jpg" alt="" class="logo-img">
	</div>
	<!-- 轮播图部分 -->
	<div class="bannerWrap">
		<img src="public/imgs/banner1.jpg" alt="" class="banner-img">
		<img src="public/imgs/banner2.jpg" alt="" class="banner-img">
		<img src="public/imgs/banner3.jpg" alt="" class="banner-img">
		<ul class="ulNum"></ul>
		<!-- 用户登录表单 -->
		<div class="boxWrap" style="z-index: 9999;">
			<div class="loginWrap">
				<div class="title">
					用户登录
				</div>
				<div class="login-content">
					<p class="surplusNum" style="display:none"></p>
					<form action="<?=base_url('nav/login')?>" method="post">
					<div class="login-item">
						<img src="public/imgs/icon4.jpg" alt="">
						<input type="text" name="username"  onblur="get_residue_num(this)" placeholder="请输入用户名">
					</div>
					<div class="login-item">
						<img src="public/imgs/icon5.jpg" alt="">
						<input type="password" name="password" autocomplete='off' placeholder="请输入密码">
					</div>
					<div class="login-item">
						<img src="public/imgs/icon6.jpg" alt="">
						<input type="text" name="code" placeholder="验证码" style="width:140px">
						<div class="code"><img src="<?=base_url('nav/getCode')?>" onclick="doChange(this)"></div>
					</div>
					<p class="login-btm">
						<!-- <a href="javascript:;" title="" class="mima right">忘记密码</a> -->
						<!-- <input type="checkbox" name="is_record">10天内自动登录 -->
						&nbsp;
					</p>
					</form>
					<a href="javascript:;" title="" onclick="doDeal()" class="login-inp">登录</a>
				<!-- 	<div class="register-box">
						没有账号？
						<a href="javascript:;" class="register-inp">立即注册</a>
					</div> -->
				</div>
			</div>
		</div>
	</div>
	<!-- 底部 -->
	<div class="footer">
		<p>总部地址：江苏省徐州市沛县  服务热线：18251636620   13512577077</p>
		<p>Copyright © 同创理论强化服务平台</p>
	</div>
</body>
<script>
	// 轮播图
	var index=0;
	var timer;
	// 获取图片
	var imgs = $(".banner-img");
	// 创建图片数量
	for(var i=0;i<imgs.length;i++){
		$(".ulNum").append("<li>");
	}
	//默认第一个高亮
	$(".banner-img").hide().eq(index).show();
	$(".ulNum li").eq(index).addClass("active").siblings().removeClass("active");
	$(".banner-img").hover(function(){
		clearTimeout(timer);
	},function(){
		timer=setTimeout(doToggleImage,4000);
	})
	timer=setTimeout(doToggleImage,4000);

	//切换图片
	function doToggleImage(){
		index++;
		if(index==imgs.length){
			index=0;
		}
		$(".banner-img").fadeOut().eq(index).fadeIn();
		$(".ulNum li").eq(index).addClass("active").siblings().removeClass("active");
		timer=setTimeout(doToggleImage,4000);
	}
	//点击按钮切换图片
	$(".ulNum").find("li").each(function(i){
		$(this).click(function(){
			clearTimeout(timer);
			index=i;
			$(".banner-img").fadeOut().eq(index).fadeIn();
			$(".ulNum li").eq(index).addClass("active").siblings().removeClass("active");
			timer=setTimeout(doToggleImage,4000);
		})
	})
	$(window).keyup(function(event){
        if(event.which == 13)
            doDeal();
    });
	function doChange(src){
		$(src).attr('src','<?=base_url("nav/getCode")?>');
	}
	function doDeal(){
	    if($(":text[name=username]").val()==''){
	        layer.tips('请输入用户名!', ':text[name=username]',{
	            tips:[2, '#ffa800']
	        });
	        return false;
	    }
	    if($(":password").val()==''){
	        layer.tips('请输入密码!', ':password',{
	            tips:[2, '#ffa800']
	        });
	        return false;
	    }
	    if($(":text[name=code]").val()==''){
	        layer.tips('请输入验证码!', ':text[name=code]',{
	            tips:[2, '#ffa800']
	        });
	        return false;
	    }
	    $.post(
	        "<?=base_url('nav/login')?>",
	        $('form').serialize()+'&check=JSON',
	        function(data){
    	    	switch(data){
    	    		case 'success':
		                // location.href = '';
		                $('form').submit();
    		    		break;
		    		case 'code':
		    			layer.msg('验证码错误!',function(){});
    		    		break;
    	    		case 'residue':
		    			layer.msg('有效次数已用尽!',function(){});
    		    		break;
    	    		case 'expire':
		    			layer.msg('已超过有效期!',function(){});
    		    		break;
    	    		default:
	    	    		layer.msg('用户名或密码错误!',function(){});
    	    			break;
    	    	}
	    });
	}
	function get_residue_num(src){
		$('.surplusNum').hide();
		if($(src).val()){
			$.post(
			    "<?=base_url('user/get_residue_num')?>",
			    {username:$(src).val()},
			    function(data){
			        if(data != 'null'){
			        	var data = JSON.parse(data);
						$('.surplusNum').html('<span>剩余次数：'+data.residue_num+'<span><span style="float:right">有效期至：'+data.expire_time+'</span>').show();
			        }
			});
		}
	}
</script>
</html>