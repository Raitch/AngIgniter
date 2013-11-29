<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Angularjs_form extends CI_Driver {

	protected $ctrl_name	=	'ItemForm';
	protected $got_ctrl		=	FALSE;
	protected $add_attr		=	array(
		'matches'			=>	'matches="%s"',
		'required'		=>	'required=""',
		'regex_match'	=>	'pattern="%s"',
	);
	protected $dropdown		=	array(
		'key'		=>	'key',
		'value'	=>	'value',
		'label'	=>	'label',
		'group'	=>	'group',
	);

	protected $CI;

	function __construct()
	{
		$this->CI	=&	get_instance();

		$this->process('dummy_preffix');
	}

	function _attr()
	{
		$arr_attr	=	array(
			'name="input"',
		);

		if ($this->got_ctrl) {

			$str_attr		=	$this->attr('ctrl', FALSE, 'ang');
			$arr_attr[]	=	$this->ng_init('clean', $str_attr);

		}

		$arr_validation	= $this->latest_validate();
		
		$arr_fields	=	array();
		foreach ($arr_validation as $arr_value)
			$arr_fields[]	=	$arr_value['field'];

		$str_fields	=	"'" . implode("', '", $arr_fields) . "'";

		$arr_attr		=	$this->ng_init('clean', $arr_attr);

		$this->ng_init('add', 'fields = [' . $str_fields . ']');

		$arr_attr[]	=	$this->ng_init('fetch');

		return implode(' ', $arr_attr);
	}

	function config_item($str_key)
	{
		$this->CI->config->load('form_validation', TRUE);

		return ($this->CI->config->item($str_key, 'form_validation') ?: array());
	}

	function dropdown($str_name, $str_ref, $str_attr = '')
	{
		if ( ! $this->is_robot()) {

			$arr			=	$this->dropdown;
			$str_attr	.=	$this->attr('input', $str_name);

			$str_options	=		"{$arr['value']}.{$arr['key']} as {$arr['value']}.{$arr['label']} ".
												"group by {$arr['value']}.{$arr['group']} ".
												"for {$arr['value']} ".
												"in ang.{$str_ref}";

			return	'<select ng-options="' . $str_options . '" '. $str_attr . '></select>';

		}	else
			return form_dropdown($str_name, ang_value($str_ref), ang_value($str_name));
	}

	function end_ctrl($str_type)
	{
		if ($str_type != 'validate')
			return FALSE;

		if ($this->got_ctrl)
			$this->end('ctrl');

		$this->got_ctrl	=	FALSE;
	}

	function id($str_name)
	{
		$str_preffix	=	strtolower($this->latest_depth('validate'));

		return 'id="' . $str_preffix . '_' . $str_name . '"';
	}

	function label($str_name)
	{
		$str_preffix	=	strtolower($this->latest_depth('validate'));

		return 'for="' . $str_preffix . '_' . $str_name . '"';
	}

	function latest_validate()
	{
		$str_validation	=	$this->latest_depth('validate');

		return $this->config_item($str_validation);
	}

	function multiselect($str_name, $str_ref, $str_attr = '')
	{
		if ( ! $this->is_robot()) {

			$str_attr		.=	'multiple=""';

			return $this->dropdown($str_name, $str_ref, $str_attr);

		}	else
			return form_multiselect($str_name, ang_value($str_ref), ang_value($str_name));
	}

	function process($str_method, $mix_one = NULL)
	{
		switch ($str_method) {
			case 'dropdown':

				$arr_values	=	$mix_one;

				if ($this->is_robot())
					return $arr_values;

				$arr	=	array();
				foreach ($arr_values as $str_key => $mix_value) {

					$str_group	=	NULL;
					if (is_array($mix_value))
						$str_group	=	$str_key;
					else
						$mix_value	=	array($str_key => $mix_value);

					foreach ($mix_value as $str_key => $str_value)
						$arr[]	=	array(
							$this->dropdown['key']		=>	$str_key,
							$this->dropdown['label']	=>	$str_value,
							$this->dropdown['group']	=>	$str_group,
						);
						
				}

				return $arr;
			case 'dummy_preffix':
		}
	}

	function set_values($mix, $arr_set = array())
	{
		$this->CI->load->helper('form');

		if (is_string($mix)) {

			$str_config			=	$mix;
			$arr_validation	=	$this->config_item($str_config);

			$arr_fields	=	array();
			foreach ($arr_validation as $arr_value)
				$arr_fields[]	=	$arr_value['field'];

		} else
			$arr_fields	=	$mix;

		$arr	=	array();
		foreach ($arr_fields as $str_field) {

			$str_current_val	=	'';
			if (isset($arr_set[$str_field]))
				$str_current_val	=	$arr_set[$str_field];

			$arr[$str_field]	=	(set_value($str_field) ?: $str_current_val);

		}

		if ($arr_set)
			$arr	=	array_merge($arr_set, $arr);

		return $arr;
	}

	function rules($str_method, $mix_one = NULL, $mix_two = NULL)
	{
		switch ($str_method) {
			case 'alter_arg':

				$str_arg		=	$mix_one;
				$str_field	=	$mix_two;

				switch ($str_field) {
					case 'regex_match':

						return substr($str_arg, 1, -1);
				}

				return $str_arg;
			case 'get':

				$str_name	=	$mix_one;

				$arr_validate	=	$this->latest_validate();
				foreach ((array) $arr_validate as $arr_input)
					if ($arr_input['field'] == $str_name) {
						
						preg_match_all("~([\w_]+(?:\[.*\])?)~", $arr_input['rules'], $arr_rules);

						return $arr_rules[1];

					}

				return FALSE;
			case 'get_attr':

				$str_name		=	$mix_one;
				$arr_rules	=	$this->rules('get', $str_name);
				if ( ! $arr_rules)
					return array();

				$arr_attr	=	array(
					$this->id($str_name),
				);
				foreach ($this->add_attr as  $str_field => $str_attr)
					foreach ($arr_rules as $str_rule)
						if (preg_match("~^{$str_field}(?:\[(.*)\])?$~", $str_rule, $arr_match)) {

							$str_arg	=	'';
							if (isset($arr_match[1]))
								$str_arg	=	$this->rules('alter_arg', $arr_match[1], $str_field);

							$arr_attr[]	=	sprintf($str_attr, $str_arg);
							break;

						}

				return $arr_attr;
		}
	}

	function validate($str_config_key, $boo_create_ctrl = FALSE)
	{
		$this->CI->config->load('form_validation', TRUE);

		$this->add_depth('validate', $str_config_key);

		$this->got_ctrl	=	$boo_create_ctrl;
		if ($this->got_ctrl)
			$this->ctrl($this->ctrl_name);
	}

}