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

define( 'EAP_PATH', realpath( PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)) ).'/' );
define( 'EAP_URL',  get_absolute_root_url().'plugins/'.basename(dirname(__FILE__)).'/' );

require_once( PHPWG_ROOT_PATH.'include/functions_session.inc.php' );
require_once( 'include/extauth.class.php' );

new ExtAuth();

