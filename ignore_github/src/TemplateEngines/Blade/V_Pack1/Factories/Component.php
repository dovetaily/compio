<?php

namespace Compio\TemplateEngines\Blade\V_Pack1\Factories;

// use Compio\Listen as B_Listen;
// use Compio\Environments\Laravel\Listen as LaravelListen;
use Compio\Traits\Singleton;
use Compio\Traits\Factory;

class Component{

	use Singleton, Factory;

	/**
	 * The paths
	 *
	 * @var array
	 */
	private $paths = [];

	/**
	 * The paths
	 *
	 * @var array
	 */
	private $component_name = [];

	/**
	 * The paths
	 *
	 * @var array
	 */
	private $component_arguments = [];

	/**
	 * The paths
	 *
	 * @var array
	 */
	private $class_render;

	/**
	 * Create a new Compio\TemplateEngines\Blade\V_Pack1\Factories\Component instance.
	 *
	 * ---@param  array  $more
	 * @return void
	 */
	public function __construct(/*array $more = []*/){
		$this
			->add_path('view', config('view.paths'))
			// ->add_path('component.render', array_map(function($v){return $v . '\components';}, config('view.paths'), ) ) // all view
			->add_path('component.render', file_exists(resource_path('views')) ? trim(resource_path('views'), '\\') . '\components' : trim(resource_path('views'), '\\') . '\components')
			->add_path('component.class', app_path('View\Components'))
			->add_path('component.assets', [
				'css' => file_exists(public_path('assets\css')) ? public_path('assets\css\components') : (file_exists(public_path('asset\css')) ? public_path('asset\css\components') : public_path('css\components')),
				'js' => file_exists(public_path('assets\js')) ? public_path('assets\js\components') : (file_exists(public_path('asset\js')) ? public_path('asset\js\components') : public_path('js\components')),
			])
		;
		// if(!empty($more)){
		// 	$last = $this->get_paths('component.assets');
		// 	foreach ($more as $key => $value) {
		// 		$value = empty($value) || (!is_string($value) && !is_array($value)) ? null : (is_string($value) ? [$value] : $value);
		// 		if(($key == 'css' || $key == 'js') && !empty($value)) $last[$key] = array_merge($last[$key], $value);
		// 	}
		// 	$this->set_paths($last, 'component.assets');
		// }
	}

	/**
	 * Get Factory Namespace
	 *
	 * @return string
	 */	
	protected static function getFactoryNameSpace(){ return '\Compio\TemplateEngines\Blade\V_Pack1\Factories'; }

	/**
	 * Add path
	 *
	 * @param  string             $key
	 * @param  array|null|string  $value
	 * @return object
	 */
	public function add_path(string $key, array|null|string $value){

		$this->paths[$key] = $value;

		return $this;

	}

	/**
	 * Set paths
	 *
	 * @param  string|null  $key
	 * @param  array        $paths
	 * @return object
	 */
	public function set_paths(array $paths, string $key = null){

		if(empty($key)) $this->paths = $paths;
		else $this->paths[$key] = $paths;

		return $this;

	}

	/**
	 * Get paths
	 *
	 * @param  string|null       $key
	 * @return array|string|null
	 */
	public function get_paths(string|null $key = null) : array|string|null{

		return $key === null ? $this->paths : (array_key_exists($key, $this->paths) ? $this->paths[$key] : null);

	}

	/**
	 * Set Component Name
	 *
	 * @param  string  $name
	 * @return object
	 */
	public function set_component_name(string $name){

		$f_info_ = array_map(function($v){ return $v == '.' ? '' : $v; }, pathinfo($name));
		$this->component_name = [
			'dir_name' => str_replace('/', '\\', $f_info_['dirname']),
			'namespace' => str_replace('/', '\\', $f_info_['dirname']),
			'file_name' => $f_info_['filename']
		];
		$this->component_name['file_path'] = ($this->component_name['dir_name'] != '' ? $this->component_name['dir_name'] . '\\' : '') . $this->component_name['file_name'];
		// var_dump($this->component_name);
		// exit();


		return $this;

	}

	/**
	 * Get Component Name
	 *
	 * @param  string|null       $key
	 * @return array|string|bool
	 */
	public function get_component_name(string|null $key = null) : array|string|bool {

		return empty($key) ? $this->component_name : (!empty($this->component_name) && array_key_exists($key, $this->component_name) ? $this->component_name[$key] : false);

	}

	/**
	 * Set Component Arguments
	 *
	 * @param  array|null  $args
	 * @return object
	 */
	public function set_component_arguments(array|null $args = []){

		$this->component_arguments = is_null($args) ? [] : $args;

		return $this;

	}

	/**
	 * Get Component Arguments
	 *
	 * @param  string|null       $key
	 * @return array|string|bool
	 */
	public function get_component_arguments(string|null $key = null) : array|string|bool {

		return empty($key) ? $this->component_arguments : (!empty($this->component_arguments) && array_key_exists($key, $this->component_arguments) ? $this->component_arguments[$key] : false);

	}

	/**
	 * Get Component Name
	 *
	 * @param  string|null       $key
	 * @return array|string|bool
	 */
	public function get_resources_path(){ return dirname(__DIR__) . '\resources\component'; }

