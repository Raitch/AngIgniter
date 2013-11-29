<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login {
	
	var $CI;
	var $config	=	array(
		'login_session'		=>	'user',
		'login_callback'	=>	'login_validation',
							
		'post_login'			=>	'login',
		'post_username'		=>	'username',
		'post_password'		=>	'password',
							
		'lang_username'		=>	'register_username',
		'lang_password'		=>	'register_password',
		'lang_message'		=>	'message_login_callback',
							
		'view'						=>	'login',
		'arr_name'				=>	'arr_login',
	);
	
	function __construct($arr_config = array())
	{
		$this->config	=	array_merge_recursive($this->config, $arr_config);
		
		if (isset($this->config['required']))
			$this->required	=	$this->config['required'];
		
		$this->CI	=&	get_instance();
		
		$this->CI->load->library('session');
		
		$this->CI->load->vars(array($this->config('arr_name') => $this->config));
	}
	
	function config($str_key)
	{
		if (isset($this->config[$str_key]))
			return $this->config[$str_key];
		
		return FALSE;
	}
	
	function encrypt($str, $str_username = NULL)
	{
		$str_salt	=	$this->CI->config->item('encryption_key');
		if ( ! empty($this->config['salt']))
			$str_salt	=	($this->config['salt']);
		
		if ($str_username)
			$str	.=	substr(md5($str_username), 10, 14);

		return base64_encode(hash_hmac('sha256', $str, $str_salt, TRUE));
	}
	
	function form_required()
	{
		return ( ! $this->logged_in() && ! $this->validated());
	}
	
	function logged_in()
	{
		$this->CI->load->library('session');
		
		return (bool) $this->CI->session->userdata($this->config['login_session']);
	}
	
	function logout($boo_redirect = FALSE)
	{
		$this->CI->load->helper('url');
		
		$this->CI->session->unset_userdata($this->config('login_session'));
		
		if ($boo_redirect)
			redirect(site_url());
	}
	
	function set_session($int_id)
	{
		$arr_values	=	array(
			'id'	=>	$int_id,
		);
		
		$this->CI->session->set_userdata($this->config('login_session'), $arr_values);
	}

	function validated()
	{
		$boo_validated	=	FALSE;
		if ($this->CI->input->post($this->config['post_login'])) {
			
			$this->CI->load->library('form_validation');
			$this->CI->config->load('form_validation', TRUE);

			$str_callback	=	$this->config('login_callback');
			$this->CI->form_validation->set_message($str_callback, $this->CI->lang->line($this->config('lang_message')));

			$arr_login	=	$this->CI->config->item('login', 'form_validation');
			$str_group	=	'';
			if ($arr_login)
				$str_group	=	'login';
			else {

				$str_username	=	$this->CI->lang->line($this->config('lang_username'));
				$str_password	=	$this->CI->lang->line($this->config('lang_password'));
				
				$this->CI->form_validation->set_rules($this->config('post_username'), $str_username, 'trim|required|xss_clean');
				$this->CI->form_validation->set_rules($this->config('post_password'), $str_password, "trim|required|xss_clean|callback_{$str_callback}");
				
			}
			
			if ($this->CI->form_validation->run($str_group))
				$boo_validated	=	TRUE;
			
		}
		
		return $boo_validated;
	}
	
}
