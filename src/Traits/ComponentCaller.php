<?php

namespace Compio\Traits;

trait ComponentCaller {

	/**
	 * Caller class
	 *
	 * @var object
	 */
	public $caller_class;

	/**
	 * Set caller class
	 *
	 * @param  object  $value
	 * @return object
	 */
	public function setCallerClass(object $value){

		$this->caller_class = $value;

		return $this;

	}

	/**
	 * Get caller class
	 *
	 * @return object|null
	 */
	public function getCallerClass(){

		return $this->caller_class;

	}

	/**
	 * Handle dynamic, calls to the object.
	 *
	 * @param  string  $method
	 * @param  array   $args
	 * @return mixed
	 */
	public function __call($method, $args){

		return $this->getCallerClass()->$method(...$args);

	}

}