<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5;

use Compio\Component\Name as ComponentName;
use Compio\TemplateEngines\Target;
use Compio\Traits\ConsoleHelpers;
use Compio\Traits\ArgumentFormat;

use Illuminate\Console\Command;

class Component extends Command {

	use ArgumentFormat, ConsoleHelpers;

	/**
	 * The pattern of arguments
	 *
	 * @var string
	 */
	protected $argument_pattern = ['default' => '/([a-z0-9_:]+=\"[^=]+\")|(\"[a-z0-9_:\s]+\"=[^\s]+)|(\"[a-z0-9_:\s]+\"=\"[^=]+\")|([a-z0-9_:]+=[^\s]+)|(\"[a-z0-9_:\s]+\")|([a-z0-9_:]+)/i', 'type' => '/\"(.*?)\"/'];

	/**
	 * The datas of components to generate.
	 *
	 * @var array
	 */
	protected $datas = [];

    /**
	 * Template engines
	 *
	 * @var array
	 */
	protected $template_engines = [];

    /**
	 * Template engines selected
	 *
	 * @var object
	 */
	protected $template_engine_selected;

	/**
	 * Generate template
	 *
	 * @param  object  $template_engine
	 * @return void
	 */
	public function generate(object $template_engine){

		$response_template = $template_engine->generateInit();

		foreach ($response_template as $key => $value) {

			$error = array_key_exists('status_error', $value) ? $value['status_error'] : false;

			$message = array_key_exists('message', $value) ? $value['message'] : null;

			if($error === false)
				$this->info($this->stylize(is_null($message) ? 'Own message' : $message));
			elseif($error === true)
				$this->error($this->stylize(is_null($message) ? 'Own message' : $message));
			else
				$this->warn($this->stylize(is_null($message) ? 'Own message' : $message));

		}

	}

	/**
	 * Get signature.
	 *
	 * @return string
	 */
	public static function get_signature() : string{

		return $this->signature;

	}

	/**
	 * Initializing Template Engine
	 *
	 * @return void
	 */
	protected function initTemplateEngine() : void{

		$this->template_engines = Target::getInstance()->getEnginesWorks();

		if(!empty($this->template_engines)){

			$choices = array_keys($this->template_engines);

			if(count($choices) > 1) $choices = strtolower($this->choice('Choose your Template Engine ', array_map(
				function($v){
					return ucfirst($v);
				},
				$choices
			)));
			elseif(!empty($choices)) $choices = strtolower(current($choices));

			if(!empty($this->template_engines) && !empty($choices) && array_key_exists($choices, $this->template_engines)){
				$this->template_engines[$choices]['selected'] = true;

				$this->template_engine_selected = $this->template_engines[$choices];
			}
			else echo "\nYou haven't template engines !\n";

			$this->info($this->stylize("\t  \"" . ucfirst($choices) .$this->template_engines[$choices]['datas']['version']['v']. "\" template engine is selected to generate component !  ") . "\n");

		}
		else echo "\nYou haven't template engine supported by dovetaily/compio !\n"; // And choose one template engine supported ->

	}

	/**
	 * Get Template Engine selected
	 *
	 * @param string|null       $key
	 * @return mixed
	 */
	public function getTemplateEngineSelected($key = null) {

		return empty($key) ? $template_engine_selected : (array_key_exists('datas', $this->template_engine_selected) && array_key_exists($key, $this->template_engine_selected['datas']) 
			? $this->template_engine_selected['datas'][$key]
			: false
		);

	}

	/**
	 * Initializing Datas
	 *
	 * @return void
	 */
	protected function initDatas($class_) : void{


		$app_config = $this->getAppConfig();

		$component_type_name = $this->askComponentName();

		if(empty($component_type_name['match'])){
			// $component_type_name['name']  = ''; // name conversion
			$this->initDatasWithInput($class_::component(), $component_type_name['name'], $app_config);

		}
		else{

			$match = $component_type_name['match'];

			$str = array_shift($match);

			if($match[0] == 'config') $this->initDatasWithConfig($match[1], $class_, $app_config);
			else $this->error($this->stylize("\t  Alternative `" . $match[0] . "`(" . $str . ") is not supported !  "));

		}

	}

