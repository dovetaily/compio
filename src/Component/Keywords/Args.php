<?php

namespace Compio\Component\Keywords;

use Compio\Traits\ArgumentFormat;

trait Args {

	protected $args_construct;

	protected $args_init;

	protected $args_params;

	protected $args_render;

	protected $args;


	/**
	 * Get constructor arguments.
	 *
	 * @return string|null
	 */
	protected function args_construct(){

		return empty($this->args_construct) 
			? ($this->args_construct = trim($this->args_(function($param_name, $param_type, $value){
					return '$this->' . $param_name . ' = $' . $param_name . ";\n\t\t";
				}), "\n\t\t")
			) 
			: $this->args_construct
		;
	
	}

	/**
	 * Get init arguments.
	 *
	 * @return string|null
	 */
	protected function args_init(){

		return empty($this->args_init) 
			? ($this->args_init = trim($this->args_(function($param_name, $param_type, $value){
					return 'public $' . $param_name . ";\n\t";
				}), "\n\t")
			)
			: $this->args_init
		;

	}

	/**
	 * Get parmeters arguments.
	 *
	 * @return string|null
	 */
	protected function args_params(){

		return empty($this->args_params) 
			? ($this->args_params = trim($this->args_(function($param_name, $param_type, $value){
				return (!empty($param_type)
						? $param_type . ' ' 
						: null
					) . '$' . $param_name . ArgumentFormat::format_value($value, $param_type) . ', '
				;
				}, true), ', ')
			) 
			: $this->args_params
		;

	}

	/**
	 * Get render arguments.
	 *
	 * @return string|null
	 */
	protected function args_render(){

		return empty($this->args_render)
			? ($this->args_render = trim($this->args_(function($param_name, $param_type, $value){
				return "'" . $param_name . '\' => $this->' . $param_name . ",\n\t\t\t";
			}), "\n\t\t\t"))
			: $this->args_render
		;
	
	}

	/**
	 * Get argument.
	 *
	 * @param  callable $callback
	 * @param  bool     $sort
	 * @return string
	 */
	protected function args_(callable $callback, bool $sort = false){

		$args = $this->arguments()->get();

		if($sort){

			$args = array_reverse(is_null($args) ? [] : $args);

			uasort($args, function ($a, $b) {
				return is_null($a) && is_null($b) 
					? -1 
					: (is_null($a) 
						? -1 
						: 1
					)
				;
			});

		} else $args = empty($args) ? [] : $args;

		$res = "";

		$declare_param = [];

		foreach ($args as $key => $value) {

			$separate = explode(':', $key);

			$param_name = array_pop($separate);

			$param_type = empty($separate) 
				? null
				: implode('|', $separate)
			;

			if(!in_array($param_name, $declare_param)){
				$res .= $callback($param_name, $param_type, $value);

				$declare_param[] = $param_name;
			}

		}

		return $res;
	
	}

	/**
	 * Get custom keyword arguments 
	 *
	 * @param string|int   $key
	 * @param string|null   $select_key
	 * @return string|null
	 */
	protected function args(string|int $key, string|null $select_key = null){

		$select_key = empty($select_key) ? '{{name}}' : $select_key;

		$args = $this->arguments()->get();

		$args_keys = array_keys($args);

		$kk = array_key_exists($key, $args_keys)
			? $key
			: (is_int(strpos($key, ($d = ',')))
				? explode($d, $key)
				: ($key === '*'
					? array_keys($args_keys)
					: null
				)
			)
		;
		$kk = is_string($kk) ? [$kk] : $kk;

		$t = [];


		foreach ($kk as $key) {

			if(array_key_exists($key, $args_keys)){

				$separate = explode(':', $args_keys[$key]);

				$param_name = array_pop($separate);

				$param_type = empty($separate) ? null : implode('|', $separate);

				$param_value = $args[$args_keys[$key]];

				$param_value_format = ArgumentFormat::format_value($param_value, $param_type);

				$all = (is_null($param_type) ? null : $param_type . ' ') . '$' . $param_name . $param_value_format;

				$param_selected = [
					'{--name--}' => $param_name,
					'{--type--}' => $param_type,
					'{--value--}' => $param_value,
					'{--type-name-value--}' => $all,
					'{--all--}' => $all,
					'{--type-name--}' => $param_type . ' $' . $param_name . '',
					'{--name-value--}' => '$' . $param_name . $param_value_format,
				];

				$new_v = $select_key;

				foreach ($param_selected as $kkey => $val)
					$new_v = str_replace($kkey, $val, $new_v);

				$t[] = $new_v;

			}

		}

		$t = !empty($t) ? implode(';', $t) . ';' : null;

		return $t;

	}

}