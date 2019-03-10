<?php
/**
 * Class Search Porperty Widget
 * Plugin URI:  	https://robinwebdesign.wixsite.com/webdesign/
 * Description:  	Plugin to create a widget with a search form for properties 
 * Version:    1.0.0
 * Author      Angel Porras <robinwebdesign@hotmail.com>
 * Author URI: https://webdesign.portfoliobox.net/
 * license:    GPL2
 * license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */


class Vre_Search_Property_WP extends WP_Widget 
{
 
	 /**
	 * Widget Name
	 *
	 * @since    	1.0.0
	 * @access   	public
	 * @var      	string    		$name    The string used to  identify this widget.
	 */
	public $name = 'VRE Vacational Search Form';
	
	/**
	 * Widget Description
	 *
	 * @since    	1.0.0
	 * @access   	public
	 * @var     	 string    		$description    The string used describe this widget .
	 */
	public $description = 'Properties Search Form';
	
	/**
	 * Widget Name
	 *
	 * @since   	1.0.0
	 * @access   	public
	 * @var      	string array   	$control_options    array options for configuration.
	 */
	public $control_options = array();

	/**
	 * Construct function to register widget
	 *
	 * @since     	1.0.0
	 * @access   	public
	 */
	function __construct() 
	{
		
		$widget_options = array(
			'classname' => __CLASS__,
			'description' => $this->description,
			);
			
		parent::__construct( __CLASS__, $this->name,$widget_options,$this->control_options);
		
		
		add_action( 'widgets_init',array($this,'vre_register_this_widget')) ;
	}
	

	/**
	 * function for register  widget
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function vre_register_this_widget() 
	{
	
		register_widget( __CLASS__);
	}
	

	
	
	/**
	 *  function to load scripts
	 *
	 * @since     	1.0.0
	 * @access 		public
	 */	
	public static function load_resources()
	{
		//wp_register_style( 'wpstyle.css', plugin_dir_url( __FILE__ ) . '/assets/css/wpstyle.css', array()  );
		//wp_enqueue_style( 'wpstyle.css');
	}
	
	
	/**
	 *  function that display the widget
	 *
	 * @since     	1.0.0
	 * @access 		public
	 * @param 		array			$args			container tags for widget
	 * @param 		object			$instance		instance of class widget
	 */	
	public function widget( $args, $instance ) 
	{
		global $wp;
	
		extract($args);
		
		$title = apply_filters( 'widget_title', $instance['title'] );
	
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		
			if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];
			
		
		$cities = get_terms( 'vre_city_tax', array(
			'orderby'    => 'name',
			'hide_empty' => 0
		) );
		$url="";
		$url= home_url( '/' ).$wp->request;
		
		?>
		<div >
			<div class="advancedsearchprop">
				<form  role="search" method="get" id="searchformwp" class="searchform" action="<?php esc_html_e($url ,'vacational-real-estate');  ?>">
					<div >
						<div class="wpinput-form">
							<input type="text" class="" placeholder="Referencia" name="vre_reference">
						</div>
					</div>
					<?php
					
					

					if(isset($instance['servicio']) && $instance['servicio'])
					{
						?>
						<div class="wpinput-form">
							<select name="vre_property_service">
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
						</div>
						
						<?php
					}
					?>
					<div class="wpinput-form">
						<select  class="form-control" name="creap_property_cities">
							<option value="0"><?php  _e('Localidad','vacational-real-estate'); ?></option>
							<?php
							if($cities && count($cities)>0)
							{
								foreach($cities as $city)
								{
									?>
									<option>
									<?php esc_html_e($city->name,'vacational-real-estate'); ?>
									</option>
                                    <?php
								}			
							}
							?>
						</select>
						<?php
						
