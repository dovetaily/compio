<?php
namespace Compio;
/**
 * 
 */

interface EnvironmentsCommandsInterface{

	/**
	 * Initializing Template Engine
	 *
	 * @return void
	 */
    public function init_template_engine() : void;

	/**
	 * Get Template Engine selected
	 *
	 * @param string|null       $key
	 * @return array|bool|sring
	 */
    public function getTemplateEngineSelected($key = null) : array|bool|string;

}