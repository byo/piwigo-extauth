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

include_once('eapbase.class.php');
include_once('defs.php');
include_once('platforms.php');

include_once( PHPWG_ROOT_PATH.'include/functions_user.inc.php' );
include_once( PHPWG_ROOT_PATH.'include/functions_mail.inc.php' );

class EAPUser extends EAPBase
{
	public static function processAuthentication( $hostPlatform, $hostPlatformId, $userInfo, $finalRedirect )
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
		if ( !is_array($data) )
		{
			// Unknown user
			$query = "
			INSERT INTO ".EAP_USERS."(
				user_id,
				platform,
				id,
				date_added,
				name,
				email
			)
			VALUES(
				-1,
				'" . pwg_db_real_escape_string($hostPlatform  ) . "',
				'" . pwg_db_real_escape_string($hostPlatformId) . "',
				NOW(),
				'" . pwg_db_real_escape_string($userInfo['name' ]) . "',
				'" . pwg_db_real_escape_string($userInfo['email']) . "'
			)";

			pwg_query($query);

			self::sendPendingEmail( $userInfo );
			self::showPending();
		}
		else if ( $data['id'] === null )
		{
			self::updateUserEntries( $hostPlatform, $hostPlatformId, $userInfo );
			self::showPending();
		}
		else
		{
			self::updateUserEntries( $hostPlatform, $hostPlatformId, $userInfo );
			self::login( $data['id'], $finalRedirect );
		}
	}

	private static function updateUserEntries( $hostPlatform, $hostPlatformId, $userInfo )
	{
		$query = "
			UPDATE ".EAP_USERS."
			SET
				name       = '" . pwg_db_real_escape_string($userInfo['name' ]) . "',
				email      = '" . pwg_db_real_escape_string($userInfo['email']) . "',
				date_added = IFNULL( date_added, NOW() )
			WHERE
				platform = '" . pwg_db_real_escape_string($hostPlatform  ) . "' AND
				id       = '" . pwg_db_real_escape_string($hostPlatformId) . "'
                        ";

		pwg_query( $query );
	}

	private static function sendPendingEmail( $userInfo )
	{
		$adminUrl = get_absolute_root_url() . 'admin.php?page=plugin&section=external_auth/admin.php';
		pwg_mail_notification_admins(
			get_l10n_args('New extauth user: %s', $userInfo['name'] ),
			array(
				get_l10n_args('User name: %s',  $userInfo['name']  ),
				get_l10n_args('User email: %s', $userInfo['email'] ),
				get_l10n_args('', ''),
				get_l10n_args('User management: %s', $adminUrl),
			)
		);
	}

	private static function showPending()
	{
		header('Location: ' . self::getUrl() . 'pending.php' );
		exit(0);
	}

	private static function login( $user_id, $finalRedirect )
	{
		log_user( $user_id, false );
		if ( is_null( $finalRedirect ) )
		{
			redirect( get_gallery_home_url() );
		}
		else
		{
			redirect( $finalRedirect );
		}
	}

	public static function getPendingEntries()
	{
		global $conf, $PLATFORMS;

		$ret = array( 'eap_users' => array(), 'users' => array() );

		$emailCache = array();
		$query = pwg_query( "
			SELECT
				".$conf['user_fields']['id']." as user_id,
				".$conf['user_fields']['username']." as user_name,
				".$conf['user_fields']['email']." as email
			FROM
				".USERS_TABLE."
			ORDER BY
				user_name
				");
		if ( !empty( $query ) )
		{
			while( $row = pwg_db_fetch_assoc($query) )
			{
				$ret['users'][] = $row;
				if ( $row['email'] != '' )
				{
					$emailCache[$row['email']] = $row['user_id'];
				}
			}
		}

		$query = pwg_query( "
			SELECT platform, id, name, email
			FROM ".EAP_USERS."
			WHERE user_id < 0
			ORDER BY platform, id
		" );
		if ( !empty($query) )
		{
			while ( $row = pwg_db_fetch_assoc( $query ) )
			{
				if ( isset( $PLATFORMS[ $row['platform'] ] ) )
				{
					$platform = $PLATFORMS[ $row['platform'] ];
					$row['platformLink'] = $platform['url'];
					$row['platformProfileLink'] = sprintf( $platform['profileUrl'], $row['id'] );
				}
				if ( isset( $emailCache[$row['email']] ) )
				{
					$row['suggestedUserId'] = $emailCache[$row['email']];
				}
				else
				{
					$row['suggestedUserId'] = -1;
				}
				$ret['eap_users'][] = $row;
			}
		}

		return $ret;
	}

	public static function associateUser( $platform, $id, $user_id )
	{
		$query = "
			UPDATE ".EAP_USERS."
			SET
				user_id = ".((int)$user_id)."
			WHERE
				platform = '" . pwg_db_real_escape_string($platform) . "' AND
				id = '" . pwg_db_real_escape_string($id) . "' AND
				user_id < 0
		";

		pwg_query($query);
	}

	public static function createUser( $platform, $id, $name, $email )
	{
		$errors = register_user( $name, md5( mt_rand() . mt_rand() ), $email, false );
		if ( count( $errors ) > 0 ) return false;

		$user_id = get_userid( $name );
		if ( $user_id === FALSE ) return false;

		return self::associateUser( $platform, $id, $user_id );
	}
}

