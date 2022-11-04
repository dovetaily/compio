<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;

class Path {

	use ComponentCaller;

	/**
	 * All component paths.
	 *
	 * @var array
	 */
	private $paths = [];

	/**
	 * Create a new Compio\Component\Path instance.
	 *
	 * @return void
	 */
	public function __construct(){}

	/**
	 * Class initialization.
	 *
	 * @return void
	 */
	public function init(){

		foreach ($this->template()->get() as $key => $value){

			if(array_key_exists('path', $value)){

				if(is_array($value['path'])){

					$user_path = !empty($a = $this->config()->get()) && array_key_exists('template', $a) && array_key_exists($key, $a['template']) && array_key_exists('path', $a['template'][$key]) && $a['template'][$key]['path'] 
						? $a['template'][$key]['path']
						: (!empty($a = $this->config()->getApp()) && array_key_exists('template', $a) && array_key_exists($key, $a['template']) && array_key_exists('path', $a['template'][$key]) && $a['template'][$key]['path']
							? $a['template'][$key]['path']
							: null
						)
					;

					$value['path'] = !empty($user_path) ? $user_path : $value['path'];

				}

				$this->addPath($key, $value['path'], (array_key_exists('file_extension', $value) ? $value['file_extension'] : "php"));

				$this->mergeWithTemplate('path', $key, $this->paths[$key]);

			}

		}

	}

	/**
	 * Get paths.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function get(string|null $key = null) {

		return $key === null ? $this->paths : (array_key_exists($key, $this->paths) ? $this->paths[$key] : false);

	}

	/**
	 * Add path.
	 *
	 * @param  string|null  $key
	 * @return Compio\Component\Path
	 */
	public function addPath(string $key, array|string|null $value, bool|array|string|null $ext){

		$value = is_array($value) ? $value : [$value];

		$ext = is_array($ext) ? end($ext) : $ext;

		$this->paths[$key] = [];

		foreach ($value as $k => $val){

			$f = $val . '\\' . $this->name()->getClassName() . ".$ext";

			$vv = pathinfo($f);
			$vv['file'] = $vv['dirname'] . '\\' . $vv['basename'];

			if(empty($this->paths[$key]) || !in_array($vv, $this->paths[$key]))
				$this->paths[$key][$k] = $vv;

		}

		return $this;

	}

}