<?php

namespace Compio;

use Compio\Traits\Singleton;

class Config{

	use Singleton;

	public $configuration = [];

	private static $last_configuration = [];


	/**
	 * Create a new Compio\Config instance.
	 *
	 * @return void
	 */
	public function __construct(){

		$this->initConfiguration();

	}

	/**
	 * Init configuration
	 *
	 * @return void
	 */
	public function initConfiguration(){

		$this->configuration = !empty(self::$last_configuration) ? self::$last_configuration : (!empty($this->configuration) ? : [
			'component' => [
				'config' => [
					'paths' => [
						'class' => getcwd() . '\app\View\Components',
						'render' => getcwd() . '\resources\views\component',
						'assets' => [
							'css' => getcwd() . '\public\css\components',
							'js' => getcwd() . '\public\css\components',
						]
					],
					'replace_component_exist' => true,
				],
			]
		]);

	}

	/**
	 * Get configuration
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function getConfiguration(string|null $key = null){

		return !empty($key) ? (array_key_exists($key, $this->configuration) ? $this->configuration[$key] : false) : $this->configuration;

	}

	/**
	 * Set configuration
	 *
	 * @param  array  $key
	 * @return void
	 */
	public function setConfiguration(array $value){

		$this->configuration = $value;

	}

	/**
	 * Set last configuration
	 *
	 * @param  array  $key
	 * @return void
	 */
	public static function setLastConfiguration(array $value){

		self::$last_configuration = $value;

	}

	/**
	 * Get last configuration
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public static function getLastConfiguration(string|null $key = null){

		return !empty($key) ? (array_key_exists($key, self::$last_configuration) ? self::$last_configuration[$key] : false) : self::$last_configuration;

	}

}