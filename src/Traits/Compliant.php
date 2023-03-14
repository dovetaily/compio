<?php

namespace Compio\Traits;

trait Compliant {

	/**
	 * Check values is compliant
	 *
	 * @param  array  $templates
	 * @return bool|array
	 */
	public static function is_compliant($template, $value, $keys_structure) {

		$error = [];

		foreach ($keys_structure as $key => $val) {
			$resp = null;
			if(
				(!array_key_exists($key, $value) && $val['require'] === true) 
					||
				array_key_exists($key, $value) 
					&&
				(
					!(
						array_key_exists($key, $value)
							&&
						(
							$resp = self::is_conforming($value[$key], $key, $val['verif'], $val['last'], (
								array_key_exists('empty',$val) 
									? ((bool) $val['empty']) 
									: false
							))
						) === true
					)
				)
			) $error[$key] = $resp !== null ? $resp : "La clé `$key` n'existe pas !";
		}
		return empty($error) ? true : $error;

	}

	/**
	 * Check values is conforming
	 *
	 * @param  mixed                 $datas
	 * @param  string                $key
	 * @param  string|callable|null  $verif
	 * @param  bool                  $last
	 * @param  bool                  $empty
	 * @return string|bool
	 */
	private static function is_conforming($datas, string $key, $verif = null, bool $last = true, bool $empty = false) {

		$verif = is_string($verif) && function_exists('\\' . $verif) 
			? $verif 
			: (!is_string($verif) && is_callable($verif)
				? $verif
				: 'is_string'
			);
		$datas = is_array($datas) ? $datas : [$datas];
		$resp = null;
		if(($resp = (function($datas, $key, $fn, $last, $empty){
			$datas = $last === true && count($datas) > 1 ? [end($datas)] : $datas;
			foreach ($datas as $value){
				if($empty === false && (empty($value) && $value !== false)) return count($datas) > 1
					? "L'une des valeurs de la clé `$key` est vide !"
					: "La clé `$key` est vide !"
				;
				elseif($empty === true && (empty($value) && $value !== false)) continue;
				if(($resp = $fn(...[$value])) !== true) return $resp;
			}

			return true;

		})($datas, $key, $verif, $last, $empty)) === true ){
			return true;
		}
		else
			return $resp === false
				? "Verifiez la valeur de la clé `$key` car elle n'est pas conforme !"
				: $resp
			;

	}

}