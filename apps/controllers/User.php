<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('user_model');
	}

	/**
	 * 用户
	 *
	 * 查询 && 添加 &&保存
	 *
	 */
	function user_list(){
		$this->load->view('user/user_list',$this->user_model->user_list());
	}
	function add_user($id=NULL){
		$row = $this->user_model->user_detail($id);
		$id && $row['title'] = '编辑';
		$this->load->view('user/add',$row);
	}
	function save_user(){
		$this->user_model->save_user();
	}
	function delete_user(){
		$this->user_model->delete_user($_POST['id']);
	}

	/**
	 * 设置剩余次数 && 有效期
	 *
	 */
	function setting($type){
		$row = $this->user_model->user_detail($_GET['id']);
		$row['sign'] = $type;
		$this->load->view('user/set',$row);
	}
	function save_setting(){
		$this->user_model->save_setting();
	}

	// 用户是否存在
	function exists(){
		echo $this->user_model->user_exists($_POST['username']);
	}

	// 密码
	function pwd(){
		$this->load->view('user/pwd');
	}
	function check_old_pwd(){
		echo $this->user_model->check_old_pwd($this->session->uid, $_POST['pwd']);
	}
	function save_pwd(){
		$this->user_model->save_pwd($this->session->uid, $_POST['pwd']);
	}

	// 剩余次数
	function get_residue_num(){
		echo json_encode($this->user_model->get_residue_info($_POST['username']));
	}
}
