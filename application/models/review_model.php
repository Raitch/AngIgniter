<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Used for Model scalability */
class Review_model extends CI_Model {
	
	function add($arr_data)
	{
		$arr_values	=	$this->general->db->extract($arr_data, array('author', 'type', 'text'));
		
		return $this->general->db->insert_update('reviews', $arr_values);
	}
	
	function get($int_limit = NULL, $int_offset = NULL)
	{
		$str_limit	=	$this->general->db->parse('limit', $int_limit, $int_offset);

		$query	=	$this->db->query(	"

								SELECT SQL_CALC_FOUND_ROWS id, author, type, text
								FROM reviews
								ORDER BY id DESC
								{$str_limit}

										");

		return $query->result();
	}
	
}
