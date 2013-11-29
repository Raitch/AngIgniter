<?php

$config['scss']	=	array(
	'pre_path'	=>	'/assets/style/scss',
	'files'			=>	array(
		'/*.scss',
	),
	'local'			=>	array(),
);

$config['css']	=	array(
	'pre_path'	=>	'/assets/style/processed',
	'all'				=>	'all',
	'files'			=>	array(
		'/layout.css',
		'/*.css',
	),
	'local'			=>	array(),
);

$config['js']	=	array(
	'pre_path'	=>	'/assets/script',
	'all'				=>	'all',
	'files'			=>	array(
		'https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js',
		'/angular/ang_helper.js',
		'/angular/init.js',
		'/angular/service/*.js',
		'/angular/directive/*.js',
		'/angular/filter/*.js',
		'/angular/controller/*.js',
		'/angular/controller/content/*.js',
		'/angular/controller/item/*.js',
	),
	'local'			=>	array(),
);