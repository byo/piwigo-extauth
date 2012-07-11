<?php

// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include_once('eapbase.class.php');
include_once('eapfacebook.class.php');

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
		// We only alter stuff if we're not logged in
		if ( !is_a_guest() ) return;
		
		$fbEnabled = self::getCfgValue( 'fbEnabled', false );
		
		if ( !$fbEnabled ) return;

		if ( $block = &$mgr[0]->get_block( 'eapLogin' ) )
		{
			$block->data = array();
			
			$block->data['fbEnabled'] = $fbEnabled;
			if ( $fbEnabled )
			{
				$block->data['fbLoginUrl'] = EAPFacebook::getLoginUrl();
			}
			$block->template = $this->getPath() . "templates/login.tpl";
		}

	}
}
