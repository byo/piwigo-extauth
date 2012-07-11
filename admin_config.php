<?php

// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once('include/eapbase.class.php');

$tplargs = array();

if ( isset($_POST['submit']) )
{
	$tplargs['updated'] = true;

	EAPBase::setCfgValues(array(
			'fbEnabled' => isset( $_POST['fbEnabled'] ) && $_POST['fbEnabled'],
			'fbAppId'   => $_POST['fbAppId'],
			'fbSecret'  => $_POST['fbSecret']
	));
}

$tplargs[ 'fbEnabled' ] = EAPBase::getCfgValue( 'fbEnabled', false );
$tplargs[ 'fbAppId'   ] = EAPBase::getCfgValue( 'fbAppId',   ''    );
$tplargs[ 'fbSecret'  ] = EAPBase::getCfgValue( 'fbSecret',  ''    );

// Fetch the template.
$template->assign('extauth',$tplargs);
