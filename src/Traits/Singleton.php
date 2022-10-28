<?php

namespace Compio\Traits;

trait Singleton {

	/**
	 * The instance of the class. 
	 *
	 * @return void
	 */
	protected static $instance;

	/**
	 * Get instance of a class. 
	 *
	 * @param  array  $args
	 * @return void
	 */
	public static function getInstance(...$args){

		$c = static::class;

		return is_null(self::$instance) ? new $c(...$args) : self::$instance;

	}

}