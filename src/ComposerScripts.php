<?php

namespace Compio;

use Composer\Script\Event;

class ComposerScripts {
	/**
	 * Handle the post-autoload-dump Composer event.
	 *
	 * @param  \Composer\Script\Event|null  $event
	 * @return void
	 */
	public static function postAutoloadDump(Event|null $event = null){

		if(class_exists(\Illuminate\Foundation\Application::class) && defined('\Illuminate\Foundation\Application::VERSION')){
			\Compio\Environments\Laravel\LaravelInking::getInstance();
		}

	}
}