						if(isset($instance['tipo']) && ($instance['tipo']))
						{
							$tipos = get_categories( array(	'orderby' => 'name','order'   => 'ASC') );
							?>
							<div class="wpinput-form" >
								<select name="creap_prop_tipo">
									<option value="0">
									<?php _e('Tipo','vacational-real-estate'); ?>
									</option>
									<?php
							
									foreach($tipos as $tipo)
									{
										if(substr($tipo->name,0,10)!="Sin catego")
										{
										?>
										<option value="<?php  esc_html_e($tipo->term_id,'vacational-real-estate'); ?>"><?php  esc_html_e($tipo->name,'vacational-real-estate'); ?></option>
										<?php							
										}
									}
									?>
							</select>
						</div>
						
							<?php
						}
						
						if(isset($instance['preciodesde']) && $instance['preciodesde'])
						{
							?>
							<div class="wpinput-form">
								<label><?php   _e('Precio Desde - Hasta:','vacational-real-estate'); ?></label> <input type="number"  style="max-width:49%; margin-right:1%;float:left"  name="preciodesde" /><input type="number"  style="max-width:50%; float:left"  name="preciohasta" />
								<div style="clear:both"></div>
							</div>
							<?php
						}
						
						
						?>
						<strong>Caracter&iacute;sticas</strong>
						<?php
						
						
						$categories = get_terms( 'creap_features_tax', 'orderby=count&hide_empty=0' );
						foreach($categories as $category)
						{
								//print_r($category);
							?>
							<div class="wpinput-form">
								<label for="creap_<?php  esc_html_e($category->slug,'vacational-real-estate'); ?>"  style="float:left; font-weight:100"><input type="checkbox" name="vre_prop_check_<?php esc_html_e($category->term_id,'vacational-real-estate');?>"   style="height:15px;float:left; width:40px;"/><?php esc_html_e($category->name,'vacational-real-estate'); ?></label>
							</div> <br clear="all" />
							<?php	
						}
						
						?>
						<div class="wpinput-form">
							<button type="submit"  class="vre-buttom-book"><?php _e('Buscar','vacational-real-estate'); ?></button>
						</div>
					</form>
                </div>
            </div>
			<?php
			
