<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Users extends CI_Migration {
	
	function up()
	{	
		/* Create Users Table */
		
		$arr_fields	=	array(
			'id'		=>	array(
				'type'						=>	'INT',
				'constraint'			=>	11,
				'auto_increment'	=>	TRUE,
			),
			'username'	=>	array(
				'type'				=>	'VARCHAR',
				'constraint'	=>	255,
			),
			'password'	=>	array(
				'type'				=>	'VARCHAR',
				'constraint'	=>	255
			),
			'name'	=>	array(
				'type'				=>	'VARCHAR',
				'constraint'	=>	255
			),
		);
		
		$this->dbforge->add_field($arr_fields);
		
		$this->dbforge->add_key('id', TRUE);
		
		$this->dbforge->create_table('users', TRUE);
		
		$this->db->query("ALTER TABLE users ADD UNIQUE (username)");

		/* Insert User Rows */
		
		$str_username	=	'admin';

		$arr_values	=	array(
			array(
				'username'	=>	$str_username,
				'password'	=>	$this->login->encrypt('password', $str_username),
			),
		);
		
		$this->general->db->insert_update('users', $arr_values);
	}
	
	function down()
	{	
		$this->dbforge->drop_table('users');	
	}
	
}