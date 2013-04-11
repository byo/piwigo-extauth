<?php
// +-----------------------------------------------------------------------+
// | Piwigo - external authentication plugin                               |
// |                                 https://github.com/byo/piwigo-extauth |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2013 Bartlomiej (byo) Swiecki                            |
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

define('PHPWG_ROOT_PATH', '../../');

include_once(PHPWG_ROOT_PATH . 'include/common.inc.php');
require_once(PHPWG_ROOT_PATH . 'include/functions_session.inc.php' );

include_once('include/eapuser.class.php');
include_once('include/eapoauth2.class.php');

class ConfigSetter extends EAPBase
{
	public static function setup()
	{
		self::setCfgValues(array(
			'fb_id'     => '494494263910157',
			'fb_secret' => '6a89b3021c24db58d95714ff739fcd47',
			'google_id' => '553659678064.apps.googleusercontent.com',
			'google_secret' => 'FpgMVQ8sp9C6b_ujamz9RJ4V',
		));
	}
}

ConfigSetter::setup();

isset($_GET['p']) or die( "Missing platform identifier" );

$platform = $_GET['p'];
$oauth = new EAPOauth2( $platform );

// Get the access code
if ( !isset( $_GET['code'] ) )
{
	$url = $oauth->getLoginUrl();
	header("Location: $url");
	exit(0);
}

// Get the information about the user
$user = $oauth->grabLoginInfo();
if ( $user === FALSE ) die( 'Error' );

EAPUser::processAuthentication( $platform, $user['id'], $user );

