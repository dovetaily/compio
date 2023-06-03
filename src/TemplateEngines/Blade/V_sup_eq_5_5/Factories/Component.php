<?php

namespace Compio\TemplateEngines\Blade\V_sup_eq_5_5\Factories;

use Compio\Component\ComponentBase;
use Compio\Traits\Factory;
use Compio\Traits\Singleton;

use Illuminate\Support\Str;

use Compio\Environments\Laravel\V_sup_eq_5_5\Foundation;

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
	 * @param bool  $data_generator
	 * @return void
	 */
	public function __construct(bool $data_generator = false){

		$this->initConfig($data_generator);

	}

	/**
	 * Initialize configuration datas.
	 *
	 * @param bool  $data_generator
	 * @return void
	 */
	public function initConfig(bool $data_generator = false){
		$dt = $data_generator
			? [
				'template' => [
					'migration' => [
						'path' => Foundation::getAppDir('\database\migrations'),
						'template_file' => dirname(__DIR__) . '\resources\data\migration.compio',
						'generate' => true,
						'convert_case' => 'd',
						// 'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							// $path_info['filename'] = '2023_03_26_212151_create_' . Str::snake(Str::plural($path_info['filename'])) .'_table';
							$path_info['filename'] = date('Y_m_d_His') . '_create_' . Str::snake(Str::plural($path_info['filename'])) .'_table';
							sleep(1);
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Migration',
						'keywords' => [
							'@class_name',
							'@migration_extends',
							'@migration_implements',
							'@migration_import_trait',
							'@migration_table',
							'@migration_column',
							'@migration_foreign',
							'@migration_properties',
							'@migration_import_class',
						]
					],
					'model' => [
						'path' => Foundation::getAppDir('\app\Models'),
						'template_file' => dirname(__DIR__) . '\resources\data\model.compio',
						'generate' => true,
						// 'convert_case' => 'camel',
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							$path_info['filename'] = Str::singular($path_info['filename']);
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Model',
						'keywords' => [
							'@class_name',
							'@model_namespace',
							'@model_class',
							'@model_full_class',
							'@model_fillable',
							'@model_hidden',
							'@model_casts',
							'@model_extends',
							'@model_implements',
							'@model_import_trait',
							'@model_belongs_to',
							'@model_has_one',
							'@model_has_many',
							'@model_properties',
							'@model_methods',
							'@model_import_class',
						]
					],
					'factory' => [
						'path' => Foundation::getAppDir('\database\factories'),
						'template_file' => dirname(__DIR__) . '\resources\data\\' . (version_compare(\Illuminate\Foundation\Application::VERSION, '8', '>=') ? 'factory.v.sup.8.compio' : 'factory.compio'),
						'generate' => true,
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							$path_info['filename'] = Str::singular($path_info['filename']) . 'Factory';
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Factory',
						'keywords' => [
							'@factory_namespace',
							'@factory_class',
							'@factory_full_class',
							'@model_full_class',
							'@model_class',
							'@factory_implements',
							'@factory_import_trait',
							'@factory_definition',
							'@factory_import_class',
						]
					],
					'seeder' => [
						'path' => Foundation::getAppDir('\database\\' . (version_compare(\Illuminate\Foundation\Application::VERSION, '8', '>=') ? 'seeders' : 'seeds')),
						'template_file' => dirname(__DIR__) . '\resources\data\seeder.compio',
						'generate' => true,
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							$path_info['filename'] = Str::singular($path_info['filename']) . 'TableSeeder';
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Seeder',
						'keywords' => [
							'@seeder_namespace',
							'@seeder_class',
							'@seeder_full_class',
							'@model_full_class',
							'@model_class',
							'@seeder_extends',
							'@seeder_implements',
							'@seeder_import_trait',
							'@seeder_seed',
							'@seeder_import_class'
						]
					],
					'repository' => [
						'path' => Foundation::getAppDir('\app\Repositories'),
						'template_file' => dirname(__DIR__) . '\resources\data\repository.compio',
						'generate' => true,
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							$path_info['filename'] = Str::singular($path_info['filename']) . 'Repository';
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Repository',
						'keywords' => [
							'@repository_namespace',
							'@repository_class',
							'@repository_full_class',
							'@model_full_class',
							'@model_class',
							'@repository_extends',
							'@repository_implements',
							'@repository_import_trait',
							'@repository_methods',
							'@repository_import_class',
						]
					],
					'resource' => [
						'path' => Foundation::getAppDir('\app\Http\Resources'),
						'template_file' => dirname(__DIR__) . '\resources\data\resource.compio',
						'generate' => true,
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							$path_info['filename'] = Str::singular($path_info['filename']) . 'Resource';
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Resource',
						'keywords' => [
							'@resource_namespace',
							'@resource_class',
							'@resource_full_class',
							'@resource_extends',
							'@resource_implements',
							'@resource_import_trait',
							'@resource_collects',
							'@resource_wrap',
							'@resource_datas',
							'@resource_import_class',
						]
					],
					'request' => [
						'path' => [Foundation::getAppDir('\app\Http\Requests'), Foundation::getAppDir('\app\Http\Requests')],
						'template_file' => dirname(__DIR__) . '\resources\data\request.compio',
						'generate' => true,
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info, $file_index, $all_template_path){
							// $path_info['filename'] = Str::singular($path_info['filename']) . ($file_index === 0 ? 'Update' : ($file_index === 1 ? 'Store' : null)) . 'Request';
							$path_info['filename'] = Str::singular($path_info['filename']) . ($file_index === 0 ? 'Store' : ($file_index === 1 ? 'Update' : null)) . 'Request';
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Request',
						'keywords' => [
							'@model_full_class',
							'@model_class',
							'@model_namespace',
							'@request_namespace',
							'@request_class',
							'@request_full_class',
							'@request_extends',
							'@request_implements',
							'@request_import_trait',
							'@request_datas',
							'@request_authorize',
							'@request_import_class',
						]
					],
					'controller' => [
						'path' => [Foundation::getAppDir('\app\Http\Controllers\Api\V1'), Foundation::getAppDir('\app\Http\Controllers')],
						'template_file' => dirname(__DIR__) . '\resources\data\controller.compio',
						'generate' => true,
						'convert_case' => ['camel', 'uf'],
						'change_file' => function(array $path_info){
							$path_info['filename'] = Str::singular($path_info['filename']) . 'Controller';
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Controller',
						'keywords' => [
							'@model_full_class',
							'@model_class',
							'@repository_full_class',
							'@repository_class',
							'@resource_namespace',
							'@resource_full_class',
							'@resource_class',
							'@request_namespace',
							'@request_full_class',
							'@request_class',
							'@controller_namespace',
							'@controller_class',
							'@controller_full_class',
							'@controller_extends',
							'@controller_implements',
							'@controller_import_trait',
							'@controller_properties',
							'@controller_methods',
							'@controller_import_class'
						]
					],
					'page' => [
						'path' => ['index' => ($pt = Foundation::getAppDir('\resources\views\pages')), 'create' => $pt, 'show' => $pt, 'edit' => $pt, 'find' => $pt],

						'template_file' => [
							'index' => dirname(__DIR__) . '\resources\data\page\index.blade',
							'create' => dirname(__DIR__) . '\resources\data\page\create.blade',
							'show' => dirname(__DIR__) . '\resources\data\page\show.blade',
							'edit' => dirname(__DIR__) . '\resources\data\page\edit.compio',
							'find' => dirname(__DIR__) . '\resources\data\page\find.compio',
							dirname(__DIR__) . '\resources\data\page.compio'
						],
						'generate' => true,
						'convert_case' => 'd',
						// 'convert_case' => ['camel', 'uf'],
						'change_file' => function($path_info, $key, $component){
							$path_info['dirname'] .= '\\' . $path_info['filename'];
							$path_info['extension'] = 'blade.php';
							$path_info['filename'] = $key;
							// $path_info['filename'] = Str::singular($path_info['filename']);
							$path_info['basename'] = $path_info['filename'] . '.'. $path_info['extension'];
							$path_info['file'] = $path_info['dirname'] . '\\' . $path_info['basename'];
							$path_info['short'] = ($path_info['short_dirname'] == '' ? ('\\' . $path_info['filename']) : ($path_info['short_dirname'] . '\\' . $path_info['filename']));
							return $path_info;
						},
						'keyword_class' => '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template\Page',
						'keywords' => [
							'@all_button_title',
							'@create_button_title',
							'@route',
							'@class_html',
							'@content',
							'@additional',
							'@page_layout',
							'@page_title',
							'@locate_css',
							'@locate_js'
						]
					],
				],


				'keyword' => [
					'__eloquentSoftDeletes' => 'Illuminate\Database\Eloquent\SoftDeletes',
					'__eloquentHasOne' => 'Illuminate\Database\Eloquent\Relations\HasOne',
					'__eloquentHasMany' => 'Illuminate\Database\Eloquent\Relations\HasMany',
					'__eloquentBelongsTo' => 'Illuminate\Database\Eloquent\Relations\BelongsTo',
					'__eloquentModel' => 'Illuminate\Database\Eloquent\Model',
					'__model' => 'Illuminate\Database\Eloquent\Model',
					'__eloquentDBCollection' => 'Illuminate\Database\Eloquent\Collection',
				],
				'default_column_type' => 'string',
				'foreign_default' => [
					'foreign_property' => version_compare(\Illuminate\Foundation\Application::VERSION, '8', '>=') ? ['foreignId' => '#'] : ['unsignedBigInteger' => '#'],
					'modifiers' => [],
					// 'modifiers' => 'nullable',
				],


				'require_template' => false,
				'replace_component_exist' => false,
			] 
			: [
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
			]
		;
		$this->config()::setDefault($dt);
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

					$keyword_class = isset($datas['keyword_class']) 
						? (is_array($datas['keyword_class']) 
							? (is_string($value = end($datas['keyword_class'])) && class_exists($value) && is_subclass_of($value, '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base')
								? new $value
								: (is_object($value) && is_subclass_of($value, '\Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base')
									? $value
									: null
								)
							) 
							: (is_object($datas['keyword_class'])
								? $datas['keyword_class']
								: (is_string($datas['keyword_class'])
									? new $datas['keyword_class']
									: null
								)
							)
						)
						: null
					;

					foreach ($creation_response as $key => $response_template) {

						$generate_ = !(isset($response_template['generate']) && $response_template['generate'] === false);

						if($response_template['status_error'] !== true){

							$content_original = file_get_contents($response_template['file']);
							$content = $generate_ ? $content_original : '';

							foreach ($keywords as $key => $value) {

								$datas_call_keyword = is_array($value) ? [
									/* 0 - $default_value */
									(array_key_exists('default_value', $value) && (is_numeric($value['default_value']) || is_string($value['default_value']) || (is_array($value['default_value']) && isset($value['default_value'][$key])))
										? (is_array($value['default_value'])
											? $value['default_value'][$key]
											: $value['default_value']
										)
										: null
									),
								] : [$value];

								$datas_call_keyword = array_merge($datas_call_keyword, [
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
								]);

								preg_match('/^@(.*)/', $key, $m__);

								$method__ = end($m__);

								if(!is_null($keyword_class) && method_exists($keyword_class, $method__)){

									$keyword_class->__329732_config__9882832(...array_merge($datas_call_keyword, [$type, $this->config()->getMerge()]));
									$value = $keyword_class->$method__(...$datas_call_keyword);

								}

								else{

								$value = is_array($value) && array_key_exists('callable', $value)
									? $value['callable'](...$datas_call_keyword)
									: (is_string($value) || is_numeric($value) 
										? $value 
										: null
									)
								;
								}

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