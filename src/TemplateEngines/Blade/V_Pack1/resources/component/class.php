<?php
@command
namespace App\View\Components\@namespace;

use Illuminate\View\Component;

class @className extends Component
{

    /**
     * The class assets
     *
     * @var array
     */
	private static $assets = [
		'css' => [@locate_css],
		'js' => [@locate_js],
	];

	// properties...
	@moreInit
	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct(@moreParamsarray){

		// properties...
		@moreConst
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
			@moreRender
		]);
	}

	/**
	 * Get the assets of this class
	 *
	 * @return array|string
	 */
	public static function getAssets(string|null $key = null){
		return empty($key) ? self::$assets : (array_key_exists($key, self::$assets) ? self::$assets[$key] : false);
	}
}
