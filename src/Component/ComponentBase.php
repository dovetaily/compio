<?php

namespace Compio\Component;

use Compio\Component\Module;
use Compio\Component\Path;

class ComponentBase {

	/**
	 * All component datas.
	 *
	 * @var array|null
	 */
	private $datas = [];

	/**
	 * Create a new Compio\Component\ComponentBase instance.
	 *
	 * @return void
	 */
	public function __construct(){ /*...*/ }


	/**
	 * Handle dynamic, calls to the object.
	 *
	 * @param  string  $method
	 * @param  array   $args
	 * @return mixed
	 */
	public function __call($method, $args){

		$result = false;

		if($method !== 'ComponentBase'){

			if(($datas = $this->getDatas($method)) !== false){

				$result = $datas;

			}
			else{

				$c = '\Compio\Component\\' . ucfirst($method);

				$instance = new $c(...$args);

				if(method_exists($instance, 'setCallerClass'))
					$instance->setCallerClass($this);

				$result = $instance;

				$this->addDatas($method, $instance);

			}

		}

		return $result;

	}


	/**
	 * Add component datas.
	 *
	 * @param  string|null  $key
	 * @return array|object|bool
	 */
	public function addDatas(string $key, $value){

		$this->datas[$key] = $value;

	}

	/**
	 * Get component datas.
	 *
	 * @param  string|null  $key
	 * @return array|object|bool
	 */
	public function getDatas($key = null) {

		return $key === null ? $this->datas : (array_key_exists($key, $this->datas) ? $this->datas[$key] : false);

	}

	/**
	 * Merge anything datas type with template
	 *
	 * @param  string  $class_
	 * @param  string  $type
	 * @param  mixed   $value
	 * @return void
	 */
	public function mergeWithTemplate(string $class_, string $type, $value){

		$t = $this->template()->get($type);

		$t[$class_] = $value;

		$this->template()->set($t, $type);

	}

}