<?php

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
	| configuration related to the components
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
