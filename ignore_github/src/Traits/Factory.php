<?php
namespace Compio\Traits;
/**
 * 
 */

trait Factory {

	protected static function getFactoryNameSpace(){

		throw new Exception("Error Processing Request", 1);

	}

	public static function __callStatic($method, $args){

		$c = static::getFactoryNameSpace() . '\\' . ucfirst($method);

		if(class_exists($c)) return method_exists($c, 'getInstance') ? $c::getInstance(...$args) : new $c(...$args);

	}

}