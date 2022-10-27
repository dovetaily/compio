<?php

namespace Compio\Component\Keywords;

trait Namespace_ {

	/**
	 * The namespace keyword.
	 *
	 * @var string|null
	 */
	protected $namespace;

	/**
	 * Get namespace keyword.
	 *
	 * @return string|null
	 */
	protected function namespace(){

		$nm = $this->name()->getNamespace();

		return empty($nm) ? '' : '\\' . $nm;

	}

}