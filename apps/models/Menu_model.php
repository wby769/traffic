<?php defined('BASEPATH') OR exit('No direct script access allowed');
 /**
  * menu model
  * @author lly
  */
class Menu_model extends CI_Model{
	/**
	 * fmenu
	 *
	 * @return  string
	 */
	function save_menu(){
		$data = array(
			'name'=>$_POST['name'],
			'subject'=>$_POST['subject'],
			'car_type'=>implode(',', $_POST['car_type']),
			'modify_time'=>time()
			);
        if(empty($_POST['id'])){
        	$data['create_time'] = time();
            $this->db->insert('t_menu',$data);
        }
        else{
        	$data['status'] = $_POST['status'];
            $this->db->where('id',$_POST['id'])->update('t_menu',$data);
        }
        echo SUCCESS;
	}

	/**
	 * child_menu
	 *
	 * @return  string
	 */
	function save_menu_child(){
		$data = array(
			'name'=>$_POST['name'],
			'fid'=>$_POST['fid'],
			'modify_time'=>time()
			);
        if(empty($_POST['id'])){
        	$data['create_time'] = time();
            $this->db->insert('t_submenu',$data);
            $id = $this->db->insert_id();
        }
        else{
        	$data['status'] = 0;
        	isset($_POST['status']) && $_POST['status']=='on' && $data['status']=1;
            $this->db->where('id',$_POST['id'])->update('t_submenu',$data);
            $id = $_POST['id'];
        }
        $this->db->where('submenu_id',$id)->delete('t_submenu_question');
        if(isset($_POST['qid'])){
        	$datas = array();
        	foreach ($_POST['qid'] as $li) {
        		$datas[] = array(
        			'submenu_id'=>$id,
        			'question_id'=>$li,
        			);
        	}
        	$datas && $this->db->insert_batch('t_submenu_question',$datas);
        }
        echo SUCCESS;
	}

	/**
	 * the question_ids of submenu
	 *
	 * @param   int  $id  submenu_id
	 * @return  array
	 */
	function submenu_question_opt($id,$return_str=FALSE){
		if(empty($id)){
			$arr = array();
		}
		else{
			$sql = 'select question_id from t_submenu_question where submenu_id=?';
			$arr =  $this->db->query($sql,array($id))->result();
		}
		return $return_str?$this->arr2str_helper($arr):$arr;
		
	}
	private function arr2str_helper($arr){
		$list = array();
		foreach($arr as $key => $li){
			$list[] = $li->question_id;
		}
		return implode(',',$list);
	}

	/**
	 * menu list
	 *
	 * @param   boolean  $is_child
	 * @return  array
	 */
	function list_menu($is_child=FALSE){
		$prefix_normal = 'select m.* ';
		$prefix_count = 'select count(1) nums ';
		$sql = ' from t_menu m where 1=1 ';
		$data = array();
		if($is_child){
			$prefix_normal = 'select m.name mname,m.subject, s.name name ,s.create_time,s.status,s.id ';
			$sql = ' from t_submenu s join t_menu m on m.id = s.fid where 1=1 ';
			if(!empty($_POST['name']) && $name = $_POST['name']){
				$sql.=' and s.name like ?';
				$data[] = "%$name%";
				$rt['name'] = $name;
			}
			if(!empty($_POST['qsubject']) && $qsubject = $_POST['qsubject']){
				$sql.=' and m.subject=?';
				$data[] = $qsubject;
				$rt['qsubject'] = $qsubject;
			}
		}
		$end = ' order by m.id limit ?,?';
		$nums = $this->db->query($prefix_count.$sql,$data)->row()->nums;

		$now_page = empty($_POST['now_page'])?1:$_POST['now_page'];
		isset($_POST['requery']) && $_POST['requery']=='yes' && $now_page =1;

		$data = array_merge($data,array(($now_page-1)*PER_PAGE,PER_PAGE));
		$rt['list'] = $this->db->query($prefix_normal.$sql.$end,$data)->result();
		$rt['totalPage'] = ceil($nums/PER_PAGE);
		$rt['now_page'] = $now_page;
		return $rt;
	}

	// show
	function show_menu($subject,$car_type){
		$sql = 'select m.id, m.name from t_menu m where m.subject=? and m.status=1 and m.car_type like ?';
		$rt['list'] = $this->db->query($sql,array($subject,"%$car_type%"))->result();
		return $rt;
	}
	function show_submenu($fid){
		$sql = 'select m.id, m.name from t_submenu m where m.fid=? and m.status =1';
		$rt['list'] = $this->db->query($sql,array($fid))->result();
		return $rt;
	}

	/**
	 * detail
	 *
	 * @param   string  $table
	 * @param   int  $id
	 * @return  array|null	
	 */
	function common_detail($table, $id=NULL){
	    if(!empty($id)){
	        $sql = "select * from $table where id = ?";
	        return $this->db->query($sql,array($id))->row_array();
	    }
	    else return NULL;
	}

	function get_menuname($id){
		return $this->db->query('select name from t_menu where id=?',array($id))->row()->name;
	}

	/**
	 * delete
	 *
	 * @param   int  $id
	 * @return  string
	 */
	function delete_menu($id){
	    $this->db->where('id',$id)->update('t_menu',array('status'=>0));
	    echo SUCCESS;
	}
	function delete_menu_child($id){
	    $this->db->where('id',$id)->update('t_submenu',array('status'=>0));
	    echo SUCCESS;
	}

	/**
	 * check if exists
	 *
	 * @param   strign  $table
	 * @param   string  $name
	 * @return  boolean
	 */
    function common_exists($table, $name, $fid=NULL){
        $sql = "select id from $table where name = ? and 1=1 ";
        $data = array($name);
        $fid && ($sql .= ' and fid = ?') && $data[] = $fid;
        $row = $this->db->query($sql,$data)->row();
        return empty($row)?TRUE:FALSE;
    }

    /**
     * menu options
     *
     * @return  array
     */
	function menu_opt(){
		$sql = 'select * from t_menu where status =1 ';
		$rt['opt'] = $this->db->query($sql)->result();
		return $rt;
	}
}