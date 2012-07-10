<?php

define('PHPWG_ROOT_PATH', '../../');

include_once(PHPWG_ROOT_PATH . 'include/common.inc.php');
require_once(PHPWG_ROOT_PATH . 'include/functions_session.inc.php' );
include_once('include/eapuser.class.php');
include_once('include/eapfacebook.class.php');

$user = EAPFacebook::grabLoginInfo();
if ( $user === FALSE ) die( 'Error' );

EAPUser::processAuthentication( 'FACEBOOK', $user['id'], array(
));
