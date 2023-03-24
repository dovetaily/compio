<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Commands;

use Compio\Component\Name as ComponentName;
use Compio\Environments\Laravel\V_sup_eq_5_5\Component as ComponentFoundation;
use Compio\Environments\Laravel\V_sup_eq_5_5\CommandInterface;

use Illuminate\Console\Command;

class Component extends ComponentFoundation implements CommandInterface {

	/**
	 * Closure for verify template engine
	 *
	 * @var array
	 */
	protected $check_template_class_with_closure = [];

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'compio:component 
							{component_name? : Component Name (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$ or use config ^\#([a-z]+)\|([^|]+)}
							{args?* : Your arguments for the class (ex. my_arg=default_value my_second_arg my_xx_arg="Hello world")}
							{--replace= : Replace the component if it exists (\'true\' for replace, ignore with \'false\')}
							';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Advanced component generator Compio(`dovetail/compio`)';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle(){

		$this->initTemplateEngine();

		$c = $this->getTemplateEngineSelected('foundation');

		if($this->argument('component_name') === null)
			parent::initDatas($c);
		else
			$this->initDatas($c);

		if(!empty($this->datas))
			foreach ($this->datas as $k => $val_)
				$this->generate($val_);

		return defined('\\' . Command::class . '::SUCCESS') ? Command::SUCCESS : true;

	}

	/**
	 * Initializing Datas
	 *
	 * @return void
	 */
	protected function initDatas($class_) : void{

		$app_config = $this->getAppConfig();

		$component_name_arg = $this->mergeCharactersDuplicate($this->argument('component_name'));

		$component_name = ComponentName::nameIsCheck($component_name_arg);

		$arguments = ($val = $this->argument('args')) !== [] ? $val : null;

		if($component_name['status'] === false){
			$this->error($this->stylize("\t  `" . $this->argument('component_name') . "` Is not correct name ! (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$ -OR- ^\#([a-z]+)\|([^|]+)$)  "));

			$component_name = $this->askComponentName();
		}

		if(empty($component_name['match'])){
			// $component_name['name'] // name conversion
			$this->initDatasWithCommand($class_::component(), array_key_exists('name', $component_name) ? $component_name['name'] : $component_name_arg, $arguments, $app_config);

		}
		else{

			$match = $component_name['match'];

			$str = array_shift($match);

			if(!empty($arguments)){

				// fusion des arguments du ficher avec celui de la command line
				$this->check_template_class_with_closure = ['closure' => function($template_engine, $args){

					$resp = array_merge(
						(!empty($v = $template_engine->arguments()->get())
							? $v
							: []
						)
					, (empty($args) || !is_array($args) 
						? [] 
						: $args
					));

					$template_engine->arguments()->set($resp);

				}, 'args' => $this->checkComponentsArgs($component_name_arg, $arguments)];

			}


			if($match[0] == 'config') $this->initDatasWithConfig($match[1], $class_, $app_config, in_array(trim(($rr = $this->option('replace'))), ['true', 'false']) ? ($rr = $rr == 'true' ? true : false) : null);
			else $this->error($this->stylize("\t  Alternative `" . $match[0] . "`(" . $str . ") is not supported !  "));

		}

	}

	/**
	 * Initialize the data received by the input.
	 *
	 * @param  object             $template_engine
	 * @param  string             $component_name
	 * @param  array|string|null  $arguments
	 * @param  array              $app_config
	 * @return void
	 */
	public function initDatasWithCommand(object $template_engine, string $component_name, $arguments, array $app_config = []){

		$template_engine->config()->setApp($app_config);
		$template_engine->config()->merge();
		$template_engine->name($component_name);

		$t = $template_engine->config()->getMerge('replace_component_exist');

		if(in_array(trim(($rr = $this->option('replace'))), ['true', 'false'])){

			$rr = $rr == 'true' ? true : false;

			if(is_array($t)) $t[] = $rr;
			else $t = [$t, $rr];

		}

		$template_engine->config()->setMerge($t, 'replace_component_exist');

		$rp = [$template_engine->config()->getMerge('replace_component_exist'), $template_engine->config()->getMerge('require_template')];

		$vrf = $this->componentExist(
			(is_array($rp[0]) 
				? end($rp[0]) 
				: ($rp[0])
			)
		, $template_engine->componentExists(), $template_engine->config()->getMerge('template'), $component_name, (bool) (is_array($rp[1])
			? end($rp[1])
			: $rp[1]
		));

		if($vrf['status'] === true){

			$template_to_generate = $vrf['template'];

			$template_engine->template()->templateToGenerate($template_to_generate);
			// $this->compliant_verif($template_engine->template()->templateToGenerate($template_to_generate));

			$args = $this->checkComponentsArgs($component_name, $arguments);

			$template_engine->arguments($args);

			$this->addData($template_engine);

		}
		else $this->warn($this->stylize("\t \"$component_name\" Component already exists !  "));


	}

	/**
	 * Checks configuration component arguments.
	 *
	 * @param  string             $component_name
	 * @param  array|string|null  $arguments
	 * @return array|string|null
	 */
	public function checkComponentsArgs(string $component_name, $arguments = null){

		return $this->checkConfigComponentsArgs($component_name, $arguments, function($name, $value){

			$value = explode('=', $value);

			$name = array_shift($value);

			if(empty($value))
				return [
					'name' => $name,
					'value' => null
				];
			else{

				$value = implode('=', $value);

				return [
					'name' => $name,
					'value' => ($value == ""
						? \Compio\Component\Arguments::NULL_VALUE
						: \Compio\Traits\ArgumentFormat::type_verif('/^\'.*?\'$|^\".*?\"$/', $value, false, ["'", '"'])
						// : (is_numeric($value)
						// 	? (strpos($value, '.') || $value < PHP_INT_MIN || $value > PHP_INT_MAX
						// 		? (double) $value
						// 		: (int) $value
						// 	)
						// 	: $value
						// )
					)
				];

			}

		});
	}

	/**
	 * Add new component data.
	 *
	 * @param  object  $template_class
	 * @return object
	 */
	public function addData(object $template_class){

		if(!empty($Closure = $this->check_template_class_with_closure)){

			call_user_func($Closure['closure'], $template_class, $Closure['args']);

		}

		$this->datas[] = $template_class;

		return $this;

	}

}
