<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;

class Template {

	use ComponentCaller;

	/**
	 * All templates. 
	 * 
	 * @var array
	*/
	private $templates = [];

	/**
	 * Create a new Compio\Component\Template instance.
	 *
	 * @return void
	 */
	public function __construct(){}

	/**
	 * Set template to generate.
	 *
	 * @param  array  $templates
	 * @return void
	 */
	public function templateToGenerate(array $templates){

		$datas = $this->config()->getMerge('template');

		$t = [];

		foreach ($datas as $key => $value) {

			if(!in_array($key, $templates)) $datas[$key]['generate'] = false;
			else{
				$datas[$key]['generate'] = true;

				$t[$key] = $datas[$key];
			}

		}

		$this->config()->setMerge($datas, 'template');

		$this->templates = $t;

	}

	/**
	 * Get template.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function get(string|null $key = null) {

		return $key === null ? $this->templates : (array_key_exists($key, $this->templates) ? $this->templates[$key] : false);

	}

	/**
	 * Set template.
	 *
	 * @param  mixed        $value
	 * @param  string|null  $key
	 * @return Compio\Component\Template|bool
	 */
	public function set($value = null, string|null $key = null) {

		if(empty($key)) $this->templates = $value;
		elseif(array_key_exists($key, $this->templates)) $this->templates[$key] = $value;
		else return false;

		return $this;

	}

}