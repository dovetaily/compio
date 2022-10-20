<?php

namespace Compio\Component;

use Compio\Traits\Factory;

class Foundation{

	use Factory;

	/**
	 * Get Factory Namespace
	 *
	 * @return string
	 */	
	protected static function getFactoryNameSpace(){ return '\Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories'; }

	public function __construct(){
		echo "string";
	}

}