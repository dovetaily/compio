<?php

namespace Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories;

use Compio\Traits\Factory;
use Compio\Component\Foundation;
use Compio\Component\Config;
class Component{

	use Factory;

	/**
	 * Get Factory Namespace
	 *
	 * @return string
	 */	
	protected static function getFactoryNameSpace(){ return '\Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories'; }

	public function __construct(){
		
		// var_dump(Config::getInstance()->getConfiguration());
		// Config::getInstance()::$configuration = 'ss';
		// var_dump(Config::getInstance()->getConfiguration());

	}

}