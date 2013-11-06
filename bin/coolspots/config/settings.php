<?php
$settings = array(
	'app'	=> array(
		'version'	=> '1.2b',
		'author'	=> 'Walter Cruz'
	),
	'instagram' => array(
		'api_url'   => 'https://api.instagram.com/v1/locations/@OBJECT_ID@/media/recent?client_id=@CLIENT_ID@',
		'api_param' => '&min_timestamp=@MIN_TIMESTAMP@'
	),
	'db'	=> array(
//		'dsn'		=> 'mysql://host=localhost;dbname=coolspots_new',
//		'user'		=> 'root',
//		'pass'		=> '',
		'dsn'		=> 'mysql://host=localhost;dbname=coolspot_db',
		'user'		=> 'coolspot_user',
		'pass'		=> 'c00lsp0ts!',
	)
);
?>
