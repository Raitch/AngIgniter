<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ang_attr()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs, 'attr');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_ctrl()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs, 'ctrl');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_end()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs, 'end');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_form_dropdown()
{
	$CI	=&	get_instance();

	$arr_func	=	array($CI->angularjs->form, 'dropdown');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_form_multiselect()
{
	$CI	=&	get_instance();

	$arr_func	=	array($CI->angularjs->form, 'multiselect');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_form_error($str_name)
{
	$CI	=&	get_instance();

	if ( ! $CI->angularjs->is_robot())
		return '<div class="error" ng-show="errors.' . $str_name . '">{{errors.' . $str_name . '}}</div>';
}

function ang_form_id()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs->form, 'id');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_form_label()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs->form, 'label');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_form_validate()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs->form, 'validate');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_repeat()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs, 'repeat');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}

function ang_value()
{
	$CI	=&	get_instance();
	
	$arr_func	=	array($CI->angularjs, 'value');
	$arr_args	=	func_get_args();
	
	return call_user_func_array($arr_func, $arr_args);
}