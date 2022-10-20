<?php
namespace Compio;
/**
 * 
 */

abstract class Listen {

	protected static array $version_supported = [];

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
			$expression = '$verif = ' . static::verif(static::convert_version($version), 'static::version()').';';
			eval($expression);
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
						preg_match('/[<>~=!]+/i', $exp, $match);
						$condition = '==';
						if(!empty($match)) $condition = $match[0];
						$version = str_replace($condition, '', $exp);
						if($condition == '~'){
							if(strpos($version, '*') === false){
								$version = "(version_compare(" . $current_version . ", '" . $version . "', '>=') && version_compare(". $current_version . ", '" . preg_replace_callback('/^([0-9]+).*/i', function($match){ return end($match) + 1;}, $version) . "', '<'))";
							}
							else {
								$version = "(version_compare(" . $current_version . ", '" . $version . "', '>=') && version_compare(" . $current_version . ", '" . preg_replace_callback('/^([0-9]+).*/i', function($match){ return end($match) + 1;}, $version) . "', '<'))";
							}
						}
						else{
							if(strpos($version, '*') === false){
								$version = "version_compare(" . $current_version . ", '" . $version . "', '" . $condition . "')";
							}
							else{
								$version = "(version_compare(" . $current_version . ", '" . $version . "', '" . $condition . "') && version_compare(" . $current_version . ", '" . preg_replace_callback('/([0-9]+).\*/i', function($match){ return end($match) + 1;}, $version) . "', '<'))";
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