	/**
	 * Initialize the data received by the input
	 *
	 * @param  object  $template_engine
	 * @param  string  $component_name
	 * @param  array   $app_config
	 * @return void
	 */
	public function initDatasWithInput(object $template_engine, string $component_name, array $app_config = []){

		$template_engine->config()->setApp($app_config);
		$template_engine->config()->merge();
		$template_engine->name($component_name);

		$rp = [$template_engine->config()->getMerge('replace_component_exist'), $template_engine->config()->getMerge('require_template')];

		$vrf = $this->componentExist(
			(is_array($rp[0]) 
				? end($rp[0]) 
				: $rp[0]
			)
		, $template_engine->componentExists(), $template_engine->config()->getMerge('template'), $component_name, (bool) (is_array($rp[1])
			? end($rp[1])
			: $rp[1]
		));

		if($vrf['status'] === true){

			$template_to_generate = $vrf['template'];

			$template_engine->template()->templateToGenerate($template_to_generate);
			// $this->compliant_verif($template_engine->template()->templateToGenerate($template_to_generate));

			$args = $this->ask('Put your arguments ');

			$template_engine->arguments($args);

			$this->addData($template_engine);

		}
		else $this->warn($this->stylize("\t \"$component_name\" Component already exists !  "));

	}

	/**
	 * Initialize the data received by configuration
	 *
	 * @param  string      $config_path
	 * @param  string      $class_
	 * @param  array       $app_config
	 * @param  bool|null   $replace_option
	 * @return void
	 */
	public function initDatasWithConfig(string $config_path, string $class_, array $app_config = [], $replace_option = null){

		$components = config($config_path);

		if(!empty($components) && is_array($components)){

			$this->info($this->stylize("\t  This config `" . $config_path . "` is matched !  "));

			foreach ($components as $key => $value) {

				if(is_array($value)){

					if(array_key_exists('name', $value) && is_string($value['name']) && !empty($value['name'])){

						$template_engine = $class_::component();
						$template_engine->config()->setApp($app_config);

						$value['name'] =  $this->getComponentNameVerify(ComponentName::getPatterns('name'), $this->mergeCharactersDuplicate($value['name']));


						
						if(
							array_key_exists('config', $value) && (
								is_array($value['config']) || (
									is_callable($value['config']) && is_array( $value['config'] = $value['config']() )
								)
							)
						) $template_engine->config()->set($value['config']);

						$template_engine->config()->merge();
						$template_engine->name($value['name']); // name conversion

						$t = $template_engine->config()->getMerge('replace_component_exist');

						if(is_bool($replace_option)){
							if(is_array($t)) $t[] = $replace_option;
							else $t = [$t, $replace_option];
						}

						$template_engine->config()->setMerge($t, 'replace_component_exist');

						$rp = [$template_engine->config()->getMerge('replace_component_exist'), $template_engine->config()->getMerge('require_template')];

						$vrf = $this->componentExist(is_array($rp[0]) 
							? end($rp[0]) 
							: ($rp[0])
						, $template_engine->componentExists(), $template_engine->config()->getMerge('template'), $value['name'], (bool) (is_array($rp[1])
							? end($rp[1])
							: $rp[1]
						));

						if($vrf['status'] === true){

							$template_engine->template()->templateToGenerate($vrf['template']);
							// $this->compliant_verif($template_engine->template()->templateToGenerate($vrf['template']));

							$value['args'] = $this->checkConfigComponentsArgs($value['name'], array_key_exists('args', $value) 
								? $value['args']
								: null
							);

							$template_engine->arguments($value['args']);

							$components_[strtolower($value['name'])] = $value;

							$this->addData($template_engine);

							$this->info($this->stylize("\t" . $key . ' : "' . trim($value['name']) . '" component is loaded !'));

						}
						else $this->warn("\t" . $key . ' : "' . trim($value['name']) . "\" component already exists !\n");

					}
					else {
						$this->error($this->stylize("\t  " . $key . ' : "' . str_replace(["\n\t\t", "\n\t", "\n"], "", var_export($value, true)) . "\" component is not loaded !  "));
						$this->error($this->stylize("\t  Because your component datas is not correct (`name` key is not exists or is not string or is empty !)  "));
					}

				}
				else {
					$this->error($this->stylize("\t  " . $key . ' : "' . str_replace(["\n\t\t", "\n\t", "\n"], "", var_export($value, true)) . '" component is not loaded ! Because your component datas is not correct (is not array)  '));
				}

			}

		}
		else $this->error($this->stylize("\t  This config `" . $config_path . "` is not correct (or empty or not supported) !  "));

	}

