<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('review_model');
	}
	
	function index()
	{
		$this->load->library('form_validation');

		$arr_set	=	array(
			'type'	=>	'COOL',
		);
		$arr_set	=	$this->angularjs->form->set_values('review', $arr_set);

		$arr_type	=	array(
			'Type'	=>	array(
				'REGULAR'	=>	'Reggie',
				'COOL'		=>	'Coolie',
			),
		);

		$arr_set['types']	=	$this->angularjs->form->process('dropdown', $arr_type);
		
		if ($this->angularjs->is_post() && $this->form_validation->run('review')) {

			$this->review_model->add($this->input->post());

			$this->angularjs->redirect(current_url());

		}
		
		$int_uri_seg	=	3;
		$int_per_page	=	4;
		$int_offset		=	$this->angularjs->pagination->offset($int_uri_seg, $int_per_page);

		$arr_result	=	$this->review_model->get($int_per_page, $int_offset);
		foreach ($arr_result as $int_key => $row)
			$arr_result[$int_key]->type	=	t("review_index_type_{$row->type}");

		$arr_set['items']['list']	=	$arr_result;

		$arr_setup	=	array(
			'total_rows'	=>	$this->general->db->found_rows(),
			'per_page'		=>	$int_per_page,
			'uri_segment'	=>	$int_uri_seg,
			'base_url'		=>	site_url('review/index'),
		);

		$this->angularjs->pagination->setup($arr_setup);

		$this->angularjs->set_data($arr_set, get_class($this), 'main');
		
		$this->general->view->load_index('review');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */