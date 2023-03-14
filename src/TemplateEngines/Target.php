<?php

namespace Compio\TemplateEngines;

use Compio\Traits\Singleton;

final class Target {

	use Singleton;
	
	/**
	 * Template engines works.
	 *
	 * @var array
	 */
	private $enginesWors = [];

	/**
	 * Create a new Compio\TemplateEngines\Target instance.
	 *
	 * @return void
	 */
	public function __construct(){ /*...*/ }

	/**
	 * Get all Template Engines works.
	 *
     * @param  array|null  $exception
     * @param  bool        $new_
	 * @return array
	 */
	public function getEnginesWorks($exception = null, bool $new_ = true) : array{

		if(!$new_ && !empty($this->enginesWors)) return $this->enginesWors;

		$exception = array_merge(['.', '..', "Target.php"], (empty($exception) ? ["Basic"/*, 'Blade'*/] : $exception));

		$this->enginesWors = [];

		foreach (scandir(__DIR__) as $value) {

			if(!in_array($value, $exception)){

				$c = '\Compio\TemplateEngines\\' . ucfirst($value) . '\\' . ucfirst($value) . 'Inking';

				if (class_exists($c) && ($version = $c::version_supported()) !== false) {
					$this->enginesWors[strtolower($value)] = [
						'selected' => false,
						'datas' => [
							'class' => $c,
							'version' => [
								'v' => $version['version'],
								'space' => '\Compio\TemplateEngines\\' . ucfirst($value) . '\\' . $version['space']
							],
							'foundation' => '\Compio\TemplateEngines\\' . ucfirst($value) . '\\' . $version['space'] . '\\' . ucfirst($value) . 'Foundation',
							'version_path' => __DIR__ . '\\' . $value . '\\' . $version['space'],
							'version_resources_path' => __DIR__ . '\\' . $value . '\\' . $version['space'] . '\resources'
						]
					];
				}

			}

		}

		return $this->enginesWors;
		
	}

}