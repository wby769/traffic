<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('menu_model');
	}
	// ---添加---
	function add_menu($id=NULL){
		$row = $this->menu_model->common_detail('t_menu',$id);
		$id && $row['title'] = '编辑';
		$this->load->view('menu/add',$row);
	}
	function add_menu_child($id=NULL){
		$data = $this->menu_model->menu_opt();
		$data['ids'] = $this->menu_model->submenu_question_opt($id,TRUE);
		$id && $data['title'] = '编辑';
		$row = $this->menu_model->common_detail('t_submenu',$id);
		$row or $row = array();
		$this->load->view('menu/add_child', array_merge($data,$row));
	}

	// ---保存---
	function save_menu(){
		$this->menu_model->save_menu();
	}
	function save_menu_child(){
		$this->menu_model->save_menu_child();
	}

	// ---删除---
	function delete_menu($id){
		$this->menu_model->delete_menu($id);
	}
	function delete_menu_child($id){
		$this->menu_model->delete_menu_child($id);
	}

	// ---列表---
	function list_menu(){
		$this->load->view('menu/list',$this->menu_model->list_menu());
	}
	function list_menu_child(){
		$this->load->view('menu/list_child',$this->menu_model->list_menu(TRUE));
	}
	
	// 校验是否存在
	function exists(){
		$table = $_POST['type']=='f'?'t_menu':'t_submenu';
		echo $this->menu_model->common_exists($table, $_POST['name'],empty($_POST['fid'])?NULL:$_POST['fid']);
	}

}
