<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Requires: CD Minify driver, config template
 */
class Custom_minify {
	
	var $CI;
	var $config;
	
	var $css;
	var $js;
	
	var $css_all_file;
	var $js_all_file;
	
	var $loaded	=	array();

	function __construct($arr_config)
	{
		$this->CI	=&	get_instance();
		
		$this->CI->load->driver('minify');
		
		$this->config	=	$arr_config;
		
		$arr_type	=	array('css', 'js', 'scss');
		foreach ($arr_type as $str_type) {
			
			$arr	=	$this->config[$str_type];
			foreach ($arr['files'] as $int_key => $str_file) {
				
				if ($str_file[0] == '/')
					$str_file	=	$arr['pre_path'] . $str_file;
				
				$this->config[$str_type]['files'][$int_key]						=	$str_file;
				$this->config[$str_type]['local']['files'][$int_key]	=	$this->fcpath($str_file, 'add');
				
			}
			
			if (isset($arr['all'])) {

				$str_all_file	=	"{$arr['pre_path']}/{$arr['all']}.{$str_type}";
				
				$this->config[$str_type]['all']						=	$str_all_file;
				$this->config[$str_type]['local']['all']	=	$this->fcpath($str_all_file, 'add');
				
			}

		}
	}
	
	function fcpath($str, $str_method)
	{
		if (preg_match("~^(https?:)?//~", $str))
			return $str;
		
		if (strpos($str, FCPATH) === 0)
			$str	=	'/' . substr($str, strlen(FCPATH));
		
		switch ($str_method) {
			case 'add':
				
				return FCPATH . ltrim($str, '/\\');
			case 'remove':
				
				return $str;
		}
	}
	
	function file_to_array($str_file, $str_type)
	{
		$arr_return	=	array();
		
		switch ($str_type) {
			case 'js':
				
				$boo_new_line	=	(strpos($str_file, "\n") !== FALSE);
				$boo_minified	=	preg_match("~\.min\.js$~", $str_file);
				
				if ($boo_minified && ! $boo_new_line) {
					
					$arr_return[]	=	file_get_contents($str_file, TRUE);
					
				} elseif (preg_match("~/\*\.js$~", $str_file) && ! $boo_new_line) {
					
					$arr_files	=	glob($str_file);
					foreach ($arr_files as $str_file)
						$arr_return	=	array_merge($arr_return, $this->file_to_array($str_file, $str_type));
					
				} elseif (preg_match("~\.js$~", $str_file) and ! $boo_new_line) {
					
					$arr_return[]	=	$this->CI->minify->js->min($str_file);
					
				} elseif (preg_match("~^(https?)?//~", $str_file) and ! $boo_new_line) {
					
					$str_content	=	file_get_contents($str_file);
					if ($str_content === FALSE)
						show_error("Tried to fetch {$str_file}, but failed.");
					
					if ($boo_minified)
						$arr_return[]	=	$str_content;
					else
						$arr_return[]	=	$this->CI->minify->js->min($str_content);
					
				}
				
				return $arr_return;
			case 'scss':

				$boo_import_file	=	(strpos(end(explode('/', $str_file)), '_') === 0);

				if (preg_match("~/\*\.scss$~", $str_file)) {

					$arr_files	=	glob($str_file);

					foreach ($arr_files as $str_file)
						$arr_return	=	array_merge($arr_return, $this->file_to_array($str_file, $str_type));

				} elseif ( ! $boo_import_file) {

					$str_key			=	$this->fcpath($str_file, 'add');
					$str_pre_path	=	$this->fcpath($this->config['scss']['pre_path'], 'add');
					$str_key			=	substr($str_file, strlen($str_pre_path));

					$arr_return[$str_key]	=	$this->CI->minify->scss->compile($str_file, TRUE);

				}

				return $arr_return;
		}
	}
	
