<?php

namespace Compio;

use Composer\Script\Event;
use Compio\Environments\Laravel\Listen as L_Laravel;

class ComposerScripts
{
    // /**
    //  * Handle the post-install Composer event.
    //  *
    //  * @param  \Composer\Script\Event  $event
    //  * @return void
    //  */
    // public static function postInstall(Event $event){
    // }

    // /**
    //  * Handle the post-update Composer event.
    //  *
    //  * @param  \Composer\Script\Event  $event
    //  * @return void
    //  */
    // public static function postUpdate(Event $event)
    // {
    //     require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';

    //     static::clearCompiled();
    // }

    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postAutoloadDump()
    {
        if(defined('LARAVEL_START')){
            L_Laravel::getInstance();
        }
    }
    public static function hh()
    {
        file_put_contents(__DIR__ . '\helpers.php', 'data');
    }
}
// var_dump(app()->runningInConsole()); // verifie si nous sommes dans la console