	/**
	 * Get user configuration for library components
	 *
	 * @return string  $path
	 * @return mixed
	 */
	public static function getAppConfig(string $path = 'compio.component.config'){

		return function_exists('\config') && ($v = config($path)) !== null ? $v : [];

	}

	/**
	 * Checks configuration component arguments
	 *
	 * @param  string             $component_name
	 * @param  array|string|null  $arguments
	 * @param  closure            $Closure
	 * @return array|string|null
	 */
	public function checkConfigComponentsArgs(string $component_name, $arguments = null, $Closure = null){

		if(is_array($arguments) && !empty($arguments)){

			$args = $arguments;

			$args_error = false;

			$name = null;

			foreach ($arguments as $name => $val) {

				if($Closure !== null){

					$response = $Closure($name, $val);

					unset($args[$name]);

					$args[$response['name']] = $response['value'];

					$name = $response['name'];

					$val = $response['value'];

				}

				$arg_exp = explode(':', $name);

				$arg_name = array_pop($arg_exp);

				if(!empty($arg_exp)){

					$ff = array_map(
						function($el){
							return (bool) preg_match('/^[a-z_\\\]+[a-z0-9_\\\]+$|^[a-z_\\\]+$|^[a-z_]$/i', $el);
						}
					, $arg_exp);

					if(array_search(false, $ff) !== false){
						$args_error = true;

						break;
					}

				}

				if(!((bool) preg_match('/^[a-z_]+[a-z0-9_]+$|^[a-z_]$/i', $arg_name))){
					$args_error = true;

					break;
				}

			}
			if($args_error === true){
				$this->error($this->stylize("\t  The arguments name `" . $name . "` of component \"" . $component_name . "\" is not correct ! (^[a-z_\\\\\]+[a-z0-9_\\\\\]+$|^[a-z_\\\\\]+$|^[a-z_]$)  "));

				$args = $this->ask('Put your arguments for this component "' . $component_name . '"');
			}

			$arguments = empty($args) ? null : $args;

		}

		return $arguments;

	}

	/**
	 * Get component name verify
	 *
	 * @param  string  $pattern
	 * @param  string  $component_name
	 * @return string
	 */
	public function getComponentNameVerify(string $pattern, string $component_name){

		while(((bool) preg_match($pattern, $component_name)) === false){
			$this->error($this->stylize("\t  `" . $component_name . "` Is not correct name ! (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$)  "));

			$component_name = trim($this->ask('Put new component name '));
		}

		return $component_name;

	}
	/**
	 * Get component name by asking console question
	 *
	 * @return array
	 */
	public function askComponentName(){

		$turn = true;

		while ($turn === true || (isset($turn['status']) && $turn['status'] === false)) {

			if(isset($component_name)) $this->error($this->stylize("\t  \"" . $component_name . "\" Is not correct name ! (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$ -OR- ^\#([a-z]+)\|([^|]+)$)  "));

			$component_name = $this->mergeCharactersDuplicate(trim($this->ask('Component name ? (Component | Path/Component)')));

			$turn = ComponentName::nameIsCheck($component_name);

			$turn['name'] = $component_name;

		}


		return $turn;

	}

