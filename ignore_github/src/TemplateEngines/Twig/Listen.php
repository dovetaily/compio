<?php

namespace Compio\TemplateEngines\Twig;

use Compio\Listen as B_Listen;
use Compio\Traits\Singleton;

class Listen extends B_Listen{

	use Singleton;

	protected static array $version_supported = [
		'V_Pack1' => '>=1.24.0'
	];

	/**
	 * Create a new Compio\Environments\Laravel\Liste instance.
	 *
	 * @return void
	 */
	public function __construct(){  }

	/**
	 * Get the current environment version is running.
	 *
	 * @return float|bool
	 */
	public static function version() : float|bool {

		return defined('Twig_Environment::VERSION') ? (function($r){$r = explode('.', $r);$f = array_shift($r); $r = $f . '.' . implode('', $r); return (float) $r;})(constant('Twig_Environment::VERSION')) : false;

	}


}