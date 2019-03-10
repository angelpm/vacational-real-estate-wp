<?php
/**
 * Uninstall file for delete files and tables
 *
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @author URI: https://webdesign.portfoliobox.net/
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */
 


	//options plugin to delete
	$option_name = 'vre_vacational_real_estate_options';
		 
	//delete options
	delete_option($option_name);
	 
	// for site options in Multisite
	delete_site_option($option_name);
	 
	// drop a custom database table
	global $wpdb;
	
	//delete tables
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}vreseasons");
	
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}vrepbookingwp");