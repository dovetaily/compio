<?php

namespace Compio;

interface InkingInterface {

    /**
     * Get the current environment version is running.
     *
     * @return string|bool
     */
    public static function version();

}