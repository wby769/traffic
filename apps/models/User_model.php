<?php defined('BASEPATH') OR exit('No direct script access allowed');
 /**
  * User
  * @author lly
  */
class User_model extends CI_Model{
	/**
	 * expired session file
	 *
	 * @return  void
	 */
	function clear_expire_tmp($ignore_file){
		$dir = config_item('sess_save_path');
		if(is_dir($dir)){
			foreach (scandir($dir) as $key => $li){
				$file = $dir.'/'.$li;
				if(is_file($file) && $li!=$ignore_file && filectime($file) < time()) unlink($file);
			}
		}
	}

	/**
	 * get user's residue num
	 *
	 * @return  int
	 */
	function get_residue_info($uname){
		$sql = 'select residue_num,from_unixtime(expire_time,"%Y-%m-%d") expire_time from t_user where username = ? and type = 1';
		$row = $this->db->query($sql,array($uname))->row();
		return empty($row)?NULL:$row;
	}

	// edge out
	private function drop_login($file){
		file_exists($file) && unlink($file);
	}

	function save_user(){
		$data = array(
			'username'=>$_POST['username'],
			'relname'=>$_POST['relname'],
			'telephone'=>$_POST['telephone'],
			'idcard'=>$_POST['idcard'],
			'subject'=>$_POST['subject'],
			'car'=>$_POST['car'],
			'expire_time'=> strtotime($_POST['expire_time']),
			'residue_num'=>$_POST['residue_num'],
			'sex'=>$_POST['sex'],
			'modify_time'=>time()
			);
		if(empty($_POST['id'])){
			$data['create_time'] = time();
			$data['last_time'] = time();
			$data['password'] = MD5($_POST['password']);
			$data['status'] = 2;
			$this->db->insert('t_user',$data);
		}
		else{
			empty($_POST['repwd']) or $data['password'] = MD5($_POST['password']);
			$this->db->where('id',$_POST['id'])->update('t_user',$data);
		}
		echo SUCCESS;
	}

	/**
	 * setting expire_time or residue_num
	 *
	 * @return  string
	 */
	function save_setting(){
		$data['modify_time'] = time();
		empty($_POST['residue_num']) or	$data['residue_num'] = $_POST['residue_num'];
		empty($_POST['expire_time']) or	$data['expire_time'] = strtotime($_POST['expire_time']);
		empty($_POST['status']) or	$data['status'] = $_POST['status'];
		
		$this->db->where('id',$_POST['id'])->update('t_user',$data);
		echo SUCCESS;
	}
	
	// check
	function user_exists($name){
	    $sql = "select id from t_user where username = ?";
	    $row = $this->db->query($sql,array($name))->row();
	    return empty($row)?TRUE:FALSE;
	}

	// list
	function user_list(){
	    $prefix_normal = 'select u.* ';
	    $prefix_count = 'select count(u.id) nums ';
	    $sql = ' from t_user u where u.type=1 ';

	    $data = $rt= array();
	    if(!empty($_POST['keyword']) && $keyword = $_POST['keyword']){

	        $sql.= ' and  u.username like ? or u.telephone like ? or relname like ?';
	        $data = array("%$keyword%", "%$keyword%", "%$keyword%");
	        $rt['keyword'] = $keyword;
	    }

	    $total = $this->db->query($prefix_count.$sql,$data)->row()->nums;

	    $now_page = empty($_POST['now_page'])?1:$_POST['now_page'];
	    isset($_POST['requery']) && $_POST['requery']=='yes' && $now_page = 1; # when requery then repage

	    $end = ' order by u.id limit ?,?';
	    $data = array_merge($data, array(($now_page-1)*PER_PAGE,PER_PAGE));
	    $rt['totalPage'] = ceil($total/PER_PAGE);
	    $rt['now_page'] = $now_page;

	    $query = $this->db->query($prefix_normal.$sql.$end,$data);
	    $rt['list'] = $query->result();
	    return $rt;
	}
	function user_detail($id=NULL){
	    if(!empty($id)){
	        $sql = 'select * from t_user where id = ?';
	        return $this->db->query($sql,array($id))->row_array();
	    }
	    else return NULL;
	}

	// 校验原始密码
	function check_old_pwd($uid, $pwd){
		$sql = 'select password from t_user where id = ?';
		$password = $this->db->query($sql,array($uid))->row()->password;
		return MD5($pwd)==$password?TRUE:FALSE;
	}

	// 保存密码
	function save_pwd($uid, $pwd){
		$this->db->where('id',$uid)->update('t_user',array('password'=>MD5($pwd)));
		echo SUCCESS;
	}

	// 上次信息
	function last_info($id){
		return $this->db->query('select last_url,last_time from t_user where id =?',array($id))->row();
	}

	# --校验用户账号密码
	function check_user($uname, $pwd, $param_md5=FALSE,$is_return=FALSE,$is_admin=FALSE){
 		$sql = 'SELECT u.* FROM t_user u WHERE u.username = ? AND u.status = 2 AND u.type='.($is_admin?'2':'1');
 		$param_md5 or $pwd = md5($pwd);
 		$query = $this->db->query($sql,array($uname));
 		$row = $query->row();
 		if($row != null && $row->password == $pwd){
 			$data = array(
 				'user'=>$row,
 				'uid'=>$row->id,
 				'utype'=>$row->type,
 				'uname' => $uname,
 				);
 			$url = $row->type==2?'tc':'';
 			$this->session->set_userdata($data);
 			$rt = SUCCESS;
 			if($row->type==1 && isset($_POST['check']) && $_POST['check']=='JSON'){
 				$sign_state = FALSE;
	 			$row->residue_num<=0 && ($rt = 'residue') && $sign_state = TRUE;
	 			$row->expire_time<=time() && ($rt = 'expire') && $sign_state = TRUE;
	 			$sign_state && $this->session->unset_userdata(array('uid','utype','uname'));
	 			$sign_state or $this->login_helper($row->id);
 			}
 		}else{
 			$rt = ERROR;
 		}

 		if(isset($_POST['check']) and $_POST['check']=='JSON'){
 			echo $rt;
 		}
 		elseif($is_return){
 			return $rt==SUCCESS?TRUE:FALSE;
 		}
 		else{
	 		redirect($url);
 		}
 	}

 	private function login_helper($uid){
		$sess_id = session_id();
		$last_sess_id = $this->session->user->last_sess_id;
		$last_sess_file = config_item('sess_save_path').'/'.config_item('sess_cookie_name').$last_sess_id;
		$this->db->where('id',$uid)
 			->set('residue_num','residue_num-1',FALSE)
 			->set('last_time',time())
 			->set('last_sess_id',$sess_id)
 			->update('t_user');
		$sess_id==$last_sess_id or $this->drop_login($last_sess_file);
			
			// clear sess_tmp before today
		if(file_exists(FCPATH.'public/lock')){
			$file = fopen(FCPATH.'public/lock', 'r+');
			$obj = json_decode(fgets($file));
			$today = date('Y-m-d',time());
			if(isset($obj->clearDate) and $obj->clearDate!=$today){
				$this->clear_expire_tmp(config_item('sess_cookie_name').$sess_id);
				fseek($file, 0);
				if(flock($file, LOCK_EX|LOCK_NB)){
					$data = array(
						'clearDate'=>date('Y-m-d',time())
						);
					fwrite($file, json_encode($data));
					flock($file, LOCK_UN);
				}
			}
			fclose($file);
		}
 	}
 	// 真删除
 	function delete_user($id){
 		$this->db->where('id',$id)->delete('t_user');
 		echo SUCCESS;
 	}


}