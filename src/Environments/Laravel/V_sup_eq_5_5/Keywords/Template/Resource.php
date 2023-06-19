<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base;
use Illuminate\Support\Str;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Helpers;

class Resource extends Base {

	use Helpers;

	/**
	 * [resource_namespace description]
	 * 
	 * @return [type] [description]
	 */
	public function resource_namespace(){
		return str_replace('/', '\\', ucfirst(pathinfo($this->getRelativeFilePath())['dirname']));
		// return 'App' . preg_replace('/^'.preg_quote(app_path(), '/').'(.*)/', '$1', pathinfo($this->file_path)['dirname']);
	}
	
	/**
	 * [resource_class description]
	 * 
	 * @return [type] [description]
	 */
	public function resource_class(){
		return end($this->template_datas['path'])['filename'];
	}
	
	/**
	 * [resource_full_class description]
	 * 
	 * @return [type] [description]
	 */
	public function resource_full_class(){
		return $this->template_datas['keywords']['@resource_namespace']['result'] . '\\' . $this->template_datas['keywords']['@resource_class']['result'];
	}
	
	/**
	 * [resource_extends description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function resource_extends(...$args){
		return $this->extend($args, 'resource', 'Illuminate\Http\Resources\Json\JsonResource','@resource_import_class', '@resource_extends');
	}
	
	/**
	 * [resource_implements description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function resource_implements(...$args){
		return $this->implement($args, 'resource', null, '@resource_import_class', '@resource_implements');
	}
	
	/**
	 * [resource_import_trait description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function resource_import_trait(...$args){
		return $this->importTrait($args, 'resource', [/*--traits--*/], '@resource_import_class', '@resource_import_trait');
	}
	
	/**
	 * [resource_collects description]
	 * 
	 * @return [type] [description]
	 */
	public function resource_collects(){
		if(isset($this->arguments['resource']['#content']) && (is_string($this->arguments['resource']['#content']) || is_callable($this->arguments['resource']['#content'])))
			return !is_string($this->arguments['resource']['#content']) ? $this->arguments['resource']['#content'](...$this->arguments) : $this->arguments['resource']['#content'];
		elseif(!isset($this->arguments['resource']['#content']) && isset($this->arguments['resource']['collects']) && is_string($this->arguments['resource']['collects']) && !empty($this->arguments['resource']['collects'])){
			if(preg_match('/^[a-z0-9_\\\]+\\\(.*)/i', ($class_ = $this->arguments['resource']['collects']), $m)){
				$file_content = str_replace('@resource_import_class', ('use ' . $class_ . ";\n@resource_import_class"), $this->file_content);
				$file_content = str_replace('@resource_collects', "\n\n\t/**\n\t * The resource that this resource collects.\n\t *\n\t * @var string\n\t */\n\tpublic \$collects = " . end($m) . "::class;\n", $file_content);
				file_put_contents($this->file_path, $file_content);
				return true;
			}
		}
		return '';
	}
	
	/**
	 * [resource_wrap description]
	 * 
	 * @return [type] [description]
	 */
	public function resource_wrap(){
		$render = '';
		if(isset($this->arguments['resource']['#content']) && (is_string($this->arguments['resource']['#content']) || is_callable($this->arguments['resource']['#content'])))
			return !is_string($this->arguments['resource']['#content']) ? $this->arguments['resource']['#content'](...$this->arguments) : $this->arguments['resource']['#content'];
		elseif(!isset($this->arguments['resource']['#content']) && isset($this->arguments['resource']['wrap']) && is_string($this->arguments['resource']['wrap']) && !empty($this->arguments['resource']['wrap']))
			$render = "\n\n\t/**\n\t * The \"data\" wrapper that should be applied.\n\t *\n\t * @var string|null\n\t */\n\tpublic \$wrap = " . $this->arguments['resource']['wrap'] . ";\n";
		return $render;
	}
	
	/**
	 * [resource_datas description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function resource_datas(...$args){
		// $call_col = config('compio-db.conf.helpers.colone');
		$datas = (function($args, $call_back){
			$ret = [];
			$cols = $call_back->colone($args['columns']);
			foreach ((isset($cols['cols']) ? $cols['cols'] : []) as $column => $value) {
				if(preg_match('/(.*)_id$/i', $column, $m)){
					$ret[$column][] = 'new ' . ucfirst(Str::camel(end($m))) . 'Resource($this->' . end($m) . ')';
				}
				else $ret[$column][] = '$this->' . $column;
			}
			return $ret;
		})($this->arguments, $this);
		$render = '';
		if(isset($this->arguments['resource']['#content']) && (is_string($this->arguments['resource']['#content']) || is_callable($this->arguments['resource']['#content'])))
			return !is_string($this->arguments['resource']['#content']) ? $this->arguments['resource']['#content'](...$this->arguments) : $this->arguments['resource']['#content'];
		elseif(!isset($this->arguments['resource']['#content'])){
			$datas = array_merge_recursive($datas, isset($this->arguments['resource']['datas']) ? $this->colone($this->arguments['resource']['datas'], false, false) : []);
			$ret = [];
			foreach ($datas as $column => $value){
				$value = is_string($value) ? [$value] : $value;
				if(is_array($value) && !is_null(end($value)) && is_string(end($value)))
					$ret[] = "'" . (preg_match('/(.*)_id$/i', $column, $m) ? end($m) : $column) . "' => " . end($value);
			}
			$r = "\n" . implode(",\n", $ret);
			$render = empty($ret) ? '' : (str_replace("\n", "\n\t\t\t", $r) . "\n\t\t");
		}
		return $render;
	}

	
	/**
	 * [resource_import_class description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function resource_import_class(...$args){
		return $this->importClass($args, 'resource', [
		]);
	}

}