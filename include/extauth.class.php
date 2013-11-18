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
include_once('platforms.php');

class ExtAuth extends EAPBase
{
	public function __construct()
	{
		// Hook events
		add_event_handler('get_admin_plugin_menu_links',  array( $this, 'admin_menu' ) );
		add_event_handler('blockmanager_register_blocks', array( $this, 'register_menubar_blocks' ) );
		add_event_handler('blockmanager_apply',           array( $this, 'apply_menubar_blocks' ) );
		add_event_handler('loc_begin_identification',     array( $this, 'apply_login_page_blocks' ) );
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

	// Grab information to be used by templates
	private function get_template_data()
	{
		global $PLATFORMS;

		$data = array('platforms'=>array());
		$anyEnabled = false;

		foreach( $PLATFORMS as $name => $info )
		{
			$enabled = self::getCfgValue( "{$name}_enabled", false );
			if ( $enabled )
			{
				$data['platforms'][$name] = array(
					'info'     => $info,
					'loginUrl' => $this->getUrl() . $info[ 'loginScript' ]
				);
				$anyEnabled = true;
			}
		}

		if ( !$anyEnabled )
		{
			return FALSE;
		}

		return $data;
	}

	// Add block with extra login buttons
	public function apply_menubar_blocks( $mgr )
	{
                // We only alter stuff if we're not logged in
                if ( !is_a_guest() ) return;

		$data = $this->get_template_data();
		if ( $data === FALSE ) return;

		if ( $block = &$mgr[0]->get_block( 'eapLogin' ) )
		{
			load_language( "plugin.lang", self::getPath() );

			$block->data = $data;
			$block->template = $this->getPath() . "templates/login.tpl";
		}
	}

	// Add extra content to the login page with external auth buttons
	public function apply_login_page_blocks()
	{
		global $template, $conf;

		load_language( "plugin.lang", self::getPath() );

		$data = $this->get_template_data();
		if ( $data === FALSE ) return;

		// Load extra template data
		$template->assign(array(
			'EXTAUTH_DATA' => $data
		));

		// Code injection will be done in prefilter
		$template->set_prefilter( 'identification', 'ExtAuth::apply_login_page_blocks_prefilter' );
	}

	public static function apply_login_page_blocks_prefilter( $content )
	{
		$search = '<form';
		$extraContent = file_get_contents( EAP_PATH . '/templates/login_page.tpl' );
		return str_replace( $search, $extraContent . $search, $content );
	}
}
