<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	var $set_language	=	'english';
	
	function __construct()
	{
		parent::__construct();

		$this->load->driver('angularjs');
		$this->lang->load('general', $this->set_language);

		if (ENVIRONMENT == 'development' && ! $this->angularjs->is_request()) {

			$this->load->library('custom_minify');

			$this->custom_minify->process();

		}
 	}
	
	function require_login()
	{
		$boo_logged_id	=	$this->login->logged_in();
		
		if ($this->login->form_required()) {
			
			$arr_set	=	array(
				'require_login'	=>	TRUE,
			);
			
			$this->angularjs->set_data($arr_set, 'Login', 'main');
			
			$this->general->view->load_index($this->login->config('view'));
			
			$this->output->_display();
			
			exit;
			
		} elseif ( ! $boo_logged_id)
			$this->angularjs->redirect(current_url());
	}
	
	function login_validation($str_password)
	{
		$this->load->model('user_model');
		$this->load->library('login');
		
		$str_username	=	$this->input->post($this->login->config('post_username'));
		
		$row	=	$this->user_model->login($str_username, $str_password);
		
		if ($row)
			$this->login->set_session($row->id);
		
		return (bool) $row;
	}
	
}