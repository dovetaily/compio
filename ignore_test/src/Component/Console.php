<?php
namespace Compio\Component;
/**
 * 
 */

abstract class Console {

	/**
	 * Console command
	 *
	 * @var object
	 */
	private static $console;

	/**
	 * Get Console Command
	 *
	 * @return object
	 */
	public static function getConsole(){

		return self::$console;

	}

	/**
	 * Set Console Command
	 *
	 * @param  object  $value
	 * @return void
	 */
	public static function setConsole($value){

		self::$console = $value;

	}

}