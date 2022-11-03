<?php

namespace Compio\Traits;

trait ConsoleHelpers {

	/**
	 * Stylize string for console message
	 *
	 * @param  string  $str
	 * @return string
	 */
	public function stylize(string $str) : string{

		$s = "";
		for($i = 0; $i < (strlen($str) - 1); $i++) $s .= " ";
		return  "\t".$s . "\n" . $str . "\n\t" . $s;

	}

}