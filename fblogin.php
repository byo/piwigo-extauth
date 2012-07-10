<?php

define('PHPWG_ROOT_PATH', '../../');

include_once(PHPWG_ROOT_PATH . 'include/common.inc.php');
include_once('include/eapfacebook.class.php');

$user = EAPFacebook::grabLoginInfo();
var_dump( $user );
