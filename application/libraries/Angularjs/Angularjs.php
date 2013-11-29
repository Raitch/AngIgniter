<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Angularjs extends CI_Driver_Library {
	
	public $valid_drivers	=	array('angularjs_form', 'angularjs_message', 'angularjs_pagination');
	public $current_depth	=	array(
		'ctrl'			=>	array(),
		'validate'	=>	array(),
	);

	protected $CI;
	protected $is_request;
	protected $is_post;

	protected $current_loop		=	array();
	protected $data						=	array();
	protected $global_data		=	array();
	protected $json_data			=	array();
	protected $ng_init_data		=	array();
	protected $ng_init_attr		=	array();
	
	protected $default_post		=	array('angularjs', 'current_url');
	protected $json_post_name	=	'angularjs';
	
	function __construct()
	{
		$this->CI	=&	get_instance();

		$this->CI->load->helper('angularjs');
		
		$this->is_request	=	$this->process_json();
		$this->is_post		=	($this->is_request && (count($_POST) > count($this->default_post)));

		$this->json_data	+=	array(
			'url'	=>	current_url(),
		);

		if ($this->CI->input->post('onpopstate'))
			$this->save_previous_url();
	}
	
	function add_depth($str_key, $mix_value)
	{
		$this->current_depth[$str_key][]	=	$mix_value;
	}

	function attr($str_type, $mix_one = NULL, $mix_two = NULL, $mix_three = NULL)
	{
		if ( ! $this->is_robot() || in_array($str_type, array('href', 'input')))
			switch($str_type) {
				case 'class':

					$str_class	=	$mix_one;
					$str_func		=	($mix_two ?: $str_class);

					return 'ng-class="{\'' . $str_class . '\': ' . $str_func . '}"';
				case 'ctrl':

					$boo_set_ctrl	=	(bool) $mix_one;
					$arr_sync			=	array(
						'key'	=>	$mix_two,
						'ang'	=>	$mix_three,
					);

					$str_ctrl	=	$this->latest_depth('ctrl');

					$arr_attr	=	array(
						'ng-controller="' . $str_ctrl . '"',
					);



					$this->ng_init('clear');
					if ($boo_set_ctrl)
						$this->ng_init('add', "ctrl = '{$str_ctrl}'");

					foreach ($arr_sync as $str_key => $str_value)
						$this->ng_init('add', "sync_{$str_key} = ". json_encode($str_value));

					$this->ng_init('add', 'startWatching()');

					$arr_attr[]	=	$this->ng_init('fetch');						

					return implode(' ', $arr_attr);
				case 'form':

					return $this->form->_attr();
				case 'href':

					$str_attr		=	'href="%s"';
					$str_value	=	$mix_one;
					if ( ! $this->is_robot())
						$str_attr	=	"ng-{$str_attr}";

					return sprintf($str_attr, $str_value);
				case 'input':

					$str_name	=	$mix_one;
					$arr_attr	=	array(
						'ng-model="ang.' . $str_name . '"',
						'name="' . $str_name . '"',
						'ng-disabled="isLoading()"',
					);

					$arr_attr	=	array_merge($arr_attr, $this->form->rules('get_attr', $str_name));

					return implode(' ', $arr_attr);
				case 'repeat':
					
					return 'ng-repeat="' . $this->current_loop['item'] . ' in ' .  $this->current_loop['list'] . '"';
			}

		return '';
	}
	
	function ctrl($str_ctrl)
	{
		$this->add_depth('ctrl', $str_ctrl);

		$this->set_data(array(), $str_ctrl);
	}
	
	function end($str_type)
	{
		$boo_exists	=	! empty($this->current_depth[$str_type]);
		if ($boo_exists)
			array_pop($this->current_depth[$str_type]);
		
		$this->form->end_ctrl($str_type);

		return $boo_exists;
	}
	
	function find_value($str, $mix_fallback = NULL)
	{
		$str_preffix	=	'ang.';
		if (substr($str, 0, strlen($str_preffix)) == $str_preffix)
			$str	=	substr($str, strlen($str_preffix));
		
		$boo_success	=	FALSE;
		if ($mix_fallback) {
			
			$boo_success	=	TRUE;

			$str	=	substr($str, (strpos($str, '.') + 1));
			if (is_object($mix_fallback))
				$tmp	=	$mix_fallback->$str;
			else
				$tmp	=	$mix_fallback[$str];
			
		} else {

			$arr_depth	=	explode('.', $str);
			
			$arr_ctrl		=	$this->current_depth['ctrl'];
			while ($arr_ctrl) {
				
				$str_current_ctrl	=	array_pop($arr_ctrl);
				
				$tmp					=	$this->data[$str_current_ctrl];
				$boo_success	=	FALSE;
				foreach ($arr_depth as $str_key)
					if (isset($tmp[$str_key])) {
						
						$tmp					=	$tmp[$str_key];
						$boo_success	=	TRUE;
						
					}
				
				if ($boo_success)
					break;
			
			}

		}

		if ( ! $boo_success)
			return "<!-- Found no value for {$str} -->";
		
		return $tmp;
	}
	
	function get_data($boo_define = TRUE)
	{
		$str	=	json_encode($this->json_data);
		if ( ! $boo_define)
			return $str;
		
		return "var ang = {$str};";
	}

	function globalize_data()
	{
		foreach ($this->data as $str_class => $arr_data)
			if ( ! in_array($str_class, $this->global_data))
				foreach ($this->global_data as $str_global)
					if (isset($this->data[$str_global]))
							$this->data[$str_class][$str_global]	=&	$this->data[$str_global];
	}
	
	function is_post()
	{
		return $this->is_post;
	}

	/* Asume json data is sent only through angularjs */
	function is_request()
	{
		return $this->is_request;
	}
	
	function is_robot()
	{
		$this->CI->load->library('user_agent');
		
		return $this->CI->agent->is_robot();
	}
	
	function latest_depth($str_key)
	{
		return end($this->current_depth[$str_key]);
	}

	function ng_init($str_method, $mix_one = NULL)
	{
		switch ($str_method) {
			case 'add':

				$this->ng_init_data[]	=	$mix_one;

				break;
			case 'clear':

				$this->ng_init_data	=	array();

				break;
			case 'clean':

				$str_attr	=	$mix_one;

				return str_replace($this->ng_init_attr, '', $str_attr);
			case 'fetch':

				if ( ! $this->ng_init_data)
					return '';

				$this->ng_init_attr	=	'ng-init="' . str_replace('"', "'", implode('; ', $this->ng_init_data)) . '"';

				return $this->ng_init_attr;
		}
	}

	function output_data($boo_exit = FALSE)
	{
		$this->CI->output->set_output($this->get_data(FALSE));

		if ($boo_exit) {

			$this->CI->output->_display();
			exit;

		}
	}

	function process_json()
	{
		$arr_data	=		(array) json_decode(trim(file_get_contents('php://input')), TRUE);
		$_POST		+=	$arr_data;
		
		return (bool) $arr_data;
	}
	
	function redirect($str_url)
	{
		$this->json_data['redirect']	=	$str_url;

		$this->output_data(TRUE);
	}

	function repeat($str_list, $str_item, $boo_print = TRUE)
	{
		$this->current_loop['list']	=	$str_list;
		$this->current_loop['item']	=	$str_item;
		
		$arr	=	$this->find_value($str_list);
		if ( ! $this->is_robot())
			return array(	array());
		
		return $arr;
	}
	
	function save_previous_url($bool = TRUE)
	{
		$str_current_url	=	$this->CI->input->post('current_url');
		if ($this->is_request() && $str_current_url)
			$this->json_data['url']	=	$str_current_url;
	}

	function set_data($arr, $str_class, $str_type = 'ctrl')
	{
		if ($str_class)
			$arr	=	array(	$str_class	=>	$arr);
		
		if ($str_type == 'global')
			$this->global_data[]	=	$str_class;
		
		$this->data			=	array_merge_recursive($arr, $this->data);
		$this->globalize_data();
		
		$this->json_data	=	array_merge_recursive($arr, $this->json_data);
		
		if ($str_type == 'main')
			$this->json_data['main']	=	$str_class;
	}

	function value($str, $mix_fallback = NULL)
	{
		if ($this->is_robot())
			return $this->find_value($str, $mix_fallback);
		else
			return '{{' . $str . '}}';
	}
	
}