<?php

// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// Include facebook sdk classes
include_once('facebooksdk/facebook.php' );


class EAPFacebook
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
//		@session_start();
		return self::getFbObject()->getLoginUrl(array(
				'redirect_uri' => EAP_URL.'fblogin.php'
		));
	}
	
	public static function grabLoginInfo()
	{
//		@session_start();
		try
		{
			$fb = self::getFbObject();

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
		return '';
	}
	
	// Get Facebook's application secret
	private static function getFbAppSecret()
	{
		return '';
	}
}

