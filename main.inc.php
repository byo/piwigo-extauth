<?php
/*
Version: 1.0
Plugin Name: External authentications plugin
Plugin URI:
Author: B.Swiecki
Description: This plugin enable users to login using etxternal authentication methods such as facebook or google.
*/

// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

require_once( 'extauth.class.php' );

new ExtAuth();

