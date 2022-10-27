<?php

namespace Compio\Component\Keywords;

trait Class_ {

	/**
	 * The html class keyword.
	 *
	 * @var string|null
	 */
	protected $class_html;

	/**
	 * The class name keyword.
	 *
	 * @var string|null
	 */
	protected $class_name;

	/**
	 * Get html class keyword.
	 *
	 * @return string|null
	 */
	protected function class_html(){

		return empty($this->class_html)
			? ($this->class_html = strtolower(
					str_replace('\\', '-', trim($this->name()->getClassName(), '\\')) . '-' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8)
				)
			)
			: $this->class_html
		;

	}

	/**
	 * Get class name keyword.
	 *
	 * @return string|null
	 */
	protected function class_name(){

		if(empty($this->class_name)){

			$e = explode('\\', $this->name()->getClassName());

			$this->class_name = end($e);

		}
		
		return $this->class_name;

	}

}