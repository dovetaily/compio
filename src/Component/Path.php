<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;
use Compio\Compio;

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

					$user_path = !empty($a = $this->config()->get()) && array_key_exists('template', $a) && array_key_exists($key, $a['template']) && array_key_exists('path', $cnf = (function($cnf){
						if(!is_string($cnf) && is_callable($cnf)) return $cnf();
						else return $cnf;
					})($a['template'][$key])) && $cnf['path'] 
						? $cnf['path']
						: (!empty($a = $this->config()->getApp()) && array_key_exists('template', $a) && array_key_exists($key, $a['template']) && array_key_exists('path', ($ret = (
							function($template){
								return !is_string($template) && is_callable($template) && is_array($conf = $template()) ? $conf : $template;
							}
							)($a['template'][$key]))) && $ret['path']
							? $ret['path']
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
					? (is_string($value['convert_case']) && !empty(trim($value['convert_case']))
						? trim($value['convert_case'])
						: (is_callable($value['convert_case']) || is_array($value['convert_case'])
							? $value['convert_case']
							: 'lower'
						)
					)
					: 'lower'
				;

				$this->addPath($key, $value['path'], $ext, (isset($value['change_file']) && !is_string($value['change_file']) && is_callable($value['change_file']) ? $value['change_file'] : null), $convert_case);

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
	 * @param  string                 $template
	 * @param  array|string|null      $value
	 * @param  string                 $ext
	 * @param  callable|null          $change_file
	 * @param  string|callable|array  $convert_case
	 * @return Compio\Component\Path
	 */
	public function addPath(string $template, $value, string $ext, $change_file, $convert_case = 'lower'){

		$value = is_array($value) ? $value : [$value];

		$this->paths[$template] = [];

		foreach ($value as $k => $val){

			$short = [];
			foreach (explode('\\', trim($this->name()->getClassName(), '\\')) as $value) $short[] = $this->convert_case($value, $convert_case);
			$short = implode(Compio::pathSep(), $short);

			$f = $val . Compio::pathSep() . $short . '.' . $ext;

			$vv = pathinfo($f);

			$vv['basename'] = $vv['filename'] . '.'. $vv['extension'];
			$vv['file'] = $vv['dirname'] . Compio::pathSep() . $vv['basename'];
			$vv['short_dirname'] = trim(pathinfo($short)['dirname'], '.');
			$vv['short'] = $vv['short_dirname'] . Compio::pathSep() . $vv['filename'];

			if(is_callable($change_file) && ($ret = $change_file(...[$vv, $k, $value])) !== null) $vv['new'] = $ret;
			// if(is_callable($change_file)) $vv['new'] = $change_file($vv);

			if(is_array($vv) && isset($vv['new'])
				&& isset($vv['new']['file']) && is_string($vv['new']['file'])
				&& isset($vv['new']['short']) && is_string($vv['new']['short'])
				&& isset($vv['new']['basename']) && is_string($vv['new']['basename'])
				&& isset($vv['new']['filename']) && is_string($vv['new']['filename'])
				&& isset($vv['new']['extension']) && is_string($vv['new']['extension'])
			) $vv = $vv['new'];

			if(empty($this->paths[$template]) || !in_array($vv, $this->paths[$template]))
				$this->paths[$template][$k] = $vv;

		}

		return $this;

	}
	/**
	 * Get converted text according to a component's criteria
	 * 
	 * @param string                 $value
	 * @param string|callable|array  $type
	 * @return string
	*/
	public function convert_case($value, $type){

		if (!is_string($type) && is_callable($type)) {

			return is_string($val = $type(...[$value])) && !empty(trim($val)) && \Compio\Component\Name::nameIsCheck($val, ['name'])
				? (((bool) preg_match('/' . preg_quote($value, "/") . '/i', $val)) == true
					? $val
					: strtolower($value)
				)
				: strtolower($value)
			;

		}
		elseif(is_array($type)){

			$ret = $value;

			foreach ($type as $type_)
				if(is_string($type_) || is_callable($type_)) $ret = $this->convert_case($ret, $type_);

			return $ret;

		}

		return is_string($type) 
			? ($type == 'default' || $type == 'd'
				? $value
				: ($type == 'upper' || $type == 'u'
					? strtoupper($value)
					: ($type == 'ucfirst' || $type == 'uc_first' || $type == 'uf'
						? ucfirst($value)
						: ($type == 'lcfirst' || $type == 'lc_first' || $type == 'lf'
							? lcfirst($value)
							: (($type == 'camel' || $type == 'kebab' || $type == 'snake' || $type == 'studly') && is_callable('Str::' . $type)
								? call_user_func('Str::' . $type, $value)
								: strtolower($value)
							)
						)
					)
				)
			)
			: strtolower($value)
		;

	}

}