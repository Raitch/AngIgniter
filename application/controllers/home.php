<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	function index()
	{
		$arr_set	=	array(
			'greeting'	=>	'Tja!',
			'items'			=>	array(
				array(
					'name'	=>	'lul',
				),
				array(
					'name'	=>	'lawl',
				),
			),
		);
		
		$this->angularjs->set_data($arr_set, get_class($this), 'main');
		
		$this->general->view->load_index('home');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */