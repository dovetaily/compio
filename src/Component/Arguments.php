<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;
use Compio\Traits\ArgumentFormat;

class Arguments{

	use ComponentCaller, ArgumentFormat;

	/**
	 * All arguments.
	 *
	 * @var array|null
	 */
	private $args;

	/**
	 * The default null value for an argument.
	 *
	 * @var array
	 */
	const NULL_VALUE = '!##Be_null||';

	/**
	 * The argument type pattern.
	 *
	 * @var string
	 */
	private static $patterns = '/^[a-z_\\\]+[a-z0-9_\\\]+$|^[a-z_\\\]+$|^[a-z_]$/i';

	/**
	 * The pattern of arguments.
	 *
	 * @var string
	 */
	private $argument_pattern = ['default' => '/([a-z0-9_\\\:]+=\"[^=]+\")|(\"[a-z0-9_\\\:\s]+\"=[^\s]+)|(\"[a-z0-9_\\\:\s]+\"=\"[^=]+\")|([a-z0-9_\\\:]+=[^\s]+)|(\"[a-z0-9_\\\:\s]+\")|([a-z0-9_\\\:]+)/i', 'type' => '/\"(.*?)\"/'];

	/**
	 * Create a new Compio\Component\Arguments instance.
	 *
	 * @param  string|array|null  $args
	 * @return void
	 */
	public function __construct($args = null){

		$this->set($args);

	}

	/**
	 * Get arguments.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function get($key = null) {

		return $key === null ? $this->args : (array_key_exists($key, $this->args) ? $this->args[$key] : false);

	}

	/**
	 * Set arguments.
	 *
	 * @param  string|array|null  $args
	 * @return object
	 */
	public function set($args = null) {

		$this->args = is_string($args) || is_null($args) ? $this->format_args(trim($args), self::NULL_VALUE, null, $this->argument_pattern, '"') : $args;

		return $this;

	}

	/**
	 * Get patterns.
	 *
	 * @return string
	 */
	public static function getPatterns() : string {

		return self::$patterns;

	}


}