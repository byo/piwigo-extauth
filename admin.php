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
global $template;

// Add our template to the global template
$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/templates/admin.tpl'
  )
);

$template->assign('extauth',$tplargs);

// Assign the template contents to ADMIN_CONTENT
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');


