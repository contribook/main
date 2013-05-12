<?php

// CONTRIBOOK Configfile

// MySQL DB name
define('CONTRIBOOK_DB_NAME', 'contribook');

// MySQL login
define('CONTRIBOOK_DB_LOGIN', '');

// MySQL password
define('CONTRIBOOK_DB_PASSWD', '');

// MySQL hostname
define('CONTRIBOOK_DB_HOST', 'localhost');

// MySQL port
define('CONTRIBOOK_DB_PORT', '3306');

// MySQL socket
define('CONTRIBOOK_DB_SOCKET', '');

// User Photo URL
define('CONTRIBOOK_USER_URL', 'index.php?user=');

// The URL to the user page
define('CONTRIBOOK_PHOTO_URL', 'foo');

// Path to the template folder
define('CONTRIBOOK_TEMPLATES_PATH', 'templates/');

// Only show Twitter post that contain this keyword
define('CONTRIBOOK_TWITTERFILTER', 'owncloud');


// The Twitter API Access tokens
define('CONTRIBOOK_TWITTER_OAUTH_ACCESS_TOKEN', '');
define('CONTRIBOOK_TWITTER_OAUTH_ACCESS_TOKEN_SECRET', '');
define('CONTRIBOOK_TWITTER_CONSUMER_KEY', '');
define('CONTRIBOOK_TWITTER_CONSUMER_SECRET', '');


// The RSS feed to the forum
$CONTRIBOOK_forum_url = 'http://forum.owncloud.org/feed.php';

// The RSS feed to the news
$CONTRIBOOK_news_url = 'https://owncloud.com/feed';


// The base API of the OCS server
$CONTRIBOOK_ocs_server = 'http://api.opendesktop.org/v1/';


// The OCS categories to be fetched from the server
$CONTRIBOOK_ocs_categories =array(
	'1'=>array('name'=>'Themes','ids'=>'1x2x3x4x5x6x7x8x9x10x11x12x13x14x15x16x17x18x19x20x21x22x23x24x25x26x27x28x29x30x31x32x34x35x36x37x38x39x40x41x42x43x44x45x55x60x61x62x63x64x65x66x67x68x70x71x72x73x74x75x76x77x78x79x80x81x102x103x287'),
	'2'=>array('name'=>'Development','ids'=>'260x261'),
	'3'=>array('name'=>'Education','ids'=>'242'),
	'4'=>array('name'=>'Games','ids'=>'250x251x252x253x254'),
	'5'=>array('name'=>'Graphics','ids'=>'222x223x224'),
	'6'=>array('name'=>'Internet','ids'=>'230x231x232x233x234x235x236'),
	'7'=>array('name'=>'Multimedia','ids'=>'220x221x56x57x58'),
	'8'=>array('name'=>'Office','ids'=>'210x211x212x213x214'),
	'9'=>array('name'=>'System','ids'=>'270x271x272x273x281'),
	'10'=>array('name'=>'Utilities','ids'=>'282x284x271x285'),
	'11'=>array('name'=>'All','ids'=>'210x211x212x213x214x220x221x56x57x58x222x223x224x230x231x232x233x234x235x236x250x251x252x253x254x260x261x260x261x270x271x272x273x281x282x284x271x285x297')
);



