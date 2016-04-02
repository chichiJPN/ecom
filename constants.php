<?php

	// define('BASE_URL',"http://" . $_SERVER['SERVER_NAME'].':8080'. substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'], "/", strpos($_SERVER['REQUEST_URI'], "/") + strlen("/"))));
	define('BASE_URL',"http://" . $_SERVER['SERVER_NAME']. substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'], "/", strpos($_SERVER['REQUEST_URI'], "/") + strlen("/"))));
	// define('BASE_URL',"http://" . $_SERVER['SERVER_NAME'] .':'.$_SERVER[SERVER_PORT]);
	// define('BASE_URL',"http://" . $_SERVER['HTTP_REFERER']);
	// echo '<pre>'; 
	// echo print_r($_SERVER); 
	// echo '</pre>';
?>