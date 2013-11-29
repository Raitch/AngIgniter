<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Angularjs_pagination extends CI_Driver {

	protected $cur_page			=	1;
	protected $total_rows		=	0;
	protected $per_page			=	30;
	protected $num_links		=	3;
	protected $uri_segment	=	3;

	protected $base_url;

	protected $lang_preffix	=	'angularjs_pagination_';
	protected $view_file		=	'item/pagination';
	protected $ctrl_name		=	'ItemPagination';

	protected $set					=	array('total_rows', 'per_page', 'uri_segment', 'base_url', 'view_file');

	protected $CI;

	function __construct()
	{
		$this->CI	=&	get_instance();
	}

	function get_list()
	{
		$this->validate();

		$arr_list		=	array();
		$int_total	=	(ceil($this->total_rows / $this->per_page) ?: 1);

		$int_start	=	($this->cur_page - $this->num_links + 1);
		$int_end		=	($this->cur_page + $this->num_links - 1);
		
		$int_start	-=	max(($int_end - $int_total), 0);
		$int_end		-=	min(($int_start - 1), 0);
		
		$int_start	=	max($int_start, 1);
		$int_end		=	min($int_end, $int_total);

		for ($i = $int_start; $int_end >= $i; $i++)
			$arr_list[]	=	array(
				'page'	=>	$i,
				'name'	=>	$this->lang('normal', $i),
			);

		$int_first	=	1;
		$int_prev		=	max(($this->cur_page - 1), 1);
		$int_next		=	min(($this->cur_page + 1), $int_total);
		$int_last		=	$int_total;

		$arr_beginning	=	array(
			array(
				'page'	=>	$int_first,
				'name'	=>	$this->lang('first', $int_first),
			),
			array(
				'page'	=>	$int_prev,
				'name'	=>	$this->lang('prev', $int_prev),
			),
		);

		$arr_end	=	array(
			array(
				'page'	=>	$int_next,
				'name'	=>	$this->lang('next', $int_next),
			),
			array(
				'page'	=>	$int_last,
				'name'	=>	$this->lang('last', $int_last),
			),
		);

		return array_merge($arr_beginning, $arr_list, $arr_end);
	}

	function lang($str_lang, $str_arg = NULL)
	{
		$str	=	$this->CI->lang->line("{$this->lang_preffix}{$str_lang}");
		if ($str && $str_arg)
			$str	=	trim(sprintf($str, $str_arg));

		return $str;
	}

	function load_view()
	{
		$arr_data	=	array(
			'str_ctrl'	=>	$this->ctrl_name,
		);

		$this->CI->load->view($this->view_file, $arr_data);
	}

	function offset($int_seg_val, $int_per_page = NULL)
	{
		if ( ! $int_per_page && ! $this->per_page)
			show_error('Requires `per_page` defined.');

		$int	=		(($this->CI->uri->segment($int_seg_val) ?: 1) - 1);
		$int	*=	($int_per_page ?: $this->per_page);

		return $int;
	}

	function set_parent_data()
	{
		$arr_set	=	array(
			'list'			=>	$this->get_list(),
			'base_url'	=>	$this->base_url,
			'cur_page'	=>	$this->cur_page,
		);

		$this->set_data($arr_set, $this->ctrl_name, 'global');
	}

	function setup($arr_config, $boo_set_data = TRUE)
	{
		foreach ($this->set as $str_config)
			if (isset($arr_config[$str_config]))
				$this->$str_config	=	$arr_config[$str_config];

		$this->base_url	=	rtrim($this->base_url, '/') . '/';

		$int_page	=	(int) $this->CI->uri->segment($this->uri_segment);
		if ($int_page)
			$this->cur_page	=	$int_page;

		if ($boo_set_data)
			$this->set_parent_data();
	}

	function total_rows($int_total)
	{
		$this->set_up(array('total_rows' => $int_total));
	}

	function validate()
	{
		foreach ($this->set as $str_config)
			if ( ! isset($this->$str_config))
				show_error("Missing value for config: {$str_config}");
	}

}