<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base;
use Illuminate\Support\Str;
use Illuminate\Foundation\Application;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Helpers;

class Seeder extends Base {

	use Helpers;

	/**
	 * [seeder_namespace description]
	 * 
	 * @return [type] [description]
	 */
	public function seeder_namespace(){
		return version_compare(Application::VERSION, '8', '>=') ? ("\nnamespace Database\\" . ucfirst(preg_replace('/^'.preg_quote(database_path()).'\\\\(.*)/', '$1', pathinfo($this->file_path)['dirname'])) . ";\n") : '';
		// return 'App\Repositories' . (($n = end($this->template_datas['path'])['short_dirname']) != '' ? ('\\' . $n) : null);
	}

	/**
	 * [seeder_class description]
	 * 
	 * @return [type] [description]
	 */
	public function seeder_class(){
		
		return end($this->template_datas['path'])['filename'];

	}

	/**
	 * [seeder_full_class description]
	 * 
	 * @return [type] [description]
	 */
	public function seeder_full_class(){
		
		return $this->template_datas['keywords']['@seeder_namespace']['result'] . '\\' . $this->template_datas['keywords']['@seeder_class']['result'];

	}

	/**
	 * [model_full_class description]
	 * 
	 * @return [type] [description]
	 */
	public function model_full_class(){
		
		return isset($this->all_keywords['model']['@model_full_class']) ? $this->all_keywords['model']['@model_full_class'] : '';

	}

	/**
	 * [model_class description]
	 * 
	 * @return [type] [description]
	 */
	public function model_class(){
		
		return isset($this->all_keywords['model']['@model_class']) ? $this->all_keywords['model']['@model_class'] : '';

	}

	/**
	 * [seeder_extends description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function seeder_extends(...$args){
		return $this->extend($args, 'seeder', 'Illuminate\Database\Seeder', '@seeder_import_class', '@seeder_extends');
	}

	/**
	 * [seeder_implements description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function seeder_implements(...$args){
		return $this->implement($args, 'seeder', null, '@seeder_import_class', '@seeder_implements');
	}

	/**
	 * [seeder_import_trait description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function seeder_import_trait(...$args){
		return $this->importTrait($args, 'seeder', [
			// 'Illuminate\Database\Eloquent\Factories\HasFactory',
		], '@seeder_import_class', '@seeder_import_trait');
	}

	/**
	 * [seeder_seed description]
	 * 
	 * @return [type] [description]
	 */
	public function seeder_seed(){
		// $call_col = config('compio-db.conf.helpers.colone');
		$cols = $this->colone($this->arguments['columns']);
		$version = version_compare(Application::VERSION, '8', '>=') ? true : false;
		$d = $version ? 'seeders' : 'seeds';
		$sm_path = preg_replace('/^'.preg_quote(database_path() . '\\' . $d).'\\\\(.*)$/', '$1', $this->file_path);
		$factory_file = database_path('factories\\' . preg_replace('/(.*)TableSeeder(\\.php)$/', '$1Factory$2', $sm_path));
		$model = $this->all_keywords['seeder']['@model_class'];
		$render = '';
		$conf = isset($this->arguments['seeder']['conf']) ? $this->arguments['seeder']['conf'] : [];
		if(is_file($factory_file) || isset($conf['useFactory'])) $render = $version ? ($model . '::factory()->count(' . (isset($conf['count']) && is_int($conf['count']) ? $conf['count'] : 20) . ')->create();') : "factory(" . $model . "::class, " . (isset($conf['count']) && is_int($conf['count']) ? $conf['count'] : 20) . ")->create();";
		else $render = "foreach ([\n\t\t\t[\n\t\t\t\t" . (!empty($cols['cols']) ? implode(",\n\t\t\t\t", (function($v){
				$t = [];
				foreach ($v as $value) if(!in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at'])) $t[] = '"' . $value . '" => ""';
				return $t;
			})(array_keys($cols['cols']))) : null) . "\n\t\t\t],\n\t\t] as \$key => \$value){\n\t\t\t" . $model . "::firstOrCreate(\$value);\n\t\t}";
		return $render;
	}

	/**
	 * [seeder_import_class description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function seeder_import_class(...$args){
		return $this->importClass($args, 'seeder', [
		]);
	}

}