<?php

namespace Compio\Environments\Laravel\V_Pack1\Commands;

use Illuminate\Console\Command;
use Compio\Traits\MoreGenerate;
use Compio\TemplateEngines\Target;

class Component extends Command
{

	use MoreGenerate;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	private $resources_path = __DIR__ . '\resources\component';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	private $argument_pattern = ['default' => '/([a-z0-9_:]+=\"[^=]+\")|(\"[a-z0-9_:\s]+\"=[^\s]+)|(\"[a-z0-9_:\s]+\"=\"[^=]+\")|([a-z0-9_:]+=[^\s]+)|(\"[a-z0-9_:\s]+\")|([a-z0-9_:]+)/i', 'type' => '/\"(.*?)\"/'];

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	private $component_name;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	private $component_arguments;

    /**
     * The name and signature of the console command.
     *
     * @var array
     */
	private $template_engine = [];

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
	protected $description = 'Il est La';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle(){

		$this->init_template_engine();

		$c = $this->template_engine['pack'] . '\Pack';
		// $c = $c::component(['css' => ['jquery']]);
		$c = $c::component();

		$this->init_datas($c);

		if(!empty($this->component_name)){

			$states = [];
			foreach ([
				'assets_files' => ['call' => $c->generate_assets_files(), 'callback' => null],
				'render' => ['call' => $c->generate_render(), 'callback' => null],
				'class' => ['call' => $c->generate_class(), 'callback' => null],
			] as $key => $value) {
				if(($result = $value['call']) !== false){
					if($result['status'] === true && array_key_exists('files', $result)){
						$states[$key] = $result;
						foreach ($result['files'] as $val) {
							if($val['exist'] === true) $this->warn($this->stylize("\t" . 'Warning | Modified : "' . $val['path'] . '"'));
							else $this->info($this->stylize("\t" . 'Success | Created : "' . $val['path'] . '"'));
						}
					}
				}
				else $this->error($this->stylize("\t  Component Name is empty !  "));
			}
			if(!empty($states)) $this->info($this->stylize("\t" . 'Component created successfully.'));
			else $this->error($this->stylize("\t" . 'Component is not created !'));
		}
		return Command::SUCCESS;

	}

	/**
	 * Get signature.
	 *
	 * @return string
	 */
	public static function get_signature() : string{ return $this->signature; }

	/**
	 * Initializing Template Engine
	 *
	 * @return void
	 */
	private function init_template_engine() : void{

		$engines = Target::getInstance()->getEnginesWorks();
		$choices = array_keys($engines);

		if(count($choices) > 1) $choices = strtolower($this->choice('Choose your Template Engine ', array_map(function($v){return ucfirst($v);}, $choices)));
		elseif(!empty($choices)) $choices = strtolower(current($choices));

		if(!empty($engines) && !empty($choices) && array_key_exists($choices, $engines)){
			$this->template_engine = $engines[$choices];
		}
		else echo "Vous n'avez pas de Template Engines, donc nous générerons un modèle par defaut.";

	}

	/**
	 * Initializing Datas
	 *
	 * @return void
	 */
	private function init_datas($class_) : void{

		$turn = true;

		while ($turn === true) {
			if(isset($cn)) $this->error($this->stylize("\t  \"" . $cn . "\" Is not correct name ! (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$)  "));
			$cn = $this->ask('Component name ? (Component | Path/Component)');
			$turn = ((bool) preg_match('/^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$/i', trim($cn))) == true ? false : true;
		}

		$class_->set_component_name($cn);
		$vrf = true;

		// if(($v = $class_->verify_component_exist()) !== false){
		// 	$this->error($this->warn(" Component \"" . $cn . "\" already exists."));
		// 	if($this->confirm('Do you want to continue and re-generate component ?', true) === false)
		// 		$vrf = false;
		// }

		if($vrf){
			$this->component_name = $cn;
			$this->component_arguments = $this->more($this->ask('Put your arguments '), '!##Be_null||', null, $this->argument_pattern, '"');
			$class_->set_component_arguments($this->component_arguments);
		}

	}

	/**
	 * Stylize string for console message
	 *
	 * @param  string  $str
	 * @return string
	 */
	private function stylize(string $str) : string{

		$s = "";
		for($i = 0; $i < (strlen($str) - 1); $i++) $s .= " ";
		return  "\t".$s . "\n" . $str . "\n\t" . $s;

	}

	public function init_paths(object $class_){
		if(is_object($class_)){
			$class_
				->add_path('view', config('view.paths'))
				->add_path('component.render', array_map(function($v){return $v . '\components';}, config('view.paths'), ) )
				->add_path('component.render', app_path('View\Components'))
			;
		}
		exit();
	}

}
