<?php
	if(!(isset($_SESSION['type']) && !empty($_SESSION['type']) && $_SESSION['type'] === '2')){
		header('Location: '.BASE_URL.'/home');
		die();
	}
?>