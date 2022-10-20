<?php

namespace Compio\TemplateEngines\Blade\V_Pack1;

// use Compio\Listen as B_Listen;
// use Compio\Environments\Laravel\Listen as LaravelListen;
use Compio\Traits\Singleton;
use Compio\Traits\Factory;

class Pack{

	use Singleton, Factory;

	/**
	 * Create a new Compio\TemplateEngines\Blade\V_Pack1\Component instance.
	 *
	 * @return void
	 */
	public function __construct(){}
	protected static function getFactoryNameSpace(){ return '\Compio\TemplateEngines\Blade\V_Pack1\Factories'; }


}