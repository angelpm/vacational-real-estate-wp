<?php

/**
 * Main class for public front side
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package   		vacational-real-estate
 * @subpackage 		vacational-real-estate/public
 * Author:  		Angel Porras <robinwebdesign@hotmail.com>
 */

class VRE_Vacationa_Real_Estate_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	public $plugin_name;
	
	
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    1.0.0.
	 */
	private $version;
	
	
	
	/**
	* Construct function 
	* @param string $plugin_name plugin name
	* @param string $version plugin version plugin
	*/
	public function __construct($plugin_name, $version) 
	{
			if ( defined( 'CREAP_COMPLETE_REAL_ESTATE_VER' ) ) {
				$this->version = CREAP_COMPLETE_REAL_ESTATE_VER;
			} else {
				$this->version = $version;
			}
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		//$this->creap_load_dependencies();
		
		add_action('wp_enqueue_scripts', array( $this,'vre_enqueue_styles'));
		
		add_action('wp_enqueue_scripts', array( $this,'vre_enqueue_scripts'));
		
		add_image_size( 'vre-property-img-full', 720, 540 ); 
	}
	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function vre_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vre_propertycss.css', array(), $this->version, 'all' );
	}
	
	
	/**
	 * Register the JavaScript for the admin area and ajax.
	 *
	 * @since    1.0.0
	 */
	public function vre_enqueue_scripts() {
	
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vre_property-public.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script($this->plugin_name,'dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
		
		
	}
	

	
}



