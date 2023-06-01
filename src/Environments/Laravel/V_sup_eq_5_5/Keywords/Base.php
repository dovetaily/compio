<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords;

use Compio\Traits\ArgumentFormat;
use Compio\Environments\Laravel\V_sup_eq_5_5\Foundation;

class Base {

	/**
	 * [$default_value description]
	 * @var [type]
	 */
	public $default_value;				/* 0 - $default_value */

	/**
	 * [$template_datas description]
	 * @var [type]
	 */
	public $template_datas;				/* 1 - $template_datas */

	/**
	 * [$arguments description]
	 * @var [type]
	 */
	public $arguments;					/* 2 - $arguments */

	// public $callback_format_value;   /* 3 - callback_format_value */

	
	/**
	 * [$file_content description]
	 * @var [type]
	 */
	public $file_content;				/* 4 - $file_content|$current_file_content */
	
	/**
	 * [$file_path description]
	 * @var [type]
	 */
	public $file_path;					/* 5 - $file_path */
	
	/**
	 * [$all_keywords description]
	 * @var [type]
	 */
	public $all_keywords;				/* 6 - $all_keywords */
	
	/**
	 * [$template_name description]
	 * @var [type]
	 */
	public $template_name;				/* 7 - $component_name */
	
	/**
	 * [$template description]
	 * @var [type]
	 */
	public $template;
	
	/**
	 * [$template_config description]
	 * 
	 * @var [type]
	 */
	public $template_config;

	/**
	 * [__329732_config__9882832 description]
	 * 
	 * @param  [type] $default_value
	 * @param  [type] $template_datas
	 * @param  [type] $arguments
	 * @param  [type] $callback_format_value
	 * @param  [type] $file_content
	 * @param  [type] $file_path
	 * @param  [type] $all_keywords
	 * @param  [type] $template_name
	 * @param  [type] $template
	 * @return void
	 */
	public function __329732_config__9882832($default_value, $template_datas, $arguments, $callback_format_value, $file_content, $file_path/*$current_file_content*/, $all_keywords, $template_name, $template/*$type<class,js,model>*/, $template_config)
	{
		$this->default_value = $default_value;
		$this->template_datas = $template_datas;
		$this->arguments = $arguments;
		// $this->callback_format_value = $callback_format_value;
		$this->file_content = $file_content; // $current_file_content
		$this->file_path = $file_path;
		$this->all_keywords = $all_keywords;
		$this->template_name = $template_name; //
		$this->template = $template;
		$this->template_config = $template_config;
	}

	/**
	 * [callbackFormatValue description]
	 * 
	 * @param  [type] $value [description]
	 * @param  [type] $type  [description]
	 * @param  [type] $equal [description]
	 * @return string
	 */
	public function callbackFormatValue($value, $type, $equal){
		return ArgumentFormat::format_value($value, $type, $equal);
	}

	/**
	 * [getRelativeFilePath description]
	 * 
	 * @param  bool  $inverse
	 * @return string
	 */
	public function getRelativeFilePath(bool $inverse = false){
		preg_match('/^' . preg_quote(Foundation::getAppDir() . '\\') . '(.*)/', $this->file_path, $m);
		return $inverse ? Foundation::getAppDir() : end($m);
	}

}