<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Reviews extends CI_Migration {
	
	function up()
	{	
		/* Create Follow Ups Table */
		
		$arr_fields	=	array(
			'id'	=>	array(
				'type'						=>	'INT',
				'constraint'			=>	11,
				'auto_increment'	=>	TRUE,
			),
			'author'	=>	array(
				'type'				=>	'VARCHAR',
				'constraint'	=>	255,
			),
			'type'	=>	array(
				'type'				=>	'ENUM',
				'constraint'	=>	"'REGULAR','COOL'",
				'default'			=>	'REGULAR',
			),
			'text'	=>	array(
				'type'	=>	'TEXT',
			),
		);
		
		$this->dbforge->add_field($arr_fields);
		
		$this->dbforge->add_key('id', TRUE);
		
		$this->dbforge->create_table('reviews', TRUE);	
	}
	
	function down()
	{	
		$this->dbforge->drop_table('reviews');	
	}
	
}