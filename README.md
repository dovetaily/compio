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
