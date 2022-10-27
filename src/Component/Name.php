<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;

class Name {

	use ComponentCaller;

	/**
	 * Patterns component name.
	 *
	 * @var array
	 */
	private static $patterns = [
		'name' => '/^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$/i',
		'config' => '/^\#([a-z]+)\|([^|]+)$/i'
	];

	/**
	 * Component name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Create a new Compio\Component\Name instance.
	 *
	 * @param  string  $name
	 * @return void
	 */
	public function __construct(string $name){

		$this->name = $name;

	}

	/**
	 * Get patterns.
	 *
	 * @param  string|null       $key
	 * @return array|string|bool
	 */
	public static function getPatterns(string|null $key = null) : array|string|bool {

		return $key === null ? self::$patterns : (array_key_exists($key, self::$patterns) ? self::$patterns[$key] : false);

	}

	/**
	 * Check if a component name is correct.
	 *
	 * @param  string     $name
	 * @return array
	 */
	public static function nameIsCheck(string $name) : array {

		$name = trim($name);

		if(((bool) preg_match(self::$patterns['name'], $name)) == true)
			return [
				'status' => true,
				'match' => null
			];

		if(((bool) preg_match(self::$patterns['config'], $name, $match)) == true)
			return [
				'status' => true,
				'match' => $match
			];

		return [
			'status' => false,
			'match' => null
		];

	}

	/**
	 * Get component name.
	 *
	 * @return string
	 */
	public function getName(){

		return $this->name;

	}

	/**
	 * Get component namespace.
	 *
	 * @return string
	 */
	public function getNamespace(){

		$d = dirname($this->getName());
		$d = $d == '.' || $d == './' || $d == '/' ? '' : $d;

		return trim(str_replace('/', '\\', $d), '\\');

	}

	/**
	 * Get component class name.
	 *
	 * @param  string|null  $sep
	 * @return string
	 */
	public function getClassName(string|null $sep = '\\'){

		preg_match('/.*[\/\\\]([a-z0-9_]+)$/i', $this->name()->getName(), $m);

		return $this->getNamespace() . $sep . (empty(end($m)) ? $this->name()->getName() : end($m));

	}


}