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
include_once(PHPWG_ROOT_PATH.'admin/include/tabsheet.class.php');

load_language( "plugin.lang", dirname(__FILE__).'/' );

$template->set_filename('plugin_admin_content', dirname(__FILE__).'/templates/admin.tpl');

if (!isset($_GET['tab']))
	$page['tab'] = 'config';
else
	$page['tab'] = $_GET['tab'];

$my_base_url = get_admin_plugin_menu_link(__FILE__);

$tabsheet = new tabsheet();
$tabsheet->add( 'config', l10n('Configuration'), add_url_params( $my_base_url, array('tab'=>'config') ) );
$tabsheet->add( 'assoc', l10n('Associate accounts'), add_url_params( $my_base_url, array('tab'=>'assoc') ) );
$tabsheet->select($page['tab']);

$tabsheet->assign();

$my_base_url = $tabsheet->sheets[ $page['tab'] ]['url'];
$template->set_filename( 'tab_data', dirname(__FILE__).'/templates/admin_'.$page['tab'].'.tpl' );
include_once( dirname(__FILE__).'/admin_'.$page['tab'].'.php');
$template->assign_var_from_handle( 'TAB_DATA', 'tab_data');
$template->assign_var_from_handle( 'ADMIN_CONTENT', 'plugin_admin_content');


