<?php defined('BASEPATH') OR exit('No direct script access allowed');

# --是否登陆(exclude)
$config['special'] = array('nav/wel','nav/login_frame','nav/tc_login_frame', 'nav/login', 'nav/logout','nav/getCode','user/get_residue_num');


$config['admin']['menu'] = array('add_menu','save_menu','save_menu_child','delete_menu','delete_menu_child','add_menu_child','list_menu','list_menu_child','exists');
$config['admin']['question'] = array('add_question','list_question','save_question','delete_question','question_exists','upload','question_opt');
$config['admin']['nav'] = array('admin');
$config['admin']['user'] = array('user_list','setting','save_setting','add_user','save_user','exists','pwd','check_old_pwd','save_pwd','delete_user');

$config['visitor']['nav'] = array('index','getCode');
$config['visitor']['user'] = array('get_residue_num');
$config['visitor']['exam'] = array('show_sub','test','simulate','load_question_json','sub_test','wrong','save_wrong','clear_wrong');