<?php
	$ReliefPath = dirname(__FILE__) . '/../system/Relief.php';
	
	$config = require_once 'config.php';
	
	require_once $ReliefPath;
	
	Relief::init($config);
?>