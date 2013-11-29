<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Angularjs_message extends CI_Driver {

	protected $view_file		=	'item/message';
	protected $ctrl_name		=	'ItemMessage';

	protected $CI;

	function __construct()
	{
		$this->CI	=&	get_instance();
	}

	function load_view()
	{
		$arr_data	=	array(
			'str_ctrl'	=>	$this->ctrl_name,
		);

		$this->CI->load->view($this->view_file, $arr_data);
	}

	function set($str)
	{
		$arr_set	=	array(
			'message'	=>	$str,
			'hide'		=>	FALSE,
		);

		$this->set_data($arr_set, $this->ctrl_name, 'global');
	}

}