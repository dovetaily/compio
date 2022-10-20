<?php

namespace Compio;

use Composer\Script\Event;

class ComposerScripts
{
    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postAutoloadDump()
    {
        if(class_exists(\Illuminate\Foundation\Application::class) && defined('\Illuminate\Foundation\Application::VERSION')){
            \Compio\Environments\Laravel\LaravelInking::getInstance();
        }
    }
}