<?php
namespace Compio\Traits;
/**
 * 
 */

trait Singleton {

	protected static $instance;

	public static function getInstance(...$args){

		$c = static::class;

		return is_null(self::$instance) ? new $c(...$args) : self::$instance;

	}

}