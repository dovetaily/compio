<?php
namespace Compio\Traits;
/**
 * 
 */

trait Factory {

	/**
	 * Get Factory namespace
	 *
	 * @return void
	 */
	protected static function getFactoryNameSpace(){

		throw new \Exception("The `getFactoryNameSpace` method is not present in `" . static::class . "`", 1);

	}

	/**
	 * Get Factory class instance 
	 *
	 * @param string  $method
	 * @param array   $args
	 * @return object
	 */
	public static function __callStatic($method, $args){

		$c = static::getFactoryNameSpace() . '\\' . ucfirst($method);

		if(class_exists($c)) return method_exists($c, 'getInstance') ? $c::getInstance(...$args) : new $c(...$args);

	}

}