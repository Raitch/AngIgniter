<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	
	function index()
	{
		$this->require_login();

		$this->load->library('form_validation');
		$this->load->model('user_model');
		
		if ($this->angularjs->is_post() && $this->form_validation->run('user')) {

			$arr_values				=	$this->input->post();
			$arr_values['id']	=	get_profile('id');

			$int_id	=	$this->user_model->edit($arr_values);

			get_profile(TRUE);

			$this->angularjs->message->set(t('user_index_updated', get_profile('name')));

		}

		$arr_set	=	array(
			'name'	=>	set_value('name', get_profile('name')),
		);
		
		$this->angularjs->set_data($arr_set, get_class($this), 'main');
		
		$this->general->view->load_index('user');
	}
	
	function logout()
	{
		$this->login->logout();
		
		$this->angularjs->save_previous_url();

		$this->require_login();
	}
	
	function register()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');

		if ($this->angularjs->is_post() && $this->form_validation->run('register')) {

			$int_id	=	$this->user_model->add($this->input->post());

			$this->login->set_session($int_id);

			$this->angularjs->redirect(site_url('user'));

		}

		$arr_set	=	array(
			'title'	=>	'=P',
		);
		
		$this->angularjs->set_data($arr_set, get_class($this) . '_register', 'main');

		$this->general->view->load_index('user_register');	
	}

}