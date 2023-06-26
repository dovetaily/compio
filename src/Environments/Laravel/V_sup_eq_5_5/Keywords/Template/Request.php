<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base;
use Illuminate\Support\Str;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Helpers;

class Request extends Base {

	use Helpers;


	/**
	 * [model_full_class description]
	 * 
	 * @return [type] [description]
	 */
	public function model_full_class(){
		return isset($this->all_keywords['model']['@model_full_class']) ? $this->all_keywords['model']['@model_full_class'] : '';
	}

	/**
	 * [model_class description]
	 * 
	 * @return [type] [description]
	 */
	public function model_class(){
		return isset($this->all_keywords['model']['@model_class']) ? $this->all_keywords['model']['@model_class'] : '';
	}

	/**
	 * [model_namespace description]
	 * 
	 * @return [type] [description]
	 */
	public function model_namespace(){
		return isset($this->all_keywords['model']['@model_namespace']) ? $this->all_keywords['model']['@model_namespace'] : '';
	}

	/**
	 * [request_namespace description]
	 * 
	 * @return [type] [description]
	 */
	public function request_namespace(){
		return str_replace('/', '\\', ucfirst(pathinfo($this->getRelativeFilePath())['dirname']));
		// return 'App' . preg_replace('/^'.preg_quote(app_path(), "/").'(.*)/', '$1', pathinfo($this->file_path)['dirname']);
	}

	/**
	 * [request_class description]
	 * 
	 * @return [type] [description]
	 */
	public function request_class(){
		return pathinfo($this->file_path)['filename'];
		// return end($this->template_datas['path'])['filename'];
	}

	/**
	 * [request_full_class description]
	 * 
	 * @return [type] [description]
	 */
	public function request_full_class(){
		return $this->template_datas['keywords']['@request_namespace']['result'] . '\\' . $this->template_datas['keywords']['@request_class']['result'];
	}

