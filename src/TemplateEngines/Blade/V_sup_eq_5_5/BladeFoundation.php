<?php

namespace Compio\TemplateEngines\Blade\V_sup_eq_5_5;

use Compio\Traits\Factory;
use Compio\Traits\Singleton;

class BladeFoundation {

	use Singleton, Factory;

	/**
	 * Create a new Compio\TemplateEngines\Blade\V_sup_eq_5_5\BladeFoundation instance.
	 *
	 * @return void
	 */
	public function __construct(){}

	/**
	 * Get factory namespace of this class. 
	 *
	 * @return string
	 */
	protected static function getFactoryNameSpace(){ return '\Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories'; }


}