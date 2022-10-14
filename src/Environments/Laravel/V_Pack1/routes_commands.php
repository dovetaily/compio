<?php
$exceptions_e82ye2yFFTDYQ272 = ['.', '..', 'Base.php'];
foreach (scandir(__DIR__ . '\Commands') as $value_29e2hUHSYUQGY28y2HUH) {
    $c_93h383hIHHejhhuwee2832 ='Compio\Environments\Laravel\V_Pack1\Commands\\' . ucfirst(pathinfo($value_29e2hUHSYUQGY28y2HUH)['filename']);
    if(!in_array($value_29e2hUHSYUQGY28y2HUH, $exceptions_e82ye2yFFTDYQ272) && class_exists($c_93h383hIHHejhhuwee2832)){
        $this->commands[] = $c_93h383hIHHejhhuwee2832;
    }
}