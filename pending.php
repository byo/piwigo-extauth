<?php
//----------------------------------------------------------- include
define('PHPWG_ROOT_PATH','../../');
include_once( PHPWG_ROOT_PATH.'include/common.inc.php' );

check_status(ACCESS_GUEST);

load_language( "plugin.lang", dirname(__FILE__).'/' );

$title= l10n('Approval pending');
$page['body_id'] = 'extAuthPending';

trigger_action('loc_begin_eap_pending');

$template->set_filename('eap_pending', realpath(dirname(__FILE__).'/templates/pending.tpl'));

// include menubar
$themeconf = $template->get_template_vars('themeconf');
if (!isset($themeconf['hide_menu_on']) OR !in_array('extAuthPending', $themeconf['hide_menu_on']))
{
	include( PHPWG_ROOT_PATH.'include/menubar.inc.php');
}

include(PHPWG_ROOT_PATH.'include/page_header.php');
include(PHPWG_ROOT_PATH.'include/page_messages.php');
$template->pparse('eap_pending');
include(PHPWG_ROOT_PATH.'include/page_tail.php');
