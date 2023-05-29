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
			'message' => true
		]
	];

	/**
	 * Error message and state.
	 * 
	 *  @var array
	 */
	private $all_keywords = [];

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
					'convert_case' => 'uf',
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
	 * @return array
	 */
	public function componentExists(){

		$p = [];

		$temp = function($template){ 
			return !is_string($template) && is_callable($template) && is_array($conf = $template()) ? $conf : $template;  
		};
		foreach (array_keys($this->config()->getMerge('template')) as $template) {

			if(
				(
					!empty($this->config()->get()) && 
					array_key_exists($template, ($rec = $this->config()->get('template'))) && 
					array_key_exists('path', (
						$rec = $temp($rec[$template])
					))
				) || 
				(
					!empty($this->config()->getApp()) && array_key_exists($template, (
						($rec = $this->config()->getApp('template')) === false 
						? [] 
						: $rec
					)) && 
					array_key_exists('path', ( $rec = $temp($rec[$template]) ))
				) || 
				(
					array_key_exists($template, (
						$rec = $this->config()->getDefault('template')
					)) && 
					array_key_exists('path', ( $rec = $temp($rec[$template]) ))
				)
			){

				$ext = trim(array_key_exists('file_extension', $rec)
					? (is_array($rec['file_extension']) && !empty($rec['file_extension']) && is_string($e = end($rec['file_extension']))
						? $e
						: (is_string($rec['file_extension'])
							? $rec['file_extension']
							: 'php'
						)
					)
					: 'php'
				, '.');

				foreach ((is_array($rec['path'])
					? $rec['path'] 
					: [$rec['path']]
				) as $path) {

					if(is_string($path) && file_exists($file = $path . '\\' . str_replace('/', '\\', $this->name()->getName()) . '.' . $ext))
						$p[$template] = $file;

				}

			}

		}

		return $p;

	}

	/**
	 * Initialization of component generation.
	 *
	 * @return array
	 */
	public function generateInit(){

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
	public function generate(string $type, $datas){

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

				if(file_exists($template_file) && is_readable($template_file) && !empty($datas['path'])){

					$creation_response = $this->createTemplate($template_file, $datas['path'], $datas['template_file']);
					$creation_response = array_key_exists('status_error', $creation_response) 
						? [$creation_response] 
						: $creation_response
					;

					$keywords = $datas['keywords'];

					foreach ($creation_response as $key => $response_template) {

						$generate_ = !(isset($response_template['generate']) && $response_template['generate'] === false);

						if($response_template['status_error'] !== true){

							$content_original = file_get_contents($response_template['file']);
							$content = $generate_ ? $content_original : '';

							foreach ($keywords as $key => $value) {

								$value = is_array($value) && array_key_exists('callable', $value)
									? $value['callable'](...[
										/* 0 - $default_value */
										(array_key_exists('default_value', $value) && (is_numeric($value['default_value']) || is_string($value['default_value']) || (is_array($value['default_value']) && isset($value['default_value'][$key])))
											? (is_array($value['default_value'])
												? $value['default_value'][$key]
												: $value['default_value']
											)
											: null
										),
										/* 1 - $template_datas */
										$datas,
										/* 2 - $arguments */
										(!empty($a = $this->arguments()->get())
											? $a
											: []
										),
										/* 3 - callback_format_value */
										function($value, $type = null, $equal = ' = '){
											return \Compio\Traits\ArgumentFormat::format_value($value, $type, $equal);
										},
										/* 4 - $file_content */
										$content,
										/* 5 - $file_path|$current_file_content */
										$response_template['file'],
										/* 6 - $all_keywords */
										$this->all_keywords,
										/* 7 - $component_name */
										$this->name()->getName()
									])
									: (is_string($value) || is_numeric($value) 
										? $value 
										: null
									)
								;

								if(empty($this->all_keywords)) $this->all_keywords[$type] = [$key => is_bool($value) ? $keywords[$key] : $value];
								else $this->all_keywords[$type][$key] = is_bool($value) ? $keywords[$key] : $value;

								if(is_string($value)){
									$content = $generate_ ? str_replace($key, $value, $content) : '';

									$original = $datas['keywords'][$key];

									if(!isset($datas['keywords'][$key]['original']))
										$datas['keywords'][$key] = ['result' => $value];
									else
										$datas['keywords'][$key]['result'] = $value;

									if(!isset($original['original']))
										$datas['keywords'][$key]['original'] = $original;
								}
								elseif($value === true && $generate_) $content = file_get_contents($response_template['file']);

							}
							if($generate_)
								file_put_contents($response_template['file'], $content);
							else
								file_put_contents($response_template['file'], $content_original);

						}

					}

					$ret = empty($creation_response) ? $ret : $creation_response;

				}
				elseif(empty($datas['path'])) { $ret['status_error'] = false; $ret['message'] = null; }
				else $ret['message'] = "\t  The template file \"" . $template_file . '" was not found or is not readable !  ';

			}
			else $ret['message'] = "\t  The template of the file was not filled require with ! On the key configuration `" . $type . "`. Insert key `template_file => 'model_path'`  ";

		}

		return $ret;

	}

	/**
	 * Creation of template file.
	 *
	 * @param string        $template_file
	 * @param mixed         $copy_template_here
	 * @param array|string  $all_template
	 * @return array
	 */
	public function createTemplate(string $template_file, mixed $copy_template_here, array|string $all_template){

		$all_template = is_array($all_template) ? $all_template : [$all_template];
		$copy_template_here = is_array($copy_template_here) ? $copy_template_here : [$copy_template_here];

		$t = [];

		foreach ($copy_template_here as $key => $value) {

			if(isset($value['generate']) && $value['generate'] === false){
				$t[] = [
					'status_error' => 1,
					'message' => "\t" . 'Info | NOT Modified : "' . $value['file'] . '"',
					'file' => $value['file'],
					'generate' => false
				];
				continue;
			}

			if(!is_numeric($key) && isset($all_template[$key])){
				if(!file_exists($all_template[$key])){
					$t[] = [
						'status_error' => true,
						'message' => "\t" . 'ERROR : This template file "' . $all_template[$key] . '" not exists. File "' . $value['file'] . '" is not created',
					];
					continue;
				}
				$template_file = $all_template[$key];
			}

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