<?php

namespace Compio\Environments\Laravel\V_Pack1\Commands;

use Illuminate\Console\Command;
use Compio\Traits\MoreGenerate;

class Base extends Command
{

    use MoreGenerate;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compio Base';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $except = ['.', '..', 'resources', 'Base.php'];
        $choice = $classes = [];
        foreach (scandir(__DIR__) as $value) {
            if(!in_array($value, $except)){
                $f_name = pathinfo($value);
                $classes[$f_name['filename']] = 'Compio\Environments\Laravel\V_Pack1\Commands\\' . $f_name['filename'];
                $choice[] = $f_name['filename'];
            }
        }
        $choice = $this->choice('Generate ?', $choice);
        if(!empty($classes) && isset($classes[$choice])){
            if(class_exists($classes[$choice])){
                // $c = new $classes[$choice]();
                // exec('php artisan ' . $c->get_signature(), $result);
                // echo $result;
            }
        }
        // $this->error();
        return Command::SUCCESS;
    }
}
