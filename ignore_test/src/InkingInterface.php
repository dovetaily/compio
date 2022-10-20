<?php
namespace Compio;
/**
 * 
 */

interface InkingInterface{

	/**
	 * Get the current version is running.
	 *
	 * @return string|bool
	 */
    public static function version() : string|bool;

}