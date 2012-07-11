<?php

// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once('eapbase.class.php');
include_once('defs.php');

include_once( PHPWG_ROOT_PATH.'include/functions_user.inc.php' );

class EAPUser
{
	public static function processAuthentication( $hostPlatform, $hostPlatformId, $userInfo )
	{
		global $conf;

		$query = "
		SELECT
		user.".$conf['user_fields']['id']." as 'id',
		eap_user.user_id IS NOT NULL as 'known'
		FROM
		".EAP_USERS." as eap_user
		LEFT JOIN
		".USERS_TABLE." as user
		ON user.".$conf['user_fields']['id']." = eap_user.user_id
		WHERE
		eap_user.platform = '" . pwg_db_real_escape_string($hostPlatform) . "' AND
		eap_user.id = '" . pwg_db_real_escape_string($hostPlatformId) ."'
		";

		$data = pwg_db_fetch_assoc(pwg_query($query));
		if ( $data === FALSE )
		{
			// Unknown user
			$query = "
			INSERT INTO ".EAP_USERS."(
			user_id,
			platform,
			id
			)
			VALUES(
			-1,
			'" . pwg_db_real_escape_string($hostPlatform) . "',
			'" . pwg_db_real_escape_string($hostPlatformId) . "'
			)";
				
			pwg_query($query);
				
			self::showPending();
		}
		else if ( $data['id'] === null )
		{
			self::showPending();
		}
		else
		{
			self::login( $data['id'] );
		}
	}

	private static function showPending()
	{
		die( "Thank you for stepping by. Administrators will give you the access soon." );
	}

	private static function login( $user_id )
	{
		log_user( $user_id, false );
		redirect( get_gallery_home_url() );
	}
}
