<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db extends CI_Model {
	
	function extract($arr_values, $arr_get, $boo_get_all = FALSE)
	{
		$arr	=	array();
		$bool	=	TRUE;
		foreach ($arr_get as $str_key) {
			
			if (isset($arr_values[$str_key]))
				$arr[$str_key]	=	$arr_values[$str_key];
			else
				$bool	=	FALSE;
			
		}
		
		if ($boo_get_all && ! $bool)
			return FALSE;
		
		return $arr;
	}
	
	function found_rows()
	{
		$query	=	$this->db->query("SELECT FOUND_ROWS() AS found_rows");

		return $query->row()->found_rows;
	}

	function insert_update($str_table = '', $arr_values = array(), $arr_uniques = array())
	{
		if (empty($str_table) or empty($arr_values))
			return FALSE;
		
		/* Secure table name in query */
		$str_table		=	$this->db->protect_identifiers($str_table);
		
		/* Allow multiple rows insert */
		if ( ! isset($arr_values[0]))
			$arr_values	=	array($arr_values);
		
		/* Escape values and create UPDATE conditions */
		$arr_default	=	array();
		$arr_select		=	array();
		$arr_update		=	array();
		if ($arr_uniques) {
			
			$arr_default['id']	=	NULL;
			$arr_select[]		=	'e.id';
			$arr_update['id']	=	'id = LAST_INSERT_ID(e.id)';
			
		}
		
		$arr_default	+=	$arr_values[0];
		$str_columns	=	implode(', ', array_keys($arr_default));
		
		$arr_rows		=	array();
		foreach(array_keys($arr_values) as $i) {
			
			foreach(array_keys($arr_values[$i]) as $h)
				if ( ! $i) {
					
					if ($h != 'id')
						$arr_update[$h]		=	"{$h} = VALUES({$h})";
					else $arr_update[$h]	=	"{$h} = LAST_INSERT_ID(h.{$h})";
					
					$arr_select[]	=	"h.{$h}";
					
					$arr_values[$i][$h]	=	$this->db->escape($arr_values[$i][$h])." AS {$h}";
					
				} else $arr_values[$i][$h]	=	$this->db->escape($arr_values[$i][$h]);
			
			$arr_rows[]	=	implode(', ', array_values($arr_values[$i]));
			
		}
		
		$str_rows		=	'SELECT ' . implode(' UNION SELECT ', array_values($arr_rows));
		$str_select		=	implode(', ', array_values($arr_select));
		$str_update		=	implode(', ', array_values($arr_update));
		
		/* Fix auto-increment bug upon multiple rows insert 
		 * by getting the id through JOIN
		 * * */
		$str_having		=	'';
		if ($arr_uniques) {
			
			$arr_where	=	array();
			foreach($arr_uniques as $h)
				$arr_where[]	=	"e.{$h} = h.{$h}";
			
			$str_where	=	implode(' AND ', $arr_where);
			
			$str_having	=	"LEFT JOIN {$str_table} AS e ON {$str_where}";
			
		}
		
		$this->db->query(	"
							
					INSERT INTO {$str_table} ({$str_columns})
					SELECT {$str_select}
					FROM ({$str_rows}) AS h
					{$str_having}
					ON DUPLICATE KEY UPDATE {$str_update}
							
							");
		
		return $this->db->insert_id();
	}
	
	function parse($str_method, $mix_one = NULL, $mix_two = NULL)
	{
		switch ($str_method) {
			case 'limit':

				$int_limit	=	$mix_one;
				$int_offset	=	$mix_two;

				if ($int_limit) {

					$str	=	trim(implode(', ', array($int_offset, $int_limit)), ',');

					return "LIMIT {$str}";
					
				}

				return '';
		}
	}

}