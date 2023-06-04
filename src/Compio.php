<?php

namespace Compio;

class Compio {

    /**
     * The Compio library version.
     *
     * @var string
     */
	const COMPIO_VERSION = '2.0';

	/**
	 * Defines the global helper functions
	 *
	 * @return void
	 */
	public static function globalHelpers(){

		require_once __DIR__ . '/helpers.php';
	
	}

	/**
	 * Get the location of this library
	 *
	 * @return string
	 */
	public static function get_location() : string {

		return __DIR__;

	}

	/**
	 * Returning a file path adapted according to the type of operating system
	 * 
	 * @param  string $path
	 * @param  string $os
	 * @return string
	 */
	public static function adaptPath(string $path, string $os = '') : string
	{
		$os = empty($os) ? PHP_OS_FAMILY : $os;
		return preg_match('/window[s]/i', $os)
			? str_replace('/', '\\', $path)
			: str_replace('\\', '/', $path)
		;
	}

	/**
	 * Return
	 * 
	 * @param bool  $inverse
	 * @return string 
	 */
	public static function pathSep(bool $inverse = false) : string
	{
		return preg_match('/window[s]/i', PHP_OS_FAMILY) || $inverse === true ? '\\' : '/';
	}

}
