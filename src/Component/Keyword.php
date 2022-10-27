<?php

namespace Compio\Component;

use Compio\Component\Keywords\Args;
use Compio\Component\Keywords\Class_;
use Compio\Component\Keywords\Command;
use Compio\Component\Keywords\Component_name;
use Compio\Component\Keywords\Locate;
use Compio\Component\Keywords\Namespace_;
use Compio\Component\Template;
use Compio\Traits\ComponentCaller;

class Keyword {

	use 
		ComponentCaller,
		Args,
		Class_,
		Command,
		Component_name,
		Locate,
		Namespace_
	;

	/**
	 * Components keywords.
	 * 
	 * @var array
	 */ 
	private $keywords = [];

	/**
	 * All default keywords for template. 
	 * 
	 * @var array
	 */ 
	public $all_keywords = [
		'class' => [
			'@command',
			'@namespace',
			'@class_name',
			'@locate_css',
			'@locate_js',
			'@args_init',
			'@args_params',
			'@args_construct',
			'@locate_render',
			'@args_render',
		],
		'render' => [
			'@component_name',
			'@class_html'
		],
		'css' => [
			'@class_html',
		], 
		'js' => [
			'@class_html',
		]
	];

	/**
	 * Create a new Compio\Component\Keyword instance.
	 *
	 * @param Compio\Component\Template|null  $template
	 * @return void
	 */
	public function __construct(Template|null $template = null){

		if(!is_null($template)) $this->init($template);

	}

	/**
	 * Initialize methode to add keywords.
	 *
	 * @param Compio\Component\Template|null  $template
	 * @return void
	 */
	public function init(Template $template){

		foreach ($template->get() as $key => $value){

			if(array_key_exists('keywords', $value) && array_key_exists($key, $this->all_keywords)) $this->addKeyword($key, is_array($value['keywords']) ? $value['keywords'] : [$value['keywords']]);
			else $this->addKeyword($key, array_merge($this->all_keywords['class'], $this->all_keywords['js'], $this->all_keywords['render'], (array_key_exists('keywords', $value) ? $value['keywords'] : [])));

		}

	}

	/**
	 * Add new keyword.
	 *
	 * @param string  $key
	 * @param array   $value
	 * @return void
	 */
	public function addKeyword(string $key, array $value){

		$this->keywords[$key] = [];


		foreach ($value as $k => $val){

			$v = null;

			if(!is_numeric($k)) { $v = $val; $val = $k; }

			if(empty($this->keywords[$key]) || !in_array($val, $this->keywords[$key])) $this->keywords[$key][$val] = $v;

		}

	}

	/**
	 * Get keyword(s).
	 *
	 * @param  string|null  $key
	 * @return array|object|bool
	 */
	public function getKeyword(string|null $key = null) {

		return $key === null ? $this->keywords : (array_key_exists($key, $this->keywords) ? $this->keywords[$key] : false);

	}

	/**
	 * Initialization of keyword generation.
	 *
	 * @return object
	 */
	public function initGenerate(){

		foreach ($this->getKeyword() as $type => $id){
			$this->keywords[$type] = $this->generate($type, $id);

			$this->mergeWithTemplate('keywords', $type, $this->keywords[$type]);
		}

		return $this;

	}

	/**
	 * Generate keywords value.
	 *
	 * @param  string|null  $key
	 * @return array|object|bool
	 */
	public function generate($keyword_type, $list_id_keyword){

		$t = [];

		foreach ($list_id_keyword as $id_keyword => $val) {

			preg_match('/^@([a-z_]+[a-z0-9_]+|[a-z_]+|[a-z_])$/i', $id_keyword, $matches);

			$matches = end($matches);

			if($matches !== false){

				if(!is_string($val) && is_callable($val)){ // only a closure function
					$r = $val($this);

					$t[$id_keyword] = is_string($r) ? $r : null;
				}
				else{

					if(method_exists($this, $matches)){
						$t[$id_keyword] = $this->$matches();
					}
					else{

						preg_match('/^args_([0-9]+)_(type|name|value|all|type-name-value|type-name|name-value)$|^args_([0-9]+)$/i', $matches, $match);

						if($match !== false && !empty($match)) $t[$id_keyword] = $this->args(
							isset($match[3]) 
								? $match[3]
								: $match[1]
							, isset($match[3])
								? null 
								: $match[2]
						);
						else $t[$id_keyword] = null;

					}

				}

			}
			else echo "Error : Your \"$id_keyword\" keyword is not available ! (^@([a-z_]+[a-z0-9_]+|[a-z_]+|[a-z_])$)";

		}

		return empty($t) ? null : $t;

	}


}