	/**
	 * Generate all Template engine files
	 *
	 * @param string|array  $datas
	 * @param callable       $callback
	 * @param string|null   $resource
	 * @return array|bool
	 */
	public function generate(string|array $datas, callable $callback, string|null $resource = null){
		$datas = is_array($datas) ? $datas : [$datas];
		$component_name = $this->get_component_name();
		if(!empty($component_name) && $component_name !== false){
			$fls = [];

			foreach ($datas as $ext => $value) {

				$dir_path = $value . '\\' . $component_name['dir_name'];
				$file_path = $value . '\\' . $component_name['file_path'] . '.' . (is_numeric($ext) ? 'php' : $ext);

				if(file_exists($dir_path) || $mk = mkdir($dir_path, 0777, true)){
					$model_file = $this->get_resources_path() . '\\' . (!is_null($resource) ? $resource : $ext) . '.php';
					if(file_exists($model_file)){
						$exist = file_exists($file_path); // if exits already
						if(copy($model_file, $file_path)){
							$callback($this, $file_path, $component_name['file_path']);
							$fls[] = ['path' => $file_path, 'exist' => $exist]; 
						}
					}
				}
				else return ['status' => false, 'response' => 'Directory xx not create !', 'dir' => $dir_path];
			}

			return ['status' => true, 'files' => $fls];
		}

		return false;

	}

	/**
	 * Generate all assets files
	 *
	 * @return array|bool
	 */
	public function generate_assets_files(){ // "@param $more" Add default file on all generate component 
		return $this->generate($this->get_paths('component.assets'), function($class_, $file_path, $c_filepath){
			$content = file_get_contents($file_path);
			$content = str_replace('@class', $class_->init_class_render($c_filepath), $content);
			file_put_contents($file_path, $content);
		});
	}

	/**
	 * Generate render file
	 *
	 * @return array|bool
	 */
	public function generate_render(){

		return $this->generate(['blade.php' => $this->get_paths('component.render')], function($class_, $file_path, $c_filepath){
			$content = file_get_contents($file_path);
			$content = str_replace('@class', $class_->init_class_render($c_filepath), $content);
			$content = str_replace('@namespace', ucfirst(preg_replace_callback('/\\\([a-z])/i', function($v){return strtoupper(end($v));}, strtolower($c_filepath))), $content);
			file_put_contents($file_path, $content);
		}, 'render');

	}
	/**
	 * Generate class file
	 *
	 * @return array|bool
	 */
	public function generate_class(){

		return $this->generate($this->get_paths('component.class'), function($class_, $file_path, $c_filepath){
			$content = file_get_contents($file_path);

			$content = str_replace('@command', '// @command', $content);
			$content = $class_->get_component_name('namespace') == '' ? str_replace('\@namespace', null, $content) : str_replace('@namespace', $class_->get_component_name('namespace'), $content);
			$content = str_replace('@className', $class_->get_component_name('file_name'), $content);

			foreach ($class_->get_paths('component.assets') as $key => $value) {
				$value = is_string($value) ? [$value] : $value;
				foreach ($value as $val) {
					$content = str_replace(
						'@locate_' . $key, 
						"\n\t\t\t'" . trim(str_replace([public_path(), '\\'], [null, '/'], $val), '/') . '/'. str_replace('\\','/',$class_->get_component_name('file_path')) . '.' . $key . "',@locate_" . $key,
						$content
					);
				}
				$content = str_replace(",@locate_" . $key, "\n\t\t", $content);
			}

			$content = str_replace("@locate_css", null, $content);
			$content = str_replace("@locate_js", null, $content);



			$moreInit = $moreParamsarray = $moreConst = $moreRender = "";
			$varr = [];

			$va = $this->get_component_arguments();
			uasort($va, function ($a, $b) {
				return is_null($a) && is_null($b) ? 1 : (is_null($a) ? 1 : -1);
			});

			foreach (array_reverse($va) as $key => $value) {
				$sep = explode(':', $key);
				$name = array_pop($sep);
				if(!in_array($name, $varr)){
					$moreInit .= 'public $' . $name . ";\n\t";
					$moreConst .= '$this->' . $name . ' = $' . $name . ";\n\t\t";
					$moreRender .= "'" . $name . '\' => $this->' . $name . ",\n\t\t\t";
					$moreParamsarray .= (!empty($sep) ? (implode('|', $sep) . ' ') : null) . '$' . $name . (
						$value === null
							? null
							: " = ". (
								$value === '!##Be_null||'
									? 'null'
									: (
										$value == '[]' || $value == '[ ]'
											? $value
											: (
												is_int($value) || is_float($value) || is_double($value)
													? $value
													: '"' . $value . '"'
											)
									)
							)
					) . ', ';
					$varr[] = $name;
				}
			}
			$content = str_replace("@moreInit", $moreInit, $content);
			$content = str_replace("@moreParamsarray", trim($moreParamsarray, ', '), $content);
			$content = str_replace("@moreConst", $moreConst, $content);
			$content = str_replace("@moreRender", $moreRender, $content);

			$content = str_replace('@locate_render', 'components.' . str_replace('\\', '.', $class_->get_component_name('file_path')), $content);

			file_put_contents($file_path, $content);

		}, 'class');

	}

	/**
	 * Get Component Name
	 *
	 * @param  string|null       $key
	 * @return array|string|bool
	 */
	public function verify_component_exist(){

		return $this->get_component_name('file_path') ? file_exists($this->get_paths('component.class') . '\\' . $this->get_component_name('file_path') . '.php') : false;

	}



	/**
	 * Get Component Name
	 *
	 * @param  string|null       $key
	 * @return array|string|bool
	 */
	public function init_class_render(string|null $name = null){ return empty($this->class_render) ? ($this->class_render = strtolower(str_replace('\\', '-', $name)) . '-' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8)) : $this->class_render; }



	public static function getInstance(...$args){

		$c = self::class;

		return new $c(...$args);

	}

}