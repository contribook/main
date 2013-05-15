<?php

	// makes it easier to debug
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	// set the default timezone to use.
	date_default_timezone_set('Europe/Berlin');

	// you have to include lib_contribook.php on every page where you want to show some of the content.
 	require('contribook/lib_contribook.php');

	// generate RSS feed
	CONTRIBOOK_BLOG::showrss('title', 'description', 'link', 30);
	
