<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nav extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('menu_model');
	}

	/**
	 * main`
	 *
	 * @return  page
	 */
	function index(){
		$rt = $this->menu_model->show_menu($this->session->user->subject,$this->session->user->car);
		$info = $this->user_model->last_info($this->session->uid);
		$rt['last_url'] = $info->last_url;
		$rt['last_time'] = $info->last_time;
		$this->load->view('nav/front',$rt);
	}
	function admin(){
		$this->load->view('nav/admin');
	}

	/**
	 * welcome
	 *
	 * @return  page
	 */
	function wel(){
		$this->load->view('nav/main');
	}

	/**
	 * login frame
	 *
	 * @return  page
	 */
	function login_frame(){
		$this->load->view('nav/login_front');
	}
	function tc_login_frame(){
		if(!empty($_COOKIE['username']) && !empty($_COOKIE['password']))
			$this->user_model->check_user($_COOKIE['username'],$_COOKIE['password'],TRUE,TRUE,TRUE) && redirect('/tc');
		else
			$this->load->view('nav/login');
	}

	// 登陆
	function login($is_admin=FALSE){
		if(isset($_POST['code'])){
			if(MD5($_POST['code'])!=$this->session->vcode){
				echo 'code';
				exit;
			}
		}
		$this->user_model->check_user($_POST['username'],$_POST['password'],FALSE,FALSE,$is_admin);
	}

	// 登出
	function logout($is_admin=FALSE){
		$this->session->sess_destroy();
		setcookie('username',NULL,-1,'/');
		setcookie('password',NULL,-1,'/');
		redirect($is_admin?'/tc_login':'/login');
	}

	// 验证码
	function getCode(){
		// $srcstr = "xc1qvw2er3tby4ui5omp6as7df8ngh9jk0lz";
		$srcstr = "0123456789";
		$len = 4;
		$width = 80;
		$height = 30;
		$img = imagecreatetruecolor($width, $height);
		$bgcolor = imagecolorallocate($img, rand(157,255), rand(157,255), rand(157,255));
		$fontcolor = imagecolorallocate($img, rand(0,156), rand(0,156), rand(0,156));
		$pixcolor = imagecolorallocate($img, rand(200,255), rand(200,255), rand(200,255));

		#填充背景
		imagefill($img, 0, 0, $bgcolor);
		#画线条
		for($i=0; $i<2; $i++){
			imageline($img, rand(0,$width), rand(0,$height), rand(0,$width), rand(0,$height), $fontcolor);
		}
		#画雪花和点
		for($i=0; $i<3; $i++){
			imagestring($img, rand(1,5), rand(0,$width), rand(0,$height), '*', $pixcolor);
		}
		for($i=0; $i<10; $i++){
			imagesetpixel($img, rand(0,$width), rand(0,$height), $pixcolor);
		}
		#生成字符串画出
		for($i=0,$str=''; $i<$len; $i++){
			$str .= $srcstr[rand(0,9)];
		}
		imagestring($img, 5, 20, 5, $str, $fontcolor);
		header('Content-type:image/png');
		imagepng($img);
		imagedestroy($img);
		$_SESSION['vcode'] = MD5($str);
	}

}
