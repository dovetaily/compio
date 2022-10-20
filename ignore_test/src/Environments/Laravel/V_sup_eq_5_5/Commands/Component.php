<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Commands;

use Illuminate\Console\Command;
// use Compio\Traits\MoreGenerate;
use Compio\Traits\Console;
use Compio\TemplateEngines\Target;
use Compio\EnvironmentsCommandsInterface;
use Compio\Component\Config;

class Component extends Command implements EnvironmentsCommandsInterface{

	use Console/*, MoreGenerate*/;

    /**
     * The template engine
     *
     * @var array
     */
	private $template_engines = [];

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'compio:component';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Advanced component generator (dovetail/compio)';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle(){

		$this->init_template_engine();

		$c = $this->getTemplateEngineSelected('foundation');

		if(class_exists($c)){
			$c::component();
		}

		return Command::SUCCESS;

	}

	/**
	 * Initializing Template Engine
	 *
	 * @return void
	 */
	public function init_template_engine() : void{

		$this->template_engines = Target::getInstance()->getEnginesWorks();

		if(!empty($this->template_engines)){

			$choices = array_keys($this->template_engines);

			if(count($choices) > 1) $choices = strtolower($this->choice('Choose your Template Engine ', array_map(function($v){return ucfirst($v);}, $choices)));
			elseif(!empty($choices)) $choices = strtolower(current($choices));

			if(!empty($this->template_engines) && !empty($choices) && array_key_exists($choices, $this->template_engines)){
				$this->template_engines[$choices]['selected'] = true;
			}
			else echo "\nYou haven't template engines !\n";

			$this->info($this->stylize("\t  \"" . ucfirst($choices) .$this->template_engines[$choices][0]['version']['v']. "\" template engine is selected to generate component !  ") . "\n");

		}
		else echo "\nYou haven't template engine supported by dovetaily/compio !\n"; // And choose one template engine supported ->

	}

	/**
	 * Get Template Engine selected
	 *
	 * @param string|null       $key
	 * @return array|bool|sring
	 */
	public function getTemplateEngineSelected($key = null) : array|bool|string {
		$result = false;
		if(!empty($this->template_engines)){
			foreach($this->template_engines as $template_engine => $value){
				if($value['selected'] === true){
					$result = $value;
					$result = array_merge(['name' => $template_engine], $value);
					if(!empty($key) && array_key_exists($key, $result[0])) $result = $result[0][$key]; 
					break;
				}
			}	
		}
		return $result;
	}

	/**
	 * Initializing Datas
	 *
	 * @return void
	 */
	private function init_datas() : void {
	}

}