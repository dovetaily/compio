<?php
@command
namespace App\View\Components@namespace;

use Illuminate\View\Component;

class @class_name extends Component
{

    /**
     * The class assets.
     *
     * @var array
     */
	private static $assets = [
		'css' => [@locate_css],
		'js' => [@locate_js],
	];

	// properties...
	@args_init

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct(@args_params){

		// properties...
		@args_construct

	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{

		return view('@locate_render', [
			// properties...
			@args_render
		]);

	}

	/**
	 * Get component assets
	 *
	 * @param string|null  $key
	 * @return array|string
	 */
	public static function getAssets(string|null $key = null){

		return empty($key) ? self::$assets : (array_key_exists($key, self::$assets) ? self::$assets[$key] : false);

	}
}
