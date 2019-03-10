<?php
/**
 * The admin section  functionality of the Complete Real Estate WP.
 *
 * Defines the options page, hooks, and variable for the manage of the Plugin
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @author URI: https://webdesign.portfoliobox.net/
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */


/**
 * Class for manage admin section of Complete Real Estate WP.
 * Description: Enqueue de scripts and styles, display de admin page
 *
 * @since    1.0.0
 */
class VRE_Vacationa_Real_Estate_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    1.0.0.
	 */
	private $version;

	/**
	 * The name of the options group.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $db_option    1.0.0.
	 */
	private $db_option = 'vre_propertywp_options';

	/**
	 * The name of the options group google API KEY.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $db_optionapikey    1.0.0.
	 */
	private $db_optionapikey = 'vre_options_googlemap_apikey';


	/**
	 * Initialize the class and add actions and filters
	 *
	 * @since 		1.0.0
	 * @access		public
	 * @param      	string    $plugin_name       Complete Real Estate WP plugin Wordpress
	 * @param      	string    $version    1.0.0.
	 */
	public function __construct( $plugin_name, $version )
	{

		//get the plugin name from parent
		$this->plugin_name = $plugin_name;

		//get the plugins version from parent
		$this->version = $version;

		// set default options
		$this->vre_get_options_plugin();

		require_once(VRE_PLUGIN_ROOT.'includes/class-vre-template-loader.php');

		require_once(VRE_PLUGIN_ROOT.'includes/widgets/class-vre-search-property-widget.php');

		//require_once(VRE_PLUGIN_ROOT.'includes/widgets/class-vre-last-properties-widget.php');

		require_once(VRE_PLUGIN_ROOT.'includes/class-vacational-real-estate-gallery.php');

		add_action( 'admin_menu', array( $this, 'vre_real_estate_admin_submenu') );

		add_action( 'admin_init', array( $this,'vre_bookingwp_register_settings' ));

		add_action('admin_enqueue_scripts', array( $this,'vre_enqueue_styles'));

		add_action('admin_enqueue_scripts', array( $this,'vre_enqueue_scripts'));

		add_filter('excerpt_more', array( $this,'vre_new_excerpt_more'));

		add_filter( 'excerpt_length',array( $this,'new_excerpt_length') );


		add_action( 'widgets_init', array( $this,'wpdocs_theme_slug_widgets_init') );


	}


	/**
	 * Change the show more from excerpt
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param    	string     $more       more from excerpt
	 * @return   	string		return 		new show more
	 */
	public function vre_new_excerpt_more($more)
	{
		global $post;

		return '...<a class="button" href="'. get_permalink($post->ID) . '">' . '<b>[+]</b>' . '</a>';
	}

  /**
   * Change the excerpt's length
   *
   * @since    	1.0.0
   * @access		public
   * @param    	string     $more       more from excerpt
   * @return   	string		return 		new show more
   */
	public function new_excerpt_length($length) {
		return 10;
	}


	public function wpdocs_theme_slug_widgets_init() {
		register_sidebar( array(
			'name'          => __( 'VRE Listing Page Sidebar', 'vacational-real-estate' ),
			'id'            => 'vre-propertylist-sidebar',
			'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'vacational-real-estate' ),
			'before_widget'  =>'',
			'after_widget'  =>'',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>',
		) );
	}

	/**
	 * Add menu to admin panel to access admin page  of the Complete Real Estate Plugin
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public function vre_real_estate_admin_submenu()
	{


		add_submenu_page(
			'edit.php?post_type=vre_property_cp',                 // parent slug
			'Settings',             // page title
			'Settings',             // sub-menu title
			'manage_options',                 // capability
			'prowp_main_menu', // your menu menu slug
			array($this,'vre_realestate_main_plugin_page')
		);

	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function vre_enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vre_propertywp-admin.css', array(), $this->version, 'all' );
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function vre_enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vre_propertywp-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script($this->plugin_name,'dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);

	}



	/**
	 * Display the Form Options of  Complete Real Estate WP plugin Wordpress
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public function vre_realestate_main_plugin_page()
	{

		if(current_user_can('manage_options'))
		{


			//Get options from Data Base if  they exists
			$vre_options = $this->vre_get_options_plugin();

			$vre_service_option=(!empty($vre_options['vre_service_option']))? $vre_options['vre_service_option']:'RS';

			$vre_calendar_option=(!empty($vre_options['vre_calendar_option']))? 'checked':"on";

			$vre_option_apikey=(!empty($vre_options['vre_option_apikey']))? $vre_options['vre_option_apikey']:"";

			$vre_number_decimals=(!empty($vre_options['vre_number_decimals']))? $vre_options['vre_number_decimals']:",";

			$vre_separator_thousand=(!empty($vre_options['vre_separator_thousand']))? $vre_options['vre_separator_thousand']:".";

			$vre_option_latitud=(!empty($vre_options['vre_option_latitud']))? $vre_options['vre_option_latitud']:"36.5967755";

			$vre_option_longitud=(!empty($vre_options['vre_option_longitud']))? $vre_options['vre_option_longitud']:"-4.632306699999958";

			$vre_option_zoom=(!empty($vre_options['vre_option_zoom']))? $vre_options['vre_option_zoom']:"8";

			$vre_action_url="";

			$vre_currency=(!empty($vre_options['vre_currency']))? $vre_options['vre_currency']:"&euro;";

			$vre_currency_position=!empty($vre_options['vre_currency_position'])? $vre_options['vre_currency_position']:"D";

			$vre_day_week=!empty($vre_options['vre_day_week'])? $vre_options['vre_day_week']:"Lunes";

			if ( isset($_POST['vre_submitted']) )
			{

				//check form security
				check_admin_referer('vre-complete-realestate');



					//if vre_service_option checked, save option
					if(!empty($_POST['vre_service_option']) && count($_POST['vre_service_option'])>0)
					{
						$vre_options['vre_service_option']=sanitize_text_field( $_POST['vre_service_option'][0] );;
						$vre_service_option=$vre_options['vre_service_option'];
					}else
					{
						$vre_options['vre_service_option']='';
						$vre_service_option='';
					}



					//if vre_calendar_option checked, save option
					if(!empty($_POST['vre_calendar_option']) && (sanitize_text_field($_POST['vre_calendar_option'])=="on"))
					{
						$vre_options['vre_calendar_option']=sanitize_text_field($_POST['vre_calendar_option']);
						$vre_calendar_option=$vre_options['vre_calendar_option']=='on'?'checked':'';
					}else
					{
						$vre_options['vre_calendar_option']='';
						$vre_calendar_option='';
					}

					//if vre_option_apikey checked, save option
					if(!empty($_POST['vre_option_apikey']) )
					{
						$vre_options['vre_option_apikey']= sanitize_text_field($_POST['vre_option_apikey']);
						$vre_option_apikey= $vre_options['vre_option_apikey'];
					}else
					{
						$vre_options['vre_option_apikey']='';
						$vre_option_apikey='';
					}

					//if vre_option_latitud checked, save option
					if(!empty($_POST['vre_option_latitud']) )
					{
						$vre_options['vre_option_latitud']= sanitize_text_field($_POST['vre_option_latitud']);
						$vre_option_latitud= $vre_options['vre_option_latitud'];
					}else
					{
						$vre_options['vre_option_latitud']='';
						$vre_option_latitud='';
					}

					//if vre_option_longitud checked, save option
					if(!empty($_POST['vre_option_longitud']))
					{
						$vre_options['vre_option_longitud']= sanitize_text_field($_POST['vre_option_longitud']);
						$vre_option_longitud= $vre_options['vre_option_longitud'];
					}else
					{
						$vre_options['vre_option_longitud']='';
						$vre_option_longitud='';
					}

					//if vre_option_zoom checked, save option
					if(!empty($_POST['vre_option_zoom']) )
					{
						$vre_options['vre_option_zoom']= sanitize_text_field($_POST['vre_option_zoom']);
						$vre_option_zoom= $vre_options['vre_option_zoom'];
					}else
					{
						$vre_options['vre_option_zoom']='';
						$vre_option_zoom='8';
					}

					//if currency checked, save option
					if( !empty($_POST['vre_currency']))
					{
						$vre_options['vre_currency']= sanitize_text_field($_POST['vre_currency']);
						$vre_currency= $vre_options['vre_currency'];
					}else
					{
						$vre_options['vre_currency']='';
						$vre_currency='';
					}

					//if currency_pos checked, save option
					if( !empty($_POST['vre_currency_position']))
					{
						$vre_options['vre_currency_position']= sanitize_text_field($_POST['vre_currency_position']);
						$vre_currency_position= sanitize_text_field($_POST['vre_currency_position']);
					}else
					{
						$vre_options['vre_currency_position']='D';
						$vre_currency_position='D';
					}

					//if currency_pos checked, save option
					if( !empty($_POST['vre_separator_thousand']))
					{
						$vre_options['vre_separator_thousand']= sanitize_text_field($_POST['vre_separator_thousand']);
						$vre_separator_thousand= sanitize_text_field($_POST['vre_separator_thousand']);
					}else
					{
						$vre_options['vre_separator_thousand']='.';
						$vre_separator_thousand='.';
					}

					if( !empty($_POST['vre_number_decimals']))
					{
						$vre_options['vre_number_decimals']= sanitize_text_field($_POST['vre_number_decimals']);
						$vre_number_decimals= sanitize_text_field($_POST['vre_number_decimals']);
					}else
					{
						$vre_options['vre_number_decimals']='0';
						$vre_number_decimals='0';
					}



					if(!empty($_POST['vre_day_week']))
					{
						$vre_options['vre_day_week']= sanitize_text_field($_POST['vre_day_week']);
						$vre_day_week= sanitize_text_field($_POST['vre_day_week']);
					}else
					{
						$vre_options['vre_day_week']='';
						$vre_day_week='';
					}

					//print_r($vre_currency_position);die();
				//update new values to data base
				update_option($this->db_option, $vre_options);

				//Display result
				echo '<div class="updated fade"><p>';
				_e('Save Successfully','vacational-real-estate');
				echo '</p></div>';
			}

			// URL for form submit, equals our current page
			//$vre_action_url = $_SERVER['REQUEST_URI'];
			$template_path=VRE_Vacationa_template_loader::vre_locate_template('admin-content.php','admin');


			if(!empty($template_path))
			{
				include($template_path);
			}

		}else
		{
			wp_die('Sorry, you do not have sufficient permissions to acces this page.');
		}

	}

	/**
	 * Display the Form Options of  Complete Real Estate WP plugin Wordpress
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public function vre_get_options_plugin()
	{

		// default values
		$vre_options = array
		(

			'vre_service_option' => '',
			'vre_rent_option' => '',
			'vre_both_option' => 'on',
			'vre_calendar_option'=>'',
			'vre_option_apikey'=>'',
			'vre_currency'=>'',
			'vre_currency_position'=>'',
			'vre_day_week'=>'Lunes'
		);

		// get saved options
		$saved = get_option($this->db_option);

			// assign them
			if (!empty($saved))
			{
				foreach ($saved as $key => $option)
				$vre_options[$key] = $option;
			}

			// update the options if necessary
			if ($saved != $vre_options)
			{
				update_option($this->db_option, $vre_options);
			}

		//return the options
		return $vre_options;
	}

	/**
	 * Create options manager for admin page
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public function vre_install()
	{
		// set default options
		$this->vre_get_options_plugin();
	}


	/**
	 * Create options manager for admin page
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public function vre_bookingwp_register_settings()
	{

		register_setting( 'vre-prowp-settings-group', 'vre_prowp_options','vre_propertywp_sanitize_options' );

	}

	/**
	 * Satinize inputs from options admin page
	 *
	 * @since    	1.0.0
	 * @access		public
	 */
	public function vre_propertywp_sanitize_options( $input )
	{
		$input['vre_service_option'] =sanitize_text_field( $input['vre_service_option'] );
		$input['vre_rent_option'] =sanitize_text_field( $input['rent_option'] );
		$input['vre_both_option'] =sanitize_text_field( $input['vre_both_option'] );
		$input['vre_option_apikey'] =sanitize_text_field( $input['vre_option_apikey'] );

		return $input;
	}







}
