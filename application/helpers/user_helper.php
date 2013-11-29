<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_profile($mix)
{
	$CI	=&	get_instance();
	
	$CI->load->library('login');
	$CI->load->model('user_model');

	$arr_user	=	$CI->session->userdata($CI->login->config('login_session'));
	if (empty($arr_user['id']))
		return NULL;

	$boo_force	=	($mix === TRUE);
	$obj				=	$CI->user_model->get_profile($arr_user['id'], $boo_force);

	if ($boo_force)
		return;

	$str_key	=	$mix;

	if (isset($obj->$str_key))
		return $obj->$str_key;
	else
		show_error("Missing key value: {$str_key}");
}