<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User_model extends CI_Model {
	
	protected $profile;

	function add($arr_data)
	{
		$this->load->library('login');

		$arr_values	=	$this->general->db->extract($arr_data, array('username', 'password'));
		
		$arr_values['password']	=	$this->login->encrypt($arr_values['password'], $arr_values['username']);

		return $this->general->db->insert_update('users', $arr_values);
	}

	function edit($arr_data)
	{
		$arr_values	=	$this->general->db->extract($arr_data, array('id', 'name'));

		return $this->general->db->insert_update('users', $arr_values);	
	}

	function get_profile($int_id, $boo_force = FALSE)
	{
		if ( ! isset($this->profile->id) || $this->profile->id != $int_id || $boo_force) {

			$arr_where	=	array(
				'id'	=>	$int_id,
			);

			$query					=	$this->db->get_where('users', $arr_where);
			$this->profile	=	$query->row();

		}

		return $this->profile;
	}

	function login($str_username, $str_password)
	{
		$this->db->select('id, username, password');
		$this->db->from('users');
		$this->db->where('username', $str_username);
		$this->db->where('password', $this->login->encrypt($str_password, $str_username));
		
		$query	=	$this->db->get();
		
		if ($query->num_rows()) {

			$this->profile	=	$query->row();

			return $this->profile;
			
		}
		
		return FALSE;
	}
	
}