	/**
	 * [request_extends description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function request_extends(...$args){
		return $this->extend($args, 'request', 'Illuminate\Foundation\Http\FormRequest','@request_import_class', '@request_extends');
	}

	/**
	 * [request_implements description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function request_implements(...$args){
		return $this->implement($args, 'request', null, '@request_import_class', '@request_implements');
	}

	/**
	 * [request_import_trait description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function request_import_trait(...$args){
		return $this->importTrait($args, 'request', [/*--traits--*/], '@request_import_class', '@request_import_trait');
	}

	/**
	 * [request_datas description]
	 * 
	 * @return [type] [description]
	 */
	public function request_datas(){
		$request_type = preg_match('/\\\.*' . preg_quote('storerequest.php') . '$/i', $this->file_path) ? 'store' : 'update';
		// $call_col = config('compio-db.conf.helpers.colone');

		$m_nsp = $this->all_keywords['request']['@model_namespace'];
		$m_cs = $this->all_keywords['request']['@model_class'];
		$m_f_cs = $this->all_keywords['request']['@model_full_class'];
		$m_prop = Str::snake($this->all_keywords['request']['@model_class']);
		$mt_prop = '$this->' . $m_prop;

		$datas = (function($args, $call_back, $request_type, $m_nsp){
			$ret = [];
			$cols = $call_back->colone($args['columns']);
			foreach ((isset($cols['cols']) ? $cols['cols'] : []) as $column => $value) {

				$primary_key = isset($args['model']['primary_key']) ? $args['model']['primary_key'] : (isset($value['primary_key']) ? $value['primary_key'] : 'id');
				
				if($column != $primary_key && $column != 'created_at' && $column != 'updated_at' && $column != 'deleted_at' && $column != 'remember_token'){

					$ret[$request_type]['rules'][$column] = [];

					$modifiers_exists = is_array($value) && isset($value["modifiers"]) && is_array($value["modifiers"]);

					$require_exists = !(
						$modifiers_exists && (
							in_array('nullable', $value["modifiers"]) || isset($value["modifiers"]['nullable']) ||
							in_array('default', $value["modifiers"]) || isset($value["modifiers"]['default']) ||
							in_array('useCurrent', $value["modifiers"]) || isset($value["modifiers"]['useCurrent'])
						)
					);

					if($request_type == 'store' && $require_exists){
						$ret[$request_type]['rules'][$column][] = 'required';
						$ret[$request_type]['messages'][$column . '.required'] = '`' . $column . '` is required';
					}

					// $table = is_array($value) && isset($value['table']) ? $value['table'] : (isset($this->all_keywords['migration']['@migration_table']) ? $this->all_keywords['migration']['@migration_table'] : $this->template_name);
					$table = isset($this->all_keywords['migration']['@migration_table']) ? $this->all_keywords['migration']['@migration_table'] : $this->template_name;

					if(preg_match('/(.*)_id$/i', $column, $m)){
						$ret[$request_type]['rules'][$column][] = 'exists:' . (isset($value['model_class']) ? trim((function($v){$v_ = preg_match('/(.*)as.*/i', $v, $m); return $v_ ? trim(end($m)) : $v;})($value['model_class']), '\\') : ($m_nsp . '\\' . ucfirst(Str::camel(end($m))))) . ',id';
						if($request_type == 'store' && $require_exists)
							$ret[$request_type]['messages'][$column . '.required'] = '`' . end($m) . '` is required';
						$ret[$request_type]['messages'][$column . '.exists'] = '`' . end($m) . '` doesn\'t exists !';
					}

					// if(!$require_exists && $request_type != 'update') $ret[$request_type]['rules'][$column][] = 'nullable';

					foreach ([
						'nullable' => ['cnd' => !$require_exists /*&& $request_type != 'update'*/, 'message' => null],
						'unique' => ['val' => 'unique:' . $table . ',' . $column, 'message' => '`' . $column . '` is already exists'],
					] as $modif => $vv) {
						$vv = is_array($vv) ? $vv : ['val' => $vv];
						$cnd = array_key_exists('cnd', $vv) ? $vv['cnd'] : $modifiers_exists && (in_array($modif, $value["modifiers"]) || isset($value["modifiers"][$modif]));
						if($cnd){
							$ret[$request_type]['rules'][$column][] = $vv['val'] ?? $modif;
							if(!array_key_exists('message', $vv) || $vv['message'] !== null)
								$ret[$request_type]['messages'][$column . '.' . ($vv['rule'] ?? $modif)] = $vv['message'] ?? null;
						}
					}

					$v_type = isset($value['type']) ? $this->colone($value['type'], false, false) : []; 

					$enum = (isset($v_type['enum']) ? (function($str){
						try {
							eval('$val = ' . $str . ';');
							$str = $val;
						} catch (\Exception $e) {}
						return ['o' => $str, 't' => is_array($str) ? '##-->' . str_replace(["\n", "\r", "\t"], '', var_export($str, true)) . '<--##' : $str];
					})(end($v_type['enum'])) : ['t' => null, 'o' => []]);

					foreach ([
						'enum' => ['val' => $enum['t'], 'message' => '`' . $column . '` is not Conform with values(' . (is_string($enum['o']) ? $enum : implode(', ', $enum['o'])) . ')'],
						'string' => ['val' => null, 'message' => '`' . $column . '` is not String'],
						'text' => ['val' => 'string', 'rule' => 'string', 'message' => '`' . $column . '` is not String'],
						'decimal' => 'decimal:'.(array_key_exists('decimal', $v_type) && is_array($v_type['decimal']) ? (implode(',', (function($vv){ $tab = []; foreach ($vv as $key => $value) {if($value != '#') $tab[] = $value;} return $tab; })($v_type['decimal']))) : 'x,x'),
						'boolean' => ['val' => null, 'message' => '`' . $column . '` is not Boolean'],
						'json' => ['val' => null, 'message' => '`' . $column . '` is not Conform to Json'],
						'integer' => ['val' => null, 'message' => '`' . $column . '` is not Integer'],
					] as $type => $vv) {
						$vv = is_array($vv) ? $vv : ['val' => $vv];
						$cnd = array_key_exists('cnd', $vv) ? $vv['cnd'] : array_key_exists($type, $v_type);
						if($cnd){
							$ret[$request_type]['rules'][$column][] = $vv['val'] ?? $type;
							// if(array_key_exists('message', $vv))
							if(!array_key_exists('message', $vv) || $vv['message'] !== null)
								$ret[$request_type]['messages'][$column . '.' . ($vv['rule'] ?? $type)] = $vv['message'] ?? null;
						}
					}
				}
			}
			return $ret;
		})($this->arguments, $this, $request_type, $m_nsp);
		$render = '';
		// dump($datas);exit;
		if(isset($this->arguments['request'][$request_type]['#content']) && (is_string($this->arguments['request'][$request_type]['#content']) || is_callable($this->arguments['request'][$request_type]['#content'])))
			return !is_string($this->arguments['request'][$request_type]['#content']) ? $this->arguments['request'][$request_type]['#content'](...$this->arguments) : $this->arguments['request'][$request_type]['#content'];
		elseif(!isset($this->arguments['request'][$request_type]['#content'])){
			$datas[$request_type]['rules'] = array_key_exists('rules', $rt = (isset($this->arguments['request'][$request_type]) && ($rr = $this->arguments['request'][$request_type]) ? $rr : []))
				? (!empty($rt['rules'])
					? $this->colone($rt['rules'], false, false)
					: null
				)
				: (isset($datas[$request_type]['rules']) ? $datas[$request_type]['rules'] : '')
			;
			$datas[$request_type]['messages'] = array_key_exists('messages', $rt = (isset($this->arguments['request'][$request_type]) && ($rr = $this->arguments['request'][$request_type]) ? $rr : []))
				? (!empty($rt['messages'])
					? $this->colone($rt['messages'], false, false)
					: null
				)
				: (isset($datas[$request_type]['messages']) ? $datas[$request_type]['messages'] : '')
			;
			$ret = [];
			$file_content = $this->file_content;
			foreach ($datas[$request_type] as $method => $value){
				$k_word = '@request_' . $method;
				$render = '[]';
				if(!empty($value)){
					$render = str_replace(["  ", "\n"], ["\t", "\n\t\t"], var_export($value, true));
					// $render = preg_replace_callback('/.*\\'(--><--)\\'/', callback, subject);
					$render = preg_replace_callback('/(.*)\'##-->(.*)<--##\'(.*)/', function($_){
						return $_[1] . 'Rule::in(' . str_replace(['\\\'', "\t", ",)"], ["'", "", ')'], $_[2]) . ')' . $_[3];
					}, $render);
					// $render = preg_replace('/(.*)\'-->(.*)<--\'(.*)/', '$1Rule::in($2)$3', $render);
				}
				$file_content = str_replace($k_word, $render, $file_content);
			}
			file_put_contents($this->file_path, $file_content);
		}
		return true;
	}

	/**
	 * [request_authorize description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function request_authorize(...$args){
		$request_type = preg_match('/\\\.*' . preg_quote('storerequest.php') . '$/i', $this->file_path) ? 'store' : 'update';
		$datas = !isset($this->arguments[$request_type]) || !array_key_exists('authorize', $this->arguments[$request_type]['request']) || $this->arguments[$request_type]['request']['authorize'] === true
			? 'return true;'
			: $this->arguments[$request_type]['request']['authorize']
		;
		return is_string($datas) ? str_replace("\n", "\n\t\t", $datas) : 'return false;';
	}

	/**
	 * [request_import_class description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function request_import_class(...$args){
		return $this->importClass($args, 'request', [
			'Illuminate\Validation\Rule'
		]);
	}


}