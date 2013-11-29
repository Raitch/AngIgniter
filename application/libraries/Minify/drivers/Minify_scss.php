<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Minify_scss extends CI_Driver {

	protected $class;

	function __construct()
	{
		require APPPATH . 'libraries/Minify/includes/scssphp/scss.inc.php';

		$this->class	=	new scssc();
	}

	function compile($str, $boo_file = FALSE)
	{
		if ($boo_file)
			$str	=	file_get_contents($str, TRUE);

		return $this->class->compile($str);
	}

	function set_import_path($str_path)
	{
		$this->class->setImportPaths($str_path);
	}

}