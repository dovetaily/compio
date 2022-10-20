<?php

namespace Compio\Environments\Laravel;

use Compio\InkingInterface;
use Compio\Inking;
use Compio\Traits\Singleton;

class LaravelInking extends Inking implements InkingInterface{

	use Singleton;

	/**
	 * All version supported
	 *
	 * @var array
	 */
	protected static array $version_supported = [
		'V_SUP_EQ_5_5' => '>=5.5'
	];

	/**
	 * Create a new Compio\Environments\Laravel\LaravelInking instance.
	 *
	 * @return void
	 */
	public function __construct(){

		$pack = self::version_supported();

		if($pack !== false && is_array($pack) && array_key_exists('space', $pack) && method_exists($c = 'Compio\Environments\Laravel\\' . $pack['space'] . '\CommandInit', 'init')){
			$m = $c . '::init'; $m();
		}
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