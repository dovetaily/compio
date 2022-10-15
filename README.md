# compio
## How to install
Open a terminal connected to your Laravel project path and run this :
```sheel
$ composer require dovetaily/compio
```

After that you will have to choose between these two methods
### 1 - Added in your `./composer.json`
In the root of your project in the `script.post-autoload-dump` key, please add `Compio\\ComposerScripts::postAutoloadDump`
Example : 
```json
{
	// code ...
	"scripts": {
		// code ...
		"post-autoload-dump": [
			// code ...
			"Compio\\ComposerScripts::postAutoloadDump"
		]
		// code ...
	}
	// code ...
}
```
And then run in the terminal:
```sheel
$ composer dump-autoload
```
And it's ready to be used !
### 2 - Added in your `./app/Console/Kernel.php`
Here, you just need to add this in the `Kernel.php` file knowing that this line of code is only valid for Laravel >=5.5 :
```php
require_once getcwd() . '\vendor\compio\compio\src\Environments\Laravel\V_Pack1\routes_commands.php';
```
And it's ready to be used !

## How to use
In the root of your project where the `./artisan` file is located, run the following command in your terminal and then follow the instructions :
```sheel
$ php artisan compio:component
  Component name ? (Component | Path/Component) :
  > MyComponent
  Put your arguments :
  > string:int:arg_name=default_value
  Success | Created : "path | C:\Users\...\root_project\public\css\components\ ... .css"
  Success | Created : "path | C:\Users\...\root_project\public\js\components\ ... .js"
  Success | Created : "path | C:\Users\...\root_project\resources\views\components\ ... MyComponent.blade.php"
  Success | Created : "path | C:\Users\...\root_project\app\View\Components\ ... MyComponent.php"
  Component created successfully.
```
It will generate 4 files :
#### .\root_project\public\css\components\ ... .css
```css
..-mycomponent-class_name_generate{
	/*...*/
}
.z-1{ z-index: 1; }




/* ---- COLOR START ----  */

	@media (prefers-color-scheme: dark) {
		..-mycomponent-class_name_generate{
			/*...*/
		}
	}

/* ---- COLOR STOP ---- */




/* ---- MEDIA SCREEN START ---- */

	/* MIN WIDTH */
	/*---- sm ----*/
	@media (min-width: 640px) { /*...*/ }
	/*---- md ----*/
	@media (min-width: 768px) { /*...*/ }
	/*---- lg ----*/
	@media (min-width: 1024px) { /*...*/ }
	/*---- xl ----*/
	@media (min-width: 1280px) { /*...*/ }
	/*---- 2xl ----*/
	@media (min-width: 1536px) { /*...*/ }
	/*---- xxl ----*/
	@media (max-width: 1400px) { /*...*/ }




	/* MAX WIDTH */
	/*---- 2xl ----*/
	@media (max-width: 1535px) { /*...*/ }
	/*---- xl ----*/
	@media (max-width: 1279px) { /*...*/ }
	/*---- lg ----*/
	@media (max-width: 1023px) { /*...*/ }
	/*---- md ----*/
	@media (max-width: 767px) { /*...*/ }
	/*---- sm ----*/
	@media (max-width: 639px) { /*...*/ }
	/*------ MORE ------*/
	@media (max-width: 576px){ /*...*/ }

	@media (max-width: 539px){ /*...*/ }

	@media (max-width: 467px){ /*...*/ }

	@media (max-width: 395px){ /*...*/ }

	@media (max-width: 355px){ /*...*/ }

	@media (max-width: 300px){ /*...*/ }

	@media (max-width: 268px){ /*...*/ }

/* ---- MEDIA SCREEN STOP ---- */
```
#### .\root_project\public\js\components\ ... .js
```js
// document.querySelector('..-mycomponent-class_name_generate') ...

// (function($) {
//	// $('..-mycomponent-class_name_generate') ...
// })(jQuery);
```
#### .\root_project\resources\views\components\ ... MyComponent.blade.php
```html
<!-- COMPONENT MyComponent START -->
	<div class=".-lorem-class_name_generate">
		 <!-- Content... -->
	</div>
<!-- COMPONENT MyComponent STOP -->
```
#### .\root_project\app\View\Components\ ... MyComponent.php"
```php
<?php
// @command
namespace App\View\Components\.;

use Illuminate\View\Component;

class MyComponent extends Component
{

	private $assets = [
		'css' => [
			'css/components/./MyComponent.css'
		],
		'js' => [
			'js/components/./MyComponent.js'
		],
	];

	// properties...
	public $gg;
	
	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($gg = "d"){

		// properties...
		$this->gg = $gg;
		
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{
		return view('components.MyComponent', [
			// properties...
			'gg' => $this->gg,
			
		]);
	}

	public static function getAssets(string|null $key = null){
		return empty($key) ? $this->assets : (array_key_exists($key, $this->assets) ? $this->assets[$key] : false);
	}
}
```
And now generated your components !

## Upcoming update !
Other commands are under development such as : 
### 1 - Generate multiple component
From the data in a configuration file (ex config(path_config.compio.component)), you can generate several components
```sheel
$ php artisan compio:component
  Component name ? (Component | Path/Component) :
  > #config|path_config.compio.component
  Success | Created : "path | C:\Users\...\root_project\public\css\components\ ... .css"
  Success | Created : "path | C:\Users\...\root_project\public\js\components\ ... .js"
  Success | Created : "path | C:\Users\...\root_project\resources\views\components\ ... ComponentName_1.blade.php"
  Success | Created : "path | C:\Users\...\root_project\app\View\Components\ ... ComponentName_1.php"
  ...
  Component created successfully.
  ...
  Success | Created : "path | C:\Users\...\root_project\app\View\Components\ ... ComponentName_2.php"
  ...

```
### 2 - Generate multiple component
If a component exists, you can choose wich files will be updated
```sheel
$ php artisan compio:component
  Component name ? (Component | Path/Component) :
  > Path/ComponentName_AlreadyExist
  Component "Path/ComponentName_AlreadyExist" already exists.
  Do you want to continue and re-generate component ?
  > class, js
  Put your arguments :
  > string:int:arg_name=default_value
  Warning | Modified : "path | C:\Users\...\root_project\public\js\components\ ... .js"
  Warning | Modified : "path | C:\Users\...\root_project\app\View\Components\ ... ComponentName.php"
  Component created successfully.
$
$
$ php artisan compio:component
  Component name ? (Component | Path/Component) :
  > ComponentName
  Component "ComponentName" already exists.
  Do you want to continue and re-generate component ?
  > render, css
  Put your arguments :
  >
  Warning | Modified : "path | C:\Users\...\root_project\public\css\components\ ... .css"
  Warning | Modified : "path | C:\Users\...\root_project\resources\views\components\ ... ComponentName.blade.php"
  Component created successfully.
```
Upcoming !