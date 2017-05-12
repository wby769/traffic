<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission{
	protected $ci;

	public function __construct(){
		$this->ci = & get_instance();
	}

	public function check(){
		$action = $this->ci->router->fetch_class();
		$method = $this->ci->router->fetch_method();
		$oper_url = $action.'/'.$method;

// echo $oper_url;
		$this->ci->config->load('auth',true);
		$spe_name = $this->ci->config->item('special','auth');
		$admin = $this->ci->config->item('admin','auth');
		$visitor = $this->ci->config->item('visitor','auth');

		$username = $this->ci->session->uname;
		if(! in_array($oper_url, $spe_name)){
			if(empty($username)){
				if(array_key_exists($action, $visitor) && in_array($method,$visitor[$action])){
					$url = base_url('login');
				}
				elseif(array_key_exists($action, $admin) && in_array($method,$admin[$action])){
					$url = base_url('tc_login');
				}
				else $this->showError();
				echo "<script>window.top.location.href='$url';</script>";
				exit;
			}
			else{
				switch($this->ci->session->utype) {
					case '1':
						if(!array_key_exists($action, $visitor) or !in_array($method,$visitor[$action])){
							$this->showError();
						}
						break;
					case '2':
						if(!array_key_exists($action, $admin) or !in_array($method,$admin[$action])){
							$this->showError();
						}
						break;
				}

			}
		}
	}
	function showError(){
		require APPPATH.'views/errors/auth.php';
		exit();
	}

}
