<?php

namespace Compio\Component\Keywords;

trait Locate {

	/**
	 * Get locate_js keyword.
	 *
	 * @return string|null
	 */
	protected function locate_js(){

		return "\n\t\t\t'" . $this->locate_('js') . "'\n\t\t";

	}

	/**
	 * Get locate_css keyword.
	 *
	 * @return string|null
	 */
	protected function locate_css(){

		return "\n\t\t\t'" . $this->locate_('css') . "'\n\t\t";

	}

	/**
	 * Get locate_render keyword.
	 *
	 * @return string|null
	 */
	protected function locate_render(){

		return $this->locate_('render', 'YE@**@!!@&@T!!', function($str){return str_replace(['/', '\\'], '.', $str);});

	}

	/**
	 * Get locate_ format keyword.
	 *
	 * @return string|null
	 */
	protected function locate_($t, $ext = null, callable|null $callback = null){

		$tt = $this->template()->get($t);

		if($tt){

			$tt = array_key_exists('short_path', $tt) ? $tt['short_path'] : 'components';
			$tt = is_array($tt) ? end($tt) : $tt;
			$tt = trim(trim(str_replace('/', '\\', is_string($tt) ? $tt : 'components')), '\\');

			$res = $tt . '\\' . trim($this->name()->getClassName(), '\\') . ($ext === null 
				? '.' . $t
				: ($ext === 'YE@**@!!@&@T!!'
					? null
					: '.' . $ext
				)
			);

			return is_callable($callback) ? $callback($res) : $res;

		}

		return null;

	}

}