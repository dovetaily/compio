<?php

namespace Compio\Component\Keywords;

trait Args {

	protected $args_construct;

	protected $args_init;

	protected $args_params;

	protected $args_render;

	protected $args;


	/**
	 * Description ...
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
	 * Description ...
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
	 * Description ...
	 *
	 * @return string|null
	 */
	protected function args_params(){

		return empty($this->args_params) 
			? ($this->args_params = trim($this->args_(function($param_name, $param_type, $value){
				return (!empty($param_type)
						? $param_type . ' ' 
						: null
					) . '$' . $param_name . $this->args_params_get_value($value) . ', '
				;
				}, true), ', ')
			) 
			: $this->args_params
		;

	}

	/**
	 * Description ...
	 *
	 * @return string|null
	 */
	protected function args_params_get_value($value){
		return $value === null
			? null
			: " = " . ($value === '!##Be_null||'
				? 'null'
				: ($value == '[]' || $value == '[ ]'
						? $value
						: (is_numeric($value)
								? $value
								: (is_string($value)
										? '"' . $value . '"'
										: var_export($value, true) 
								)
						)
				)
			);
	}

	/**
	 * Description ...
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
	 * Description ...
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
	 * Description ...
	 *
	 * @param string|int   $key
	 * @param string|null   $select_key
	 * @return string|null
	 */
	protected function args(int $key, string|null $select_key = null){

		$select_key = empty($select_key) ? 'name' : $select_key;

		$args = $this->arguments()->get();

		$separate = explode(':', array_keys($args)[$key]);

		$param_name = array_pop($separate);

		$param_type = empty($separate) ? null : implode('|', $separate);

		$param_value = $args[array_keys($args)[$key]];

		$param_value_format = $this->args_params_get_value($param_value);

		$all = $param_type . ' $' . $param_name . $param_value_format;

		$param_selected = [
			'name' => $param_name,
			'type' => $param_type,
			'value' => $param_value,
			'type-name-value' => $all,
			'all' => $all,
			'type-name' => $param_type . ' $' . $param_name . '',
			'name-value' => '$' . $param_name . $param_value_format,
		];

		return array_key_exists($select_key, $param_selected) ? $param_selected[$select_key] : null;

	}

}