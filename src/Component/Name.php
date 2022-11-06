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
		'name_except' => '/\\/[0-9]/i',
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
	 * @param  array     $pattern_type
	 * @return array
	 */
	public static function nameIsCheck(string $name, array $pattern_type = ['name', 'config']) : array {

		$name = trim($name);

		foreach ($pattern_type as $value) {

			if(is_string($value) && array_key_exists($value, self::$patterns) && ((bool) preg_match(self::$patterns[$value], $name, $match)) == true && (($value == 'name' && (((bool) preg_match(self::$patterns['name_except'], $name)) == false)) || $value != 'name'))
				return [
					'status' => true,
					'match' => $value == 'name' ? null : $match
				];

		}

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