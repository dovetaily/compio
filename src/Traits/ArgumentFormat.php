<?php

namespace Compio\Traits;

trait ArgumentFormat {

	/**
	 * The default pattern of arguments.
	 *
	 * @var string
	 */
	private static $default_pattern = ['default' => '/--([^\s]+=\'[^=]+\')|--(\'[^=]+\'=[^\s]+)|--(\'[^=]+\'=\'[^=]+\')|--([^\s]+=[^\s]+)|--(\'[^=]+\')|--([^\s]+)/i', 'type' => '/\'(.*?)\'/'];


	/**
	 * Get formatted arguments.
	 *
	 * @param  string|null          $more
	 * @param  bool|null|string     $be_null
	 * @param  callable|null|array  $Closure = null
	 * @param  array|null           $pattern
	 * @param  string               $strophe
	 * @return array|null
	 */
	public static function format_args($more, $be_null = false, $Closure = null, $pattern = null, string $strophe = "'") {

		$pattern = is_null($pattern) ? self::$default_pattern : $pattern;

		$Closure = is_callable($Closure) 
			? ['return' => $Closure] 
			: (is_array($Closure) && ((isset($Closure['items']) && is_callable($Closure['items'])) || (isset($Closure['return']) && is_callable($Closure['return']))) 
				? $Closure 
				: null
			)
		;

		$be_null = $be_null === null ? false : $be_null;

		if($more === null) return null;

		$m = [];

		$more = str_replace("\'", '&apos;', $more);
		$more = str_replace("\:", '&dbp;', $more);
		preg_match_all($pattern['default'], $more, $matches);

		if(!empty($matches[1]) || !empty($matches[2]) || !empty($matches[3]) || !empty($matches[4]) || !empty($matches[5]) || !empty($matches[6])){

			array_shift($matches);

			foreach ($matches as $vd) {

				foreach ($vd as $v) {

					if(!empty($v)){

						$a = explode('=', $v);
						$val = null;
						if(isset($a[1])){

							$val = explode(':', $a[1]);

							if(count($val) > 1){

								$vl = [];

								foreach ($val as $oov) {

									$db = self::type_verif(isset($pattern['type']) ? $pattern['type'] : '/\'(.*?)\'/', trim($oov, $strophe), $be_null, $strophe);
									$db = is_string($db) ? (((int) str_replace('#', null, $db)) ? str_replace('#', null, $db) : str_replace('\#', '&hastag;', $db)) : $db;
									$db = !is_string($db) ? $db : str_replace('\#', '&hastag;', $db);

									$vl[] = $db;

								}

								$val = $vl;

							}
							else{

								$val = self::type_verif(isset($pattern['type']) ? $pattern['type'] : '/\'(.*?)\'/', $val[0], $be_null, $strophe);
								$val = is_string($val) ? str_replace('&apos;', "'", $val) : $val;
								$val = is_string($val) ? str_replace('&dbp;', ":", $val) : $val;

							}
						
						}
						if(is_array($Closure) && isset($Closure['items']) && is_callable($Closure['items'])){

							$rr = $Closure['items']($a[0], $val, array_key_exists('args', $Closure) 
								? $Closure['args']
								: null
							);

							if(is_array($rr) && count($rr) == 2 && isset($rr['key']) && (isset($rr['value']) || $rr['value'] == null))
								$m[$rr['key']] = $rr['value'];

						}
						else $m[$a[0]] = $val;
					
					}
				
				}

			}
		
		}

		if(!empty($m) && is_array($Closure) && isset($Closure['return']) && is_callable($Closure['return'])){

			$response_closure = $Closure['return']($m, array_key_exists('args', $Closure) 
				? $Closure['args']
				: null
			);

			return is_array($response_closure) ? $response_closure : null;

		}
		else return empty($m) ? null : $m;

	}

	/**
	 * Check the type of an argument against its value.
	 *
	 * @param  string            $pattern
	 * @param  string            $value
	 * @param  bool|string|null  $be_null
	 * @param  string|array      $strophe
	 * @return mixed
	 */
	public static function type_verif(string $pattern, string $value, $be_null, $strophe){
		$strophe = is_string($strophe) ? [$strophe] : $strophe;
		$trim_v = (function($val, $tab){foreach ($tab as $v) { trim($val, $v); } return $val;})($value, $strophe);
		return preg_match($pattern, $value)
			? $trim_v
			: (
				$value == 'true'
					? true
					: (
						$value == 'false'
						? false
						: (
							$value == 'null'
							? ($be_null === false ? null : (is_string($be_null) && !empty($be_null) ? $be_null : '!beNull'))
							: (
								// ((float) $value) && preg_match('/[0-9]+/', $value) && !(preg_match('/\.([0-9]+)\./', $value))
								is_numeric($value)
								// ? ((float) $value)
								? (strpos($value, '.') || $value < PHP_INT_MIN || $value > PHP_INT_MAX
									? (double) $value
									: (int) $value
								)
								// : trim($value, $strophe)
								: (function($v){
									preg_match('/^\\[.*\\]$|^array\\(.*\\)$/', $v, $m);
									$exp = '$test = ' . end($m) . ';';
									try { eval($exp); } catch (\Error $e) { $test = $v; }
									return $test;
								})($trim_v)
							)
						)
					)
			)
		;

	}

	/**
	 * Get formated value.
	 *
	 * @param  mixed   $value
	 * @param  string  $type
	 * @param  string  $equal
	 * @return mixed
	 */
	public static function format_value($value, $type = null, $equal = ' = '){
		return $value === null
			? null
			: $equal . ($value === \Compio\Component\Arguments::NULL_VALUE
				? 'null'
				// : ($value == '[]' || $value == '[ ]'
				: (is_string($value) && preg_match('/^\\[.*\\]/', $value) && preg_match('/^array.*/', $type)
						? $value
						: (is_numeric($value)
								? $value
								: (is_string($value)
										? (!empty($type) && preg_match('/^bool.*/', $type) && ($value == 'false' || $value == 'true')
											? ($value == 'false'
												? 'false'
												: 'true'
											)
											: '"' . $value . '"'
										)
										: var_export($value, true)
								)
						)
				)
			);
	}

}