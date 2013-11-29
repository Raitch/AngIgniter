<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	protected function _translate_fieldname($fieldname)
	{
		$preffix	=	'lang:';
		if (substr($fieldname, 0, strlen($preffix)) == $preffix) {

			$line		=	substr($fieldname, strlen($preffix));

			$fieldname	=	$this->CI->lang->line($line);
			if ($fieldname === FALSE)
				return $line;

		}

		return trim($fieldname);
	}

	function error_list()
	{
		$arr_error	=	array();
		foreach ($this->_field_data as $str_field => $arr_value)
			if ( ! empty($arr_value['error']))
				$arr_error[$str_field]	=	$arr_value['error'];
		
		return $arr_error;
	}

}