<?php
return [

	'component' => [
		'config' => [
			'paths' => [
				'class' => getcwd() . '\app\View\Components',
				'render' => getcwd() . '\resources\views\component',
				'assets' => [
					'css' => getcwd() . '\public\css\components',
					'js' => getcwd() . '\public\css\components',
				]
			],
			'replace_component_exist' => true,
		],
		// 'to-generate' => [
		// 	[
		// 		'name' => 'ComponentName',
		// 		'args' => 'type1:type2:typeX:argument_name=default_value typeX:argument2_name',
		// 		'config' => [
		// 			'replace_component_exist' => true, // (true) If the components exists, it is replaced immediately !
		// 			// 'paths' => [] // The paths where the component files(class, render, assets[css, js]) will be generated
		// 		],
		// 	],
		// ]
	]
];