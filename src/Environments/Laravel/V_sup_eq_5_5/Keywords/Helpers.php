<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords;
use Illuminate\Support\Str;

trait Helpers {

	/**
	 * Returns a structured string for properties of a class
	 * 
	 * @param  string             $name
	 * @param  array|string|null  $description
	 * @param  string|null        $access
	 * @param  string|null        $type
	 * @param  string|null        $value
	 * @param  string             $tab
	 * @return string
	 */
	public function propertyStructure(string $name, $description = null, $access = null, $type = null, $value = null, string $tab = "") : string
	{
		$model = "{{@description}}{{@access}}{{@type}}{{@name}}{{@value}}";
		$description = is_string($description) ? trim($description) : $description;
		$description = is_array($description)
			? $description
			: (is_string($description)
				? explode(strpos($description, "\n") !== false ? "\n" : "\r", $description)
				: null
			)
		;
		$model = str_replace([
			"{{@name}}",
			"{{@description}}",
			"{{@access}}",
			"{{@type}}",
			"{{@value}}",
		], [
			$name, // name
			(!empty($description) // description
				? "/**\n * " . implode("\n * ", $description) . "\n */\n"
				: null
			),
			(!empty($access) // access
				? $access . ' '
				: null
			),
			(!empty($type) // type
				? (is_array($type)
					? implode('|', $type)
					: $type
				) . ' '
				: null
			),
			($value == '#be!!null!!' ? null : (" = " . str_replace("  ", "\t", var_export($value, true)))) . ";",
		], $model);
		return $tab . str_replace("\n", "\n" . $tab, $model);
		// code...
	}

	/**
	 * Returns a structured string for the methods of a class or a simple function
	 * 
	 * @param  string             $name
	 * @param  array|string|null  $description
	 * @param  string|null        $access
	 * @param  array|string|null  $args
	 * @param  array|string|null  $type
	 * @param  array|string|null  $code
	 * @param  string|null        $return_
	 * @param  string             $tab
	 * @return string
	 */
	public function functionStructure(string $name, $description = null, $access = null, $args = null, $type = null, $code = null, $return_ = null, string $tab = "") : string
	{
		$model = "{{@description}}{{@access}}function {{@name}}({{@args}}) {{@type}}\n{{{@code}}{{@return}}\n}";
		$description = is_string($description) ? trim($description) : $description;
		$description = is_array($description)
			? $description
			: (is_string($description)
				? explode(strpos($description, "\n") !== false ? "\n" : "\r", $description)
				: null
			)
		;
		$model = str_replace([
			"{{@name}}",
			"{{@description}}",
			"{{@access}}",
			"{{@args}}",
			"{{@type}}",
			"{{@code}}",
			"{{@return}}",
		], [
			$name, // name
			(!empty($description) // description
				? "/**\n * " . implode("\n * ", $description) . "\n */\n"
				: null
			),
			(!empty($access) // access
				? $access . ' '
				: null
			),
			(!empty($args) // args
				? (is_array($args)
					? implode(', ', $args)
					: $args
				)
				: null
			),
			(!empty($type) // type
				? ': '. (is_array($type)
					? implode('|', $type)
					: $type
				)
				: null
			),
			(!empty($code) // code
				? (is_array($code) ? implode("\n", $code) : $code)
				: null
			),
			(!empty($return_) // return
				? "\n\treturn " . $return_ . ";"
				: null
			),
		], $model);
		return $tab . str_replace("\n", "\n" . $tab, $model);
	}

