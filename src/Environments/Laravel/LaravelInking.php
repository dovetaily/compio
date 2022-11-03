<?php

namespace Compio\Environments\Laravel;

use Compio\Inking;
use Compio\InkingInterface;
use Compio\Traits\Singleton;

class LaravelInking extends Inking implements InkingInterface {

	use Singleton;

	/**
	 * All version supported
	 *
	 * @var array
	 */
	protected static array $version_supported = [
		'V_sup_eq_5_5' => '>=5.5'
	];

	/**
	 * Create a new Compio\Environments\Laravel\LaravelInking instance.
	 *
	 * @return void
	 */
	public function __construct(){

		$version = self::version_supported();

		if($version !== false && is_array($version) && array_key_exists('space', $version) && method_exists($c = 'Compio\Environments\Laravel\\' . $version['space'] . '\Foundation', 'init'))
			call_user_func($c . '::init');
		else echo "\nLaravel version is not supported !\n";

	}

	/**
	 * Get the current environment version is running.
	 *
	 * @return string|bool
	 */
	public static function version() : string|bool {

		return class_exists(\Illuminate\Foundation\Application::class) && defined('\Illuminate\Foundation\Application::VERSION') ? \Illuminate\Foundation\Application::VERSION : false;

	}

}