	function load_src($str_src, $str_type = NULL, $boo_no_cache = FALSE)
	{
		if ( ! $str_type)
			$str_type	=	preg_replace("/^.+\.(\w+)?$/", "$1", $str_src);
		
		if (strpos($str_src, '*') !== FALSE) {
			
			$arr_src	=	glob($this->fcpath($str_src, 'add'));
			$str_return	=	'';
			foreach ($arr_src as $str_src) {

				$boo_not_all	=	($this->fcpath($str_src, 'add') != $this->config[$str_type]['local']['all']);
				if ($boo_not_all)
					$str_return	.=	$this->load_src($this->fcpath($str_src, 'remove'), $str_type);
			}
			
			return $str_return;
			
		}
		
		if ( ! in_array($this->fcpath($str_src, 'add'), $this->loaded)) {

			$this->loaded[]	=	$this->fcpath($str_src, 'add');
			
			if (preg_match("~^/[^/]~", $str_src))
				$str_src	=	base_url() . substr($str_src, 1);
			
			if ($boo_no_cache)
				$str_src	.=	'?_nocache=' . time();
			
			$arr_types	=	array(
				'css'	=>	'<link rel="stylesheet" type="text/css" href="%s" />',
				'ico'	=>	'<link rel="shortcut icon" href="%s" />',
				'js'	=>	'<script type="text/javascript" src="%s"></script>',
			);
			
			if (isset($arr_types[$str_type]))
				$str_html	=	$arr_types[$str_type];
			else $str_html	=	'<!-- %s is not a valid type -->';
			
			return sprintf($str_html, $str_src) . "\n";

		}
		
		return '';
	}
	
	function parse($boo_exclude_js = FALSE)
	{
		$boo_all_sources	=	(ENVIRONMENT == 'development');

		$arr_files	=	array();
		if ($boo_all_sources)
			$arr_files	=	$this->config['css']['files'];
		else
			$arr_files	=	array($this->config['css']['all']);
		
		if ( ! $boo_exclude_js)
			if ($boo_all_sources)
				$arr_files		=	array_merge($arr_files, $this->config['js']['files']);
			else
				$arr_files[]	=	$this->config['js']['all'];
		
		$str_output	=	'';
		foreach ($arr_files as $str_src)
			$str_output		.=	$this->load_src($this->fcpath($str_src, 'remove'));
		
		return $str_output;
	}
	
	function process($str_method = NULL)
	{
		$this->CI->load->driver('minify');
		
		switch($str_method) {
			case 'css':
				
				$str	=	$this->CI->minify->combine_files($this->config['css']['local']['files']);
				
				$str_css_all_file	=	$this->config['css']['local']['all'];
				
				file_put_contents($str_css_all_file, $str);
				
				break;
			case 'js':
				
				$arr	=	array();
				foreach ($this->config['js']['local']['files'] as $str_file)
					$arr	=	array_merge($arr, $this->file_to_array($str_file, 'js'));
				
				$str	=	implode("\r\n;", $arr);
				
				$str_js_all_file	=	$this->config['js']['local']['all'];
				
				file_put_contents($str_js_all_file, $str);
				
				break;
			case 'scss':

				$str_import_path	=	$this->fcpath($this->config['scss']['pre_path'], 'add');

				$this->CI->minify->scss->set_import_path($str_import_path);

				$arr	=	array();
				foreach ($this->config['scss']['local']['files'] as $str_file)
					$arr	=	array_merge($arr, $this->file_to_array($str_file, 'scss'));
				
				foreach ($arr as $str_file => $str_compile) {

					$str_new_file	=	$this->fcpath($this->config['css']['pre_path'] . $str_file, 'add');
					$str_new_file	=	substr($str_new_file, 0, -strlen('.scss')) . '.css';

					file_put_contents($str_new_file, $str_compile);

				}

				break;
			case NULL:
				
				$this->process('scss');
				$this->process('css');
				$this->process('js');
				
				return;
		}
	}
	
}
