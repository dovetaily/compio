<?php
namespace Compio\Traits;
/**
 * 
 */

trait Singleton {

    /**
     * Object instance
     *
     * @var object
     */
	protected static $instance;

	/**
	 * Get object instance
	 *
	 * @param  mixed  $args
	 * @return object
	 */
	public static function getInstance(...$args){

		$c = static::class;

		return is_null(self::$instance) ? self::$instance = new $c(...$args) : self::$instance;

	}

	/**
	 * Get new object instance
	 *
	 * @param  mixed  $args
	 * @return object
	 */
	public static function getNewInstance(...$args){

		$c = static::class;
		
		return self::$instance = new $c(...$args);

	}

}