	/**
	 * Returns the data needed to structure a function or method of a class
	 * 
	 * @param  array  $functions
	 * @param  array  $default_value
	 * @return array
	 */
	public function functionArray(array $functions, array $default_value = []) : array
	{
		if(empty($functions)) return ['datas' => [], 'class' => []];
		$k_b = $this->template_config['keyword'];
		$import_class = [];
		foreach ($functions as $function_name => $value) {

			if(!is_null($value)){
				$value['type_returned'] = isset($value['type_returned']) ? (is_string($value['type_returned'])
					? (strpos($value['type_returned'], '|')
						? explode('|', $value['type_returned'])
						: [$value['type_returned']]
					)
					: $value['type_returned']
				) : (array_key_exists('type_returned', $default_value) ? $default_value['type_returned'] : null);
				if(!empty($value['type_returned']))
					foreach ($value['type_returned'] as $k => $v){
						if(isset($k_b[$v])) $value['type_returned'][$k] = $k_b[$v];
						if(!in_array($vl = $value['type_returned'][$k], $import_class) && preg_match('/\\\/i', $vl))
							$import_class[] = $vl;
					}
				$value['args'] = isset($value['args']) && !empty($ret = $this->colone($value['args'], false)) ? $ret['cols'] : null;
				if(is_array($value['args']) && !empty($value['args']))
					foreach ($value['args'] as $key_1 => $value_1){
						$value['args'][$key_1] = !empty($value_1) && (is_array($value_1) || is_string($value_1)) 
							? (is_string($value_1)
								? (strpos($value_1, '|')
									? explode('|', $value_1)
									: [$value_1]
								)
								: $value_1
							)
							: null
						;
						// dump($key_1, $value['']);exit;
					}
					if(!empty($value['args']))
						foreach ($value['args'] as $k1 => $v1) {
							if(!empty($v1)){
								foreach ($v1 as $k => $v) {
									if(isset($k_b[$v])) $value['args'][$k1][$k] = $k_b[$v];
									if(!in_array($vl = $value['args'][$k1][$k], $import_class) && preg_match('/\\\/i', $vl))
										$import_class[] = $vl;
									// code...
								}
							}
						}

				$value['code'] = isset($value['code']) && (is_string($value['code']) || is_array($value['code'])) && !empty($value['code'])
					? $value['code']
					: null
				;
				$value['return'] = isset($value['return']) && ((is_string($value['return']) && !empty($value['return'])) || is_callable($value['return']))
					? /*(is_null(*/$value['return']//)
					// 	? $value['return']
					// 	: $value['return']
					// )
					: (array_key_exists('return', $default_value) ? $default_value['return'] : null)
				;
				$value['description'] = isset($value['description']) && is_string($value['description'])
					? explode(strpos($value['description'], "\n") !== false ? "\n" : "\r", $value['description'])
					: (isset($value['description']) && is_callable($value['description'])
						? $value['description']
						: (array_key_exists('description', $default_value) ? $default_value['description'] : null)
					)
				;
				$value['access'] = isset($value['access']) && is_string($value['access']) && !empty(trim($value['access']))
					? trim($value['access'])
					: (array_key_exists('access', $default_value) ? $default_value['access'] : null)
				;
			}

			$functions[$function_name] = $value;
		}
		return ['datas' => $functions, 'class' => $import_class];
		// code...
	}

	/**
	 * Searches for a character pattern in the values of an array
	 * 
	 * @param  string $search
	 * @param  array $datas
	 * @return bool
	 */
	public function inArray($search, $datas) : bool
	{
		foreach ($datas as $key => $value)
			if((preg_match('/^\\/.*\\/$/i', $value) && preg_match($value, $search)) || $value == $search)
				return true;
		return false;
	}

	/**
	 * Get column data from a migration table
	 * 
	 * @param  array                 $args
	 * @param  object|callable|null  $call_back
	 * @param  array                 $foreign_default
	 * @param  string|array          $modifiers_def
	 * @return array
	 */
	public function getMigrationDatas($args, $call_back, $foreign_default, $modifiers_def = null) : array
	{
		$ret = [];
		$cols = $this->colone($args['columns'], false)['cols'];

		foreach ($cols as $column => $value) {
			$prg = preg_match('/(.*)_id$/i', $column, $m);
			$tes = ['__timestamps' => true, '__remember_token' => true, '__soft_deletes' => true];
			$ret[$column] = is_array($value) ? $value : (!is_null($value) ? ['other' => $value] : []);
			$ret[$column]['type'] = isset($value['type']) 
				? (!is_array($value['type'])
					? [$value['type'] => ($prg ? 'id' : (isset($tes[$column])
						? [Str::camel(trim($column, '__')) => false]
						: null
					))]
					: $value['type']
				)
				: (isset($tes[$column])
					? [Str::camel(trim($column, '__')) => false]
					: ($prg
						? $foreign_default['foreign_property']
						// : (!empty($cf = $this->template_config['default_column_type'])
						: (!empty($cf = $this->template_config['default_column_type']) && $column != 'id'
							? (is_array($cf)
								? $cf
								: [$cf => '#'] 
							)
							: null
						)
					)
				)
			;
			$ret[$column]['modifiers'] = isset($value['modifiers'])
				? $this->colone($value['modifiers'], false, false, false)
				: ($prg && !empty($rr = $foreign_default['modifiers'])
					? $this->colone($rr, false, false, false)
					: (is_null($modifiers_def)
						? null
						: $this->colone($modifiers_def, false, false, false)
					)
				)
			;
		}
		return $this->colone($ret, false, false);
	}

