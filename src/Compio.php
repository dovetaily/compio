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
}
