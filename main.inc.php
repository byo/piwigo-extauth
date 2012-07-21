<?php
/*
Version: 1.0
Plugin Name: External authentications plugin
Plugin URI: https://github.com/byo/piwigo-extauth
Author: Bartlomiej (byo) Swiecki
Description: This plugin enable users to login using etxternal authentication methods such as facebook or google.
*/

// +-----------------------------------------------------------------------+
// | Piwigo - external authentication plugin                               |
// |                                 https://github.com/byo/piwigo-extauth |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2012 Bartlomiej (byo) wiecki                             |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation; either version 2 of the License, or     |
// | (at your option) any later version.                                   |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define( 'EAP_PATH', realpath( PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)) ).'/' );
define( 'EAP_URL',  get_absolute_root_url().'plugins/'.basename(dirname(__FILE__)).'/' );

require_once( PHPWG_ROOT_PATH.'include/functions_session.inc.php' );
require_once( 'include/extauth.class.php' );

new ExtAuth();

