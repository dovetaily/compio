<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;

class Config {

	use ComponentCaller;

	/**
	 * Default Configuration.
	 *
	 * @var array
	 */
	private static $default = [];

	/**
	 * Application Configuration.
	 *
	 * @var array
	 */
	private static $app = [];

	/**
	 * Configuration Merge.
	 *
	 * @var array
	 */
	private $merge_conf = [];

	/**
	 * Current configuration.
	 *
	 * @var array
	 */
	private $current = [];

	/**
	 * Create a new Compio\Component\Config instance.
	 *
	 * @param  mixed  $config
	 * @return void
	 */
	public function __construct($config = null){

		if(!is_null($config)) $this->app = $config;

	}

	/**
	 * Get default configuration.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public static function getDefault($key = null) {

		return $key === null ? self::$default : (array_key_exists($key, self::$default) ? self::$default[$key] : false);

	}

	/**
	 * Set default configuration.
	 *
	 * @param  array  $value
	 * @return void
	 */
	public static function setDefault(array $value) {

		self::$default = $value;

	}

	/**
	 * Get current configuration.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function get($key = null) {

		return $key === null ? $this->current : (array_key_exists($key, $this->current) ? $this->current[$key] : false);

	}

	/**
	 * Set current configuration.
	 *
	 * @param  array  $value
	 * @return object
	 */
	public function set(array $value) {

		$this->current = $value;

		return $this;

	}

	/**
	 * Get app configuration.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public static function getApp($key = null) {

		return $key === null ? self::$app : (array_key_exists($key, self::$app) ? self::$app[$key] : false);

	}

	/**
	 * Set app configuration..
	 *
	 * @param  array  $value
	 * @return void
	 */
	public static function setApp(array $value) {

		self::$app = $value;

	}

	/**
	 * Get merge configuration.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function getMerge($key = null) {

		return $key === null ? $this->merge_conf : (array_key_exists($key, $this->merge_conf) ? $this->merge_conf[$key] : false);

	}

	/**
	 * Set merge configuration.
	 *
	 * @param  mixed        $value
	 * @param  string|null  $key
	 * @return void
	 */
	public function setMerge($value, $key = null) {

		$ret = $this;

		if(empty($key)) $this->merge_conf = $value;
		elseif(array_key_exists($key, $this->merge_conf)) $this->merge_conf[$key] = $value;
		else $ret = false;

		return $ret;

	}

	/**
	 * Merge all configuration.
	 *
	 * @return object
	 */
	public function merge() {

		$this->merge_conf = array_merge_recursive(self::getDefault(), self::getApp(), $this->get());

		return $this;

	}


}