	/**
	 * Returns a class import structure
	 * 
	 * @param  array         $args
	 * @param  string        $key_
	 * @param  string|mixed  $default_
	 * @param  string|null   $pattern
	 * @return string|mixed
	 */
	public function importClass($args, $key_, $default_, $pattern = null){
		$datas = ['default' => $default_];
		$datas['new'] = isset($args[2][$key_]['import_class']) && is_array($args[2][$key_]['import_class'])
			? $args[2][$key_]['import_class']
			: []
		;
		$k_b = $this->template_config['keyword'];
		// $checked_class_import = config('compio-db.conf.helpers.checked_class_import');
		if(isset($datas['new']['#content']) && (is_string($datas['new']['#content']) || is_callable($datas['new']['#content'])))
			return !is_string($datas['new']['#content']) ? $datas['new']['#content'](...$args[2]) : $datas['new']['#content'];
		elseif(!isset($datas['new']['#content'])){
			$datas = is_array($datas['new']) && count($datas['new']) > 0 ? $datas['new'] : $datas['default'];
			foreach ($datas as $key => $value) {
				$datas[$key] = isset($k_b[$value]) ? $k_b[$value] : $value;
			}
			$datas = $this->checkedClassImport($args[4], $datas, $pattern);
			return empty($datas) ? '' : "use " . implode(";\nuse ", $datas) . ";";
		}
	}

	/**
	 * Returns a trait import structure
	 * 
	 * @param  array         $args
	 * @param  string        $key_
	 * @param  string|mixed  $default_
	 * @param  string        $kw_imp_cls
	 * @param  string        $kw_trt
	 * @return string|bool|mixed
	 */
	public function importTrait($args, $key_, $default_, $kw_imp_cls, $kw_trt){
		// $call_col = config('compio-db.conf.helpers.colone');
		// $checked_class_import = config('compio-db.conf.helpers.checked_class_import');
		$k_b = $this->template_config['keyword'];
		$datas = ['default' => $this->colone($default_, false)];
		$datas['new'] = isset($args[2][$key_]['import_trait']) && is_array($args[2][$key_]['import_trait'])
			? $this->colone($args[2][$key_]['import_trait'], false)
			: []
		;
		$file_content = $args[4];
		if(isset($datas['new']['#content']) && (is_string($datas['new']['#content']) || is_callable($datas['new']['#content'])))
			return !is_string($datas['new']['#content']) ? $datas['new']['#content'](...$args[2]) : $datas['new']['#content'];
		elseif(!isset($datas['new']['#content'])){
			$dts = is_array($datas['new']) && isset($datas['new']['cols']) && count($datas['new']['cols']) > 0 ? $datas['new']['cols'] : (isset($datas['default']['cols']) ? $datas['default']['cols'] : null);
			if(empty($dts)) return '';
			$cols = [];
			foreach ($dts as $key => $value) {
				preg_match('/^.*\\\.* as (.*)|^.*\\\(.*)|.*$/i', $key, $m);
				$cols[end($m)] = $value;
				$key = isset($k_b[$key]) ? $k_b[$key] : $key;
				if(preg_match('/\\\/i', $key) == true && !empty($this->checkedClassImport($file_content, $key)))
					$file_content = str_replace($kw_imp_cls, ('use ' . $key . ";\n" . $kw_imp_cls), $file_content);
			}
			$file_content = str_replace($kw_trt, (empty($cols) ? null : "\n\tuse " . implode(", ", array_keys($cols)) . ";"), $file_content);
			file_put_contents($args[5], $file_content);
			return true;
		}
	}