		echo $args['after_widget'];
	}

	 
	/**
	 *  function to display options form
	 *
	 * @since     	1.0.0
	 * @access 		public
	 * @param 		object			$instance		new data por instance
	 */	
	public function form( $instance ) 
	{
		$defaults = array(
			'title' => 'Form Properties',
			'horizontal' => '',
			'referencia' => '',
			'servicio' => '',
			'ciudad' => '',
			'tipo' => '',
			'preciodesde' => '',
			'preciohasta' => '',
			'parking' => '',
			'animales' => ''		
		);
		
		$instance= wp_parse_args( (array) $instance, $defaults);
		
		$title = $instance['title'];
		$horizontal = $instance['horizontal'];
		$referencia = $instance['referencia'];
		$servicio = $instance['servicio'];
		$ciudad = $instance['ciudad'];
		$tipo = $instance['tipo'];
		$preciodesde = $instance['preciodesde'];
		$preciohasta = $instance['preciohasta'];
		$parking = $instance['parking'];
		$animales = $instance['animales'];
		
		
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','vacational-real-estate'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_html($title,'vacational-real-estate'); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'horizontal' ); ?>"><?php _e('Horizontal:','vacational-real-estate'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'horizontal' ); ?>" name="<?php echo $this->get_field_name( 'horizontal' ); ?>"  <?php checked($instance[ 'horizontal'],'on');  ?> type="checkbox"  />
		</p>
		
		<p>
		<label><input type="checkbox" class="inputswidgetsearch" id="Referencia" name="<?php echo $this->get_field_name( 'referencia' ); ?>" <?php checked($instance[ 'referencia'],'on');  ?>  /> <?php esc_html_e('Referencia','vacational-real-estate'); ?></label>
        </p>
        <p>
		<label><input type="checkbox"  class="inputswidgetsearch" id="Servicio" name="<?php echo $this->get_field_name( 'servicio' ); ?>" <?php checked($instance[ 'servicio'],'on');  ?>  <?php if(isset($instance[ 'servicio']) && $instance[ 'servicio' ]=="on") echo'checked="checked"'; ?> /> <?php esc_html_e('Servicio','vacational-real-estate'); ?></label>
        </p>
		<p>
        <label><input type="checkbox"  class="inputswidgetsearch"id="Ciudad" name="<?php echo $this->get_field_name( 'ciudad' ); ?>" <?php checked($instance[ 'ciudad'],'on');  ?> /> <?php esc_html_e('Servicio','vacational-real-estate'); ?>Ciudad</label>
        </p>
        <p>
		<label><input type="checkbox" class="inputswidgetsearch" id="Tipo" name="<?php echo $this->get_field_name( 'tipo' ); ?>" <?php checked($instance[ 'tipo'],'on');  ?>  /> <?php esc_html_e('tipo','vacational-real-estate'); ?>Tipo</label>
        </p>
        <p>
		<label><input type="checkbox" class="inputswidgetsearch" id="PrecioDesde" name="<?php echo $this->get_field_name( 'preciodesde' ); ?>" <?php checked($instance[ 'preciodesde'],'on');  ?>/> <?php esc_html_e('Precio','vacational-real-estate'); ?></label>
        </p>
        <p>	
		<label><input type="checkbox" class="inputswidgetsearch" id="Dormitorios" name="<?php echo $this->get_field_name( 'dormitorios' ); ?>" <?php checked($instance[ 'dormitorios'],'on');  ?>/> <?php esc_html_e('Dormitorios','vacational-real-estate'); ?></label>
        </p>
        <p>
		<label><input type="checkbox" class="inputswidgetsearch" id="Parking" name="<?php echo $this->get_field_name( 'parking' ); ?>" <?php checked($instance[ 'parking'],'on');  ?>   /> <?php esc_html_e('Parking','vacational-real-estate'); ?></label>
        </p>
        <p>
		<label><input type="checkbox" class="inputswidgetsearch" id="Animales" name="<?php echo $this->get_field_name( 'animales' ); ?>" <?php checked($instance[ 'animales'],'on');  ?>  /> <?php esc_html_e('Animales','vacational-real-estate'); ?></label>
		</p>
		
	<?php 
	}

 
 
	/**
	 *  function to save updated options for the search widget
	 *
	 * @since     	1.0.0
	 * @access 		public
	 * @param 		object			$new_instance		new data por instance
	 * @param 		object			$old_instance		old instance
	 */	
	public function update( $new_instance, $old_instance ) 
	{
		
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		$instance['horizontal'] = ( ! empty( $new_instance['horizontal'] ) ) ? "on" : 'off';
		$instance['referencia'] = ( ! empty( $new_instance['referencia'] ) ) ? "on" : 'off';
		$instance['servicio'] = ( ! empty( $new_instance['servicio'] ) ) ? "on" : 'off';
		$instance['ciudad'] = ( ! empty( $new_instance['ciudad'] ) ) ? "on" : 'off';
		$instance['servicio'] = ( ! empty( $new_instance['servicio'] ) ) ? "on" : 'off';
		$instance['tipo'] = ( ! empty( $new_instance['tipo'] ) ) ? "on" : 'off';
		$instance['preciodesde'] = ( ! empty( $new_instance['preciodesde'] ) ) ? "on" : 'off';
		
		$instance['dormitorios'] = ( ! empty( $new_instance['dormitorios'] ) ) ? "on" : 'off';
		$instance['parking'] = ( ! empty( $new_instance['parking'] ) ) ? "on" : 'off';
		$instance['animales'] = ( ! empty( $new_instance['animales'] ) ) ? "on" : 'off';
		
		return $instance;
	}
	

} 


$my_widget= new Vre_Search_Property_WP();


