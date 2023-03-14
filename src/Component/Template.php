<?php

namespace Compio\Component;

use Compio\Traits\ComponentCaller;
use Compio\Traits\Compliant;

class Template {

	use ComponentCaller;

	/**
	 * All templates. 
	 * 
	 * @var array
	*/
	private $templates = [];

	/**
	 * Create a new Compio\Component\Template instance.
	 *
	 * @return void
	 */
	public function __construct(){}

	/**
	 * Set template to generate.
	 *
	 * @param  array  $templates
	 * @return void
	 */
	public function templateToGenerate(array $templates){

		$datas = $this->config()->getMerge('template');

		$t = [];

		$error = [];

		foreach ($datas as $key => $value) {

			$datas[$key]['generate'] = false;

			$c = null;

			if(in_array($key, $templates) && ($c = Compliant::is_compliant($key, $value, self::getTemplateStructure())) === true){

				$datas[$key]['generate'] = true;

				$t[$key] = $datas[$key];

			}
			elseif($c !== null) $error[$key] = $c;

		}

		$this->config()->setMerge($datas, 'template');

		$this->templates = $t;

		return empty($error) ? true : $error;

	}

	/**
	 * Get template keys structure.
	 *
	 * @param  array  $templates
	 * @return void
	 */

	public static function getTemplateStructure(){

		return [
			'path' => [
				'require' => true,
				'verif' => 'is_string',
				'last' => false,
				'empty' => false
			],
			'template_file' => [
				'require' => true,
				'verif' => function($value){

					return is_string($value) && is_readable($value)
						? true
						: (!is_string($value) 
							? 'La clé `template_file` n\'est pas une chaîne de caractère !'
							: "Le fichier `$value` de votre clé `template_file` n'a pas été trouvé ou ne peut être lu en raison des autorisations de lecture du fichier !"
						)
					;

				},
				'last' => true,
				'empty' => false
			],
			'generate' => [
				'require' => false,
				'verif' => 'is_bool',
				'last' => true,
				'empty' => true
			],
			'convert_case' => [
				'require' => false,
				'verif' => function($value){

					return is_string($value) || is_callable($value)
						? true
						: 'La clé `convert_case` n\'est pas une chaîne de caractère ou une fonction de rappel !'
					;

				},
				'last' => true,
				'empty' => false
			],
			'file_extension' => [
				'require' => false,
				'verif' => 'is_string',
				'last' => true,
				'empty' => false
			],
			'short_path' => [
				'require' => false,
				'verif' => 'is_string',
				'last' => true,
				'empty' => false
			],
			'keywords' => [
				'require' => false,
				'verif' => function($value){

					return is_string($value) || is_callable($value) || is_null($value)
						? true
						: 'La(les) valeur(s) de la clé `keywords` n\'est(ne sont) pas conforme(s) !'
					;

				},
				'last' => false,
				'empty' => true
			]
		];

	}

	/**
	 * Get template.
	 *
	 * @param  string|null  $key
	 * @return mixed
	 */
	public function get($key = null) {

		return $key === null ? $this->templates : (array_key_exists($key, $this->templates) ? $this->templates[$key] : false);

	}

	/**
	 * Set template.
	 *
	 * @param  mixed        $value
	 * @param  string|null  $key
	 * @return Compio\Component\Template|bool
	 */
	public function set($value = null, $key = null) {

		if(empty($key)) $this->templates = $value;
		elseif(array_key_exists($key, $this->templates)) $this->templates[$key] = $value;
		else return false;

		return $this;

	}

}