<?php

namespace Compio\Traits;

trait Factory {

	/**
	 * Get factory namespace of class. 
	 *
	 * @return string
	 * 
	 * @throws \RuntimeException
	 */
	protected static function getFactoryNameSpace(){

		throw new RuntimeException('Factory does not implement getFactoryNameSpace method.');

	}


	/**
     * Handle dynamic, calls to the object and
     * get instance of a factory class. 
	 *
	 * @param  string  $method
	 * @param  mixed   $args
	 * @return object
	 * 
	 * @throws \RuntimeException
	 */
	public static function __callStatic($method, $args){

		$c = static::getFactoryNameSpace() . '\\' . ucfirst($method);

		if(class_exists($c)) return method_exists($c, 'getInstance') ? $c::getInstance(...$args) : new $c(...$args);

		throw new RuntimeException('The factory class `' . $c . '` is not found !.');

	}

}