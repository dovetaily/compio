<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base;
use Illuminate\Support\Str;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Helpers;

class Page extends Base {

	use Helpers;

	/**
	 * [all_button_title description]
	 * 
	 * @return [type] [description]
	 */
	public function all_button_title(){

		return ucfirst($this->template_name);

	}

	/**
	 * [create_button_title description]
	 * 
	 * @return [type] [description]
	 */
	public function create_button_title(){

		return ucfirst(Str::singular($this->template_name));

	}

	/**
	 * [route description]
	 * 
	 * @return [type] [description]
	 */
	public function route(){

		return Str::singular($this->template_name);

	}

	/**
	 * [class_html description]
	 * 
	 * @return [type] [description]
	 */
	public function class_html(){
		preg_match('/.*\\\(.*)\\.blade\\.php$/i', $this->file_path, $m);
		return 'page-'. $this->template_name . '-' . end($m) . '-' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
	}

	/**
	 * [content description]
	 * 
	 * @return [type] [description]
	 */
	public function content(){
		preg_match('/.*\\\(.*)\\.blade\\.php$/i', $this->file_path, $m);
		$type = end($m);
		return '{{-- ... --}}';
	}

	/**
	 * [additional description]
	 * 
	 * @return [type] [description]
	 */
	public function additional(){
		preg_match('/.*\\\(.*)\\.blade\\.php$/i', $this->file_path, $m);
		$type = end($m);
		return '';
	}

	/**
	 * [page_layout description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function page_layout(...$args){
		return isset($this->arguments['page']['layout']) ? $this->arguments['page']['layout'](...$args) : 'main';
	}

	/**
	 * [page_title description]
	 * 
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function page_title(...$args){
		preg_match('/.*\\\(.*)\\.blade\\.php$/i', $this->file_path, $m);
		$type = ' | ' . Str::title(end($m));
		return isset($this->arguments['page']['title']) ? (!is_string($this->arguments['page']['title']) && is_callable($this->arguments['page']['title']) ? $this->arguments['page']['title'](end($m), ...$args) : $this->arguments['page']['title']) : "'" . Str::singular(Str::title(str_replace('_', ' ', $this->template_name))) . $type . "'";
	}

	/**
	 * [locate_css description]
	 * 
	 * @return [type] [description]
	 */
	public function locate_css(){

		return $this->callLocate($this->default_value);

	}

	/**
	 * [locate_js description]
	 * 
	 * @return [type] [description]
	 */
	public function locate_js(){

		return $this->callLocate($this->default_value);

	}

}