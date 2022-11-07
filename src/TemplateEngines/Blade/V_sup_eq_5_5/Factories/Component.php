<?php

namespace Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories;

use Compio\Component\ComponentBase;
use Compio\Traits\Factory;
use Compio\Traits\Singleton;

class Component extends ComponentBase {

	use Singleton;

	/**
	 * Error message and state.
	 * 
	 *  @var array
	 */
	public $error = [
		[
			'status_error' => false,
			'message' => null
		]
	];

	/**
	 * Create a new Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories\Component instance.
	 *
	 * @return void
	 */
	public function __construct(){

		$this->initConfig();

	}

	/**
	 * Initialize configuration datas.
	 *
	 * @return void
	 */
	public function initConfig(){
		$this->config()::setDefault([
			'template' => [
				'class' => [
					'path' => $this->getRootPath() . '\app\View\Components',
					'template_file' => dirname(__DIR__) . '\resources\component\class.php',
					'generate' => true,
					'convert_case' => 'default', // new
					'keywords' => [
						'@command',
						'@namespace',
						'@class_name',
						'@locate_css',
						'@locate_js',
						'@locate_render',
						'@args_init',
						'@args_params',
						'@args_construct',
						'@args_render',
					]
				],
				'render' => [
					'path' => $this->getRootPath() . '\resources\views\components',
					'file_extension' => 'blade.php',
					'short_path' => 'components',
					'template_file' => dirname(__DIR__) . '\resources\component\render.php',
					'generate' => true,
					'keywords' => [
						'@component_name',
						'@class_html',
					]
				],
				'css' => [
					'path' => $this->getRootPath() . '\public\css\components',
					'file_extension' => 'css',
					'short_path' => 'css\components',
					'template_file' => dirname(__DIR__) . '\resources\component\css.php',
					'generate' => true,
					'keywords' => [
						'@class_html',
					]
				],
				'js' => [
					'path' => $this->getRootPath() . '\public\js\components',
					'file_extension' => 'js',
					'short_path' => 'js\components',
					'template_file' => dirname(__DIR__) . '\resources\component\js.php',
					'generate' => true,
					'keywords' => [
						'@class_html',
					]
				]
			],
			'require_template' => false,
			'replace_component_exist' => null,
		]);
	}

	/**
	 * Checks if the component exists.
	 *
	 * @return bool
	 */
	public function componentExists(){

		$p = $this->config()->getMerge('template');
		$p = is_array($p) && array_key_exists('class', $p) && array_key_exists('path', $p['class']) ? $p['class']['path'] : null;
		$p = is_array($p) ? end($p) : $p;

		return !empty($p) ? file_exists($p . '\\' . $this->name()->getName() . '.php') : false;

	}

	/**
	 * Initialization of component generation.
	 *
	 * @return array
	 */
	public function generateInit(){

		$this->path()->init();

		$this->keyword($this->template())->initGenerate();

		$this->error = [];

		foreach ($this->template()->get() as $type => $value) {

			$val = $this->generate($type, $value);

			if(array_key_exists('status_error', $val)) $this->error[] = $val;
			else $this->error = array_merge($this->error, $val);

		}

		$this->error['terminate'] = [
			'status_error' => false,
			'message' => "\t" . 'Component (' . $this->name()->getName() . ') created successfully !'
		];

		return $this->error;

	}

	/**
	 * Component generation.
	 *
	 * @return string      $type
	 * @return array|null  $datas
	 * @return array
	 */
	public function generate(string $type, array|null $datas){

		$ret = [
			'status_error' => true,
			'message' => "\t  Component `" . $this->name()->getName() . '`is not created !  '
		];

		if(!empty($datas)){

			if(array_key_exists('template_file', $datas)){

				$template_file = $datas['template_file'];
				$template_file = is_array($template_file) 
					? (is_string(end($template_file)) 
						? end($template_file) 
						: 'default\/|'
					)
					: (is_string($template_file) 
						? $template_file 
						: 'default\/|'
					)
				;

				if(file_exists($template_file) && is_readable($template_file)){

					$creation_response = $this->createTemplate($template_file, $datas['path']);
					$creation_response = array_key_exists('status_error', $creation_response) 
						? [$creation_response] 
						: $creation_response
					;

					$keywords = $datas['keywords'];

					foreach ($creation_response as $key => $response_template) {

						if($response_template['status_error'] !== true){

							$content = file_get_contents($response_template['file']);

							foreach ($keywords as $key => $value) {

								$value = is_callable($value) && !is_string($value) 
									? $value(...[$datas, $this->arguments()->get()]) 
									: (is_string($value) || is_numeric($value) 
										? $value 
										: null
									)
								;

								$content = str_replace($key, $value, $content);

							}

							file_put_contents($response_template['file'], $content);

						}

					}

					$ret = empty($creation_response) ? $ret : $creation_response;

				}
				else $ret['message'] = "\t  The template file \"" . $template_file . '" was not found or is not readable !  ';

			}
			else $ret['message'] = "\t  The template of the file was not filled require with ! On the key configuration `" . $type . "`. Insert key `template_file => 'model_path'`  ";

		}

		return $ret;

	}

	/**
	 * Creation of template file.
	 *
	 * @return string      $template_file
	 * @return array|null  $copy_template_here
	 * @return array
	 */
	public function createTemplate(string $template_file, array|string $copy_template_here){

		$copy_template_here = is_array($copy_template_here) ? $copy_template_here : [$copy_template_here];

		$t = [];

		foreach ($copy_template_here as $key => $value) {

			if(file_exists($value['dirname']) || mkdir($value['dirname'], 0777, true)){

				$copy_already_exists = file_exists($value['file']);

				if(copy($template_file, $value['file'])){

					$t[] = $copy_already_exists === true 
						? [
							'status_error' => null,
							'message' => "\t" . 'Warning | Modified : "' . $value['file'] . '"',
							'file' => $value['file']
						]
						: [
							'status_error' => false,
							'message' => "\t" . 'Success | Created : "' . $value['file'] . '"',
							'file' => $value['file']
						]
					;

				}
				else $t[] = [
					'status_error' => true,
					'message' => "\t  \"" . $value['file'] . '" file cannot be created due to write permission ! Change write permissions or create the file and try again !  '
				];

			}
			else $t[] = [
				'status_error' => true,
				'message' => "\t  \"" . $value['file'] . '" folder cannot be created due to write permission ! Change write permissions or create the folder and try again !  '
			];

		}

		return empty($t) 
			? [
				'status_error' => true,
				'message' => "\t" . 'Error : "' . implode(', ', $copy_template_here) . '" This files is not created ! Change write permissions or create files and try again !'
			]
			: $t
		;

	}

	/**
	 * Get console command
	 *
	 * @return string
	 */
	public function getConsoleCommand($args = null){

		return '// php artisan compio:component ' . $this->name()->getName() . ' ' . $args;

	}


	/**
	 * Get project root path
	 *
	 * @return string
	 */
	public function getRootPath()
	{
		return getcwd();
	}

}