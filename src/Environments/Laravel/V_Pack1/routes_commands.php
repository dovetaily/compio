<?php
foreach (scandir(__DIR__ . '\Commands') as $key => $value) {
    $c ='Compio\Environments\Laravel\V_Pack1\Commands\\' . ucfirst(pathinfo($value)['filename']);
    if($value != '.' && $value != '..' && class_exists($c)){
        $this->commands[] = $c;
    }
}