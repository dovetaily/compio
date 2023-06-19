<?php

namespace Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Template;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Base;
use Illuminate\Support\Str;

use Compio\Environments\Laravel\V_sup_eq_5_5\Keywords\Helpers;

class Migration extends Base {

	use Helpers;

	/**
	 * [class_name description]
	 * 
	 * @return string|bool
	 */
	public function class_name()
	{
		preg_match('/.*_create_(.*)_table.*$/i', $this->file_path, $table);
		return 'Create'. ucfirst(Str::camel(end($table))) . 'Table';
	}

	/**
	 * [migration_extends description]
	 * 
	 * @param  [type] $args
	 * @return string|bool
	 */
	public function migration_extends(...$args) {
		// dump();
		return $this->extend($args, 'migration', 'Illuminate\Database\Migrations\Migration', '@migration_import_class', '@migration_extends');
	}

	/**
	 * [migration_implements description]
	 * 
	 * @param  [type] $args
	 * @return string|bool
	 */
	public function migration_implements(...$args){
		return $this->implement($args, 'migration', null, '@migration_import_class', '@migration_implements');
	}

	public function migration_import_trait(...$args){
		return $this->importTrait($args, 'migration', [
			// 'Illuminate\Database\Eloquent\Factories\HasFactory',
		], '@migration_import_class', '@migration_import_trait');
	}

	public function migration_table(){
		preg_match('/.*_create_(.*)_table.*$/i', $this->file_path, $table);
		return end($table);
	}

	public function migration_column()
	{
		$foreign_default = $this->template_config['foreign_default'];
		$datas = $this->getMigrationDatas($this->arguments, /*$_call_col*/null, $foreign_default, isset($this->arguments['migration']['modifiers']) ? $this->arguments['migration']['modifiers'] : null);
		$import_class = [];
		$render = '';
		if(isset($this->arguments['migration']['#content']) && (is_string($this->arguments['migration']['#content']) || is_callable($this->arguments['migration']['#content'])))
			return !is_string($this->arguments['migration']['#content']) ? $this->arguments['migration']['#content'](...$this->arguments) : $this->arguments['migration']['#content'];
		elseif(!isset($this->arguments['migration']['#content'])){
			// dump($datas);exit;
			$ret = [];
			foreach ($datas as $column => $value) {
				$rr = '$table';
				foreach (['type', 'modifiers'] as $t){
					// if($column == 'id' && $t == 'type') $value[$t] = ['bigIncrements' => array_key_exists('bigIncrements', empty($value[$t]) ? [] : $value[$t]) ? $value[$t]['bigIncrements'] : '#'];
					// if($column == 'id' && $t == 'type') $value[$t]['bigIncrements'] = array_key_exists('bigIncrements', empty($value[$t]) ? [] : $value[$t]) ? $value[$t]['bigIncrements'] : '#';
					if($column == 'id' && $t == 'type') $value[$t]['id'] = '';
					if(!empty($value[$t])){
						foreach ($value[$t] as $type => $val) {
							$rr .= "->" . $type . "(" . (($r = $val) === null
								? var_export($column, true)
								: ($r === false
									? null
									: ($r !== '#'
										? (is_array($val) 
											? implode(', ', (function($val, $col, $t = null){
												foreach ($val as $k => $value)
													$val[$k] = $value == '#' ? var_export($col, true) : ($value == '#t#' && !is_null($t) ? var_export($t, true) : $value);
												return $val;
												})($val, $column, (isset($value['table']) ? $value['table'] : null))) 
											: ($val == '#t#' && isset($value['table'])
												? var_export($value['table'], true)
												: $val
											)
										)
										: var_export($column, true)
									)
								)
							) . ")";
						}
					}
				}

				$ret[] = ($rr == '$table' ? '// ' . $rr . "->type('" . $column . "')" : $rr) . ';';
			}
			return !empty($ret) ? "\n\t\t\t". implode("\n\t\t\t", $ret) : '';

		}
		return '';
	}