	/**
	 * Check if component exist
	 *
	 * @param  bool|null      $replace_component_exist
	 * @param  bool  $component_exist
	 * @param  array      $module_list
	 * @param  string  $component_name
	 * @param  bool  $already_ask_template
	 * @return object
	 */
	public function componentExist($replace_component_exist, $component_exist, $module_list, $component_name, $already_ask_template = false){

		$vrf = true;

		$module = array_keys($module_list);

		if(($replace_component_exist === null && (!empty($component_exist)) === true) || ($already_ask_template === true && $replace_component_exist !== false)){

			if($already_ask_template !== true || ((!empty($component_exist)) === true && $replace_component_exist === null)){
				
				$this->warn(" Component \"" . $component_name . "\" already exists.\n\n File" . (count($component_exist) > 1 
					? 's' 
					: ''
				) . " found :");

				foreach ($component_exist as $template => $file)
					$this->warn("\n\t Template `" . $template . "` : " . $file);

				if($this->confirm('Do you want to continue and regenerate component ?', true) === false)
					$vrf = false;

			}

			if($vrf === true) $module = (function($cons, $module_list, $component_exist, $already_ask_template){

				$turn = true;

				while($turn === true){

					echo " Only change templates :\n".implode('', array_map(function(string $key, string $val){return "\n\t[" . $key . "] " . $val; }, array_keys($module_list), array_values($module_list))) . "\n\n";

					$generate__ = explode(',', $cons->ask('Choose one or more templates (ex. 2, 4)', '0'));

					$vr = [];

					foreach ($generate__ as $kk) {

						if(array_key_exists(trim($kk), $module_list)) $vr[] = $module_list[trim($kk)];
						elseif(is_numeric($kk)) $cons->warn("\t  The key `$kk` doesn't exist ! So she will be ignored !  ");

					}

					if(empty($vr)) $cons->error($cons->stylize("\t  Choose from the list of options ! ([0-9,]+|[0-9])  ") . "\n");
					else{

						if(in_array('ALL', $vr)) $vr = (function($t){array_shift($t); return $t;})($module_list);

						$cons->info($cons->stylize("\t  These template(s) will be " . (
							$already_ask_template !== true || (!empty($component_exist)) === true 
								? 're' 
								: null
						) . "generate : " . implode(', ', $vr) . "  \n"));

						$turn = false;

					}

				}

				return $vr;

			})($this, array_merge(['ALL'], $module), (!empty($component_exist)), $already_ask_template);

		}
		elseif($replace_component_exist === true && (!empty($component_exist)) === true) $vrf = true;
		elseif($replace_component_exist === false && (!empty($component_exist)) === true) {
			$vrf = false;

			$module = [];
		}

		if(!(!empty($component_exist))){

			$config_template_to_generate = array_map(
				function($v){
					return (bool) array_key_exists('generate', $v)
						? (is_array($v['generate']) 
							? end($v['generate']) 
							: $v['generate']
						)
						: true
					;
				}
				, $module_list
			);

			$tab = [];

			foreach ($module as $key)
				if($config_template_to_generate[$key] === true) $tab[] = $key;

			$module = $tab;

		}

		return [
			'status' => $vrf,
			'template' => $module
		];

	}

	/**
	 * Add new component data
	 *
	 * @param  object  $template_class
	 * @return object
	 */
	public function addData(object $template_class){

		$this->datas[] = $template_class;

		return $this;

	}

	/**
	 * Get merge characters duplicate
	 *
	 * @param  string|null  $value
	 * @param  string       $pattern
	 * @param  string       $trim
	 * @return string
	 */
	public function mergeCharactersDuplicate($value, string $pattern = '/([\\/]+|[.]+)/', string $trim = '/')
	{
		return trim((!empty(trim($value)) && is_string($value) ? preg_replace_callback($pattern, function($match){
			return end($match)[0];
		}, $value) : $value), $trim);
	}

	// public function compliant_verif($value){
	// 	$this->error('lorem');
	// 	dump($value);
	// 	exit('ss');

	// }

}
