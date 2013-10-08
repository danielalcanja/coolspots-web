<?php
// https://api.instagram.com/v1/locations/6620052/media/recent?client_id=028ea9a1bd024485bae1780ecb871f6d&max_id=535277361521038567_420119420

$settings = array(
	'app'	=> array(
		'version'	=> '1.0b',
		'author'	=> 'Walter Cruz'
	),
	'instagram' => array(
		'client_id'	=> array(
			'028ea9a1bd024485bae1780ecb871f6d'
		),
		'api_url'   => 'https://api.instagram.com/v1/locations/@OBJECT_ID@/media/recent?client_id=@CLIENT_ID@',
		'api_param' => '&max_id=@MAX_ID@'
	),
	'db'	=> array(
		'dsn'		=> 'mysql://host=localhost;dbname=coolspots_new',
		'user'		=> 'root',
		'pass'		=> '',
	)
);
?>