	/**
	 * Returns an interface implementation structure
	 * 
	 * @param  array         $args
	 * @param  string        $key
	 * @param  array|string  $default_
	 * @param  string        $kw_imp_cls
	 * @param  string        $kw_ext
	 * @return string|bool
	 */
	public function implement($args, $key, $default_, $kw_imp_cls, $kw_ext)
	{
		$file_content = $args[4];
		$implement_class = isset($args[2][$key]['implement']) && is_string($args[2][$key]['implement']) ? $args[2][$key]['implement'] : $default_;
		$k_b = $this->template_config['keyword'];
		$checked_class_import = config('compio-db.conf.helpers.checked_class_import');
		$implement_class = is_string($implement_class) ? explode(',', $implement_class) : (is_array($implement_class) ? $implement_class : []);
		if(!empty($implement_class)){
			$import_class = [];
			foreach ($implement_class as $key => $interface_class) {
				if(is_string($interface_class))
					$import_class[] = isset($k_b[$interface_class]) ? $k_b[$interface_class] : trim($interface_class);
			}
			if(!empty($import_class)){
				$import_class = $checked_class_import($file_content, $import_class);
				$file_content = str_replace($kw_imp_cls, ('use ' . implode(";\nuse ", $import_class) . ";\n" . $kw_imp_cls), $file_content);
				$interface = [];
				foreach ($import_class as $val) {
					preg_match('/^.*\\\.* as (.*)|^.*\\\(.*)|.*$/i', $val, $m);
					$interface[] = end($m);
				}
				$file_content = str_replace($kw_ext, (" implements " . implode(', ', $interface)), $file_content);
				file_put_contents($args[5], $file_content);
				return true;
			}
		}
		return '';
	}

	/**
	 * Returns the structure of the extended class
	 * 
	 * @param  array   $args
	 * @param  string  $key
	 * @param  string  $default_
	 * @param  string  $kw_imp_cls
	 * @param  string  $kw_ext
	 * @return string|bool
	 */
	public function extend($args, $key, $default_, $kw_imp_cls, $kw_ext)
	{
		$file_content = $args[4];
		$extend_class = isset($args[2][$key]['extend']) && is_string($args[2][$key]['extend']) ? $args[2][$key]['extend'] : $default_;
		$k_b = $this->template_config['keyword'];
		$extend_class = isset($k_b[$extend_class]) ? $k_b[$extend_class] : $extend_class;

		
		// $checked_class_import = config('compio-db.conf.helpers.checked_class_import'); $this->checkedClassImport()
		$extend_class = !empty($r = $this->checkedClassImport($file_content, $extend_class)) ? end($r) : null;

		$file_content = !is_null($extend_class) && preg_match('/\\\/i', $extend_class) == true ? str_replace($kw_imp_cls, ('use ' . $extend_class . ";\n" . $kw_imp_cls), $file_content) : $file_content;
		if(!empty($extend_class)){
			preg_match('/^.*\\\.* as (.*)|^.*\\\(.*)|.*$/i', $extend_class, $m);
			$file_content = str_replace($kw_ext, (" extends " . end($m)), $file_content);
			file_put_contents($args[5], $file_content);
			return true;
		} else return '';
	}

	/**
	 * Returns classes to import into a namespace
	 * 
	 * @param  string        $file_content
	 * @param  array|string  $classes
	 * @param  string        $pattern
	 * @return array
	 */
	public function checkedClassImport($file_content, $classes, $pattern = null) : array
	{
		$pattern = is_null($pattern) ? '/^<\\?php[\s\S]+namespace.*([\s\S]+;)[\s\S]+class/i' : $pattern;
		$ret = [];
		$classes = is_string($classes) ? [$classes] : $classes;
		if(is_array($classes) && !empty($classes)){
			foreach ($classes as $cls) {
				preg_match($pattern, $file_content, $m);
				$m = explode(';', str_replace(['; ', 'use '], [';', ''], trim(preg_replace('/[\s]+/i', ' ', preg_replace('/as .*| as .*/i', '', end($m))))));
				// echo $file_content . "\n";
				if(!in_array($cls, $m))
					$ret[] = $cls;
			}
		}
		return $ret;
	}

