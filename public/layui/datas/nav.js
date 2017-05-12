var navs = [
{
	"title": "个人中心",
	"icon": "fa-cog",
	"spread": false,
	"children": [
		{
			"title": "密码修改",
			"href": "user/pwd"
		},
	]
},
{
	"title": "题库管理",
	"icon": "fa-file-text",
	"spread": false,
	"children": [
	{
		"title": "录入题目",
		"href": "question/add_question"
	},
	{
		"title": "题目列表",
		"href": "question/list_question"
	}, 
	]
},
{
	"title": "用户管理",
	"href": "user/user_list",
	"icon": "fa-user",
	"spread": false,
	"children": []
},
{
	"title": "菜单管理",
	"icon": "fa-bars",
	"spread": false,
	"children": [
		{
			"title": "父菜单管理",
			"spread": false,
			"href": "menu/list_menu",
			"children": []
		},
		{
			"title": "子菜单管理",
			"href": "menu/list_menu_child",
			"spread": false,
			"children": []
		},
	]
}
];