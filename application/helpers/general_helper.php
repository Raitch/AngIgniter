<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Short for potential value and made to skip using @ */
function ptn($mix, $str_extra = NULL)
{
	if (is_object($mix) and $str_extra) {
		
		if (isset($mix->$str_extra))
			return $mix->$str_extra;
		
	} elseif (is_array($mix) and $str_extra) {
		
		if (isset($mix[$str_extra]))
			return $mix[$str_extra];
		
	} elseif (preg_match("/^\\$[\w_\[\]\"]+$/", (string)$mix)) {
		
		$str	=	NULL;
		/* For depth given array. Parse in quotes. */
		eval("\$str = (isset({$mix}) ? {$mix} : NULL);");
		
		return $str;
		
	}
	
	return NULL;
}

/* Fix for failure upon form_validation rules */
if ( ! function_exists('set_value')) {
	
	function set_value($field = '', $default = '') {
		
		$obj =& _get_validation_object();
		
		if ($obj === TRUE && isset($obj->_field_data[$field]))
			return form_prep($obj->set_value($field, $default));
		elseif ( ! isset($_POST[$field]))
			return $default;
		
		return form_prep($_POST[$field]);
		
	}
	
}

function t($str)
{
	$CI	=&	get_instance();
	
	if (isset($CI->lang->language[$str])) {
		
		$str_line	=	$CI->lang->line($str);
		if ($str_line !== FALSE)
			$str	=	$str_line;
		
	}
	
	$arr_args		=	func_get_args();
	$arr_args[0]	=	$str;
	
	if (count($arr_args) > 1)
		$str	=	call_user_func_array('sprintf', $arr_args);
	
	return trim($str);
}

/* Get plural or singular language; for pure pickyness */
function tnum($int_amount, $str_line = 'amount')
{
	$CI	=&	get_instance();
	
	if (preg_match("/percent/", $str_line))
		$str_amount	=	number_format((int)$int_amount, 2, '.', ' ');
	else
		$str_amount	=	number_format((int)$int_amount, 0, '', ' ');
	
	$str		=	t($str_line, $str_amount);
	
	if ($int_amount == 1) {
		
		$str_one	=	str_replace('amount', 'one', $str_line);
		if (isset($CI->lang->language[$str_one]))
			$str	=	t($str_one, $str_amount);
		
	}
	
	return $str;
}