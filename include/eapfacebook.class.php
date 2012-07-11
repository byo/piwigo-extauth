<?php

// Chech whether we are indeed included by Piwigo.
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
	
	public static function getLoginUrl()
	{
		$ret = self::getFbObject()->getLoginUrl(array(
				'redirect_uri' => EAP_URL.'fblogin.php'
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
				// TODO: CSRF Protection is gone
				$response = @file_get_contents( 'https://graph.facebook.com/oauth/access_token'.
					'?client_id='.self::getFbAppId().
					'&redirect_uri='.urlencode(EAP_URL.'fblogin.php').
					'&client_secret='.self::getFbAppSecret().
					'&code='.urlencode($_GET['code']) );
				parse_str($response,$response);
				if ( isset($response['access_token']) )
				{
					$fb->setAccessToken($response['access_token']);
					$_SESSION['fb_'.self::getFbAppId().'_code'] = $_GET['code'];
					$_SESSION['fb_'.self::getFbAppId().'_access_token'] = $response['access_token'];
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

