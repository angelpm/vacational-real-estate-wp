<?php
/**
 * Custom Post Type vre_property_cp
 *
 * Define the arguments for register the custom post vre_property_cp
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */


 class vre_vacational_property_custom_post
 {

	 public function __construct()
   {

 	  add_action( 'init', array($this,'vre_add_custom_post_property' ));
		add_action('add_meta_boxes',  array($this,'vre_add_custom_meta_generaldata'));
		add_action('add_meta_boxes', array($this,'vre_add_custom_meta_direccion'));
		add_action('add_meta_boxes', array($this,'vre_add_custom_meta_calendarbook'));
		add_action('add_meta_boxes', array($this,'vre_add_custom_meta_seasons'));

		add_action( 'save_post', array($this,'vre_savemeta_address' ));
		add_action( 'save_post', array($this,'vre_save_season_function') );
		add_action( 'save_post', array($this,'vre_property_savemeta_generaldata') );
		add_action( 'save_post', array($this,'creap_save_seasons_property') );


		add_action( 'init',array($this, 'vre_cities_hierarchical_taxonomy') );
		add_action( 'init', array($this,'vre_topics_hierarchical_taxonomy'));
	}


  /**
   * Function to register labels and arguments for the custom post property
   *
   * @since    1.0.0
   */
	public  function  vre_add_custom_post_property()
	{
		//Labels for custom post
		$labels = array(
				'name'                	=> _x( 'Properties' , 'Post Type General Name', 'vacational-real-estate' ),
				'singular_name'       	=> _x( 'Property' , 'Post Type Singular Name', 'vacational-real-estate' ),
				'menu_name'          	=> _x( 'Property' , 'Admin Menu Text', 'vacational-real-estate' ),
				'name_admin_bar'       	=> _x( 'Property' , 'Add New on Toolbar', 'vacational-real-estate' ),
				'parent_item_colon'   	=> __( 'Property' , 'vacational-real-estate' ),
				'view_item'           	=> __( 'View Properties' , 'vacational-real-estate' ),
				'add_new'            	=> __( 'Add New' , 'vacational-real-estate' ),
				'add_new_item'        	=> __( 'New Property' , 'vacational-real-estate' ),
				'edit_item'           	=> __( 'Edit Property' , 'vacational-real-estate' ),
				'update_item'         	=> __( 'Update Property' , 'vacational-real-estate' ),
				'all_items'           	=> __( 'All Properties' , 'vacational-real-estate' ),
				'search_items'        	=> __( 'Search Property' , 'vacational-real-estate' ),
				'not_found'           	=> __( 'No Properties Found' , 'vacational-real-estate' ),
				'not_found_in_trash'  	=> __( 'No Properties Found in Trash' , 'vacational-real-estate' ),
				'featured_image'      	=> __( 'Set cover image' , 'vacational-real-estate' ),
				'remove_featured_image' => __( 'Remove cover image' , 'vacational-real-estate' ),
				'use_featured_image'    => __( 'Use as cover image' , 'vacational-real-estate' ),
				'archives'       		=> __( 'Property archives' ,  'vacational-real-estate' ),
				'insert_into_item'      => __( 'Insert into property' , 'vacational-real-estate' ),
			);

		//Labels for custom post
		$args = array(

			'labels'              => $labels,

			'supports'            => array( 'title', 'editor', 'author','thumbnail' ),

			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 15,
			'menu_icon'           => 'dashicons-admin-home',
			'can_export'          => true,
			'has_archive'         => true,
			'rewrite' 			  => array( 'slug' => 'property' ),

			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'taxonomies'          => array( 'category' ),
		);

		// Registering your Custom Post Type
		register_post_type( 'vre_property_cp', $args );

		flush_rewrite_rules();
	}

	public function vre_add_custom_meta_direccion()
	{
		 add_meta_box(
			   'section2propiedad',       // $id
			   __('Datos Direccion'),                  // $title
			   array($this,'vre_display_section1_datosdireccion'),  // $callback
			   'vre_property_cp',                 // $page
			   'normal',                  // $context
			   'high'                     // $priority
		   );
	}



	/**
	* Add section general data
	*
	* @since    1.0.0
	*/
	public function vre_add_custom_meta_generaldata()
	{

	   add_meta_box(
		   'section1property',       // $id
		   __('General Data'),                  // $title
		   array($this,'vre_display_section1_generaldata'),  // $callback
		   'vre_property_cp',                 // $page
		   'normal',                  // $context
		   'high'                     // $priority
	   );


	}




	/**
	 * Function for save form general data
	 *
	 * @since    1.0.0
	 * @param      object    $post    Actual post
	 */
	public function vre_property_savemeta_generaldata( $post_id )
	{

		// if auto saving skip saving our meta box data
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

		// process form data if $_POST is set
		if( isset( $_POST['prowp_save_meta_box'] ) && !wp_verify_nonce($_POST['prowp_save_meta_box'],basename( __FILE__ )) )
		{

			global $post;
			$post_type = get_post_type_object( $post->post_type );

			// Check if the current user has permission to edit the post.
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return $post_id;
			}


			if ( ! empty($_POST['vre_property_service']) )
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_property_service',sanitize_text_field( $_POST['vre_property_service'] ) );
			}

			if ( ! empty($_POST['vre_reference']) )
			{
				update_post_meta( $post_id, 'vre_reference',sanitize_text_field( $_POST['vre_reference'] ) );
			}

			if ( ! empty($_POST['vre_prop_mtconstruido']) )
			{
				update_post_meta( $post_id, 'vre_prop_mtconstruido',sanitize_text_field( $_POST['vre_prop_mtconstruido'] ) );
			}

			if ( ! empty($_POST['vre_prop_mttotal']) )
			{
				update_post_meta( $post_id, 'vre_prop_mttotal',sanitize_text_field( $_POST['vre_prop_mttotal'] ) );
			}

			if ( ! empty($_POST['vre_prop_year']) )
			{
				update_post_meta( $post_id, 'vre_prop_year',sanitize_text_field( $_POST['vre_prop_year'] ) );
			}

			if ( ! empty($_POST['property_orientacion']) )
			{
				update_post_meta( $post_id, 'property_orientacion',sanitize_text_field( $_POST['property_orientacion'] ) );
			}

			if ( ! empty($_POST['creap_prop_ocupado']) )
			{
				update_post_meta( $post_id, 'creap_prop_ocupado',sanitize_text_field( $_POST['creap_prop_ocupado'] ) );
			}else
			{
				update_post_meta( $post_id, 'creap_prop_ocupado','off' );
			}

			if ( ! empty($_POST['vre_property_rooms']) )
			{
				update_post_meta( $post_id, 'vre_property_rooms',sanitize_text_field( $_POST['vre_property_rooms'] ) );
			}

			if ( isset($_POST['property_terraza']) )
			{
				update_post_meta( $post_id, 'property_terraza',sanitize_text_field( $_POST['property_terraza'] ) );
			}else
			{
				update_post_meta( $post_id, 'property_terraza','off' );
			}

			if ( ! empty($_POST['vre_ckrent_holidays']) )
			{
				update_post_meta( $post_id, 'vre_ckrent_holidays',sanitize_text_field( $_POST['vre_ckrent_holidays'] ) );
			}else
			{
				update_post_meta( $post_id, 'vre_ckrent_holidays','off' );
			}

			if ( ! empty($_POST['vre_ckrent_month']) )
			{
				update_post_meta( $post_id, 'vre_ckrent_month',sanitize_text_field( $_POST['vre_ckrent_month'] ) );
			}else
			{
				update_post_meta( $post_id, 'vre_ckrent_month','off' );
			}

			if ( ! empty($_POST['creap_property_parking']) )
			{
				update_post_meta( $post_id, 'creap_property_parking',sanitize_text_field( $_POST['creap_property_parking'] ) );
			}


			if ( ! empty($_POST['vre_prop_deshabilitado']) && ($_POST['vre_prop_deshabilitado']=="on") )
			{//
				update_post_meta( $post_id, 'vre_prop_deshabilitado',sanitize_text_field( $_POST['vre_prop_deshabilitado'] ) );
			}else
			{
				update_post_meta( $post_id, 'vre_prop_deshabilitado',"off" );
			}

			if ( ! empty($_POST['vre_property_reduced']) && ($_POST['vre_property_reduced']=="on"))
			{
				update_post_meta( $post_id, 'vre_property_reduced',sanitize_text_field( $_POST['vre_property_reduced'] ) );
			}else
			{
				update_post_meta( $post_id, 'vre_property_reduced',"off" );
			}

			if ( ! empty($_POST['property_noche']) && is_numeric($_POST['property_noche']) )
			{
				update_post_meta( $post_id, 'property_noche',sanitize_text_field( $_POST['property_noche'] ) );
			}

			if ( ! empty($_POST['vre_property_price_night']) && is_numeric($_POST['vre_property_price_night']) )
			{
				update_post_meta( $post_id, 'vre_property_price_night',sanitize_text_field( $_POST['vre_property_price_night'] ) );
			}

			if ( ! empty($_POST['property_precioper']) && is_numeric($_POST['property_precioper']))
			{
				update_post_meta( $post_id, 'property_precioper',sanitize_text_field( $_POST['property_precioper'] ) );
			}

			if ( ! empty($_POST['vre_property_price_month']) && is_numeric($_POST['vre_property_price_month']))
			{
				update_post_meta( $post_id, 'vre_property_price_month',sanitize_text_field( $_POST['vre_property_price_month'] ) );
			}

			if ( ! empty($_POST['vre_property_pricesale']) )
			{
				update_post_meta( $post_id, 'vre_property_pricesale',sanitize_text_field( $_POST['vre_property_pricesale'] ) );
			}

			if ( ! empty($_POST['vre_property_banos']) )
			{
				update_post_meta( $post_id, 'vre_property_banos',sanitize_text_field( $_POST['vre_property_banos'] ) );
			}





		}
	}


	public function vre_display_section1_datosdireccion()
{
	//global variable
	global $post;

	/* generate the security code for the form */
	wp_nonce_field( plugin_basename( __FILE__ ), 'prowp_save_meta_box' );

	//get options from data base
	$prop_country=get_post_meta($post->ID, 'vre_prop_country', true);
	$vre_prop_route=get_post_meta($post->ID, 'vre_prop_route', true);
	$vre_prop_locality=get_post_meta($post->ID, 'vre_prop_locality', true);
	$vre_prop_postal_code=get_post_meta($post->ID, 'vre_prop_postal_code', true);
	$prop_postal_code=get_post_meta($post->ID, 'vre_prop_postal_code', true);
	$prop_lat=get_post_meta($post->ID, 'vre_prop_lat', true);
	$prop_lng=get_post_meta($post->ID, 'vre_prop_lng', true);
	$prop_level_1=get_post_meta($post->ID, 'vre_prop_level_1', true);
	$prop_level_2=get_post_meta($post->ID, 'vre_prop_level_2', true);
	$opt_plugin=get_option('vre_propertywp_options');
	$show_map=false;


		if(isset($opt_plugin['vre_option_apikey']) && ($opt_plugin['vre_option_apikey']))
		{
			$show_map=true;
		}

	// URL for form submit, equals our current page
	//$vre_action_url = $_SERVER['REQUEST_URI'];
	$template_path=VRE_Vacationa_template_loader::vre_locate_template('map-content.php','post-type');


		if(!empty($template_path))
		{
			include($template_path);
		}


	}


	/**
	 * Display calendars with season task
	 *
	 * @since    1.0.0
	 */
	public function vre_show_section_calendarbook()
	{

		//global variables
		global $post;
		global $wpdb;

		// generate the security code for the form
		wp_nonce_field( plugin_basename( __FILE__ ), 'prowp_save_meta_box' );

		//get differents seasons for the actual property
		$seasons = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vreseasons WHERE post_id = '".$post->ID."'");

		//initialize variables
		$price_base=0;
		$seasonbid=0;
		$seasonstmp=array();


			//we structure the seasons array and prices
			foreach($seasons as $season)
			{
				if($season->name=='price_base')
				{
					$price_base=$season->pricepernight;
					$seasonbid=$season->season_id;
				}else
				{
					$seasonstmp[]=$season;
				}

			}

		//get calendar class file
		require_once( plugin_dir_path( __DIR__ )  . 'class-vacational-real-calendar.php' );

		//instance de claendar class object
		//$my_widget = new Creap_booking_calendar_real_estate();


		// URL for form submit, equals our current page
		//$vre_action_url = $_SERVER['REQUEST_URI'];
		$template_path=VRE_Vacationa_template_loader::vre_locate_template('seasonbooking-content.php','post-type');


		if(!empty($template_path))
		{
			include($template_path);
		}


	}


	/**
	* Display the form for General data
	*
	* @since    1.0.0
	*/
	public function vre_display_section1_generaldata()
	{
		global $post;

		//get actual property service (Sell, Rent, Both)
		$prowp_service=get_post_meta($post->ID, 'vre_property_service', true);

		//get actual property reference
		$vre_prop_referencia=get_post_meta($post->ID, 'vre_prop_referencia', true);

		//get actual property surface
		$vre_prop_mtconstruido=get_post_meta($post->ID, 'vre_prop_mtconstruido', true);

		//get actual property total surface
		$vre_prop_mttotal=get_post_meta($post->ID, 'vre_prop_mttotal', true);

		//get actual property year
		$vre_prop_year=get_post_meta($post->ID, 'vre_prop_year', true);

		//get actual property disabled
		$vre_prop_deshabilitado=get_post_meta($post->ID, 'vre_prop_deshabilitado', true);

		//get actual property orientation
		$property_orientacion=get_post_meta($post->ID, 'property_orientacion', true);

		//get actual property ocupated
		$creap_prop_ocupado=get_post_meta($post->ID, 'creap_prop_ocupado', true);

		//get actual property saleprice
		$vre_property_pricesale=get_post_meta($post->ID, 'vre_property_pricesale', true);

		//get actual property  parking
		$creap_property_parking=get_post_meta($post->ID, 'creap_property_parking', true);

		//get actual property terrace
		$property_terraza=get_post_meta($post->ID, 'property_terraza', true);

		//get actual property offer
		$vre_property_reduced=get_post_meta($post->ID, 'vre_property_reduced', true);

		//get actual property price per night
		$property_noche=get_post_meta($post->ID, 'property_noche', true);

		//get actual property price extra person
		$property_precioper=get_post_meta($post->ID, 'property_precioper', true);

		//get actual property number rooms
		$vre_property_rooms=get_post_meta($post->ID, 'vre_property_rooms', true);

		//get actual property number baths
		$vre_property_banos=get_post_meta($post->ID, 'vre_property_banos', true);

		//get actual property price per night
		$vre_property_price_night=get_post_meta($post->ID, 'vre_property_price_night', true);

		//get actual property price per night
		$vre_property_price_month=get_post_meta($post->ID, 'vre_property_price_month', true);

		//get actual property price per night
		$vre_ckrent_holidays=get_post_meta($post->ID, 'vre_ckrent_holidays', true);

		//get actual property price per night
		$vre_ckrent_month=get_post_meta($post->ID, 'vre_ckrent_month', true);

		$options = get_option('vre_propertywp_options');

		//nonce for security
		wp_nonce_field( 'name_of_my_action','name_of_nonce_field' );

		?>

		<table cellpadding="10px" width="100%">
		<tr>
			<td> <?php  _e('Servicio','vacational-real-estate');?><br />
				<select name="vre_property_service" id="vre_property_service">

                    <option value="<?php echo VRE_RENT_PROPERTY; ?>" <?php echo selected( $prowp_service, VRE_RENT_PROPERTY, false ) ?> >
                        <?php _e('Rent','vacational-real-estate');?>
                    </option>

                    <option value="<?php echo VRE_SALE_PROPERTY; ?>" <?php echo selected( $prowp_service, VRE_SALE_PROPERTY, false )?> >
                        <?php _e('Sale','vacational-real-estate');?>
                    </option>

                    <option value="<?php echo VRE_RENT_SALE_PROPERTY; ?>" <?php echo selected( $prowp_service, VRE_RENT_SALE_PROPERTY, false )?> >
                        <?php _e('Rent and Sell','vacational-real-estate');?>
                    </option>
                </select>
			</td>
			<td>
				<?php _e('Superficie construida','vacational-real-estate'); ?><br />
				<input type="text" name="vre_prop_mtconstruido"  value="<?php esc_html_e( $vre_prop_mtconstruido,'vacational-real-estate'); ?>">
			</td>
			<td>
				<?php _e('Dormitorios','vacational-real-estate'); ?><br />
				<input type="text" name="vre_property_rooms"  value="<?php esc_html_e($vre_property_rooms,'vacational-real-estate'); ?>" >
			</td>
			<td>
				<?php

				?>
				<label for="vre_prop_year"><?php _e('Deshabilitado','vacational-real-estate'); ?> <input type="checkbox" name="vre_prop_deshabilitado" <?php checked( $vre_prop_deshabilitado, "on" ); ?>  /> </label>
			</td>
		 </tr>
		 <tr>
			<td>
			<?php _e('Referencia','vacational-real-estate'); ?><br />
				<input type="text" name="vre_prop_referencia" value="<?php esc_html_e($vre_prop_referencia,'vacational-real-estate'); ?>" >
			</td>
			<td>
				<?php _e('Superficie Total:','vacational-real-estate'); ?><br />
				<input type="text" name="vre_prop_mttotal"  value="<?php esc_html_e($vre_prop_mttotal,'vacational-real-estate'); ?>">
			</td>
			<td>
				<?php _e('Plazas Parking:','vacational-real-estate'); ?><br />
				<input type="text" name="creap_property_parking"  value="<?php esc_html_e($creap_property_parking,'vacational-real-estate'); ?>" >
			</td>
			<td>

				<label for="vre_property_reduced">
				<?php _e('Rebajado: ','vacational-real-estate'); ?>
				<input type="checkbox" name="vre_property_reduced"  <?php checked( $vre_property_reduced, "on" ); ?> ></label>
			</td>
		 </tr>
		 <tr>
			<td>
				<label for="creap_prop_ocupado">
				<?php _e('Ocupado','vacational-real-estate');

					$checked='checked="checked"';
					?>
				<input type="checkbox" name="creap_prop_ocupado"  <?php checked( $creap_prop_ocupado, "on" ); ?>></label>
			</td>
			<td>
				<?php _e('A&ntilde;o de Construcci&oacute;n:','vacational-real-estate');?><br />
				<input type="text" name="vre_prop_year" value="<?php esc_html_e($vre_prop_year,'vacational-real-estate'); ?>" >
			</td>
			<td>
				<?php _e('Orientacion: ','vacational-real-estate'); ?><br />
				<select name="property_orientacion" id="property_orientacion">
					<option value="" <?php selected( $property_orientacion, '', false ) ?>> <?php __('') ?>
					</option>
					<option value="Norte" <?php echo selected( $property_orientacion, 'Norte', false )?> > <?php _e('Norte','vacational-real-estate'); ?>
					</option>
					<option value="Sur" <?php echo selected( $property_orientacion, 'Sur', false )?> ><?php _e('Sur','vacational-real-estate'); ?>
					</option>
					<option value="Este" <?php echo selected( $property_orientacion, 'Este', false ) ?>><?php _e('Este','vacational-real-estate');?>
					</option>
					<option value="Oeste" <?php echo selected( $property_orientacion, 'Oeste', false ) ?>><?php _e('Oeste','vacational-real-estate'); ?>
					</option>
				</select>
			</td>
			<td>
			<?php _e('Suplem./Persona Extra: ','vacational-real-estate'); ?> <?php if(isset($options['vre_currency'])) echo($options['vre_currency']); ?><br />
				<input type="text" name="property_precioper"  value="<?php esc_html_e($property_precioper,'vacational-real-estate'); ?>" >

			</td>
		 </tr>
		 <tr>
			<td>
            <label for="vre_property_banos">
				<?php _e('Ba&ntilde;os: ','vacational-real-estate'); ?>
				<input type="text" name="vre_property_banos"  value="<?php esc_html_e($vre_property_banos,'vacational-real-estate'); ?>" ></label>


			</td>
			<td>
            	<?php
				if($prowp_service && ($prowp_service==VRE_RENT_PROPERTY || $prowp_service==VRE_RENT_SALE_PROPERTY))
				{

				?>
                    <label for="property_rent_holidays">
                    <?php _e('Alquiler Vacacional( por Noches): ','vacational-real-estate'); ?>
                    <input type="checkbox" name="vre_ckrent_holidays"  id="vre_ckrent_holidays" <?php checked( $vre_ckrent_holidays, "on" ); ?> ></label>
                <?php
				}
				?>
			</td>
			<td>

            <label for="property_rent_month">
            <?php _e('Alquiler Mensual: ','vacational-real-estate'); ?>
            <input type="checkbox" name="vre_ckrent_month" checked="checked" id="vre_ckrent_month" <?php checked( $property_terraza, "on" ); ?> ></label>

			</td>
			<td>
            <?php
			$checked="";
				if($property_terraza=='on' )
					$checked='checked="checked"';
					?>

            	<label for="property_terraza">
				<?php _e('Terraza','vacational-real-estate'); ?>
                <input type="checkbox" name="property_terraza"  id="property_terraza" <?php checked( $property_terraza, "on" ); ?> ></label>
			</td>
		 </tr>
         <tr>
         	<td>
             <?php _e('Precio Venta','vacational-real-estate'); ?> <?php if(isset($options['vre_currency'])) echo($options['vre_currency']); ?><br />
				<input type="text" name="vre_property_pricesale"  value="<?php esc_html_e($vre_property_pricesale,'vacational-real-estate');?>" >
            </td>
            <td>
            <?php if($prowp_service && ($prowp_service==VRE_RENT_PROPERTY || $prowp_service==VRE_RENT_SALE_PROPERTY)): ?>
				<?php _e('Precio Alquiler/ Noche (Fuera Temporada)','vacational-real-estate'); ?>
				<?php if(isset($options['vre_currency'])) echo($options['vre_currency']); ?>
                	<br />
					<input type="text" name="vre_property_price_night" id="vre_property_price_night"   value="<?php esc_html_e($vre_property_price_night,'vacational-real-estate');?>" >
                <?php endif; ?>
            </td>
            <td>
            <?php
				if(!empty($prowp_service) && ($prowp_service=="alquiler" || $prowp_service=="alquileryventa"))
				{

					_e('Precio Alquiler/ Mes','vacational-real-estate'); ?> <?php if(isset($options['vre_currency'])) echo($options['vre_currency']); ?><br />
					<input type="text" name="vre_property_price_month"  id="vre_property_price_month"  value="<?php esc_html_e($vre_property_price_month,'vacational-real-estate');?>" >
                <?php
				}
				?>
            </td>
            <td></td>
         </tr>
		 </table>


		<?php
	}

	/**
 *  update new data from address form
 *
 * @since     	1.0.0
 * @access 		public
 */
