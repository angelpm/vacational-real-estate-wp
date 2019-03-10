<?php
/**
 * The core plugin class.
 *
 * That is the Property Class to manage all features from a property
 *
 * @package   		vacational-real-estate
 * @subpackage 		vacational-real-estate/public
 * Author:  		Angel Porras <robinwebdesign@hotmail.com>
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
 * It's the main class that load and execute all requeriments.
 *
 * @class Creap_Real_Estate_WP
 * @version 1.0.0
 * @since 1.0.0
 */
class VRE_Vacational_Rental_Estate {




	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;




	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	private  $version;



	/**
	 * function for register  widget
	 *
	 * @since    1.0.0
	 * @access    public
	 */
	public function __construct()
	{
		if ( defined( 'VRE_VERSION' ) ) {
			$this->version = VRE_VERSION;
		} else {
			$this->version = '1.0.0';
		}

    //register activation and deactivation hooks
		register_activation_hook( __FILE__, 'activate_plugin_name' );
		register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

    //add filters for the template property page
		add_filter( 'template_include',array( $this,'property_template_include') );
		add_filter( 'post_thumbnail_html', array( $this,'disable_header_image_single_page'), 10, 3 );

		$this->plugin_path = VRE_PLUGIN_ROOT;
		$this->plugin_name = 'vacational-real-estate';



		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();


	}

	public function disable_header_image_single_page( $html, $post_id, $post_image_id ) {

		if(is_single() && (get_post_type()=='vre_property_cp')) {
			return '';
		} else

		return $html;
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-plugin-name-activator.php
	 */
	public function activate_plugin_name() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-vacational-real-estate-activator.php';
		VRE_Vacationa_Real_Estate_Activator::activate();
	}


	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-plugin-name-deactivator.php
	 */
	public function deactivate_plugin_name() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-vacational-real-estate-deactivator.php';
		VRE_Vacationa_Real_Estate_Deactivator::deactivate();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for loadingg required files
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vacational-rental-estate-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vacational-rental-estate-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vacational-rental-estate-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-vacational-rental-estate-public.php';

		/**
		 * The script responsive to load custom post type Property
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/class-property-post-type.php';

		/**
		 * The script responsive to load custom post type Booking
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-type/class-booking-post-type.php';

		//require_once plugin_dir_path( dirname( __FILE__ ) ) .'includes/class-vacational-real-calendar.php';

		$this->loader = new VRE_Vacationa_Real_Estate_Loader();
	}


	/**
	 * activate function.
	 * Create tables for booking and seasons
	 * @since    	1.0.0
	 * @access    	public
	 * @param 		string
	 */
	public function property_template_include( $template_path )
	{
		ob_start();

		$template_name='';

		if ( get_post_type() == 'vre_property_cp' ) {

			if ( is_single() ) {

				$template_name='single-property.php';

			}elseif(is_archive())
			{
				$template_name='archive-property.php';

			}elseif ( empty( $template_path ) ) {

				throw new Exception( "Template /templates/{$name}.php in plugin dir {$plugin_dir} not found." );

			}


			if ( $theme_file = locate_template( array( $template_name ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path =$this->plugin_path ."templates/{$template_name}";
			}
		}

		return $template_path;
	}



	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new VRE_Vacationa_Real_Estate_Textdomain_I18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new VRE_Vacationa_Real_Estate_Admin( $this->get_plugin_name(), $this->get_version() );

		$post_type_property = new vre_vacational_property_custom_post();
		//$post_type_booking = new vre_vacational_booking_custom_post();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'vre_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'vre_enqueue_scripts' );
		$this->loader->add_action( 'init', $post_type_property, 'vre_add_custom_post_property' );
		$this->loader->add_action( 'init', $post_type_booking, 'vre_booking_custompost_register' );


	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new VRE_Vacationa_Real_Estate_Public( $this->get_plugin_name(), $this->get_version() );
		//$my_widget = new Creap_booking_calendar_real_estate();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'creap_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'creap_enqueue_scripts' );


	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}


	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
