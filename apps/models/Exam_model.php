<?php defined('BASEPATH') OR exit('No direct script access allowed');
 /**
  * Exam model
  * @author lly
  */
class Exam_model extends CI_Model{
	/**
	 * 生成试题
	 *
	 * @param   int   $mid         菜单ID
	 * @param   boolean  $is_submenu  是否子菜单
	 * @param   boolean  $is_full     是否100题
	 * @param   boolean  $is_rand     是否随机
	 * @return  array
	 */
	function get_qids($mid, $is_submenu=TRUE, $is_full=FALSE, $is_rand=FALSE){
		$sql = 'SELECT distinct(s.question_id) FROM t_submenu_question s JOIN t_question q ON q.id = s.question_id WHERE 1=1 ';
		$ids = implode(',', explode('-', $mid));
		$sql.=$is_submenu?" AND s.submenu_id in($ids) ":' AND s.submenu_id in (SELECT id FROM t_submenu WHERE fid = ?)';
		$data=$is_submenu?array():array($mid);
		$sql.=' AND q.status=1 AND q.question_subject like ? and q.question_car like ? ';
		$sql.= ' ORDER BY ';
		$sql.=$is_rand?' rand() ':' question_id ';
		$is_full or $sql.= ' LIMIT 100';
		$user = $this->session->user;
		return $this->db->query($sql,array_merge($data,array("%{$user->subject}%", "%{$user->car}%")))->result_array();
	}

	// 仿真考试
	function get_simulate_qids($question_type=NULL, $limit=NULL){
		$sql = 'SELECT q.id question_id FROM t_question q WHERE q.status=1 and q.question_subject like ? and q.question_car like ? ';
		$data = array("%{$this->session->user->subject}%","%{$this->session->user->car}%");
		if(!empty($question_type)){
			$sql.=' AND q.question_type = ? ';
			$data[] = $question_type;
		}
		$sql.=' ORDER BY rand() LIMIT '.(empty($limit)?100:$limit);
		return $this->db->query($sql,$data)->result_array();
	}

	// 清空错题
	function clear_wrong($uid){
		$this->db->where('user_id',$uid)->delete('t_question_wrong');
		echo SUCCESS;
	}

	// 我的错题
	function wrong_question($uid){
		$sql = 'SELECT distinct(s.question_id) FROM t_question_wrong s WHERE s.user_id=? ORDER BY s.question_id';
		return $this->db->query($sql,array($uid))->result_array();
	}

	// 保存错题
	function save_wrong($uid, $qids){
		$data = array();
		foreach ($qids as $li) {
			$data[] = array(
				'user_id'=>$uid,
				'question_id'=>$li
				);
		}
		empty($data) OR $this->db->insert_batch('t_question_wrong',$data);
		echo '{"state":"success"}';
	}

	// 本次测试时间
	function test_time($uid){
		$this->db->where('id',$uid)->update('t_user',array('last_time'=>time()));
	}
	// 上次URL
	function test_url($uid, $url){
		$this->db->where('id',$uid)->update('t_user',array('last_url'=>$url));
	}
	
}