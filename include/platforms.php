<?php
global $PLATFORMS;
$PLATFORMS = array(
	'fb' => array(
		'name'        => 'Facebok Connect',
		'url'         => 'https://www.facebook.com/',
		'profileUrl'  => 'https://www.facebook.com/%s/',
		'loginScript' => 'oauth2login.php?p=fb',
	),
	'google' => array(
		'name'        => 'Google',
		'url'         => 'https://www.google.com/',
		'profileUrl'  => 'https://profiles.google.com/%s',
		'loginScript' => 'oauth2login.php?p=google',
	),
);

