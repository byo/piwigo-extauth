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

