<?php

namespace Compio\TemplateEngines\Blade\V_sup_eq_5_5;

// use Compio\Listen as B_Listen;
// use Compio\Environments\Laravel\Listen as LaravelListen;
use Compio\Traits\Singleton;
use Compio\Traits\Factory;

class BladeFoundation{

	use Singleton, Factory;

	/**
	 * Create a new Compio\TemplateEngines\Blade\V_sup_eq_5_5\BladeFoundation instance.
	 *
	 * @return void
	 */
	public function __construct(){}
	protected static function getFactoryNameSpace(){ return '\Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories'; }


}