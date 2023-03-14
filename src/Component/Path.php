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

				$ext = array_key_exists('file_extension', $value) && (is_string($value['file_extension']) || (is_array($value['file_extension']) && !empty($value['file_extension']))) 
					? $value['file_extension'] 
					: "php"
				;
				$ext = is_array($ext) 
					? (is_string($v = end($ext))
						? trim($v)
						: 'php'
					)
					: trim($ext)
				;

				$convert_case = array_key_exists('convert_case', $value) 
					? (is_array($v = $value['convert_case']) && !empty($v) && is_string($v = end($v)) && !empty(trim($v))
						? trim($v)
						: (is_string($value['convert_case']) && !empty(trim($value['convert_case']))
							? trim($value['convert_case'])
							: (is_callable($value['convert_case'])
								? $value['convert_case']
								: 'lower'
							)
						)
					)
					: 'lower'
				;

				$this->addPath($key, $value['path'], $ext, $convert_case);

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
	public function get($key = null) {

		return $key === null ? $this->paths : (array_key_exists($key, $this->paths) ? $this->paths[$key] : false);

	}

	/**
	 * Add path.
	 *
	 * @param  string             $template
	 * @param  array|string|null  $value
	 * @param  string             $ext
	 * @param  string|callable    $convert_case
	 * @return Compio\Component\Path
	 */
	public function addPath(string $template, $value, string $ext, $convert_case = 'lower'){

		$value = is_array($value) ? $value : [$value];

		$this->paths[$template] = [];

		foreach ($value as $k => $val){

			$short = $this->convert_case($this->name()->getClassName(), $convert_case);

			$f = $val . '\\' . $short . ".$ext";

			$vv = pathinfo($f);
			$vv['file'] = $vv['dirname'] . '\\' . $vv['basename'];
			$vv['short'] = $short;

			if(empty($this->paths[$template]) || !in_array($vv, $this->paths[$template]))
				$this->paths[$template][$k] = $vv;

		}

		return $this;

	}
	/**
	 * Get converted text according to a component's criteria
	 * 
	 * @param string          $value
	 * @param string|callable  $type
	 * @return string
	*/
	public function convert_case($value, $type){

		if (!is_string($type) && is_callable($type)) {

			return is_string($val = $type(...[$value])) && !empty(trim($val)) && \Compio\Component\Name::nameIsCheck($val, ['name'])
				? (((bool) preg_match('/' . preg_quote($value) . '/i', $val)) == true
					? $val
					: strtolower($value)
				)
				: strtolower($value)
			;

		}

		return is_string($type) 
			? ($type == 'default' || $type == 'd'
				? $value
				: ($type == 'upper' || $type == 'u'
					? strtoupper($value)
					: ($type == 'ucfirst' || $type == 'uc_first' || $type == 'uf'
						? preg_replace_callback('/\\\([^ ])/i', function($_1){return strtoupper(current($_1));}, ucfirst($value))
						: ($type == 'lcfirst' || $type == 'lc_first' || $type == 'lf'
							? preg_replace_callback('/\\\([^ ])/i', function($_1){return strtolower(current($_1));}, lcfirst($value))
							: strtolower($value)
						)
					)
				)
			)
			: strtolower($value)
		;

	}

}