	/**
	 * Returns a structured string for the properties of a Model of a Laravel DB
	 * 
	 * @param  array          $arguments
	 * @param  array|string   $template
	 * @param  array          $conf
	 * @param  array          $new_conf
	 * @param  callable|null  $call_render
	 * @return string
	 */
	public function propertyModel($arguments, $template, $conf, $new_conf, $call_render = null){
		$render = '';
		$template = is_array($template) ? $template : [$template];
		// $call_col = config('compio-db.conf.helpers.colone');
		// $call_in_array = config('compio-db.conf.helpers.in-array');
		$call_render = is_null($call_render) ? function($datas){
			return str_replace("\n","\n\t\t", "\n" . preg_replace('/([a-z0-9_]+)/i', '"$1"', implode(",\n", array_keys($datas)))) . "\n\t";
		} : $call_render;
		$datas = [];
		if(isset($arguments['columns']) && !empty($arguments['columns']) && is_array($arguments['columns'])){
			$cols = $this->colone($arguments['columns']);
			$conf['new'] = is_array($new_conf)
				? $new_conf
				: []
			;

			if(isset($conf['new']['content']) && (is_string($conf['new']['content']) || is_callable($conf['new']['content']))) $render = !is_string($conf['new']['content']) ? $conf['new']['content'](...[$cols, $conf]) : $conf['new']['content'];
			elseif(isset($conf['default']['content']) && (is_string($conf['default']['content']) || is_callable($conf['default']['content']))) $render = !is_string($conf['default']['content']) ? $conf['default']['content'](...[$cols, $conf]) : $conf['default']['content'];
			elseif(!isset($conf['new']['content'])){
				$except = isset($conf['new']['excepts']) && is_array($conf['new']['excepts']) ? $conf['new']['excepts'] : $conf['default']['excepts'];
				$exists = isset($conf['new']['exists']) && is_array($conf['new']['exists']) ? $conf['new']['exists'] : $conf['default']['exists'];
				$all = isset($conf['new']['all']) ? ((bool) $conf['new']['all']) : (isset($conf['default']['all']) ? ((bool) $conf['default']['all']) : true);
				foreach ($cols['cols'] as $col => $conf_) {

					if($this->inArray($col, $exists) === true && !in_array($col, $datas)) $datas[$col] = $conf_;
					// if(in_array($col, $exists) && !in_array($col, $datas)) $datas[$col] = $conf_;
					if($this->inArray($col, $except) === false && $all === true && !in_array($col, $datas)) $datas[$col] = $conf_;
				}
				$rr = $call_render($datas);
				$render = (empty($datas) && count($template) > 1 && end($template) === true || empty($rr)) ? '' : str_replace('---replace---', $rr, current($template));
			}
		}
		dump($render);
		return $render;
	}

