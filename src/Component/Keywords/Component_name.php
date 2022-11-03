<?php

namespace Compio\Component\Keywords;

trait Component_name {

	/**
	 * The component name keyword.
	 * 
	 * @var string|null
	 */ 
	protected $component_name;

	/**
	 * Get component name keyword.
	 *
	 * @return string|null
	 */
	protected function component_name(){

		return empty($this->component_name) 
			? ($this->component_name = ucfirst(preg_replace_callback('/\\\([a-z])/i', function($v){
					return strtoupper(end($v));
				}
				, strtolower($this->name()->getClassName())))
			)
			: $this->component_name
		;

	}

}