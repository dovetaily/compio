<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5;
use Compio\Compio;

abstract class Foundation {

	/**
	 * Command path
	 *
	 * @var string
	 */
	private static $command_path = __DIR__ . '\Commands';

	/**
	 * Class initialization
	 *
	 * @return void
	 */
	public static function init(){

		self::init_command();

		self::init_config();

	}

	/**
	 * Get any path
	 * 
	 * @param  int          $back
	 * @param  string|null  $path
	 * @return string
	 */
	public static function path(int $back, string|null $path = null) : string
	{

		$d = __DIR__;

		for($i = $back; $i > 0; $i--)
			$d = dirname($d);

		return Compio::adaptPath($d . (!empty($path) ? Compio::pathSep() . trim(str_replace(Compio::pathSep(true), Compio::pathSep(), $path), Compio::pathSep()) : null));

	}

	/**
	 * Get root project path
	 *
	 * @param string  $path
	 * @return string
	 */
	public static function getAppDir(string $path = null) : string
	{

		return self::path(7, $path);

	}

	/**
	 * Get Compio path
	 * 
	 * @param  string|null $path
	 * @return string
	 */
	public static function getCompioDir(string|null $path = null) : string
	{

		return self::path(4, $path);

	}

	/**
	 * Get command path
	 *
	 * @return void
	 */
	protected static function command_path_exist() {

		return file_exists(Compio::adaptPath(self::$command_path)) && is_dir(Compio::adaptPath(self::$command_path)) ? Compio::adaptPath(self::$command_path) : false;

	}

	/**
	 * Get Kernel path
	 *
	 * @return void
	 */
	public static function getKernelPath(){

		return self::getAppDir('\app\Console\Kernel.php');

	}

	/**
	 * Command initialization
	 *
	 * @return void
	 */
	public static function init_command(){

		echo "\n  |~ Compio for Laravel>=5.5 is load !\n";

		if($cp = self::command_path_exist()){

			$kernel_path = self::getKernelPath();

			if(file_exists($kernel_path) && is_file($kernel_path)){

				$file_content = file_get_contents($kernel_path);

				$command_path = "'" . self::getCompioDir('src/Environments/Laravel/V_sup_eq_5_5/resources/routes.commands.php') . "'";

				$new_content = preg_replace('/(require base_path[(\s]+\'routes\/console\.php\'[);\s]+)/', 'require_once ' . $command_path . ";\n\t\t$1",$file_content);

				$pattern = '/(' . preg_quote("require_once " . $command_path, "/") . ')/i';

				if(((bool) preg_match($pattern, $file_content)) === false){

					if(file_put_contents($kernel_path, $new_content)) echo "\n\t  ~ The `$kernel_path` file has ben modified  \n\t    to integrate Compio Laravel command !\n";
					else echo "\n\t  ~ Write error in `$kernel_path` file !\n";

				}
				else echo "\n\t  ~ The `$kernel_path` file already has\n\t    Compio Laravel command !\n";

			}
			else echo "\n\t  ~ Laravel kernel file `$kernel_path` doesn't exist !\n";

		}
		else echo "\n\t  ~ An error is occured !\n\t    The file `" . self::command_path_exist() . "` was not found !\n\t    Re-Instal this package 'dovetaily/compio'\n";

	}

	/**
	 * Initialization of configuration templates
	 *
	 * @return void
	 */
	public static function init_config(){

		$config_file_path = Compio::adaptPath(config_path('compio.php'));

		if(!file_exists($config_file_path)){

			$config_path = Compio::adaptPath(dirname($config_file_path));

			if(file_exists($config_path) || mkdir($config_path, 0777, true)){

				$template_config_file = Compio::adaptPath(__DIR__ . '\resources\config.php');

				if(copy($template_config_file, $config_file_path)) echo "\n\t  ~ The configuration file `" . $config_file_path . "` has been created !\n";
				else echo "\n\t  ~ Error ! Le fichier ne peut être créé !\n";

			}
			else echo "\n\t  ~ Permission Level Error ! Directory `$config_path` is not created ! Create it and execute again `composer dump-autoload` !\n";

		}
		else echo "\n\t  ~ The config file `$config_file_path` already exists !\n";

	}

}