	/**
	 * Returns a structured string for the functions (or methods) of a Model of a Laravel DB
	 * 
	 * @param  array     $args
	 * @param  array     $conf
	 * @param  string    $model_class
	 * @param  string    $keyword_
	 * @param  array     $default_dts
	 * @param  array     $new_dts
	 * @param  callable  $return_render
	 * @param  callable  $description_render
	 * @return string|null|bool|mixed
	 */
	public function functionModel($args, $conf, $model_class, $keyword_, $default_dts, $new_dts, $return_render, $description_render){
		$arguments = $args[2];
		$file_content = $args[4];
		$file = $args[5];
		// $call_col = config('compio-db.conf.helpers.colone');
		$k_b = $this->template_config['keyword'];
		$struct = [
			'type_returned' => [],
			'args' => [],
		];
		$datas = ['default' => $default_dts
		];
		$datas['new'] = $new_dts;
		$render = '';
		$import_class = [];
		if(isset($datas['new']['cols']['content']) && (is_string($datas['new']['cols']['content']) || is_callable($datas['new']['cols']['content'])))
			return !is_string($datas['new']['cols']['content']) ? $datas['new']['cols']['content'](...$arguments) : $datas['new']['cols']['content'];
		elseif(!isset($datas['new']['cols']['content'])){
			$functions = !empty($datas['new']) && is_array($datas['new']) && isset($datas['new']['cols'])? $datas['new']['cols'] : (isset($datas['default']['cols']) ? $datas['default']['cols'] : []);
			if(empty($functions)) return '';
			$cols = $this->colone($arguments['columns'])['cols'];
			// $fun_struct = $this->functionStructure;
			$functions = $this->functionArray($functions);
			$import_class = $functions['class'];
			foreach ($functions['datas'] as $function_name => $value) {
				$value['type_returned'] = is_array($value['type_returned']) && !empty($value['type_returned'])
					? array_map(function($type){
						preg_match('/^.*\\\(.*)|.*$/i', $type, $m);
						return end($m);
					}, $value['type_returned'])
					: null
				;
				// dump($value['args']);
				$value['args'] = is_array($value['args']) && !empty($value['args'])
					? array_map(function($arg_type, $arg_name){
						// dump(['name' => $arg_name, 'type' => $arg_type]);exit;
						$type = is_array($arg_type) && !empty($arg_type)
							? array_map(function($type){
								preg_match('/^.*\\\(.*)|.*$/i', $type, $m);
								return end($m);
							}, $arg_type)
							: null
						;
						return (!empty($type)
							? implode('|', $type) . " "
							: null
						) . "$" . $arg_name;
					}, $value['args'], array_keys($value['args']))
					: null
				;
				$value['description'] = is_null($value['description'])
					? $description_render(...[$function_name, $value, $model_class]) . "\n" . (!empty($value['args']) ? "\n@param " . (
						preg_replace('/(.*\\$.*) =.*|(.*\\$.*)=.*/i', '$1$2', implode("\n@param ", $value['args']))
					) : null) . (
						!empty($functions['datas'][$function_name]['type_returned'])
							? "\n" . "@return " . implode('|', $functions['datas'][$function_name]['type_returned']) 
							: null
					)
					: $value['description']
				;

				$render .= (empty($render) ? "" : "\n") . $this->functionStructure($function_name, (!is_string($value['description']) && is_callable($value['description'])
					? $value['description'](...[$function_name, $value, $functions])
					: $value['description']
				), $value['access'], $value['args'], $value['type_returned'], ((is_string($value['code']) || is_array($value['code'])) ? $value['code'] : null), (is_string($value['return']) || is_callable($value['return'])
					? (!is_string($value['return'])
						? $value['return'](...[$function_name, $value, $model_class, $cols, $return_render])
						: $value['return']
					)
					: $return_render(...[$function_name, $value, $model_class])
				), "\t") . "\n";
			}
		}
		if(empty($import_class)) return $render;
		else{
			$file_content = str_replace('@model_import_class', ('use ' . implode(";\nuse ", $import_class) . ";\n@model_import_class"), $file_content);
			$file_content = str_replace($keyword_, $render . "\n", $file_content);
			file_put_contents($file, $file_content);
			return true;
		}
	}

	/**
	 * Returns an array data structure by changing the string value of an element to indexing
	 * keys if the key is a number
	 * 
	 * @param  array|string      $colones
	 * @param  bool|boolean      $feature
	 * @param  bool|boolean      $cols
	 * @param  null|array|mixed  $default_
	 * @return array
	 */
	public function colone($colones, bool $feature = true, bool $cols = true, $default_ = null) : array
	{
		$ret = [];
		$feature = $cols === false ? false : $feature;
		$colones = is_string($colones) ? [$colones] : $colones;
		foreach ($colones as $key => $value) {
			if(is_numeric($key) && is_string($value)){
				$key = $value;
				$value = $default_;
			}
			elseif(is_numeric($key) && !is_string($value)) continue;

			if(preg_match('/^__(.*)/i', $key, $match) && $feature === true){
				
				$ret['feature'][$key] = $value;

				if(end($match) == 'timestamps'){
					$ret['cols']['created_at'] = isset($ret['cols']['created_at']) ? $ret['cols']['created_at'] : null; 
					$ret['cols']['updated_at'] = isset($ret['cols']['updated_at']) ? $ret['cols']['updated_at'] : null;
				}
				if(end($match) == 'soft_deletes') $ret['cols']['deleted_at'] = isset($ret['cols']['deleted_at']) ? $ret['cols']['deleted_at'] : null;
				if(end($match) == 'remember_token') $ret['cols']['remember_token'] = isset($ret['cols']['remember_token']) ? $ret['cols']['remember_token'] : null;
			}
			else{
				if($cols) $ret['cols'][$key] = $value;
				else $ret[$key] = $value;
			}
		}
		return $ret;
	}

	/**
	 * Returns an enclosing string of `resource_path(...)`
	 * 
	 * @param  string  $default_value
	 * @return string
	 */
	public function callLocate(string $default_value) : string
	{
		return empty(trim($default_value)) || trim($default_value) == "''" ? '' : ('resource_path(' . implode('), resource_path(', explode(',', str_replace(["\n", "\r", "\t"], '', $default_value))) . ')');
	}

}