<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config	=	array(
	'login'	=>	array(
		array(
			'field'	=>	'username',
			'label'	=>	'lang:register_username',
			'rules'	=>	'trim|required',
		),
		array(
			'field'	=>	'password',
			'label'	=>	'lang:register_password',
			'rules'	=>	'required|callback_login_validation',
		),
		array(
			'field'	=>	'login',
			'label'	=>	'',
			'rules'	=>	'required',
		),
	),
	'register'	=>	array(
		array(
			'field'	=>	'username',
			'label'	=>	'lang:register_username',
			'rules'	=>	'trim|required',
		),
		array(
			'field'	=>	'password',
			'label'	=>	'lang:register_password',
			'rules'	=>	'required|regex_match[/^.{6,}$/]',
		),
		array(
			'field'	=>	'password_confirm',
			'label'	=>	'lang:register_password_confirm',
			'rules'	=>	'required|matches[password]',
		),
	),
	'review'	=>	array(
		array(
			'field'	=>	'author',
			'label'	=>	'lang:register_author',
			'rules'	=>	'trim|required|xss_clean',
		),
		array(
			'field'	=>	'type',
			'label'	=>	'lang:register_type',
			'rules'	=>	'required',
		),
		array(
			'field'	=>	'text',
			'label'	=>	'lang:register_text',
			'rules'	=>	'trim|required|xss_clean',
		),
	),
	'user'	=>	array(
		array(
			'field'	=>	'name',
			'label'	=>	'lang:register_name',
			'rules'	=>	'trim|required|xss_clean',
		),
	),
);