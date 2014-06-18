<?php

$conf = array(
	'db' => array(
		'host'	 => 'localhost',
		'dbname' => 'brain',
		'user'	 => 'root',
		'pass'	 => 'goblin21',
	),

	'api' => array(
		'login' => 'igloo077@gmail.com',
		'password' => md5('Masha4523910'),
	),

	'api_url' => array(
		'auth'			 => 'http://api.brain.com.ua/auth',
		'logout'		 => 'http://api.brain.com.ua/logout/',
		'categories'	 => 'http://api.brain.com.ua/categories/',
		'products'		 => 'http://api.brain.com.ua/products/',
		'product'		 => 'http://api.brain.com.ua/product/',
		'vendors'		 => 'http://api.brain.com.ua/vendors/',
		'targets'		 => 'http://api.brain.com.ua/targets/',
		'delivery_time'	 => 'http://api.brain.com.ua/delivery_time/',
	),

	'api_param' => array(
		'limit' => 1000,
	),
);