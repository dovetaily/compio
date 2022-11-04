# Compio

- [La version française](https://gist.github.com/gedeontimothy/284ed34902d8e0ba8014b4b5075f23eb)

## Descrption
Compio is an advanced component generator. It works temporarily only on Laravel with Artisan (CLI) by generating Blade components, but you can customize it to generate components from other template engines like `Twig` or `VueJs` or others.



## How to install
After creating your laravel project you have <b>"Two methods"</b> to install <b>Compio</b> The two methods are:
<ul>
	<li>First method : Add a code in the <code class="notranslate">`./composer.json`</code> file.</li>
	<li>Second method : Add a code in the <code class="notranslate">`./app/Console/Kernel.php`</code> file.</li>
</ul>

### First method
In your file ./composer.jsonyou need to paste this :
```json
"Compio\\ComposerScripts::postAutoloadDump"
```
Copy it and paste it into your file `./composer.json` in the key(or property) `scripts.post-autoload-dump`.
You need to paste it like this:
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
Finally, run the following command in your project :
```console
$ composer dump-autoload
  ...
  ...	
  |~ Compio for Laravel>=5.5 is load !

          ~ The `C:\Path\...\my_project\app\Console\Kernel.php` file has ben modified
            to integrate Compio Laravel command !

          ~ The configuration file `C:\Path\...\my_project\config\compio.php` has been created !
  ...
```
If the script `Compio\\ComposerScripts::postAutoloadDump` was successful, you will see in your file `./app/Console/Kernel.php` a line of code that looks like this :
```php
require_once getcwd() . '\vendor\dovetaily\compio\src\Environments\Laravel\...';
```
If you don't have this line of code in your file `./app/Console/Kernel.php`, make sure the method `commands` exists and there is this line of code `require base_path('routes/console.php');` and then re-run the command `composer dump-autoload` in the console.

If all in all your file still hasn't added this `require_once getcwd() . '\vendor\dovetaily\compio\src\Environments\Laravel\...';`, then try the second method.

If all goes well, a configuration file <b>Compio</b> with the minimum possible configuration will be created. Here is the content of this configuration file :
```php
return [

	/*
	|--------------------------------------------------------------------------
	| The configuration of the Compio(`dovetaily/compio`) Lib.
	|--------------------------------------------------------------------------
	|
	| Customize the way Compio(`dovetaily/compio`) generates your components
	| by adding your own templates or by modifying the way default templates
	| are generated. And you have the possibility to list your components to
	| generate in any configuration file, Compio(`dovetaily/compio`) will
	| generate all your components according to your global and current
	| configuration related to the components.
	|
	*/
	'component' => [
		'config' => [
			'require_template' => false,
			'replace_component_exist' => null
		],
		'components' => [
		]
	]
];
```
Everything concerning the customization of component generations will be done in this configuration file and will be explained later.
Finish for the <b>First method</b> !

### Second method
In this second method, you will paste the code corresponding to your Laravel version into your file `./app/Console/Kernel.php` in the `commands`.
```php
# For larvel >=5.5
require_once getcwd() . '\vendor\dovetaily\compio\src\Environments\Laravel\V_sup_eq_5_5\resources\routes.commands.php';
```
Your `./app/Console/Kernel.php` file will look like this :
```php
# code ...
class Kernel extends ConsoleKernel
{
	# code ...
		protected function commands()
		{
			# code ...

			# For larvel >=5.5
				require_once getcwd() . '\vendor\dovetaily\compio\src\Environments\Laravel\V_sup_eq_5_5\resources\routes.commands.php';

			# code ...
		}

}
```
Finish for the <b>Second Method</b> !

### Finishing the installation
If you are trying one or both methods, run the command `php artisan list` as follows to confirm that <b>Compio</b> is working:
```console
$ php artisan list
Laravel Framework ...

Usage:
  ...

Options:
  ...

Available commands:
 ...
 ...
 compio
  compio:component       Advanced component generator Compio(`dovetail/compio`)
 ...
 ...

```
If you have tried both methods and you do not have this result, then please check your version <b>Laravel</b> if it conforms to the versions supported by <b>Compio</b>.



## How to use
<b>Compio</b> is very easy to use.
When Compio generates a component it creates by default 4 file templates including :
<ul>
	<li>A file for the php class in the folder <code class="notranslate">./app/View/Components/</code></li>
	<li>A file for rendering (Blade by default) in the folder <code class="notranslate">./resources/views/components/</code></li>
	<li>A js file in the folder <code class="notranslate">./public/js/components/</code></li>
	<li>A css file in the folder <code class="notranslate">./public/css/components/</code></li>
</ul>
However, you can customize the folder paths or select the generated template or more, create your own generated templates.

There are <i>two ways</i> to use <b>Compio</b>.
<ul>
	<li>1 - Direct use with the console.</li>
	<li>2 - Use with a configuration file.</li>
</ul>

### 1 - Direct use with the console
Let's see the <b>Compio</b> command in depth.
```console
compio
	compio:component       Advanced component generator Compio(`dovetail/compio`)
...
...
$ php artisan compio:component -h
Description:
  Advanced component generator Compio(`dovetail/compio`)

Usage:
  compio:component [options] [--] [<component_name> [<args>...]]

Arguments:
  component_name           Component Name (^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$ or use config ^\#([a-z]+)\|([^|]+)
  args                     Your arguments for the class (ex. my_arg=default_value my_second_arg my_xx_arg="Hello world")

Options:
  -r, --replace[=REPLACE]  Replace the component if it exists ('true' for replace, ignore with 'false')
  -h, --help               Display help for the given command. When no command is given display help for the list command
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi|--no-ansi     Force (or disable --no-ansi) ANSI output
  -n, --no-interaction     Do not ask any interactive question
      --env[=ENV]          The environment the command should run under
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
The command usage looks like this :
```console
$ php artisan compio:component [<component_name> [<args>...]]
```
<ul>
	<li><code class="notranslate">&lt;name&gt;</code> : The name of your component, according to the regular expression <code class="notranslate">/^[a-z_]+[a-z0-9\/_]+$|^[a-z_]$/i</code>(or again <code class="notranslate">/^\#([a-z]+)\|([^|]+)$/i</code> but this expression will be seen in the use with a configuration file).
	</li>
	<li><code class="notranslate">&lt;arguments&gt;</code> : The arguments of your class and this entry is not mandatory, you can leave it out.</li>
</ul>

<b>Let's illustrate this with the example above :</b>
```console
$ php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3

		  "Blade>=5.5" template engine is selected to generate component !



		Success | Created : "C:\Path\...\my_project\app\View\Components\Path\MyComponent.php"


		Success | Created : "C:\Path\...\my_project\resources\views\components\Path\MyComponent.blade.php"


		Success | Created : "C:\Path\...\my_project\public\css\components\Path\MyComponent.css"


		Success | Created : "C:\Path\...\my_project\public\js\components\Path\MyComponent.js"


		Component (Path/MyComponent) created successfully !
```

The command `php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3` receives all component information on a single line, but you can also run it as follows :
```console
$ php artisan compio:component

          "Blade>=5.5" template engine is selected to generate component !

 Component name ? (Component | Path/Component):
 > Path/MyComponent

 Put your arguments :
 > string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2=null my_argument_require_3
 	...
 	...
        Component (Path/MyComponent) created successfully !
```

<b>Let's see the generated files</b>
#### File `my_project\app\View\Components\Path\MyComponent.php`
```php
// php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3
namespace App\View\Components\Path;

use Illuminate\View\Component;

class MyComponent extends Component
{

    /**
     * The class assets.
     *
     * @var array
     */
	private static $assets = [
		'css' => [
			'css\components\Path\MyComponent.css'
		],
		'js' => [
			'js\components\Path\MyComponent.js'
		],
	];

	// properties...
	public $my_argument_require_3;
	public $my_string_type_argument;
	public $my_array_or_null_type_argument_2;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($my_argument_require_3, string $my_string_type_argument = "Default value", array|null $my_array_or_null_type_argument_2 = null){

		// properties...
		$this->my_argument_require_3 = $my_argument_require_3;
		$this->my_string_type_argument = $my_string_type_argument;
		$this->my_array_or_null_type_argument_2 = $my_array_or_null_type_argument_2;

	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|\Closure|string
	 */
	public function render()
	{

		return view('components.Path.MyComponent', [
			// properties...
			'my_argument_require_3' => $this->my_argument_require_3,
			'my_string_type_argument' => $this->my_string_type_argument,
			'my_array_or_null_type_argument_2' => $this->my_array_or_null_type_argument_2,
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

```
On the following line :
```php
# code ...
	public function __construct($my_argument_require_3, string $my_string_type_argument = "Default value", array|null $my_array_or_null_type_argument_2 = null){
# code ...
```
Let's analyze the generated arguments :
<ul>
	<li><code class="notranslate">string:my_string_type_argument="Default value"</code> : Typed argument with a default value.</li>
	<li><code class="notranslate">array:null:my_array_or_null_type_argument_2=</code> : Typed argument with default value <code class="notranslate">null</code>.<br>If you enter your arguments in the input <code class="notranslate">Put your arguments :</code>, you must enter the value <code class="notranslate">null</code>(like this <code class="notranslate">array:null:my_array_or_null_type_argument_2=null</code>)</li>
	<li><code class="notranslate">my_argument_require_3</code> :  Mandatory untyped argument.</li>
</ul>

#### File `my_project\resources\views\components\Path\MyComponent.blade.php`
```blade
<!-- COMPONENT PathMycomponent START -->
	<div class="path-mycomponent-f0ul3mey">
		<!-- Content... -->
	</div>
<!-- COMPONENT PathMycomponent STOP -->
```

#### File `my_project\public\css\components\Path\MyComponent.css`
```css
.path-mycomponent-f0ul3mey{
	/*...*/
}
.z-1{ z-index: 1; }




/* ---- COLOR START ----  */

	@media (prefers-color-scheme: dark) {
		.path-mycomponent-f0ul3mey{
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

#### File `my_project\public\js\components\Path\MyComponent.js`
```js
// document.querySelector('.path-mycomponent-f0ul3mey') ...

// (function($) {
//	// $('.path-mycomponent-f0ul3mey') ...
// })(jQuery);
```

### 2 - Use with a configuration file
In this section, we will use data from a configuration file.
During the installation of Compio when all goes well, it generates a configuration file `./my_project/config/compio.php` if it is not created please create it, and to configure what affects the generations of the components, add the key `component` and inside you add the key there `config` and for a minimum rendering of the configuration, it will give this :
```php
return [
	// ...
	'component' => [
		'config' => [
			// ...
		]
	]
];
```
#### A. Utiliser simplement le fichier de configuration pour générer plusieurs composants
So we have `component.config`, and before seeing the possible compio configurations in point <b>'B'</b> , we will first see how to generate several components at the same time. Please add the key where the list of your generated components will be stored, it can be named as you wish but cannot contain the symbol `|`. So for an example we will add the key `datas` and the structure will become `component.datas`. And we're going to put our components there.
```php
return [
	// ...
	'component' => [
		'config' => [
			// ...
		],
		'datas' => [
			['name' => 'Path/MyComponent',
				'args' => [
					'string:my_string_type_argument' => "Default value", //
					'array:null:my_array_or_null_type_argument_2' => \Compio\Component\Arguments::NULL_VALUE, // set `null` value
					'my_argument_require_3' => null // Mandatory argument
				]
			],
			['name' => 'Cards/Post/User',
				'args' => [
					'string:title' => null, // Mandatory argument
					'string:null:description' => \Compio\Component\Arguments::NULL_VALUE, // set `null` value
					'array:string:image' => ['http://my.cdn.com/img/21128376.jpg', 'http://my_cdn.com/img/44837110.jpg'], // set `array` value
					'int:null:like' => \Compio\Component\Arguments::NULL_VALUE,  // set `null` value
					'\Illuminate\Contracts\View\View:\Closure:string:null:more' => \Compio\Component\Arguments::NULL_VALUE  // set `null` value
				]
			],
			['name' => 'Shape/Ball']
		]
	]
];
```
Now that the configuration file has components to generate, we'll run the following command `php artisan compio:component "#config|[config_keys]"`. The entry `[config_keys]` will be the configuration path to the components key like when we use `config([config_keys])` Laravel's helper function :
```console
$ php artisan compio:component "#config|compio.component.datas"

          "Blade>=5.5" template engine is selected to generate component !

          This config `compio.component.components` is matched !

        0 : "Path/MyComponent" component is loaded !
        1 : "Cards/Post/User" component is loaded !
        2 : "Shape/Ball" component is loaded !


        Success | Created : "C:\Path\...\my_project\app\View\Components\Path\MyComponent.php"
        Success | Created : "C:\Path\...\my_project\resources\views\components\Path\MyComponent.blade.php"
        Success | Created : "C:\Path\...\my_project\public\css\components\Path\MyComponent.css"
        Success | Created : "C:\Path\...\my_project\public\js\components\Path\MyComponent.js"
        Component (Path/MyComponent) created successfully !

        Success | Created : "C:\Path\...\my_project\app\View\Components\Cards\Post\User.php"
        ...
        ...
        Success | Created : "C:\Path\...\my_project\public\js\components\Cards\Post\User.js"
        Component (Cards/Post/User) created successfully !

        Success | Created : "C:\Path\...\my_project\app\View\Components\Shape\Ball.php"
        ...
        ...
        Success | Created : "C:\Path\...\my_project\public\js\components\Shape\Ball.js"
        Component (Shape/Ball) created successfully !
```
Namely, we will see the parameters (arguments) of the `__construct` component method `Cards/Post/User` to understand the default values of the arguments :
<b>File `C:\Path\...\my_project\app\View\Components\Cards\Post\User.php`</b>
```php
// ...
namespace App\View\Components\Cards\Post;
class User extends Component
	// code ...
	public function __construct(
		string $title, // '...title' => null, // Mandatory argument
		string|null $description = null, // '...description' => \Compio\Component\Arguments::NULL_VALUE, // default value `null`
		int|null $like = null, // '...like' => \Compio\Component\Arguments::NULL_VALUE, // default value `null`
		array|string $image = array (
			0 => 'http://my.cdn.com/img/21128376.jpg',
			1 => 'http://my_cdn.com/img/44837110.jpg',
		),
		\Illuminate\Contracts\View\View|\Closure|string|null $more = null
	)
	// code ...
```
#### B. Possible compio configurations
Here are the main keys of compio for a good personal management of the library :
```php
return [
	// ...
	'component' => [
		'config' => [
			'template' => [
				'class' => [
					// code ...
				],
				'render' => [
					// code ...
				],
				'css' => [
					// code ...
				],
				'js' => [
					// code ...
				]
			],
			'require_template' => false,
			'replace_component_exist' => null,
		]
	]
]
```
As you may have noticed, when you generate a component, there are four files that are created and these files correspond to the number of templates that there are in `component.config.template`(`class`, `render`, `css` and `js`).
Before seeing how to <b>Add, Stop or Modify Templates</b> , we will first see the keys `component.config.require_template` and `component.config.replace_component_exist`

##### The key  `component.config.require_template`
When you run a command to generate a component, it does not ask you which generated template, it automatically generates 4 template files predefined by <b>Compio</b>.

The key `component.config.require_template` only accepts values of type : `bool`

- If the key `component.config.require_template` is `false` (default key value) : you have no way to choose which generated template.
- If the key `component.config.require_template` is `true` : you can choose which generated template.

We will see an example when the key `component.config.require_template` is `true`.
```php
'require_template' => true,
```
And in the console :
```console
$ php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3

          "Blade>=5.5" template engine is selected to generate component !

 Only change templates :

        [0] ALL
        [1] class
        [2] render
        [3] css
        [4] js

 Choose one or more templates (ex. 2, 4) [0]:
 > 1, 2

          These template(s) will be generate : class, render

        Success | Created : "C:\Users\...\my_project\app\View\Components\Path\MyComponent.php"
        Success | Created : "C:\Users\...\my_project\resources\views\components\Path\MyComponent.blade.php"
        Component (Path/MyComponent) created successfully !
```
And that's when for the key `'require_template' => true`. When it is `false`, the generation process will be done as in the examples above.
##### The key  `component.config.replace_component_exist`
This key gives us a way to <b>Replace</b>, <b>Ignore</b> or <b>Choose</b>, a component that already exists.

The key `component.config.replace_component_exist` only accepts values of type : `bool|null`

- If the key `component.config.replace_component_exist` is `null` (default key value) : during the process it will ask you to <b>Choose</b> if you want to regenerate the component (it will only ask you if it finds the template of `class`) and you will have to choose which template to regenerate.
- If the key `component.config.replace_component_exist` is `true`: whenever a component already exists, it will automatically <b>Replace</b>.
- If the key `component.config.replace_component_exist` is `false`: If a component already exists, it will automatically <b>Ignore</b>(this component will not be regenerated).

Example if `component.config.replace_component_exist` is `null`
```console
$ php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3
 ...
 Component "Path/MyComponent" already exists.

 Do you want to continue and regenerate component ? (yes/no) [yes]:
 > yes

 Only change templates :

        [0] ALL
        [1] class
        [2] render
        [3] css
        [4] js

 Choose one or more templates (ex. 2, 4) [0]:
 > 0

          These template(s) will be regenerate : class, render, css, js

        Warning | Modified : "C:\...\my_project\app\View\Components\Path\MyComponent.php"
        Warning | Modified : "C:\...\my_project\resources\views\components\Path\MyComponent.blade.php"
        Warning | Modified : "C:\...\my_project\public\css\components\Path\MyComponent.css"
        Warning | Modified : "C:\...\my_project\public\js\components\Path\MyComponent.js"
        Component (Path/MyComponent) created successfully !
```
Example if `component.config.replace_component_exist` is `true`
```console
$ php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3

          "Blade>=5.5" template engine is selected to generate component !

        Warning | Modified : "C:\Users\...\my_project\app\View\Components\Path\MyComponent.php"
        ...
        ...
        Warning | Modified : "C:\Users\...\my_project\public\js\components\Path\MyComponent.js"
        Component (Path/MyComponent) created successfully !
```
Example if `component.config.replace_component_exist` is `false`
```console
$ php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3

          "Blade>=5.5" template engine is selected to generate component !

         "Path/MyComponent" Component already exists !
```
You can also configure it directly on the command line with the option `--replace`.
```console
$ php artisan compio:component Path/MyComponent --replace=true
```
##### The key to templates (`component.config.template`)
This key has default templates including :
<ul>
	<li>`class`</li>
	<li>`render`</li>
	<li>`css`</li>
	<li>`js`</li>
</ul>

###### The template `class`
The <b>Compio</b> configuration values for this template are :
```php
return [
	// code ...
			'template' => [
				'class' => [
					'path' => getcwd() . '\app\View\Components',
					'template_file' => dirname(__DIR__) . '\resources\component\class.php',
					'generate' => true,
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
				]
			]
	// code ...
]
```

- The key `path` (type `string|array`) : this is the folder where the generated files will be stored.
- The key `template_file` (type `string`) : this is the template file to generate components (it can be a .txt, .php,... or other file). And the function `dirname(__DIR__)` is in the <b>Compio</b> class.
- The key `generate` (type `bool`) : if it is `false`, the template will not be generated and if it is `true` it will be the opposite.
- The key `keywords` (type `array`): these are the keywords found in the template file (you will see their usefulness in the template file).

Here's how the default template file(`template_file`) looks like :
```php
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
```
We now clearly observe the keywords `@command`, `@namespace`, `@class_name`, `@locate_css`, `@locate_js`, `@locate_render`, `@args_init`, `@args_params`, `@args_construct` and `@args_render`

- The keyword `@command` : the command to generate the same component.
- The keyword `@namespace` : the namesapce.
- The keyword `@class_name` : the class name.
- The keyword `@locate_css` : the path of the css file.
- The keyword `@locate_js` : the path of the js file.
- The keyword `@locate_render` : the path to the render file.
- The keyword `@args_init` : the argument initialization zone as a property of the class.
- The keyword `@args_params` : structuring the arguments in the method `__construct`.
- The keyword `@args_construct` : assignment of arguments in class properties.
- The keyword `@args_render` : data to pass to rendering.

You can customize the keywords to return what you need.
Example :
```php
// file : '.\my_project\config\compio.php'
// ______________________________________
return [
	// code ...
			'template' => [
				'class' => [
					// code ...
					'keywords' => [
						'@command' => '--- My customize content ---' 
					]
				]
				// code ...
			]
	// code ...
]


// file : '.\my_project\app\View\Components\Path\MyComponent.php'
// ______________________________________________________________
<?php
--- My customize content ---
namespace App\View\Components\Path;

use Illuminate\View\Component;

class MyComponent extends Component
// code ...
```
Anything you put as text in a keyword will be replaced in the same keyword in the template file, but the keyword can contain a callback function, like this :
```php
'@command' => function(array $template_datas, array|null $arguments){
	return '--- My customize content ---';
} 
```
Namely, here is the argument structure of the callback function :
```php
// If you run the command :
	// php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3
$template_datas = [
	'path' => [  // chemin des fichiers généré
		0 => [
			"dirname" => "C:\Users\...\my_project\app\View\Components\Path",
			"basename" => "MyComponent.php",
			"extension" => "php",
			"filename" => "MyComponent",
			"file" => "C:\Users\...\my_project\app\View\Components\Path\MyComponent.php"
		]
	],
	"template_file" => "C:\Users\...\my_project\vendor\dovetaily\compio\...", // Template file
	"generate" => true,
	"keywords" => [ // Keyword replaced in template file
		"@command" => function(array $template_datas, array|null $arguments){/*...*/},
		"@namespace" => "\Path",
		"@class_name" => "MyComponent",
		"@locate_css" => "\n\t\t\t'css\components\Path\MyComponent.css'\n\t\t",
		"@locate_css" => "\n\t\t\t'js\components\Path\MyComponent.js'\n\t\t",
		"@locate_render" => "components.Path.MyComponent",
		"@args_init" => "public $my_string_type_argument;\n\tpublic $my_array_or_null_type_argument_2;\n\tpublic $my_argument_require_3;",
		"@args_params" => "$my_argument_require_3, string $my_string_type_argument = \"Default value\", array|null $my_array_or_null_type_argument_2 = null",
		"@args_construct" => "$this->my_string_type_argument = $my_string_type_argument;\n\t\t$this->my_array_or_null_type_argument_2 = $my_array_or_null_type_argument_2;\n\t\t$this->my_argument_require_3 = $my_argument_require_3;",
		"@args_render" => "'my_string_type_argument' => $this->my_string_type_argument,\n\t\t\t'my_array_or_null_type_argument_2' => $this->my_array_or_null_type_argument_2,\n\t\t\t'my_argument_require_3' => $this->my_argument_require_3,"
	]
]
$arguments = [
	"string:my_string_type_argument" => "Default value",
	"array:null:my_array_or_null_type_argument_2" => "!##Be_null||", // \Compio\Component\Arguments::NULL_VALUE
	"my_argument_require_3" => null
]
```
###### Le modèle `render`
The Compio configuration values for this template are :
```php
return [
	// code ...
			'template' => [
				// code ...
				'render' => [
					'path' => getcwd() . '\resources\views\components',
					'file_extension' => 'blade.php',
					'short_path' => 'components',
					'template_file' => dirname(__DIR__) . '\resources\component\render.php',
					'generate' => true,
					'keywords' => [
						'@component_name',
						'@class_html',
					]
				]
			]
	// code ...
]
```

- The key `path` (type `string|array`) : refer to the template `class`.
- The key `file_extension` (type `string`) : define the extension of your file (it can also be defined in the template `class`).
- The key `short_path` (type `string`) : it serves as a shortcut path for the keyword `@locate_render` in the template `class` (not used in the template `class`).
- The key `template_file` (type `string`) : refer to the template `class`.
- The key `generate` (type `bool`) : refer to the template `class`.
- The key `keywords` (type `array`): refer to the template `class`.

Here's how the default template file(`template_file`) looks like:
```html
<!-- COMPONENT @component_name START -->
	<div class="@class_html">
		<!-- Content... -->
	</div>
<!-- COMPONENT @component_name STOP -->
```
In this template file, there are two new keywords including `@component_name` and `@class_html`.
- The keyword `@component_name` : the name of the component merged with its path.
- The keyword `@class_html` : a random HTML class merged with the component name.

Let's see what these keywords contain :
```php
// If you run the command :
	// php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3
$template_datas = [
	'path' => [  // chemin des fichiers généré
		// code ...
	],
	// code ...
	"keywords" => [ // Mot clé a remplacé dans le fichier du modèle
		"@namespace" => "PathMycomponent",
		"@class_name" => "path-mycomponent-72h4pk05",
	]
]
$arguments = [
	// code ...
]
```
###### Le modèle `css`
The Compio configuration values for this template are :
```php
return [
	// code ...
			'template' => [
				// code ...
				'css' => [
					'path' => getcwd() . '\public\css\components',
					'file_extension' => 'css',
					'short_path' => 'css\components',
					'template_file' => dirname(__DIR__) . '\resources\component\css.php',
					'generate' => true,
					'keywords' => [
						'@class_html',
					]
				]
			]
	// code ...
]
```

- The key `path` (type `string|array`) : refer to the template `class`.
- The key `file_extension` (type `string`) : refer to the template `render`.
- The key `short_path` (type `string`) : it serves as a shortcut path for the keyword `@locate_css` in the template `class`.
- The key `template_file` (type `string`) : refer to the template `class`.
- The key `generate` (type `bool`) : refer to the template `class`.
- The key `keywords` (type `array`): refer to the template `class`.

Here's how the default template file(`template_file`) looks like :
```css
.@class_html{
	/*...*/
}
.z-1{ z-index: 1; }




/* ---- COLOR START ----  */

	@media (prefers-color-scheme: dark) {
		.@class_html{
			/*...*/
		}
	}

/* ---- COLOR STOP ---- */




/* ---- MEDIA SCREEN START ---- */
	...
	...
	...
/* ---- MEDIA SCREEN STOP ---- */
```
###### Le modèle `js`
The <b>Compio</b> configuration values for this template are:
```php
return [
	// code ...
			'template' => [
				// code ...
				'js' => [
					'path' => getcwd() . '\public\js\components',
					'file_extension' => 'js',
					'short_path' => 'js\components',
					'template_file' => dirname(__DIR__) . '\resources\component\js.php',
					'generate' => true,
					'keywords' => [
						'@class_html',
					]
				]
			]
	// code ...
]
```

- The key `path` (type `string|array`) : refer to the template `class`.
- The key `file_extension` (type `string`) : refer to the template `render`.
- The key `short_path` (type `string`) : it serves as a shortcut path for the keyword `@locate_js` in the template `class`.
- The key `template_file` (type `string`) : refer to the template `class`.
- The key `generate` (type `bool`) : refer to the template `class`.
- The key `keywords` (type `array`): refer to the template `class`.

Here's how the default template file(`template_file`) looks like :
```js
// document.querySelector('.@class_html') ...

// (function($) {
//	// $('.@class_html') ...
// })(jQuery);
```
###### Create your own templates
The creation of the templates is very simple, let's start by making a new configuration.
To add your own template, you must place it in the key `component.config.template.my_template`
```php
return [
	'component' => [
		'config' => [
			'template' => [
				'my_template' => [
					// required key
						'path' => string|array, // the storage location of the generated component
						'template_file' => string, // the template file
						'generate' => bool, // decide if the template should be generated
					// not required
						'keywords' => array, // the keywords to replace in template 
						'short_path' => string, // path shortcuts to template file or other
						'file_extension' => string, // the file extension of the generated template (`.php` by default)
				]
			]
		]
	]
]
```
By following this structure you have the possibility to create your own templates.
If you create a template from the name of the templates that already exist (eg `class`, `render`, `css` and `js`), you will have the default values for this template.
The templates `class`, `render`, `css` and `js` are automatically generated by <b>Compio</b>, to prevent this you will need to set their key `generate` to the value `false`, like this:
```php
return [
	'component' => [
		'config' => [
			'template' => [
				'class' => ['generate' => false],
				'render' => ['generate' => false],
				'css' => ['generate' => false],
				'js' => ['generate' => false],
			]
			// code ...
		]
		// code ...
	]
]
```

###### The `keywords` in depth
You should know that you can also create your own keywords `component.config.template.my_template.keywords.my_own_keyword`
Default `keywords` already has values including :
```php
// code ...
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
	'@component_name',
	'@class_html',
]
// code ...
```
And when you create your keyword, it will be merged with the default value of `keywords`.

You can also get your arguments as a keyword, as follows `@args[argument key][string template]`
In the template text you can write whatever you want and combine it with the argument information :
```php
// If you run the command :
	// php artisan compio:component Path/MyComponent string:my_string_type_argument="Default value" array:null:my_array_or_null_type_argument_2= my_argument_require_3 

	// [argument key]
	// [0] string:my_string_type_argument="Default value"
	// [1] array:null:my_array_or_null_type_argument_2=
	// [2] my_argument_require_3 

'keywords' => [
	'@args[0][My first argument is "{--name--}"]', // Result : My first argument is "my_string_type_argument"
	'@args[1][My argument {--name--} is of type {--type--}]', // Result : My argument my_array_or_null_type_argument_2 is of type array|null
	'@args[2][The value of argument {--name--} is "{--value--}"]', // Result : The value of argument my_argument_require_3 is ""
	'@args[*][{--all--}]', // Result : string $my_string_type_argument = "Default value"; array|null $my_array_or_null_type_argument_2 = null; $my_argument_require_3;
	'@args[0][{--type-name--}]', // Result : string $my_string_type_argument
	'@args[1][{--name-value--}]', // Result : my_array_or_null_type_argument_2 = "Default value"
	'@args[1][{--all--}]', // Result : array|null $my_array_or_null_type_argument_2 = null
	'@args[*][{--name--}]', // Result : my_string_type_argument; my_array_or_null_type_argument_2; my_argument_require_3;
	'@args[*][name -> {--name--} type -> {--type--}]', // Result : name -> my_string_type_argument -> string; name -> my_array_or_null_type_argument_2 -> array|null; name -> my_argument_require_3 -> ;
]
```

Now let's see how to create a keyword.
The instructions are simple to have a keyword you must respect :
	1 - The regular expression `/^@([a-z_]+.*|[a-z_]+|[a-z_])$/i`
	2 - The value of your default value must be of type `string|closure`. The `closure` must return a value of type `string|null`.
Example :
```php
// code ...
	'component' => [
		'config' => [
			'template' => [
				'my_template' => [
					// code ...
					'keywords' => [ // the keywords to replace in the template
						'@command' => 'My own value', // Changing the default value of this keyword
						'@my_own_keyword', // defaut value `null`
						'@my_own_keyword_2' => function(array $template_datas, array|null $arguments){
							$result = null;
							// code ...
								// ... your runtime code
							return $result;
						}, // 
						'@my_own_keyword_3' => 'My value',
					],
					// code ...
				]
			]
		]
	]
// code ...
```
##### Apply different configurations in multiple components
By using the configuration file to generate several components, you will be able to apply different configurations for each of them.
```php
return [
	// ...
	'component' => [
		'config' => [
			// ...
		],
		'datas' => [
			['name' => 'Path/MyComponent',
				'args' => [ /*...*/ ],
				'config' => [
					'template' =>
					[
						'class' => [
							// required key
								'path' => string|array, // the storage location of the generated component
								'template_file' => string, // the template file
								'generate' => bool, // decide if the template should be generated
							// not required
								'keywords' => array, // the keywords to replace in template 
								'short_path' => string, // path shortcuts to template file or other
								'file_extension' => string, // the file extension of the generated template (`.php` by default)
						]
					],
					'require_template' => true,
					'replace_component_exist' => false,
				]
			],
			['name' => 'Cards/Post/User',
				'args' => [ /*...*/ ],
				'config' => [
					// component config
				]
			],
			['name' => 'Shape/Ball']
		]
	]
];
```