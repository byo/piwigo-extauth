<?php
//----------------------------------------------------------- include
define('PHPWG_ROOT_PATH','../../');
include_once( PHPWG_ROOT_PATH.'include/common.inc.php' );

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+
check_status(ACCESS_GUEST);

//----------------------------------------------------- template initialization
//
// Start output of page
//
$title= l10n('About Piwigo');
$page['body_id'] = 'extAuthPending';

trigger_action('loc_begin_eap_pending');

$template->set_filename('eap_pending', realpath(dirname(__FILE__).'/templates/pending.tpl'));

// include menubar
$themeconf = $template->get_template_vars('themeconf');
if (!isset($themeconf['hide_menu_on']) OR !in_array('theAboutPage', $themeconf['hide_menu_on']))
{
	include( PHPWG_ROOT_PATH.'include/menubar.inc.php');
}

include(PHPWG_ROOT_PATH.'include/page_header.php');
include(PHPWG_ROOT_PATH.'include/page_messages.php');
$template->pparse('eap_pending');
include(PHPWG_ROOT_PATH.'include/page_tail.php');
