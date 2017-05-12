<?php defined('BASEPATH') OR exit('No direct script access allowed');
 /**
  * question
  * @author lly
  */
class Question_model extends CI_Model{
    /**
     * save question
     *
     * @return  string
     */
	function save_question(){
        $question = $_POST['q'];
        switch ($question['question_type']) {
            case '1':
                $question['question_answer'] = $_POST['question_answer_op'];
                break;
            case '2':
                $question['question_answer'] = $_POST['question_answer_single'];
                break;
            case '3':
                $question['question_answer'] = implode(',', $_POST['question_answer_multi']);
                break;
        }
        $question['question_subject'] = implode(',', $question['question_subject']);
        $question['question_car'] = implode(',', $question['question_car']);
        $question['modify_time'] = time();

        if(empty($_POST['qid'])){
            $question['create_time'] = time();
            $this->db->insert('t_question',$question);
        }
        else{
            $this->db->where('id',$_POST['qid'])->update('t_question',$question);
        }
        echo SUCCESS;
	}

    /**
     * question_detail
     *
     * @param   int  $qid  question id
     * @return  array|null
     */
    function question_detail($qid=NULL){
        if(!empty($qid)){
            $sql = 'select * from t_question where id = ?';
            return $this->db->query($sql,array($qid))->row_array();
        }
        else return NULL;
    }

    /**
     * check if exists
     *
     * @param   int  $qid  id
     * @return  boolean
     */
    function question_exists($qid){
        $sql = 'select id from t_question where id = ?';
        $row = $this->db->query($sql,array($qid))->row();
        return empty($row)?TRUE:FALSE;
    }

	/**
	 * upload by lly
	 *
	 * @param   string   $folder      folder name
	 * @param   string   $fname       file name
	 * @param   boolean  $folder_new  folder is new
	 * @param   boolean  $fname_new   filename is new
	 * @return  json_string
	 */
	function upload($folder=NULL, $fname=NULL, $folder_new=FALSE, $fname_new=FALSE){
        $path = "public/upload/{$folder}/";
        $folder_new && $path = $folder;
        file_exists($path) OR mkdir($path);
		        
        if (isset($_FILES['file'])) { 
            $name = $_FILES['file']['name']; 
            $size = $_FILES['file']['size']; 
            $name_tmp = $_FILES['file']['tmp_name']; 
            if (empty($name)) { 
                echo json_encode(array("code"=>404,"msg"=>"您还未选择图片")); 
                exit; 
            } 
            $type = strtolower(substr(strrchr($name, '.'), 1));
             
            $pic_name = date('YmdHis',time()) . rand(10000, 99999) . "." . $type;
            if(!empty($fname)){
                $pic_name = $fname. "." . $type;
                $fname_new && $pic_name = $fname;
            }
            $pic_url = $path . $pic_name;

            if (move_uploaded_file($name_tmp, $pic_url)) {
                echo json_encode(array("code"=>"200","pic"=>$pic_url,"name"=>$pic_name,"type"=>$type)); 
            }
            else{ 
                echo json_encode(array('code'=>400,"msg"=>"上传有误，清检查服务器配置")); 
            } 
	    }
	}

    function delete_question($qid){
        $this->db->where('id',$qid)->update('t_question',array('status'=>0));
        echo SUCCESS;
    }

    /**
     * question list
     *
     * @return  array
     */
    function question_list(){
        $prefix_normal = 'select t.* ';
        $prefix_count = 'select count(t.id) nums ';
        $sql = ' from t_question t where 1=1 ';

        $data = $rt= array();
        if(!empty($_POST['qcontent']) && $content = $_POST['qcontent']){
            $sql.= ' and  t.question_content like ? ';
            $data[] = "%$content%";
            $rt['qcontent'] = $content;
        }
        if(!empty($_POST['qid']) && $qid = $_POST['qid']){
            $sql.= ' and  t.id = ? ';
            $data[] = $qid;
            $rt['qid'] = $qid;
        }
        if(!empty($_POST['qsubject']) && $qsubject = $_POST['qsubject']){
            $sql.= ' and  t.question_subject like ? ';
            $data[] = "%$qsubject%";
            $rt['qsubject'] = $qsubject;
        }

        $total = $this->db->query($prefix_count.$sql,$data)->row()->nums;

        $now_page = empty($_POST['now_page'])?1:$_POST['now_page'];
        isset($_POST['requery']) && $_POST['requery']=='yes' && $now_page = 1; # when requery then repage

        $end = ' order by t.id limit ?,?';
        $data = array_merge($data, array(($now_page-1)*PER_PAGE,PER_PAGE));
        $rt['totalPage'] = ceil($total/PER_PAGE);
        $rt['now_page'] = $now_page;

        $query = $this->db->query($prefix_normal.$sql.$end,$data);
        $rt['list'] = $query->result();
        return $rt;
    }
    
    /**
     * question array of submenu
     *
     * @param   int  $subject
     * @return  array
     */
    function question_opt($subject){
        $sql = 'select question_content,id from t_question where question_subject like ? and status =1 order by id';
        return $this->db->query($sql,array("%$subject%"))->result();
    }

}