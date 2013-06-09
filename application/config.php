<?php
	return array(
		'applicationPath' => dirname(__FILE__),
		'autoload'        => array(
			'application.models',
			'application.extensions'
		),
		'db'              => array(
			'connectionName' => array(
				'dsn'      => 'mysql:host=localhost;dbname=test',
				'username' => 'root',
				'password' => '',
				'charset'  => 'utf8',
			)
		),
		'log'             => 'application.logs',
		'uriAliases'      => array(
			'kezdolap' => 'index/default'
		),
		/*
		'sessionHandler'       => 'mysql', // Alapertelmezett
		'sessionConnectioName' => 'connectionName' // Adatbaziskapcsolat neve
		
		'sessionHandler' => 'cookie' // Suti alapu session
		
		'sessionHandler'  => 'file', // Fajl alapu session
		'sessionSavePath' => 'system.sessions', // Fajlok ide mentodnek, ha nincs megadva akkor az alapertelmezett
		 
		'sessionLifeTime' => 3600 // session elettartam
		*/
	);
?>