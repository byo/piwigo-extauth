<?php


function plugin_install() {
	global $prefixeTable;
	
	$q = "
CREATE TABLE IF NOT EXISTS {$prefixeTable}ext_auth_users(
	`platform` VARCHAR(30) NOT NULL ,
	`id` VARCHAR(150) NOT NULL ,
	`user_id` VARCHAR(45) NULL ,
	PRIMARY KEY (`platform`, `id`) 
)";

	pwg_query( $q );
}

function plugin_activate() {
}

function plugin_uninstall() {
	global $prefixeTable;

	$q = "DROP TABLE {$prefixeTable}ext_auth_users";
	pwg_query($q);
}
