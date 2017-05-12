<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('exam_model');
		$this->load->model('menu_model');
		$this->load->model('question_model');
	}

	// 子菜单
	function show_sub($fid){
		$this->load->view('exam/sub',array_merge($this->menu_model->show_submenu($fid),array('fid'=>$fid)));
	}

	// 学习测试
	function test($mid){
		$rt['list'] = $this->exam_model->get_qids($mid,FALSE,FALSE,TRUE);
		$rt['qtitle'] = $this->menu_model->get_menuname($mid);
		$this->save_time();
		$this->test_url("exam/test/$mid");
		$this->load->view('exam/test',$rt);
	}

	// 子菜单测试
	function sub_test($mid,$ids,$is_rand=FALSE){
		$rt['list'] = $this->exam_model->get_qids($ids,TRUE,TRUE,$is_rand);
		$rt['qtitle'] = $this->menu_model->get_menuname($mid);
		$this->save_time();
		$this->test_url("exam/sub_test/$mid/$ids/$is_rand");
		$this->load->view('exam/test',$rt);
	}

	// 仿真考试
	function simulate(){
		switch ($this->session->user->subject) {
			case 1:
				$data = $this->exam_model->get_simulate_qids();
				$minute = 45;
				break;
			case 2:
				$data = $this->exam_model->get_simulate_qids(1, 22);
				$data = array_merge($data, $this->exam_model->get_simulate_qids(2, 23));
				$data = array_merge($data, $this->exam_model->get_simulate_qids(3, 5));
				$minute = 30;
				break;
		}
		$rt['list']= $data;
		$rt['minute']= $minute;
		$this->save_time();
		$this->test_url("exam/simulate");
		$this->load->view('exam/simulate',$rt);
	}
	
	// 错题
	function wrong(){
		$rt['list'] = $this->exam_model->wrong_question($this->session->uid);
		$this->load->view('exam/test',array_merge($rt,array('qtitle'=>NULL)));
	}
	function save_wrong(){
		$this->exam_model->save_wrong($this->session->uid, $_POST['ids']);
	}

	function clear_wrong(){
		$this->exam_model->clear_wrong($this->session->uid);
	}

	// 加载试题
	function load_question_json(){
		$data = array(
			'index'=>$_POST['index'],
			'selected'=>$_POST['selected'],
			'data'=>$this->question_model->question_detail($_POST['qid'])
			);
		echo json_encode($data);
	}

	// 本次时间
	protected function save_time(){
		$this->exam_model->test_time($this->session->uid);
	}
	// 本次URL
	protected function test_url($url){
		$this->exam_model->test_url($this->session->uid, $url);
	}
	

}
