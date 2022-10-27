<?php

namespace Compio;

class Compio
{
    const COMPIO_VERSION = '1.9.1';
    /**
     * Defines the global helper functions
     *
     * @return void
     */
    public static function globalHelpers()
    {
        require_once __DIR__ . '/helpers.php';
    }
    public static function get_location(){ return __DIR__; }
}
