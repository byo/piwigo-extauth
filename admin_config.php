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

// Setup the template.
$template->assign('extauth',$tplargs);