	/**
	 * [migration_foreign description]
	 * 
	 * @param  [type] $args
	 * @return string|bool
	 */
	public function migration_foreign(...$args){
		$datas = [
			'default' => (function($args, $call_back){
				$ret = [];
				$cols = $call_back->colone($args['columns'], false, false);
				foreach ($cols as $column => $value) {
					if(preg_match('/(.*)_id$/i', $column, $m)){
						$ret[current($m)] = [
							'table' => Str::plural(end($m)),
							'primary_key' => 'id',
							// 'type' => isset($value['type']) ? (is_string($value['type']) ? [$value['type'] => 'id'] : $value['type']) : ['unsignedBigInteger' => '#'],
						];
					}
				}
				// dump($cols);
				return $call_back->colone($ret, false, false);
			})($args[2], $this),
			'new' => isset($args[2]['migration']['foreign']) && !empty($args[2]['migration']['foreign']) && is_array($args[2]['migration']['foreign'])
				? (function($dts){
					$ret = [];
					foreach ($dts as $key => $value) {
						$prg = preg_match('/(.*)_id$/i', $key, $m);
						$ret[$key] = is_array($value) ? $value : [];
						if($key == '#content')
							$ret[$key] = $value;
						else{
							$ret[$key]['table'] = isset($value['table']) ? $value['table'] : ($prg ? Str::plural(end($m)) : null);
							$ret[$key]['primary_key'] = isset($value['primary_key']) ? $value['primary_key'] : 'id';
						}
					}
					return $ret;
				})($this->colone($args[2]['migration']['foreign'], false, false))
				: null
			,
		];
		$import_class = [];
		$render = '';
		if(isset($datas['new']['#content']) && (is_string($datas['new']['#content']) || is_callable($datas['new']['#content'])))
			return !is_string($datas['new']['#content']) ? $datas['new']['#content'](...$args[2]) : $datas['new']['#content'];
		elseif(!isset($datas['new']['#content'])){
			$datas = is_array($datas['new']) ? $datas['new'] : $datas['default'];
			$dts = [];
			foreach ($datas as $column => $value) {
				$dts[] = '$table->foreign(\'' . $column . '\')->references(\'' . $value['primary_key'] . '\')->on(\'' . $value['table'] . '\');';
			}
			$render = implode("\n\t\t\t", $dts);
				// dump($datas['new']);exit;
			// $dts = ;
		}
		return empty($render) ? '' : "\n\n\t\t\t". $render;
	}

	/**
	 * [migration_properties description]
	 * 
	 * @return string|bool
	 */
	public function migration_properties(){
		$datas = isset($this->arguments['migration']['properties']) ? $this->arguments['migration']['properties'] : [];
		$render = '';
		$dts = [];
		foreach ($datas as $method => $value) {
			$dts[] = '$table->' . $method . '(' . (is_array($value) 
				? implode(',', $value)
				: (is_string($value)
					? $value
					: (is_string($rr = $value(...[$method, $this->arguments]))
						? $rr
						: null
					)
				)
			) . ');';
		}
		return empty($dts) ? '' : "\n\t\t\t" . implode("\n\t\t\t", $dts);
	}

	/**
	 * [migration_down description]
	 * 
	 * @return string|null
	 */
	public function migration_down()
	{
		$temp = "\n\t\tSchema::table('" . $this->template_name . "', function (Blueprint \$table) {\n\t\t\t--replace--\n\t\t});";

		$datas = (function($args, $call_back){
			$ret = [];
			$cols = $call_back->colone($args['columns'], false, false);
			foreach ($cols as $column => $value) {
				if(preg_match('/(.*)_id$/i', $column, $m)){
					$ret[current($m)] = [
						'table' => is_array($value) && isset($value['table']) ? $value['table'] : Str::plural(end($m)),
						'primary_key' => 'id',
						// 'type' => isset($value['type']) ? (is_string($value['type']) ? [$value['type'] => 'id'] : $value['type']) : ['unsignedBigInteger' => '#'],
					];
				}
			}
			// dump($cols);
			return $call_back->colone($ret, false, false);
		})($this->arguments, $this);

		$dt = [];

		foreach ($datas as $column => $value)
			$dt[] = "\$table->dropForeign('" . $this->template_name . "_" . $column . "_foreign');";

		return !empty($dt) ? str_replace('--replace--', implode("\n\t\t\t", $dt), $temp) : '';
	}

	/**
	 * [migration_import_class description]
	 * 
	 * @param  [type] $args
	 * @return string|bool
	 */
	public function migration_import_class(...$args){
		return $this->importClass($args, 'migration', [
			'Illuminate\Database\Schema\Blueprint',
			'Illuminate\Support\Facades\Schema',
		]);
	}

}