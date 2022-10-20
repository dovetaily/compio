<?php

namespace Compio\TemplateEngines;
use Compio\Traits\Singleton;


final class Target{

	use Singleton;

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
	 * @return array
	 */
	public function getEnginesWorks(array|null $exception = null) : array{

		$exception = array_merge(['.', '..', "Target.php"], (empty($exception) ? ["Basic"/*, 'Blade'*/] : $exception));
		$this->enginesWors = [];

		foreach (scandir(__DIR__) as $value) {
			if(!in_array($value, $exception)){
				$c = '\Compio\TemplateEngines\\' . ucfirst($value) . '\Listen';
				if (class_exists($c) && ($pack = $c::version_supported()) !== false) {
					$this->enginesWors[strtolower($value)] = [
						'class' => $c,
						'pack' => '\Compio\TemplateEngines\\' . ucfirst($value) . '\\' . $pack,
						'pack_path' => __DIR__ . '\\' . $value . '\\' . $pack,
						'pack_resources_path' => __DIR__ . '\\' . $value . '\\' . $pack . '\resources'
					];
				}
			}
		}

		return $this->enginesWors;
		
	}

}