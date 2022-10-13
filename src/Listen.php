<?php
namespace Compio;
/**
 * 
 */

abstract class Listen {

	protected static array $version_supported = [];
	/**
	 * Get the current environment version is running.
	 *
	 * @return float|bool
	 */
	public static function version() : float|bool {

		return function_exists('app') ? (function($r){$r = explode('.', $r);$f = array_shift($r); $r = $f . '.' . implode('', $r); return (float) $r;})(app()->version()) : false;

	}

	public static function convert_version(string $value) : string{
		return preg_replace('/([0-9\.\*]+)-[a-z]+/i', '$1', $value);
	}

	/**
	 * Verify if the current environment version is supported.
	 *
	 * @return string|bool
	 */
	public static function version_supported() : string|bool {

		foreach (static::$version_supported as $pack => $version) {
			eval('$verif = ' . static::verif(static::convert_version($version), 'static::version()').';');
			if($verif === true){ return $pack; }
		}

		return false;
	}
	protected static function verif($expression, $current_version){
		$tab = [];
		$expl = explode('||', $expression);
		foreach ($expl as $k => $expression_0) {
			$tab_0 = [];
			$expl_0 = explode(',', trim($expression_0));
			foreach ($expl_0 as $expression_1) {
				$tab_1 = [];
				$expl_1 = explode(' ', trim($expression_1));
				foreach ($expl_1 as $expression_2) {
					$tab_1[] = (function($exp, $current_version){
						$blck = true;
						preg_match('/[<>~=!]+/i', $exp, $match);
						$condition = '==';
						if(!empty($match)) $condition = $match[0];
						$version = str_replace($condition, '', $exp);
						$version = $blck === true ? (function($r){$r = explode('.', $r);$f = array_shift($r); $r = $f . '.' . implode('', $r); return $r;})($version) : $version;
						if($condition == '~'){
							if(strpos($version, '*') === false){
								$version = $current_version.' >= ' . $version . ' && '.$current_version.' < ' . ceil((float) $version) . '.0';
							}
							else {
								$version = str_replace(['.*', '*'], '.*', $version);
								preg_match('/([0-9]+).\*/i', $version, $mm);
								$version = $current_version.' >= ' . ($blck === true ? (function($r){$r = explode('.', $r);$f = array_shift($r); $r = $f . '.' . implode('', $r); return $r;})(str_replace('.*', '.0', $version)) : str_replace('.*', '.0', $version)) . ' && ' . $current_version . ' < ' . ceil((float) str_replace('.*', '.0', $version));
							}
						}
						else{
							if(strpos($version, '*') === false) 
								$version = $current_version.' ' . $condition . ' ' . $version;
							else{
								$version = str_replace(['.*', '*'], '.*', $version);
								preg_match('/([0-9]+).\*/i', $version, $mm);
								$v2 = preg_replace('/([0-9]+).\*/i', (end($mm) + 1), $version);
								$version = $current_version.' ' . $condition . ' ' . ($blck === true ? (function($r){$r = explode('.', $r);$f = array_shift($r); $r = $f . '.' . implode('', $r); return $r;})(str_replace('.*', '.0', $version)) : str_replace('.*', '.0', $version)) . ' && '.$current_version.' < ' . str_replace('.*', '.0', $v2);
							}
						}
						return $version;
					})(trim($expression_2), $current_version);
				}
				$tab_0[] = implode(' && ', $tab_1);
			}
			$tab[] = implode(' && ', $tab_0);
		}
		return implode(' || ', $tab);
	}

}