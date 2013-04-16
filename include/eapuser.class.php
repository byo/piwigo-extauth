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

class EAPUser extends EAPBase
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
			'" . pwg_db_real_escape_string($hostPlatform) . "',
			'" . pwg_db_real_escape_string($hostPlatformId) . "',
			NOW(),
			'" . pwg_db_real_escape_string($userInfo['name']) . "',
			'" . pwg_db_real_escape_string($userInfo['email']) . "'
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
		header('Location: ' . self::getUrl() . 'pending.php' );
		exit(0);
	}

	private static function login( $user_id )
	{
		log_user( $user_id, false );
		redirect( get_gallery_home_url() );
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
}

