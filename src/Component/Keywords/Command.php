<?php

namespace Compio\Component\Keywords;

use Compio\Traits\ArgumentFormat;

trait Command {

	/**
	 * The command keyword.
	 *
	 * @var string|null
	 */
	protected $command;

	/**
	 * Get console command.
	 *
	 * @return string|null
	 */
	protected function command(){

		$args = $this->arguments()->get();

		if(empty($this->command)){

			$t = '';

			foreach ((!empty($args) 
				? $args 
				: []
			) as $key => $value) {

				$t .= (
					strpos($key, ' ') === true
						? '"' . $key . '"'
						: $key
				) . str_replace("\n", null, (
					\Compio\Component\Arguments::NULL_VALUE == $value 
						? '=' 
						: ArgumentFormat::format_value($value, $key, '=')
				)) . ' ';

			}

			$this->command = method_exists($this->getCallerClass(), 'getConsoleCommand') ? $this->getConsoleCommand(trim($t)) : trim($t);

		}
		else $this->command = null;

		return $this->command;

	}

}