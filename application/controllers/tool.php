<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tool extends MY_Controller {
	
	function migrate($str_bool = '')
	{
		$this->load->library('migration');
		
		require_once APPPATH . 'config/local_settings.php';
		/* $arr_settings */
		
		if ( ! $this->input->is_cli_request() and ! empty($arr_settings['db_password']))
			die('Execute via command line: php index.php tool/migrate');
		
		if (strtoupper($str_bool) == 'TRUE')
			$bool	=	$this->migration->current();
		else $bool	=	$this->migration->latest();	
		
		if( ! $bool)
			show_error($this->migration->error_string());
	}
	
	function minify($str_method = NULL)
	{
		$this->load->library('custom_minify');
		
		$this->custom_minify->process($str_method);
	}
	
}