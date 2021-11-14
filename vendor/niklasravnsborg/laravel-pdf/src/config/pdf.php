<?php
// dd("SS");
return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'default_font'          => 'sans-serif',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('../temp/'),
	'font_path' => base_path('resources/fonts/'),
	'font_data' => [
		'islam' => [
			'R'  => 'Amiri-Regular.ttf',    // regular font
			'B'  => 'Amiri-Bold.ttf',       // optional: bold font
			'I'  => 'Amiri-Italic.ttf',     // optional: italic font
			'BI' => 'Amiri-BoldItalic.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		]
		// ...add as many as you want.
	]
];
