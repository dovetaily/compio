<?php

namespace Compio\Environments\Laravel;

use Compio\Listen as B_Listen;
use Compio\Traits\Singleton;

class Listen extends B_Listen{

	use Singleton;

	protected static array $version_supported = [
		'V_Pack1' => '>=5.5'
	];

	/**
	 * Create a new Compio\Environments\Laravel\Liste instance.
	 *
	 * @return void
	 */
	public function __construct(){

		$pack = self::version_supported();
		if($pack !== false && method_exists($c = 'Compio\Environments\Laravel\\' . $pack . '\CommandInit', 'init')){
			$m = $c . '::init'; $m();
		}
		else echo "Laravel version is not supported !";
	}


}