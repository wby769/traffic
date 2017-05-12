<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('question_model');
	}

	// edit frame
	function add_question($qid=NULL){
		$row = $this->question_model->question_detail($qid);
		$row['qid'] = $qid;
		$this->load->view('question/add',$row);
	}

	// list
	function list_question(){
		header('Cache-control: private, must-revalidate');
		$rt = $this->question_model->question_list();
		$this->load->view('question/list',$rt);
	}

	// save
	function save_question(){
		$this->question_model->save_question();
	}

	// delete
	function delete_question($qid){
		$this->question_model->delete_question($qid);
	}

	// check
	function question_exists(){
		echo $this->question_model->question_exists($_POST['qid']);
	}

	// 上传图片&&语音讲解
	function upload($type='image'){
		$this->question_model->upload($type=='audio'? 'audio': 'picture');
	}
	
	// 加载题目
	function question_opt(){
		echo json_encode($this->question_model->question_opt($_POST['subject']));
	}

}
