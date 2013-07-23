<?php
// +-----------------------------------------------------------------------+
// | Piwigo - external authentication plugin                               |
// |                                 https://github.com/byo/piwigo-extauth |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2012-2013 Bartlomiej (byo) Swiecki                       |
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
include_once('LightOAuth2.php' );
include_once('eapbase.class.php');


class EAPOAuth2 extends EAPBase
{
	public function __construct( $platform )
	{
		$this->platform = $platform;
		$this->validatePlatform();
		$this->oauth = new LightOAuth2( $this->getClientId(), $this->getSecret() );
	}
	
	private function getRedirectUrl()
	{
		return EAP_URL . 'oauth2login.php?p=' . urlencode( $this->platform );
	}

	public function getLoginUrl()
	{
		$opts = $this->platformConfig( 'opts' );
		$opts[ 'state' ] = $this->generateNewCSRFState();

		$ret = $this->oauth->getAuthUrl(
			$this->platformConfig( 'authorize' ), 
			$this->getRedirectUrl(),
			$opts
		);
		return $ret;
	}

	public function grabLoginInfo()
	{
		try
		{
			// Protect against CSRF attack
			$this->validateCSRFState( $_GET['state'] );

			// get access token
			$obj = $this->oauth->getToken(
				$this->platformConfig( 'access_token' ),
				$this->getRedirectUrl(),
				$_GET['code'], 
				$this->platformConfig( 'response_type' )
			);
			$this->oauth->setToken($obj->access_token);
			
			$login_info = $this->platformConfig( 'login_info' );
			$response = json_decode( $this->oauth->fetch( $login_info['url'] ), true );
			if ( $response === null )
			{
				throw new Exception( "Invalid response from the login info" );
			}
			$ret = array();
			foreach( $login_info['fields'] as $src => $dst )
			{
				$ret[ $dst ] = $response[ $src ];
			}
			return $ret;
		}
		catch ( Exception $e )
		{
			error_log( "Exception: $e" );
			return FALSE;
		}
	}

	// Get the name of session variable that will store the CSRF
	protected function getCSRFStateVariableName()
	{
		return 'extauth_oauth2_state_' . $this->platform;
	}

	// Create new CSRF state value, store it in the session and return back
	protected function generateNewCSRFState()
	{
		$state = md5(uniqid(mt_rand(), true));
		pwg_set_session_var( $this->getCSRFStateVariableName(), $state );
		return $state;
	}

	// Validate the CSRF state
	protected function validateCSRFState( $state )
	{
		$saved = pwg_get_session_var( $this->getCSRFStateVariableName() );
		pwg_unset_session_var( $this->getCSRFStateVariableName() );
		if ( $saved !== $state )
		{
			throw new Exception( "Potential CSRF attack detected" );
		}
	}

	// Get Platform's client id
	private function getClientId()
	{
		return self::getCfgValue( $this->platform . '_id', '' );
	}

	// Get Platform's secret
	private function getSecret()
	{
		return self::getCfgValue( $this->platform . '_secret', '' );
	}
	
	private function platformConfig( $data )
	{
		return self::$platformConfig[ $this->platform ][ $data ];
	}
	
	private function validatePlatform()
	{
		if ( !isset( self::$platformConfig[ $this->platform ] ) )
		{
			die( "Invalid platform name: {$this->platform}" );
		}
	}

	private $platform;
	private $oauth;
	
	private static $platformConfig = array(
	
		// Facebook OAuth configuration
		'fb' => array(
			'authorize'     => 'https://graph.facebook.com/oauth/authorize',
			'access_token'  => 'https://graph.facebook.com/oauth/access_token',
			'opts'          => array(
				'scope'     => 'email'
			),
			'response_type' => 'url',
			'login_info'    => array(
				'url'       => 'https://graph.facebook.com/me',
				'fields'    => array(
					'id'    => 'id',
					'email' => 'email',
					'name'  => 'name'
				)
			)
		),
		
		// Google OAuth configuration
		'google' => array(
			'authorize'     => 'https://accounts.google.com/o/oauth2/auth',
			'access_token'  => 'https://accounts.google.com/o/oauth2/token',
			'opts'          => array(
				'scope'     => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
			),
			'response_type' => 'json',
			'login_info'    => array(
				'url'       => 'https://www.googleapis.com/oauth2/v1/userinfo',
				'fields'    => array(
					'id'    => 'id',
					'email' => 'email',
					'name'  => 'name'
				)
			)
		)
	);

}

