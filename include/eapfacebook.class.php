<?php
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

// Include facebook sdk classes
include_once('facebooksdk/facebook.php' );
include_once('eapbase.class.php');


class EAPFacebook extends EAPBase
{
	public static function getFbObject()
	{
		return new Facebook(array(
			'appId'  => self::getFbAppId(),
			'secret' => self::getFbAppSecret()
		));
	}

	private static function getRedirectUrl()
	{
		return EAP_URL.'fblogin.php';
	}

	public static function getLoginUrl()
	{
		$ret = self::getFbObject()->getLoginUrl(array(
				'redirect_uri' => self::getRedirectUrl()
		));
		return $ret;
	}

	public static function grabLoginInfo()
	{
		try
		{
			$fb = self::getFbObject();

			// Some workaround since native methods from the sdk didn't want to work
			if ( isset( $_GET['code'] ) )
			{
				$sess_prefix = 'fb_'.self::getFbAppId();
				if ( !isset( $_GET['state'] )
				  || !isset( $_SESSION["{$sess_prefix}_state"] )
				  || ( $_GET['state'] != $_SESSION["{$sess_prefix}_state"] ) )
				{
					die('CSRF attack protection. Please try again later.');
				}
				unset( $_SESSION["{$sess_prefix}_state"] );

				$response = @file_get_contents( 'https://graph.facebook.com/oauth/access_token'.
					'?client_id='.self::getFbAppId().
					'&redirect_uri='.urlencode(self::getRedirectUrl()).
					'&client_secret='.self::getFbAppSecret().
					'&code='.urlencode($_GET['code']) );
				parse_str($response,$response);
				if ( isset($response['access_token']) )
				{
					$fb->setAccessToken($response['access_token']);
					$_SESSION["{$sess_prefix}_code"] = $_GET['code'];
					$_SESSION["{$sess_prefix}_access_token"] = $response['access_token'];
				}
			}

			$user = $fb->getUser();
			if ( !$user ) return FALSE;

			return $fb->api( '/me' );
		}
		catch ( Exception $e )
		{
			var_dump( $e );
			return FALSE;
		}
	}

	// Get Facebook's application id
	private static function getFbAppId()
	{
		return self::getCfgValue( 'fbAppId', '' );
	}

	// Get Facebook's application secret
	private static function getFbAppSecret()
	{
		return self::getCfgValue( 'fbSecret', '' );
	}
}
