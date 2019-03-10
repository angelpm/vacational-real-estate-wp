<?php
/**
 * Uninstall class
 * Delete tables and options
 *
 * @package    vacational-real-estate
 * @subpackage vacational-real-estate/includes
 * @author     Your Name <email@example.com>
 */

 
class Creap_Property_WP_uninstall {


	 /**
	 * Uninstall function of the plugin.
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public static function uninstall() 
	{
	
		$option_name = 'vre_propertywp_options';
		 
		delete_option($option_name);
		 
		// for site options in Multisite
		delete_site_option($option_name);
		 
		// drop a custom database table
		global $wpdb;
		
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}vreseasons");
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}vrebookingwp");
					
	
	}
	
}