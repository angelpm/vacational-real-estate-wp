<?php
/*
 * Plugin Name:  	Vacational Real Estate
 * Plugin URI:  	https://robinwebdesign.wixsite.com/webdesign/
 * Description:  	Complete Real Estateplugin for Vacationals and Selling.
 * @version:    	1.0.0
 * Author:  		Angel Porras <robinwebdesign@hotmail.com>
 * Author URI: 		https://robinwebdesign.wixsite.com/webdesign/donate
 * @license:    	GPL2
 * @license URI: 	ttps://www.gnu.org/licenses/gpl-2.0.html
 * @package   		vacational-real-estate
 * Domain Path:	    /languages
*/
/*  Copyright 2019 Vacational Real Estate (email: robinwebdesign@hotmail.com)
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if( !defined( 'VRE_PLUGIN_ROOT' ) ) {
	define('VRE_PLUGIN_ROOT',plugin_dir_path( __FILE__ ));
}


/* Set constant path to the plugin directory. */
if( !defined( 'VRE_PLUGIN_ABSOLUTE' ) ) {
	define( 'VRE_PLUGIN_ABSOLUTE',  __FILE__  );
}

/* Set constant path to the plugin directory. */
if( !defined( 'VRE_PLUGIN_NAME' ) ) {
	define( 'VRE_PLUGIN_NAME','Vacational Real Estate'  );
}


/* Debug output control. */
if( !defined( 'VRE_DEBUG_OUTPUT' ) ) {
	define( 'VRE_DEBUG_OUTPUT', 0 );
}

/* Currently pligin version.. */
if( !defined( 'VRE_VERSION' ) ) {
	define( 'VRE_VERSION',  '1.0.0' );
}

/* Set constant for textdomain. */
if( !defined( 'VRE_TEXTDOMAIN' ) ) {
	define( 'VRE_TEXTDOMAIN',  'vacational-real-estate' );
}

/* Set constant for sale option. */
if( !defined( 'VRE_SALE_PROPERTY' ) ) {
	define( 'VRE_SALE_PROPERTY',  100 );
}

/* Set constant for rent option. */
if( !defined( 'VRE_RENT_PROPERTY' ) ) {
	define( 'VRE_RENT_PROPERTY',  101 );
}

/* Set constant for rent and sale option. */
if( !defined( 'VRE_RENT_SALE_PROPERTY' ) ) {
	define( 'VRE_RENT_SALE_PROPERTY',  102 );
}

/* Set constant for currency position  left option. */
if( !defined( 'VRE_CURRENCY_POSITION_LEFT' ) ) {
	define( 'VRE_CURRENCY_POSITION_LEFT',  103 );
}

/* Set constant for currency position  right option. */
if( !defined( 'VRE_CURRENCY_POSITION_RIGHT' ) ) {
	define( 'VRE_CURRENCY_POSITION_RIGHT',  104 );
}



/**
 * Load the textdomain of the plugin
 *
 * @return void
 */
function vre_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), VRE_TEXTDOMAIN );
	load_textdomain( VRE_TEXTDOMAIN, trailingslashit( WP_PLUGIN_DIR ) . VRE_TEXTDOMAIN . '/languages/' . VRE_TEXTDOMAIN . '-' . $locale . '.mo' );
}

add_action( 'plugins_loaded', 'vre_load_plugin_textdomain', 1 );

//We check if php version is correct
if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {

	function vre_deactivate_plugin() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}


	function vre_show_deactivation_notice() {
		echo wp_kses_post(
			sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				__( '"Plugin Name" requires PHP 5.6 or newer.', PN_TEXTDOMAIN )
			)
		);
	}

	add_action( 'admin_init', 'vre_deactivate_plugin' );
	add_action( 'admin_notices', 'vre_show_deactivation_notice' );
	// Return early to prevent loading the other includes.
	return;
}



//get property class file
require_once VRE_PLUGIN_ROOT . 'includes/class-vacational-real-estate.php';





/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {
	$plugin = new VRE_Vacational_Rental_Estate();
	$plugin->run();
}
run_plugin_name();
