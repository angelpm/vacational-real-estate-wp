<?php
/**
 * Class Calendar for Complete Real Estate WP
 *
 * Add calendar widget with bookings, seasons and price
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @author URI: https://webdesign.portfoliobox.net/
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */
 
 
class VRE_Vacationa_Real_Estate_Activator {
	
	
	/**
	 * activate function.
	 * Create tables for booking and seasons
	 * @since    1.0.0
	 * @access    public
	 */
	public static function activate() 
	{
	
		//Creamos las tablas de
		global $wpdb;
		
		$prefix=$wpdb->get_blog_prefix();
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix.'vreseasons';
	
			//We create season table
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				$table_name = $wpdb->prefix . 'vreseasons';
				
				$creation_query =
				'CREATE TABLE `'.$table_name.'` (
				  `season_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
				  `name` text NOT NULL,
				  `post_id` int(11) NOT NULL,
				  `pricepernight` int(11) NOT NULL,
				  `minimun` smallint(6) NOT NULL,
				  `date_from` date NOT NULL,
				  `date_to` date NOT NULL,
				  `strtotime_from` int(11) NOT NULL,
				  `strtotime_to` int(11) NOT NULL,
				  `anual` boolean,
				  	PRIMARY KEY (season_id)
				) '.$charset_collate.';';
				
				$wpdb->query( $creation_query );
				
			}
			
			$table_name = $wpdb->prefix.'vrebookingwp';
			//We create season table
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
			{
				
				
				$creation_query =
				'CREATE TABLE `'.$table_name.'` (
				  `booking_id` int(11) NOT NULL UNIQUE AUTO_INCREMENT,
				  `season_id`  int(11) NOT NULL,
				  `user_id`  int(11) NOT NULL,
				  `phone`  text NOT NULL,
				  `property_id` int(11) NOT NULL,
				  `pricexnight` smallint(6) NOT NULL,
				  `date_from` date NOT NULL,
				  `date_to` date NOT NULL,
				  `month` smallint(6) NOT NULL,
				  `year` smallint(6) NOT NULL,
				  	PRIMARY KEY (season_id)
				) '.$charset_collate.';';
				
				$wpdb->query( $creation_query );
				
			}
			
			
			//generate default options for admin page
			$vre_options=array(
				'vre_service_option'=>'RS',
				'vre_number_decimals'=>0,
				'vre_calendar_option'=>'',
				'vre_currency_position'=>'D',
				'vre_separator_thousand'=>'.',
				'vre_option_latitud'=>'36.596776',
				'vre_option_longitud'=>'-4.632307',
				'vre_option_zoom'=>8,
				'vre_currency'=>'&euro;',
				'vre_currency_position'=>'D',
				'vre_day_week'=>'L',
			
			);
			
			update_option( 'vre_propertywp_options', $vre_options );
	}
	
}