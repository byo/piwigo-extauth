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
include_once('eapfacebook.class.php');
include_once('eapoauth2.class.php');
include_once('platforms.php');

class ExtAuth extends EAPBase
{
	public function __construct()
	{
		// Hook events
		add_event_handler('get_admin_plugin_menu_links',  array( $this, 'admin_menu' ) );
		add_event_handler('blockmanager_register_blocks', array( $this, 'register_menubar_blocks' ) );
		add_event_handler('blockmanager_apply',           array( $this, 'apply_menubar_blocks' ) );
	}
	
	// Add an entry to the 'Plugins' menu.
	public function admin_menu( $menu )
	{
		$menu[] = array(
			'NAME'  => 'External authentications',
			'URL'   => get_admin_plugin_menu_link(dirname(dirname(__FILE__))).'/admin.php'
		);
		return $menu;
	}

	// Register extra menubar block that will override the initial mbLogin one
	public function register_menubar_blocks( $mgr )
	{
		$mgr[0]->register_block( new RegisteredBlock( 'eapLogin', 'Connect with', 'eap' ) );
	}

	// Prepare out custom menu block to contain usual login form and extra login fields
	public function apply_menubar_blocks( $mgr )
	{
		global $PLATFORMS;

		// We only alter stuff if we're not logged in
		if ( !is_a_guest() ) return;

		$data = array('platforms'=>array());
		$anyEnabled = false;
		
		foreach( $PLATFORMS as $name => $info )
		{
			$enabled = self::getCfgValue( "{$name}_enabled", false );
			if ( $enabled )
			{
				$oauth = new EAPOauth2( $name );
				$data['platforms'][$name] = array(
					'info'     => $info,
					'loginUrl' => $oauth->getLoginUrl()
				);
				$anyEnabled = true;
			}
		}
		
		if ( !$anyEnabled ) return;

		if ( $block = &$mgr[0]->get_block( 'eapLogin' ) )
		{
			load_language( "plugin.lang", self::getPath() );
			
			$block->data = $data;
			$block->template = $this->getPath() . "templates/login.tpl";
		}
	}
}
