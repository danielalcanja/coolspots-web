<?php
// https://api.instagram.com/v1/locations/17220915/media/recent?client_id=028ea9a1bd024485bae1780ecb871f6d&max_id=327021608562783931_42114284
// https://api.instagram.com/v1/locations/17220915/media/recent?min_timestamp=1381057042&client_id=028ea9a1bd024485bae1780ecb871f6d
$settings = array(
	'app'	=> array(
		'version'	=> '1.1b',
		'author'	=> 'Walter Cruz'
	),
	'instagram' => array(
		'client_id'	=> array(
			'028ea9a1bd024485bae1780ecb871f6d'
		),
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
