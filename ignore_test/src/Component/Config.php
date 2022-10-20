<?php

namespace Compio\Component;

use Compio\Config as Base_Config;

class Config extends Base_Config{



	/**
	 * Get configuration
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function getConfiguration(string|null $key = null){

		return !empty($key) ? (array_key_exists($key, self::$configuration['component']) ? self::$configuration['component'][$key] : false) : self::$configuration['component'];

	}

}