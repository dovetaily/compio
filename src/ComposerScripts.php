<?php

namespace Compio;

use Composer\Script\Event;
use Compio\Environments\Laravel\Listen as L_Laravel;

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
            L_Laravel::getInstance();
        }
    }
}