create database jgll default character set utf8;
use jgll;
DROP TABLE IF EXISTS t_question;
create table t_question(
	id int primary key,
	question_subject varchar(10) comment '科目 config',
	question_car varchar(20) comment '车型 config',
	question_type int comment '1判断 2单选 3多选',
	question_content varchar(500),
	question_explain varchar(500) comment '讲解',
	question_answer varchar(10) comment '答案',
	opt_a varchar(100),
	opt_b varchar(100),
	opt_c varchar(100),
	opt_d varchar(100),
	img_url varchar(100) comment '题目图片',
	audio_url varchar(100) comment '语言讲解',
	status int default 1 comment '1有效 2停用',
	create_time int,
	modify_time int
)ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS t_user;
create table t_user(
	id int primary key auto_increment,
	username varchar(50),
	password varchar(50),
	relname varchar(20),
	telephone varchar(11),
	idcard varchar(50),
	subject int comment 'config',
	car int comment '车型 config',
	type int default 1 comment '1普通用户 2后台',
	expire_time int,
	residue_num int,
	last_time int,
	last_url varchar(200) comment '继续上次',
	last_sess_id varchar(200),
	referrer_id int default 0,
	referrer varchar(200),
	sex int comment 'config',
	status int default 1 comment '1未审核 2有效 4停用',
	create_time int,
	modify_time int
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
insert into t_user(username,password,type,status) values('admin',MD5(123),2,2);


DROP TABLE IF EXISTS t_menu;
create table t_menu(
	id int primary key auto_increment,
	name varchar(50),
	subject int comment 'config',
	status int default 1 comment '1有效 2停用',
	car_type varchar(20) comment '车型 config',
	create_time int,
	modify_time int
)ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS t_submenu;
create table t_submenu(
	id int primary key auto_increment,
	fid int,
	name varchar(50),
	status int default 1 comment '1有效 2停用',
	create_time int,
	modify_time int
)ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS t_submenu_question;
create table t_submenu_question(
	submenu_id int,
	question_id int
)ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS t_question_wrong;
create table t_question_wrong(
	user_id int,
	question_id int
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

