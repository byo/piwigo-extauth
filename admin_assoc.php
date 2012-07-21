<?php

// Check whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once('include/eapbase.class.php');
include_once('include/eapuser.class.php');

if ( isset($_POST['submit']) )
{
	if ( isset($_POST['user']) )
	{	
		foreach( $_POST['user'] as $user )
		{
			if ( $user['user_id'] > 0 )
			{
				EAPUser::associateUser( $user['platform'], $user['id'], $user['user_id'] );
			}
		}
	}
}
		
// Setup the template
$template->assign('extauthpending', EAPUser::getPendingEntries() );
