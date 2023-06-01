<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base;
use Illuminate\Support\Str;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Helpers;

class Repository extends Base {

	use Helpers;

	public function repository_namespace(){
		return 'App' . preg_replace('/^'.preg_quote(app_path()).'(.*)/', '$1', pathinfo($this->file_path)['dirname']);
		// return 'App\Repositories' . (($n = end($this->template_datas['path'])['short_dirname']) != '' ? ('\\' . $n) : null);
	}

	public function repository_class(){
		return end($this->template_datas['path'])['filename'];
	}

	public function repository_full_class(){
		// dump($this->all_keywords['model']['@model_full_class']);
		return $this->template_datas['keywords']['@repository_namespace']['result'] . '\\' . $this->template_datas['keywords']['@repository_class']['result'];
	}

	public function model_full_class(){
		return isset($this->all_keywords['model']['@model_full_class']) ? $this->all_keywords['model']['@model_full_class'] : '';
	}

	public function model_class(){
		return isset($this->all_keywords['model']['@model_class']) ? $this->all_keywords['model']['@model_class'] : '';
	}

	public function repository_extends(...$args){
		return $this->extend($args, 'repository', null, '@repository_import_class', '@repository_extends');
	}

	public function repository_implements(...$args){
		return $this->implement($args, 'repository', null, '@repository_import_class', '@repository_implements');
	}

	public function repository_import_trait(...$args){
		return $this->importTrait($args, 'repository', [
			// 'Illuminate\Database\Eloquent\Factories\HasFactory',
		], '@repository_import_class', '@repository_import_trait');
	}

