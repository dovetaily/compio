<?php

namespace Compio\Environments\Laravel\V_Pack1;

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
		if($cp = self::path_commands_exist()){
			if(file_exists(self::getKernelPath()) && is_file(self::getKernelPath())){
				$file_content = file_get_contents(self::getKernelPath());
				$cp_ = "getcwd() . '" . (str_replace(getcwd(), null, (dirname($cp) . '\routes_commands.php'))) . "'";
				$nw_c = preg_replace('/(require base_path[(\s]+\'routes\/console\.php\'[);\s]+)/', 'require_once ' . $cp_ . ";\n\t\t$1",$file_content);
				$pattern = '/(' . str_replace(['\\', '.', '(', ')', "'"], ['\\\\', '\.', '\(', '\)', "\'"], "require_once $cp_") . ')/i';
				if(((bool) preg_match($pattern, $file_content)) === false){
					if(file_put_contents(self::getKernelPath(), $nw_c)) echo "Generate successfully !";
					else echo '"' . self::getKernelPath() . '" file write error';
				}
				// else echo "Already";
			}
			else echo "File \"" . self::getKernelPath() . "\" not exits !";
		}
		else echo "An error is occured ! Re-Instal this package 'compio/compio'";
	}

	protected static function path_commands_exist() : string|bool {
		return file_exists(self::$path_commands) && is_dir(self::$path_commands) ? self::$path_commands : false;
	}

	public static function getKernelPath(){
		// $f = function_exists('config') && \config('app.path_console_kernel') ? \config('app.path_console_kernel') : getcwd() . '\app\Console\Kernel.php'; // Dans le cas où le fichier kernel n'est pas trouver
		$f = getcwd() . '\app\Console\Kernel.php'; // Dans le cas où le fichier kernel n'est pas trouver
		return $f;
	}


}