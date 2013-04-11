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
include_once('include/platforms.php');

$tplargs = array();

if ( isset($_POST['submit']) )
{
	$tplargs['updated'] = true;

	foreach( $PLATFORMS as $platform => $pinfo )
	{
		EAPBase::setCfgValues(array(
				"{$platform}_enabled" => isset( $_POST["{$platform}_enabled"] ) && $_POST["{$platform}_enabled"],
				"{$platform}_id"      => $_POST["{$platform}_id"],
				"{$platform}_secret"  => $_POST["{$platform}_secret"],
		));
	}
}

$tplargs[ 'platforms' ] = array();
foreach( $PLATFORMS as $platform => $pinfo )
{
	$tplargs[ 'platforms' ][ $platform ] = array(
		'name' => $pinfo['name'],
		"enabled" => EAPBase::getCfgValue( "{$platform}_enabled", false ),
		"id"      => EAPBase::getCfgValue( "{$platform}_id",      ''    ),
		"secret"  => EAPBase::getCfgValue( "{$platform}_secret",  ''    ),
	);
}

// Setup the template.
$template->assign('extauth',$tplargs);