public function vre_savemeta_address( $post_id )
{
	// verify if this is an auto save routine.
	// If it is the post has not been updated, so we don't want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// process form data if $_POST is set
	if( !empty($_POST['prowp_save_meta_box']) && !wp_verify_nonce($_POST['prowp_save_meta_box'],basename( __FILE__ )))
	{
		// Get the post type object.
		global $post;
		$post_type = get_post_type_object( $post->post_type );

			// Check if the current user has permission to edit the post.
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return $post_id;
			}

			//if not empty country from address form
			if ( !empty( $_POST['vre_prop_country'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_country',sanitize_text_field( $_POST['vre_prop_country'] ) );
			}

			//if not empty country from address form
			if ( !empty( $_POST['vre_prop_referencia'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_referencia',sanitize_text_field( $_POST['vre_prop_referencia'] ) );
			}

			//if not empty direction from address form
			if (  !empty( $_POST['vre_prop_route'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_route',sanitize_text_field( $_POST['vre_prop_route'] ) );
			}

			//if not empty postal code from  address form
			if (  !empty( $_POST['vre_prop_postal_code'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_postal_code',sanitize_text_field( $_POST['vre_prop_postal_code'] ) );
			}

			//if not empty street number from  address form
			if ( !empty( $_POST['vre_prop_postal_code'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_postal_code',sanitize_text_field( $_POST['vre_prop_postal_code'] ) );
			}

			//if not empty locality from address form
			if (  !empty( $_POST['vre_prop_locality'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_locality',sanitize_text_field( $_POST['vre_prop_locality'] ) );
			}

			//if not empty city from address form
			if (  !empty( $_POST['vre_prop_level_2'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_level_2',sanitize_text_field( $_POST['vre_prop_level_2'] ) );
			}

			//if not empty community from address form
			if ( !empty( $_POST['vre_prop_level_1'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_level_1',sanitize_text_field( $_POST['vre_prop_level_1'] ) );
			}


			//if not empty latitude from address form
			if ( !empty( $_POST['vre_prop_lat'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_lat',sanitize_text_field( $_POST['vre_prop_lat'] ) );
			}

			//if not empty longitude from address form
			if (  !empty( $_POST['vre_prop_lng'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_prop_lng',sanitize_text_field( $_POST['vre_prop_lng'] ) );
			}

			//if not empty community from address form
			if ( !empty( $_POST['vre_property_price_night'] ))
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				update_post_meta( $post_id, 'vre_property_price_night',sanitize_text_field( $_POST['vre_property_price_night'] ) );
			}

	}
}

/**
	 * Function that save the new season and price for the property
	 *
	 * @since    1.0.0
	 * @param      object    $post_id    Actual post ID
	 */
	public function vre_save_season_function( $post_id )
	{

		global $wpdb;

		// if auto saving skip saving our meta box data
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

		// process form data if $_POST is set
		if( isset( $_POST['prowp_save_meta_box'] ) &&  !wp_verify_nonce($_POST['prowp_save_meta_box'],basename( __FILE__ )))
		{
			global $post;
			$post_type = get_post_type_object( $post->post_type );
			//print_r($_POST); die();
			// Check if the current user has permission to edit the post.
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return $post_id;
			}

			if ( isset($_POST['vre_property_service']) && (sanitize_text_field($_POST['vre_property_service'])!="ventas") )
			{
				// save the meta box data as post meta using the post ID as a unique prefix
				if ( isset($_POST['price_base']) &&  is_numeric($_POST['price_base']) )
				{
					//get table name
					$table_name = $wpdb->prefix . 'vreseasons';

						//if exists price base for the  property, update
						if(isset($_POST['season_idbase']) && $_POST['season_idbase'] && is_numeric($_POST['season_idbase']))
						{
							$wpdb->update(
									$table_name,
									array(
										'name' => 'price_base',
										'post_id'=>$post_id,
										'pricepernight'=>sanitize_text_field($_POST['price_base']),
										'anual'=>1

									),
									array(
										"season_id" =>sanitize_text_field($_POST['season_idbase'])
									)
								);


						}else
						{
							//if dont exists price base for the  property, insert
							/*$wpdb->insert(
									$table_name,
									array(
										'name' => 'price_base',
										'post_id'=>$post_id,
										'day'=>'1',
										'month'=>'1',
										'year'=>date('Y'),
										'pricepernight'=>sanitize_text_field($_POST['price_base']),
										'anual'=>'1',

									)
								);*/

						}


				}

				//If exists season tax update or insert
				if ( isset($_POST['vre_season_name']) &&  ($_POST['vre_season_name']) && isset($_POST['vre_date_from']) && isset($_POST['vre_date_to'])  && isset($_POST['vre_pricepernight']))
				{
					//get the table name
					$table_name = $wpdb->prefix . 'vreseasons';


					//foreach season tax
					foreach($_POST['vre_season_name'] as $key => $season)
					{

						$anual=0;

						//if is season annual
						if(isset($_POST['vre_check_anual'][$key]) && !empty($_POST['vre_check_anual'][$key]) && sanitize_text_field($_POST['vre_check_anual'][$key])=="on")
						{
							$anual=1;
						}


						//if exists the seasonids update it, else insert new
						if(isset($_POST['vre_season_id'][$key]) && !empty($_POST['vre_season_id'][$key]) && is_numeric($_POST['vre_season_id'][$key]))
						{

							$datefrom="";
							$dateto="";
							$pricepernight="";

							//if exists date from , date to and price por night, save them
							if(isset($_POST['vre_date_from'][$key]) && !empty($_POST['vre_date_from'][$key]) && isset($_POST['vre_date_to'][$key]) && !empty($_POST['vre_date_to'][$key]) && isset($_POST['vre_pricepernight'][$key]) && is_numeric($_POST['vre_pricepernight'][$key]) )
							{

								$season_id=sanitize_text_field($_POST['vre_eason_id'][$key]);

								//We update the season
								$wpdb->update(
									$table_name,
									array(
										'name' => $season,
										'post_id'=>$post_id,
										'date_from'=>sanitize_text_field($datefrom),
										'date_to'=>sanitize_text_field($dateto),
										'strtotime_from'=>strtotime($datefrom),
										'strtotime_to'=>strtotime($dateto),
										'pricepernight'=>$pricepernight,
										'anual'=>$anual
									),
									array(
										"season_id" => $season_id
									)
								);
							}

						}else
						{
							$datefrom=sanitize_text_field($_POST['vre_date_from'][$key]);
							$dateto=sanitize_text_field($_POST['vre_date_to'][$key]);
							$pricepernight=sanitize_text_field($_POST['vre_pricepernight'][$key]);
							//We insert a new season
							$wpdb->insert(
								$table_name,
								array(
									'name' => $season,
									'post_id'=>$post_id,
									'date_from'=>sanitize_text_field($datefrom),
									'date_to'=>sanitize_text_field($dateto),
									'strtotime_from'=>strtotime($datefrom),
									'strtotime_to'=>strtotime($dateto),
									'pricepernight'=>$pricepernight,
									'anual'=>$anual

								)
							);

						}


					}

				}

			}


		}
	}


/**
* Function the differents seasons created
*
* @since    1.0.0
* @param      object    $post_id    Actual post ID
*/
public function vre_save_seasons_property( $post_id )
{

	global $wpdb;

	// if auto saving skip saving our meta box data
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	return $post_id;

	// process form data if $_POST is set
	if( !empty( $_POST['prowp_save_meta_box'] ) && !wp_verify_nonce($_POST['prowp_save_meta_box'],basename( __FILE__ )))
	{

		global $post;
		$post_type = get_post_type_object( $post->post_type );

		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		//check nonce for security
		if ( ! empty( $_POST ) && check_admin_referer( 'creap_seasopn_noncefield', 'creap_save_season_nonce' ) )
		{

			//if plugin options only for saleproperties, we dont dispaly any season
			if ( !empty($_POST['vre_property_service']) && ($_POST['vre_property_service']!="ventas") )
			{
				$table_name = $wpdb->prefix . 'vreseasons';

				//Delete de actual seasons
				//$wpdb->delete( $table_name, array( 'post_id' => $post_id ) );

				//if base price exists
				if ( !empty($_POST['price_base']) &&  is_numeric($_POST['price_base']) )
				{
					//get table name
					$table_name = $wpdb->prefix . 'vreseasons';

						//if exists price base for the  property, update
						if(isset($_POST['season_idbase']) && $_POST['season_idbase'] && is_numeric($_POST['season_idbase']))
						{
							$wpdb->update(
									$table_name,
									array(
										'name' => 'price_base',
										'post_id'=>$post_id,
										'pricepernight'=>sanitize_text_field($_POST['price_base']),
										'anual'=>1

									),
									array(
										"season_id" =>sanitize_text_field($_POST['season_idbase'])
									)
								);


						}else
						{
							//if dont exists price base for the  property, insert
							$wpdb->insert(
									$table_name,
									array(
										'name' => 'price_base',
										'post_id'=>$post_id,
										'day'=>'1',
										'month'=>'1',
										'year'=>date('Y'),
										'pricepernight'=>sanitize_text_field($_POST['price_base']),
										'anual'=>'1',

									)
								);

						}


				}

				$table_name = $wpdb->prefix . 'vreseasons';

				//if exists new season  with name , price and dates
				if ( !empty($_POST['season_name']) && !empty($_POST['vre_date_from']) && !empty($_POST['vre_date_to'])  && !empty($_POST['pricepernight'])  && filter_input(INPUT_POST, 'pricepernight', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY))
				{


					//foreach season
					foreach($_POST['season_name'] as $key => $season)
					{

						$anual=0;

						//if is a anual season
						if(!empty($_POST['check_anual']) && !empty($_POST['check_anual'][$key]) && sanitize_text_field($_POST['check_anual'][$key])=="on")
							$anual=1;

						//if exists the season before we update, else insert
						if(!empty($_POST['creap_season_id']) && !empty($_POST['creap_season_id'][$key]) && is_numeric($_POST['creap_season_id'][$key]))
						{
							$wpdb->update(
								$table_name,
								array(
									'name' => sanitize_text_field($season),
									'post_id'=>intval($post_id),
									'vre_date_from'=>sanitize_text_field($_POST['vre_date_from'][$key]),
									'vre_date_to'=>sanitize_text_field($_POST['vre_date_to'][$key]),
									'strtotime_from'=>strtotime(sanitize_text_field($_POST['vre_date_from'][$key])),
									'strtotime_to'=>strtotime(sanitize_text_field($_POST['vre_date_to'][$key])),
									'pricepernight'=>sanitize_text_field($_POST['pricepernight'][$key]),
									'anual'=>$anual

								),
								array(
									"season_id" => sanitize_text_field($_POST['creap_season_id'][$key])
								)
							);
							//print_r($_POST['creap_season_id'][$key]);die();
						}else
						{

							$wpdb->insert(
								$table_name,
								array(
									'name' => sanitize_text_field($season),
									'post_id'=>intval($post_id),
									'vre_date_from'=>sanitize_text_field($_POST['vre_date_from'][$key]),
									'vre_date_to'=>sanitize_text_field($_POST['vre_date_to'][$key]),
									'strtotime_from'=>strtotime(sanitize_text_field($_POST['vre_date_from'][$key])),
									'strtotime_to'=>strtotime(sanitize_text_field($_POST['vre_date_to'][$key])),
									'pricepernight'=>sanitize_text_field($_POST['pricepernight'][$key]),
									'anual'=>$anual

								)
							);
						}


					}

				}

				//print_r($_POST);die();
				//if exists new season  with name , price and dates
				if ( !empty($_POST['deleteseason_id'])  )
				{


					//foreach season
					foreach($_POST['deleteseason_id'] as $key => $season)
					{
						if(intval($season))
							$wpdb->delete( $table_name, array( 'creap_season_id' => intval($season )) );

					}

				}

			}

		}
	}
}


public function vre_cities_hierarchical_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Ciudades', 'taxonomy general name' ),
    'singular_name' => _x( 'Ciudad', 'taxonomy singular name' ),
    'search_items' =>  __( 'Buscar Ciudad' ),
    'all_items' => __( 'Todas las Ciudades' ),
    'parent_item' => __( 'Ciudad Padre' ),
    'parent_item_colon' => __( 'Ciudad Padre:' ),
    'edit_item' => __( 'Editar Ciudad' ),
    'update_item' => __( 'Actualizar Ciudad' ),
    'add_new_item' => __( 'Agregar Nueva Ciudad' ),
    'new_item_name' => __( 'Nuevo Nombre' ),
    'menu_name' => __( 'Ciudades' ),
  );

// Now register the taxonomy

  register_taxonomy('vre_city_tax',array('vre_property_cp'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,

  ));

}

public function vre_topics_hierarchical_taxonomy() {



    $labels = array(
		'name' => _x( 'Features', 'taxonomy general name' ),
		'singular_name' => _x( 'Feature', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Features' ),
		'all_items' => __( 'All Features' ),
		'parent_item' => __( 'Parent Feature' ),
		'parent_item_colon' => __( 'Parent Feature:' ),
		'edit_item' => __( 'Edit Feature' ),
		'update_item' => __( 'Update Feature' ),
		'add_new_item' => __( 'Add New Feature' ),
		'new_item_name' => __( 'New Name' ),
		'menu_name' => __( 'Features' ),
	  );


// Now register the taxonomy

  register_taxonomy('creap_features_tax',array('vre_property_cp'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,

  ));

}

}
