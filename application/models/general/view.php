<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Model {
	
	protected $dir_return		=	'return';
	
	protected $default_index	=	'index';
	
	protected $class_active		=	'active';
	
	function __construct(&$obj_general)
	{
		parent::__construct();
		
		$this->load->helper(array('url', 'general'));
	}
	
	function class_active($str_href, $str_class = NULL)
	{
		$str_class		=	($str_class ?: $this->class_active);
		
		$str_href		=	trim($str_href, '/');
		$str_href		=	site_url($str_href);
		
		$str_url		=	current_url();
		$str_shorten	=	substr($str_url, 0, strlen($str_href));
		
		$boo_got_method	=	preg_match("~{$this->router->method}(/[\w-_]+)*/?$~", $str_href);
		
		$bool	=	FALSE;
		if ($str_href == $str_shorten) {
			
			$bool	=	(bool) rtrim(str_replace(site_url($this->router->directory), '', "{$str_href}/"), '/');
			
			if ( ! $bool)
				$bool	=	($this->router->class == $this->router->default_controller);
			
		} elseif (site_url() == current_url())
			$bool	=	($this->router->class == $this->router->default_controller && $boo_got_method);
		
		if ($bool)
			return $str_class;
	}
	
	function get_js_lang()
	{
		$arr_lang	=	array(	'base_url'	=>	base_url());
		
		foreach($this->lang->language as $str_key => $str_line)
			if (preg_match("/^js_/", $str_key)) {
				
				$str_noffix	=	substr($str_key, 3);
				
				$arr_lang[$str_noffix]	=	t($str_line);
				
			}
		
		return $arr_lang;
	}
	
	function get_menu()
	{
		$arr_menu	=	array(
			array(
				'href'	=>	'',
				'name'	=>	t('view_menu_home'),
			),
			array(
				'href'	=>	'review',
				'name'	=>	t('view_menu_review'),
			),
			array(
				'href'	=>	'user',
				'name'	=>	t('view_menu_user'),
			),
			array(
				'href'	=>	'user/register',
				'name'	=>	t('view_menu_user_register'),
			),
		);
		
		foreach ($arr_menu as $int_key => $arr) {
			
			$arr_menu[$int_key]['href']		=	site_url($arr['href']);
			$arr_menu[$int_key]['class']	=	$this->class_active($arr['href']);
			
		}
		
		return $arr_menu;
	}
	
	function load_block($str_type, $str_current = NULL)
	{
		switch ($str_type) {
			case 'content':
				
				$str_preffix		=	APPPATH . 'views/';
				$str_view_dir		=	'block/content/';

				$str_js_content	=	FCPATH . 'assets/script/angular/controller/content/';

				if ($this->angularjs->is_robot()) {

					$this->angularjs->ctrl(ucfirst($str_current));

					$this->load->view($str_view_dir . $str_current);

					$this->angularjs->end('ctrl');

				} else {
					
					$str_path		=	"{$str_preffix}{$str_view_dir}*.php";
					$arr_files	=	glob($str_path);
					
					foreach ($arr_files as $str_file) {

						$str_file_name	=	substr($str_file, strlen($str_preffix . $str_view_dir), -strlen('.php'));
						$str_class			=	ucfirst($str_file_name);

						$arr_data		=	array(
							'str_class'	=>	$str_class,
							'str_view'	=>	substr($str_file, strlen($str_preffix), -strlen('.php')),
						);

						$this->load->view('block/content', $arr_data);
						
					}

				}
				
				break;
			case 'content_script':

				$str_view_glob	=	APPPATH . 'views/block/content/';
				$str_js_content	=	FCPATH . 'assets/script/angular/controller/content/';

				$arr_files			=	glob("{$str_view_glob}*.php");

				$arr_classes		=	array();
				foreach ($arr_files as $str_file) {

					$str_file_name	=	substr($str_file, strlen($str_view_glob), -strlen('.php'));
					$str_class			=	ucfirst($str_file_name);

					if ( ! file_exists("{$str_js_content}{$str_file_name}.js"))
							$arr_classes[]	=	$str_class;

				}

				$arr_data	=	array(
					'arr_classes'	=>	$arr_classes,
				);

				$this->load->view('block/content_script', $arr_data);

				break;
		}
	}
	
	function load_index($str_view, $arr_data = array(), $str_index = NULL)
	{
		/* Menu */
		$arr_set	=	array(	'items'	=>	$this->get_menu());
		
		$this->angularjs->set_data($arr_set, 'Menu');
		
		/* User */
		$arr_set	=	array(
			'id'				=>	get_profile('id'),
			'username'	=>	get_profile('username'),
			'name'			=>	get_profile('name'),
		);

		$this->angularjs->set_data($arr_set, 'user', 'global');

		/* Init Vars */
		if ( ! $this->angularjs->is_request()) {

			$arr_set	=	array(
				'base_url'	=>	base_url(),
			);

			$this->angularjs->set_data($arr_set, 'init', 'global');		

		}

		/* Error Validation */
		$arr_errors	=	(object) array();
		if (class_exists('MY_Form_validation'))
			$arr_errors	=	$this->form_validation->error_list();

		$this->angularjs->set_data($arr_errors, 'errors', 'global');

		/* Index file */
		$str_index	=	($str_index ?: $this->default_index);
		
		/* Meta Info */
		$arr_data	+=	array(
			'str_index_title'	=>	t('index_default_title'),
			'str_ang_data'		=>	$this->angularjs->get_data(),
		);
		
		/* Javascript Language */
		$arr_data['str_index_lang_js']	=	json_encode($this->get_js_lang());
		
		/* Load Correct Content */
		$arr_data['str_index_content']	=	$str_view;
		
		if ($this->angularjs->is_request())
			$this->angularjs->output_data();
		else
			$this->load->view($str_index, $arr_data);
	}
	
	function load_return($str_view, $arr_data = array())
	{
		return $this->load->view("{$this->dir_return}/{$str_view}", $arr_data, TRUE);
	}
	
}