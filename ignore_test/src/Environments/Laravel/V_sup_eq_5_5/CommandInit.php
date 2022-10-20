<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5;

// use Compio\Traits\Singleton;

abstract class CommandInit{

	private static $path_commands = __DIR__ . '\Commands';

	// use Singleton;
	/**
	 * Create a new Compio\Environments\Laravel\Liste instance.
	 *
	 * @return void
	 */
	public static function init(){
		self::init_command();
		self::init_config_model();
	}

	protected static function path_commands_exist() : string|bool {
		return file_exists(self::$path_commands) && is_dir(self::$path_commands) ? self::$path_commands : false;
	}

	public static function getKernelPath(){
		// $f = function_exists('config') && \config('app.path_console_kernel') ? \config('app.path_console_kernel') : getcwd() . '\app\Console\Kernel.php'; // Dans le cas où le fichier kernel n'est pas trouver
		$f = getcwd() . '\app\Console\Kernel.php'; // Dans le cas où le fichier kernel n'est pas trouver
		return $f;
	}

	public static function init_command(){
		if($cp = self::path_commands_exist()){
			if(file_exists(self::getKernelPath()) && is_file(self::getKernelPath())){
				$file_content = file_get_contents(self::getKernelPath());
				$cp_ = "getcwd() . '" . (str_replace(getcwd(), null, (dirname($cp) . '\resources\routes.commands.php'))) . "'";
				$nw_c = preg_replace('/(require base_path[(\s]+\'routes\/console\.php\'[);\s]+)/', 'require_once ' . $cp_ . ";\n\t\t$1",$file_content);
				$pattern = '/(' . str_replace(['\\', '.', '(', ')', "'"], ['\\\\', '\.', '\(', '\)', "\'"], "require_once $cp_") . ')/i';
				if(((bool) preg_match($pattern, $file_content)) === false){
					if(file_put_contents(self::getKernelPath(), $nw_c)) echo "\nGenerate successfully !\n";
					else echo "\n\"" . self::getKernelPath() . "\" file write error\n";
				}
				// else echo "Already";
			}
			else echo "\nFile \"" . self::getKernelPath() . "\" not exits !\n";
		}
		else echo "\nAn error is occured ! Re-Instal this package 'compio/compio'\n";
	}

	public static function init_config_model(){
		$file_config_path = config_path('compio.php');
		if(!file_exists($file_config_path)){
			$config_path = dirname($file_config_path);
			if(file_exists($config_path) || mkdir($config_path, 0777, true)){
				$model_file = __DIR__ . '\resources\config.php';
				if(copy($model_file, $file_config_path)) echo "The configuration file `" . $file_config_path . "` has been created !\n";
				else echo "\nError ! Le fichier ne peut être créé !\n";
			}
			else echo "\nError ! Directory `" . $config_path . "` is not created ! Create it !\n";
		}
		// else echo "Confiration file for compio is already exists !";
	}

}