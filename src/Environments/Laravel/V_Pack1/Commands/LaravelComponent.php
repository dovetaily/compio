<?php

namespace Compio\Environments\Laravel\V_Pack1\Commands;

use Illuminate\Console\Command;
use Compio\Traits\MoreGenerate;
use Compio\TemplateEngines\Target;

class LaravelComponent extends Command
{

	use MoreGenerate;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	private $resources_path = __DIR__ . '\resources\component';

	/**
	 * The pattern of arguments
	 *
	 * @var string
	 */
	private $argument_pattern = ['default' => '/([a-z0-9_:]+=\"[^=]+\")|(\"[a-z0-9_:\s]+\"=[^\s]+)|(\"[a-z0-9_:\s]+\"=\"[^=]+\")|([a-z0-9_:]+=[^\s]+)|(\"[a-z0-9_:\s]+\")|([a-z0-9_:]+)/i', 'type' => '/\"(.*?)\"/'];

	/**
	 * The datas of components to generate.
	 *
	 * @var array
	 */
	private $datas = [];

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
	protected $description = 'Advanced component generator (dovetail/compio)';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle(){

		$this->init_template_engine();

		$c = $this->template_engine['pack'] . '\Pack';
		// $c = $c::component(['css' => ['jquery']]);
		// $c = $c::component();

		$this->init_datas($c);
		
		if(!empty($this->datas)){
			foreach ($this->datas as $k => $val_) {
				$states = [];
				$c = $val_['template_class'];
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
				if(!empty($states)) $this->info($this->stylize("\t" . 'Component (' . $val_['component_name'] . ') created successfully.'));
				else $this->error($this->stylize("\t" . 'Component is not created !'));
			}
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
		// dump();
		// dump($match);
		// exit();
		while ($turn === true) {
			if(isset($component_name)) $this->error($this->stylize("\t  \"" . $component_name . "\" Is not correct name ! (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$ -OR- ^\#([a-z]+)\|([^|]+)$)  "));
			$component_name = trim($this->ask('Component name ? (Component | Path/Component)'));
			$turn = ((bool) preg_match('/^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$/i', $component_name)) == true || (!empty($component_name) && ((bool) preg_match('/^\#([a-z]+)\|([^|]+)$/i', $component_name, $match)) == true) ? false : true;
		}

		if(empty($match)){
			$tm_class = $class_::component();
			// var_dump($class_::component());
			// var_dump($component_name, config('compi.component'));
			$tm_class->set_component_name($component_name);
			$module_list = [
				'a' => 'all',
				'r' => 'render',
				'c' => 'class',
				'cs' => 'css',
				'j' => 'js',
			];
			if(!function_exists('compio\environments\laravel\v_pack1\commands\module_w9i22n2e_vrf')){
				function module_w9i22n2e_vrf($cons, $module_list){
					$turn = true;
					while($turn === true){
						echo " Changer que les fichiers :\n".implode('', array_map(function(string $key, string $val){return "\n\t[" . $key . "] " . $val; }, array_keys($module_list), array_values($module_list))) . "\n\n";
						$generate__ = explode(',', $cons->ask('Choisissez des options (ex. render, css)', 'a'));
						$vr = [];
						foreach ($generate__ as $kk) {
							if(array_key_exists(trim($kk), $module_list)) $vr[] = $module_list[trim($kk)];
						}
						if(empty($vr)) $cons->error($cons->stylize("\t  Veuillez choisir parmi la liste d'option !  ") . "\n");
						else{
							if(in_array('all', $vr)) $vr = (function($t){array_shift($t); return $t;})($module_list);
							$cons->info($cons->stylize("\t  Les options traités seront : " . implode(', ', $vr) . "  \n"));
							$turn = false;
						}
					}
					return $vr;
				}
			}
			$vrf = true;

			if(($v = $tm_class->verify_component_exist()) !== false){
				$this->error($this->warn(" Component \"" . $component_name . "\" already exists."));
				if($this->confirm('Do you want to continue and re-generate component ?', true) === false)
					$vrf = false;
				else $module_list = module_w9i22n2e_vrf($this, $module_list);
			}

			if($vrf){
				$args = $this->more($this->ask('Put your arguments '), '!##Be_null||', null, $this->argument_pattern, '"');
				$tm_class->set_component_arguments($args);
				$this->add_data($component_name, $args, $tm_class, config('compio.component.config'), $module_list);
			}
		}
		else{
			$str = array_shift($match);
			if($match[0] == 'config'){
				$components = config($match[1]);
				if($components !== null && is_array($components)){
					$components_ = [];
					foreach ($components as $key => $value) {
						if(is_array($value)){
							if(array_key_exists('name', $value) && is_string($value['name']) && !empty($value['name'])){
								$tm_class = $class_::component();
								while(((bool) preg_match('/^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$/i', $value['name'])) === false){
									$this->error($this->stylize("\t  \"" . $value['name'] . "\" Is not correct name ! (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$)  "));
									$value['name'] = trim($this->ask('Component name ? (Component | Path/Component)'));
								}

								if(is_array(config('compio.component.config')) && !empty(config('compio.component.config'))) $value['config'] = array_merge(config('compio.component.config'), (array_key_exists('config', $value) ? $value['config'] : []));

								$value['config']['replace_component_exist'] = array_key_exists('replace_component_exist', $value['config']) ? $value['config']['replace_component_exist'] : null;

								$tm_class->set_component_name($value['name']);
								$vrf = $value['config']['replace_component_exist'] === null ? true : $value['config']['replace_component_exist'];

								if($value['config']['replace_component_exist'] === null && ($v = $tm_class->verify_component_exist()) !== false){
									$this->error($this->warn(" Component \"" . $value['name'] . "\" already exists."));
									if($this->confirm('Do you want to continue and re-generate component ?', true) === false)
										$vrf = false;
									else $module_list = module_w9i22n2e_vrf($this, $module_list);
								}
								elseif($value['config']['replace_component_exist'] === true && ($v = $tm_class->verify_component_exist()) !== false) $vrf = true;
								elseif($value['config']['replace_component_exist'] === false && ($v = $tm_class->verify_component_exist()) !== false) $vrf = false;
								else $vrf = true;
								if($vrf){
									$value['args'] = array_key_exists('args', $value) ? $value['args'] : null;
									if(is_string($value['args']) && !empty($value['args'])){
										$value['args'] = $this->more(trim($value['args']), '!##Be_null||', null, $this->argument_pattern, '"');
									}
									elseif(is_array($value['args']) && !empty($value['args'])){
										$args = [];
										$args_error = false;
										foreach ($value['args'] as $type => $val) {
											$arg_exp = explode(':', $type);
											$arg_name = array_pop($arg_exp);
											if(!empty($arg_exp)){
												$ff = array_map(function($el){return (bool) preg_match('/^[a-z\\\]+[a-z0-9\\\]+$|^[a-z\\\]+$|^[a-z]$/i', $el);}, $arg_exp);
												if(array_search(false, $ff) !== false){
													$args_error = true; break;
												}
											}
											if(!((bool) preg_match('/^[a-z]+[a-z0-9]+$|^[a-z]$/i', $arg_name))){
												$args_error = true; break;
											}
										}
										if($args_error === true){
											$this->error($this->stylize("\t  The arguments `" . $type . "` of component \"" . $value['name'] . "\" is not correct ! (^[a-z\\\\\]+[a-z0-9\\\\\]+$|^[a-z\\\\\]+$|^[a-z]$)  "));
											$args = $this->more($this->ask('Put your arguments for this component "' . $value['name'] . '"'), '!##Be_null||', null, $this->argument_pattern, '"');
										}
										$value['args'] = empty($args) ? null : $args;
										$tm_class->set_component_arguments($value['args']);
									}

									$components_[strtolower($value['name'])] = $value;
									$this->add_data($value['name'], $value['args'], $tm_class, $value['config'], $module_list);
									$this->info($this->stylize("\t" . $key . ' : "' . trim($value['name']) . '" component is loaded !'));
								}
								else $this->warn("\t" . $key . ' : "' . trim($value['name']) . "\" component already exists !\n");
							}
							else {
								$this->error($this->stylize("\t  " . $key . ' : "' . (function($vd){if(is_array($vd)){ob_start();print_r($vd); return str_replace(["\n\t\t", "\n\t", "\n"], "", ob_get_clean());} return is_callable($vd) ? 'is callable' : $vd;})($value['name']) . "\" component is not loaded !  "));
								$this->error($this->stylize("\t  Because your component datas is not correct (`name` key is not exists or is not string or is empty !)  "));
							}
						}
						else {
							$this->error($this->stylize("\t  " . ($key + 1) . ' : "' . (function($vd){ob_start();if(!is_callable($vd)){print_r($vd);}else{echo $vd;} return str_replace(["\n\t\t", "\n\t", "\n"], "", ob_get_clean());})($value) . '" component is not loaded ! Because your component datas is not correct (is not array)  '));
						} 
					}
				}
				else $this->error($this->stylize("\t  This config `" . $match[1] . "` is not correct (or not supported) !  "));
			}
			else $this->error($this->stylize("\t  Alternative `" . $match[0] . "`(" . $str . ") is not supported !  "));
		}

	}

	/**
	 * Add new component data
	 *
	 * @param  string      $component_name
	 * @param  array|null  $args
	 * @param  object      $template_class
	 * @param  array|null  $config
	 * @return object
	 */
	public function add_data(string $component_name, array|null $args, object $template_class, array|null $config, array $module){
		$this->datas[] = [
			'component_name' => $component_name,
			'args' => $args,
			'template_class' => $template_class,
			'config' => $config,
			'module' => $module
		];
		return $this;
	}

	/**
	 * Stylize string for console message
	 *
	 * @param  string  $str
	 * @return string
	 */
	public function stylize(string $str) : string{

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
