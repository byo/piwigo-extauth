<?php

class EAPBase
{
	// Get the base path of the plugin
	protected static function getPath()
	{
		return EAP_PATH;
	}
	
	// Get the base url of the plugin
	protected static function getUrl()
	{
		return EAP_URL;
	}
	
	protected static function getConfig()
	{
		global $conf;
		
		if ( self::$_cfg === null )
		{
			if ( isset( $conf['ExtAuthPlugin'] ) )
			{
				self::$_cfg = unserialize( $conf['ExtAuthPlugin'] );
			}
		}
		
		if ( !is_array( self::$_cfg ) )
		{
			self::$_cfg = array();
		}
		
		return self::$_cfg;
	}
	
	public static function getCfgValue( $name, $default = null )
	{
		$cfg = self::getConfig();
		return isset( $cfg[$name] ) ? $cfg[$name] : $default;
	}
	
	public static function setCfgValues( $values )
	{
		self::getConfig();
		foreach( $values as $name => $value )
		{
			self::$_cfg[$name] = $value;
		}
		
		conf_update_param('ExtAuthPlugin', pwg_db_real_escape_string(serialize(self::$_cfg)));
	}
	
	private static $_cfg = null;
}

