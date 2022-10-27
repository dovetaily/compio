<?php

namespace Compio\Component\Keywords;

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

		if(empty($this->command) && !empty($args)){

			$t = '';

			foreach ($args as $key => $value) {

				$t .= (
					strpos($key, ' ') === true
						? '"' . $key . '"'
						: $key
				) . (
					is_string($value)
						? '=' . ($this->arguments()::NULL_VALUE == $value
							? ''
							: '"' . $value . '"'
						)
						: ($value === null || is_array($value) || is_object($value)
							? null
							: '=' . var_export($value, true)
						)
				) . ' ';

			}

			$this->command = method_exists($this->getCallerClass(), 'getConsoleCommand') ? $this->getConsoleCommand(trim($t)) : trim($t);

		}
		else $this->command = null;

		return $this->command;

	}

}