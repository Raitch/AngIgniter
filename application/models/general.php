<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Used for Model scalability */
class General extends CI_Model {
	
	var $autoload	=	array('view', 'db');
	
	var $loaded		=	array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load($this->autoload);
	}
	
	function load($mix)
	{
		$arr_load			=	array();
		$boo_is_array		=	is_array($mix);
		$boo_return_class	=	! $boo_is_array;
		if ($boo_is_array)
			$arr_load	=	$mix;
		else
			$arr_load[]	=	$mix;
		
		foreach ($arr_load as $int_key => $str_class) {
			
			$str_lower	=	strtolower($str_class);
			$str_name	=	ucfirst($str_class);
			
			$str_file	=	APPPATH . "models/general/{$str_lower}.php";
			
			$boo_success	=	FALSE;
			if (file_exists($str_file))
				if ( ! in_array($str_lower, $this->loaded)) {
					
					require_once $str_file;
					
					$this->$str_lower	=	new $str_name($this);
					$boo_success		=	TRUE;
					
				}
			
			if ( ! $boo_success)
				unset($arr_load[$int_key]);
			
		}
		
		if ($boo_return_class)
			return $this->$str_lower;
		
		return (bool) count($arr_load);
	}
	
}