	// public function repository_methods(...$args){
	public function repository_methods(){
		// dump($this->all_keywords['model']);
		$datas = isset($this->arguments['repository']['methods']) ? $this->arguments['repository']['methods'] : [];
		$conf = isset($this->arguments['repository']['methods']['__conf']) ? $this->arguments['repository']['methods']['__conf'] : [];
		$m_cs = $this->all_keywords['repository']['@model_class'];
		$m_f_cs = $this->all_keywords['repository']['@model_full_class'];
		$m_prop = Str::snake($this->all_keywords['repository']['@model_class']);
		$mt_prop = '$this->' . $m_prop;

		// CONSTRUCT  START  .......
			$datas['__construct'] = array_key_exists('__construct', $datas) && is_null($datas['__construct']) ? null : [
				'type_returned' => isset($datas['__construct']['type_returned']) ? $datas['__construct']['type_returned'] : null,
				'args' => isset($datas['__construct']['args']) ? $datas['__construct']['args'] : [
					($m_prop . ' = null') => $m_cs
				],
				'access' => isset($datas['__construct']['access']) ? $datas['__construct']['access'] : 'public',
				'description' => isset($datas['__construct']['description']) ? $datas['__construct']['description'] : $this->all_keywords['repository']['@repository_class'] . ' constructor',
				// 'description' => isset($datas['__construct']['description']) ? $datas['__construct']['description'] : function($function_name, $value, $functions, $desc, $this->all_keywords, $datas = []){
				// 	if(!empty($datas) && is_array($datas)) extract($datas);
				// 	return $desc ($this->all_keywords['repository']['@repository_class'] . ' constructor', $value['args'], [$m_cs]);
				// },
				'code' => isset($datas['__construct']['code']) ? (is_callable($cl = $datas['__construct']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['__construct']['code']) : $mt_prop . " = $" . $m_prop . " ?? new " . $m_cs . ";",
				'return' => isset($datas['__construct']['return']) ? $datas['__construct']['return'] : null
			];
		// CONSTRUCT  STOP  .......
		
		// STORE  START  .......
			$datas['store'] = [
				'type_returned' => isset($datas['store']['type_returned']) ? $datas['store']['type_returned'] : $m_f_cs,
				'args' => isset($datas['store']['args']) ? $datas['store']['args'] : [
					'fields' => 'array'
				],
				'access' => isset($datas['store']['access']) ? $datas['store']['access'] : 'public',
				'description' => isset($datas['store']['description']) ? $datas['store']['description'] : 'Store ' . $m_cs,
				// 'description' => isset($datas['store']['description']) ? $datas['store']['description'] : function($function_name, $value, $functions, $desc, $this->all_keywords, $datas = []){
				// 	if(!empty($datas) && is_array($datas)) extract($datas);
				// 	return $desc ('Create ' . $m_cs, $value['args'], [$m_cs]);
				// },
				'code' => isset($datas['store']['code']) ? (is_callable($cl = $datas['store']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['store']['code']) : null,
				'return' => isset($datas['store']['return']) ? $datas['store']['return'] : $mt_prop . '->create($fields)'
			];	
		// STORE  STOP  .......
		
		// UPDATE  START  .......
			$datas['update'] = [
				'type_returned' => isset($datas['update']['type_returned']) ? $datas['update']['type_returned'] : $m_f_cs,
				'args' => isset($datas['update']['args']) ? $datas['update']['args'] : [
					'fields' => 'array',
					'id'
				],
				'access' => isset($datas['update']['access']) ? $datas['update']['access'] : 'public',
				'description' => isset($datas['update']['description']) ? $datas['update']['description'] : 'Update ' . $m_cs,
				'code' => isset($datas['update']['code'])
					? (is_callable($cl = $datas['update']['code']) && !is_string($cl)
						? $cl(...[$this->arguments, $this->all_keywords]) 
						: $datas['update']['code']
					) 
					: ["\$" . strtolower($m_cs) . "_model_instance = \$this->getById(\$id);\n", "\tforeach(\$fields as \$property => \$value)", "\t\t\$" . strtolower($m_cs) . "_model_instance->\$property = \$value;\n", "\t\$" . strtolower($m_cs) . "_model_instance->save();\n"],
				'return' => isset($datas['update']['return']) ? $datas['update']['return'] : '$this->getById($id)'
			];
		// UPDATE  STOP  .......
		
		// DELETE  START  .......
			$datas['delete'] = [
				'type_returned' => isset($datas['delete']['type_returned']) ? $datas['delete']['type_returned'] : null,
				'args' => isset($datas['delete']['args']) ? $datas['delete']['args'] : [
					'id'
				],
				'access' => isset($datas['delete']['access']) ? $datas['delete']['access'] : 'public',
				'description' => isset($datas['delete']['description']) ? $datas['delete']['description'] : 'Delete ' . $m_cs,
				'code' => isset($datas['delete']['code']) ? (is_callable($cl = $datas['delete']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['delete']['code']) : '$this->getById($id)->delete();',
				'return' => isset($datas['delete']['return']) ? $datas['delete']['return'] : null
			];
		// DELETE  STOP  .......
		
		// // EXIST  START  .......
			if(isset($datas['exists']) && $datas['exists'] === true)
				$datas['exists'] = [
					'type_returned' => isset($datas['exists']['type_returned']) ? $datas['exists']['type_returned'] : 'bool',
					'args' => isset($datas['exists']['args']) ? $datas['exists']['args'] : [
						'field', 'value', 'condition =\'=\'' => 'string'
					],
					'access' => isset($datas['exists']['access']) ? $datas['exists']['access'] : 'public',
					'description' => isset($datas['exists']['description']) ? $datas['exists']['description'] : 'Check if `' . $m_cs . '` entry model exists',
					'code' => isset($datas['exists']['code']) ? (is_callable($cl = $datas['exists']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['exists']['code']) : null,
					'return' => isset($datas['exists']['return']) ? $datas['exists']['return'] : $mt_prop . '->where($field, $condition, $value)->exists()'
				];
		// EXIST  STOP  .......

		// ALL  GET SEARCH

			$dt = $this->getMigrationDatas($this->arguments, $this, $this->template_config['foreign_default']);
			foreach ($dt as $function_name => $value) {
				if(is_array($value['type']) && !empty($value['type']) && (function($types, $patterns){
					foreach ($types as $type)
						foreach($patterns as $pattern)
							if(preg_match('/' . $pattern . '/i', $type)) return true;
					return false;
				})(array_keys($value['type']), ['string', '.*text']) === true){
					$f_name = 'getSearchBy'. ucfirst(Str::camel($function_name));
					$arg_ = Str::snake($function_name);
					$datas[$f_name] = [
						'type_returned' => isset($datas[$f_name]['type_returned']) ? $datas[$f_name]['type_returned'] : null,
						'args' => isset($datas[$f_name]['args']) ? $datas[$f_name]['args'] : [
							$arg_ => 'string'
							// 'field', 'value', 'condition =\'=\'' => 'string'
						],
						'access' => isset($datas[$f_name]['access']) ? $datas[$f_name]['access'] : 'public',
						'description' => isset($datas[$f_name]['description']) ? $datas[$f_name]['description'] : 'Get `' . $m_cs . '` by `' . $function_name . '`',
						'return' => isset($datas[$f_name]['return']) ? $datas['exists']['return'] : "$" . $m_prop . "->get()"
						// 'return' => isset($datas[$f_name]['return']) ? $datas['exists']['return'] : "$" . $m_prop . "->orderBy('created_at','asc')->get()"
					];
					$datas[$f_name]['code'] = isset($datas[$f_name]['code'])
						? (is_callable($cl = $datas[$f_name]['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas[$f_name]['code']) 
						: '$' . $m_prop . " = " . $m_cs . "::query();\n\tif(!empty($" . $arg_ . ")){\n\t\t$" . $m_prop . "->where('".$function_name."', 'LIKE', '%' . $" . $arg_ . " . '%');\n\t}";
				}
			}

		// ALL  GET SEARCH

		// GET_ALL  START  .......
			$datas['getAll'] = [
				'type_returned' => isset($datas['getAll']['type_returned']) ? $datas['getAll']['type_returned'] : '__eloquentDBCollection',
				'args' => isset($datas['getAll']['args']) ? $datas['getAll']['args'] : [],
				'access' => isset($datas['getAll']['access']) ? $datas['getAll']['access'] : 'public',
				'description' => isset($datas['getAll']['description']) ? $datas['getAll']['description'] : 'Get all ' . $m_cs,
				'code' => isset($datas['getAll']['code']) ? (is_callable($cl = $datas['getAll']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['getAll']['code']) : null,
				'return' => isset($datas['getAll']['return']) ? $datas['getAll']['return'] : $mt_prop . '->all()'
			];
		// GET_ALL  STOP  .......
		
		// GET_BY_ID  START  .......
			$datas['getById'] = [
				'type_returned' => isset($datas['getById']['type_returned']) ? $datas['getById']['type_returned'] : $m_f_cs,
				'args' => isset($datas['getById']['args']) ? $datas['getById']['args'] : [
					'id'
				],
				'access' => isset($datas['getById']['access']) ? $datas['getById']['access'] : 'public',
				'description' => isset($datas['getById']['description']) ? $datas['getById']['description'] : 'Get `' . $m_cs . '`',
				'code' => isset($datas['getById']['code']) ? (is_callable($cl = $datas['getById']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['getById']['code']) : null,
				'return' => isset($datas['getById']['return']) ? $datas['getById']['return'] : $mt_prop . '->find($id)'
			];
		// GET_BY_ID  STOP  .......
		
		// // GET  START  .......
		// 	$datas['get'] = [
		// 		'type_returned' => isset($datas['get']['type_returned']) ? $datas['get']['type_returned'] : null,
		// 		'args' => isset($datas['get']['args']) ? $datas['get']['args'] : [
					
		// 		],
		// 		'access' => isset($datas['get']['access']) ? $datas['get']['access'] : 'public',
		// 		'description' => isset($datas['get']['description']) ? $datas['get']['description'] : null,
		// 		'code' => isset($datas['get']['code']) ? (is_callable($cl = $datas['get']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['get']['code']) : null,
		// 		'return' => isset($datas['get']['return']) ? $datas['get']['return'] : null
		// 	];
		// // GET  STOP  .......
		
		// GET_PAGINATE  START  .......
			$datas['getPaginate'] = [
				'type_returned' => isset($datas['getPaginate']['type_returned']) ? $datas['getPaginate']['type_returned'] : 'Illuminate\Contracts\Pagination\LengthAwarePaginator',
				'args' => isset($datas['getPaginate']['args']) ? $datas['getPaginate']['args'] : [
					'n = 15' => 'int'
				],
				'access' => isset($datas['getPaginate']['access']) ? $datas['getPaginate']['access'] : 'public',
				'description' => isset($datas['getPaginate']['description']) ? $datas['getPaginate']['description'] : 'Get a paginate list',
				'code' => isset($datas['getPaginate']['code']) ? (is_callable($cl = $datas['getPaginate']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['getPaginate']['code']) : null,
				'return' => isset($datas['getPaginate']['return']) ? $datas['getPaginate']['return'] : $mt_prop . '->paginate($n)'
			];
		// GET_PAGINATE  STOP  .......
		
		// // GET_LIST_BY_PAGINATION  START  .......
		// 	$datas['getListByPagination'] = [
		// 		'type_returned' => isset($datas['getListByPagination']['type_returned']) ? $datas['getListByPagination']['type_returned'] : null,
		// 		'args' => isset($datas['getListByPagination']['args']) ? $datas['getListByPagination']['args'] : [
					
		// 		],
		// 		'access' => isset($datas['getListByPagination']['access']) ? $datas['getListByPagination']['access'] : 'public',
		// 		'description' => isset($datas['getListByPagination']['description']) ? $datas['getListByPagination']['description'] : null,
		// 		'code' => isset($datas['getListByPagination']['code']) ? (is_callable($cl = $datas['getListByPagination']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['______']['code']) : null,
		// 		'return' => isset($datas['getListByPagination']['return']) ? $datas['getListByPagination']['return'] : null
		// 	];
		// // GET_LIST_BY_PAGINATION  STOP  .......
		
		// // GET_LIST  START  .......
		// 	$datas['getList'] = [
		// 		'type_returned' => isset($datas['getList']['type_returned']) ? $datas['getList']['type_returned'] : null,
		// 		'args' => isset($datas['getList']['args']) ? $datas['getList']['args'] : [
					
		// 		],
		// 		'access' => isset($datas['getList']['access']) ? $datas['getList']['access'] : 'public',
		// 		'description' => isset($datas['getList']['description']) ? $datas['getList']['description'] : null,
		// 		'code' => isset($datas['getList']['code']) ? (is_callable($cl = $datas['getList']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['______']['code']) : null,
		// 		'return' => isset($datas['getList']['return']) ? $datas['getList']['return'] : null
		// 	];
		// // GET_LIST  STOP  .......
		
		// DESTROY  START  .......
			if(isset($datas['destroy']) && $datas['destroy'] === true)
				$datas['destroy'] = [
					'type_returned' => isset($datas['destroy']['type_returned']) ? $datas['destroy']['type_returned'] : null,
					'args' => isset($datas['destroy']['args']) ? $datas['destroy']['args'] : [
						'ids' => 'array'
					],
					'access' => isset($datas['destroy']['access']) ? $datas['destroy']['access'] : 'public',
					'description' => isset($datas['destroy']['description']) ? $datas['destroy']['description'] : 'Destroy ' . $m_cs,
					'code' => isset($datas['destroy']['code']) ? (is_callable($cl = $datas['destroy']['code']) && !is_string($cl) ? $cl(...[$this->arguments, $this->all_keywords]) : $datas['destroy']['code']) : $mt_prop . '->destroy($ids);',
					'return' => isset($datas['destroy']['return']) ? $datas['destroy']['return'] : null
				];
		// DESTROY  STOP  .......

		// $fun_struct = config('compio-db.conf.helpers.function_structure');
		$functions = $this->functionArray($datas, ['access' => 'public']);
		// $checked_class_import = config('compio-db.conf.helpers.checked_class_import');
		$render = '';
		$description_render = function($function_name, $value){
			return $function_name;
			};
		$import_class = $functions['class'];
		$desc = function($description, $args, $type_returned){
			$description = is_string($description) ? [$description] : $description;
			$type_returned = is_string($type_returned) ? [$type_returned] : $type_returned;
			return ((!empty($description) ? implode("\n", $description) . "\n" : null) . (!empty($args) 
					? "\n@param " . (preg_replace('/(.*\\$.*) =.*|(.*\\$.*)=.*/i', '$1$2', implode("\n@param ", $args)))
					: null
				) . (!empty($type_returned)
					? "\n" . "@return " . implode('|', $type_returned) 
					: null
				)
			);
		};
			// dump($functions['datas']);
		foreach ($functions['datas'] as $function_name => $value) {
			if(!empty($value)){
				$value['type_returned'] = is_array($value['type_returned']) && !empty($value['type_returned'])
					? array_map(function($type){
						preg_match('/^.*\\\.* as (.*)|^.*\\\(.*)|.*$/i', $type, $m);
						return end($m);
					}, $value['type_returned'])
					: null
				;

				$value['args'] = is_array($value['args']) && !empty($value['args'])
					? array_map(function($arg_type, $arg_name){
						// dump(['name' => $arg_name, 'type' => $arg_type]);exit;
						$type = is_array($arg_type) && !empty($arg_type)
							? array_map(function($type){
								preg_match('/^.*\\\.* as (.*)|^.*\\\(.*)|.*$/i', $type, $m);
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

				$value['description'] = is_array($value['description']) || empty($value['description'])
					? $desc($value['description'], $value['args'], $functions['datas'][$function_name]['type_returned'])
					: (is_callable($value['description']) && !is_string($value['description'])
						? $value['description'](...[$function_name, $value, $functions, $desc, $this->all_keywords, ['m_cs' => $m_cs, 'mt_prop' => $mt_prop, 'mt_prop' => $mt_prop]])
						: $value['description']
					)
				;

				if(!empty($value)){
					$render .= $this->functionStructure($function_name, $value['description'], $value['access'], $value['args'], $value['type_returned'], (!empty($value['code']) ? "\n\t". (is_array($value['code']) ? implode("\n", $value['code']) : $value['code']) : null), $value['return'], "\t") . "\n\n";
				}
			}
		}
		$import_class = $this->checkedClassImport($this->file_content, $import_class);
		if(empty($import_class)) return $render;
		else{
			$this->file_content = str_replace('@repository_import_class', ('use ' . implode(";\nuse ", $import_class) . ";\n@repository_import_class"), $this->file_content);
			$this->file_content = str_replace('@repository_methods', $render . "\n", $this->file_content);
			file_put_contents($this->file_path, $this->file_content);
			return true;
		}
		return $render;
	}

	public function repository_import_class(...$args){
		return $this->importClass($args, 'repository', [
			// '__eloquentModel',
			'__eloquentDBCollection',
			'Illuminate\Contracts\Pagination\LengthAwarePaginator',
		]);
	}

}