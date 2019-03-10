<?php
/*
 * Clas Name:  		VRE Last Properties Box
 * Description:  	Display  Last Properties Box 
 * @version:    	1.0.0 
 * Author:  		Angel Porras <robinwebdesign@hotmail.com>
 * Author URI: 		https://robinwebdesign.wixsite.com/webdesign/donate
 * @license:    	GPL2
 * @license URI: 	ttps://www.gnu.org/licenses/gpl-2.0.html
 * @package   		Vacational_Real_Estate
 * Domain Path:	    /languages
*/

class Vre_Last_Properties_Widget extends WP_Widget{
	
	public $name='VRE Last Properties Box';
	
	public $description="Display  Last Properties Box";
	
	public $control_options = array();
	
	
	function __construct()
	{
		$widget_options=array(
			'classname'		=>__CLASS__,
			'description'	=>$this->description
		);	
		
		parent::__construct(__CLASS__,$this->name,$widget_options,$this->control_options);
		
		add_action( 'widgets_init',array($this,'vre_register_this_widget')) ;
	}
	
	static function vre_register_this_widget()
	{
		register_widget(__CLASS__);	
	}
	
	
// The widget form (for the backend )
	public function form( $instance ) {
		// Set widget defaults
		$defaults = array(
			'vre_title'    => '',
			'vre_service'    => '',
			'vre_select_rows'   => '',
			'vre_select_columns'   => '',
		);
		
		$num_rows=4;
		$num_columns=6;
		$vre_title   = isset( $instance['vre_title'] ) ? $instance['vre_title'] : '';
		$vre_service   = isset( $instance['vre_service'] ) ? $instance['vre_service'] : '';
		$select_rows   = isset( $instance['vre_select_rows'] ) ? $instance['vre_select_rows'] : '';
		$select_columns = ! empty( $instance['vre_select_columns'] ) ? $instance['vre_select_columns'] : false;
		
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php // Widget Title ?>
		<p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'vre_title' ) ); ?>"><?php _e( 'Widget Title', 'vacational-real-estate' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vre_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vre_title' ) ); ?>" type="text" value="<?php echo esc_attr( $vre_title ); ?>" />
        </p>
	
		<p>
		<label for="<?php echo $this->get_field_id( 'vre_service' ); ?>"><?php _e( 'Servicio', 'vacational-real-estate' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'vre_service' ); ?>" class="widefat">
			<option value="venta"  <?php selected( $vre_service, "venta", true )?> ><?php _e('Venta','vacational-real-estate'); ?></option>
            <option value="alquiler"  <?php selected( $vre_service,"alquiler", true )?>><?php _e('Alquiler','vacational-real-estate'); ?></option>
            <option value="ventayalquiler"   <?php  selected( $vre_service, "ventayalquiler", true )?>><?php _e('Venta y Alquiler','vacational-real-estate'); ?></option>
		</select>
		</p>
                
		<p>
		<label for="<?php echo $this->get_field_id( 'vre_select_rows' ); ?>"><?php _e( 'Number of Rows', 'vacational-real-estate' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'vre_select_rows' ); ?>" class="widefat">
			<?php
			for ($i=0;$i<$num_rows;$i++){
				echo '<option value="' . esc_attr( $i ) . '" id="' . esc_attr( $i ) . '" '. selected( $select_rows, $i, false ) . '>'. $i . '</option>';
			} 
			?>
		</select>
		</p>
        
       	<p>
		<label for="<?php echo $this->get_field_id( 'vre_select_columns' ); ?>"><?php _e( 'Numero Columnas', 'vacational-real-estate' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'vre_select_columns' ); ?>" class="widefat">
			<?php
			for ($i=0;$i<$num_columns;$i++){
				echo '<option value="' . esc_attr( $i ) . '" id="' . esc_attr( $i ) . '" '. selected( $select_columns, $i, false ) . '>'. $i . '</option>';
			} 
			?>
		</select>
		</p>

	<?php 
	}
	
	
	
	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['vre_title']    = isset( $new_instance['vre_title'] ) ? wp_strip_all_tags( $new_instance['vre_title'] ) : '';
		$instance['vre_service']   = isset( $new_instance['vre_service'] ) ? wp_strip_all_tags( $new_instance['vre_service'] ) : '';
		$instance['vre_select_rows']   = isset( $new_instance['vre_select_rows'] ) ? wp_strip_all_tags( $new_instance['vre_select_rows'] ) : '';
		$instance['vre_select_columns']   = isset( $new_instance['vre_select_columns'] ) ? wp_strip_all_tags( $new_instance['vre_select_columns'] ) : '';
		return $instance;
	}
	
	
	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		// Check the widget options
		$title    = isset( $instance['vre_title'] ) ? apply_filters( 'widget_title', $instance['vre_title'] ) : '';
		
		
		// WordPress core before_widget hook (always include )
		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';
			// Display widget title if defined
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		
		$select_service   = (isset( $instance['vre_service'] ) && ($instance['vre_service']!="ventayalquiler")) ? $instance['vre_service'] : '';
		$select_rows   = isset( $instance['vre_select_rows'] ) ? $instance['vre_select_rows'] : '';
		$select_columns = ! empty( $instance['vre_select_columns'] ) ? $instance['vre_select_columns'] : false;
		
		
		
		// get saved options
		$saved = get_option('vre_propertywp_options');
		//Get options from Data Base if  they exists
		
			if(!empty($saved) && !empty($saved['vre_currency']) )
			{
				$vre_currency=$saved['vre_currency'];
				
			}
			if(!empty($saved) && !empty($saved['vre_currency_position']) )
			{
				$vre_currency_position=$saved['vre_currency_position'];
				
			}
		
			if(intval($select_rows) && intval($select_columns))
			{
				$total_properties=$select_rows*$select_columns;
				
				$args = array( 
					'post_type' => 'vre_property_cp', 
					'posts_per_page' => $total_properties ,
					
					'orderby' => 'publish_date', 
					'order' => 'DESC'
					);
				
				if(!empty($select_service))
					$args['meta_query']= array(
						  array(
							  'key' => 'vre_property_service',
							  
							  'value' => $select_service, // limit to "karma value = 100"
							  
						   )
						   );
				
				$loop = new WP_Query( $args );
				
				while ( $loop->have_posts() ) : $loop->the_post();
				
					$vre_property_pricesale=get_post_meta(get_the_ID(), 'vre_property_pricesale', true); 
					$vre_property_rooms=get_post_meta(get_the_ID(), 'vre_property_rooms', true);
					$vre_property_banos=get_post_meta(get_the_ID(), 'vre_property_banos', true);
					$vre_prop_mtconstruido=get_post_meta(get_the_ID(), 'vre_prop_mtconstruido', true);
					$vre_prop_mttotal=get_post_meta(get_the_ID(), 'vre_prop_mttotal', true);
					
					$vre_property_reduced=get_post_meta(get_the_ID(), 'vre_property_reduced', true);
					
					$rebajado="";
					$vre_currency="";
					$vre_ciy="";
					$vre_type="";
					$vre_price_str="";
					
					if(!empty($vre_property_reduced) && ("on"==sanitize_text_field($vre_property_reduced)))
						$rebajado='<div class="reduced-pannel-div">'.__("Rebajado",'vacational-real-estate').'</div>';
					
					$terms_city = get_the_terms( get_the_ID(), 'vre_city_tax' );
					
					$vre_propertywp_options =get_option('vre_propertywp_options');//print_r($vre_propertywp_options);
					
					if(!empty($vre_propertywp_options) && isset($vre_propertywp_options['vre_currency']))
						$vre_currency=$vre_propertywp_options['vre_currency'];
					
					if(!empty($terms_city) && isset($terms_city[0]->name))
						$vre_ciy=$terms_city[0]->name;
					
					
					$terms_city = get_the_terms( get_the_ID(), 'vre_city_tax' );
					$categories = get_the_category( get_the_ID() ); 
					
					
					if(!empty($categories) && isset($categories[0]->name))
						$vre_type=$categories[0]->name;
					
				
					if($select_service=="venta")
					{
						$service_str="Venta";
					}elseif($select_service=="alquiler")
					{
						$service_str="Alquiler";
					}else
					{
						$service_str="Venta y Alquiler";	
					}

					if($select_service && $select_service!="venta")
					{
						
						
						$vre_property_price_night=get_post_meta(get_the_ID(), 'vre_property_price_night', true);
						
						$vre_property_price_month=get_post_meta(get_the_ID(), 'vre_property_price_month', true);
					}
					
					
					
					if(!empty($vre_property_pricesale))
					{
						
						if($vre_currency && $vre_currency_position && $vre_currency_position=="Izquierda")
						{
							$vre_price_str='<div class="price-pannel-div">'.$vre_currency." ".$vre_property_pricesale.'</div>';
						}else
						{
							$vre_price_str='<div class="price-pannel-div">'.$vre_property_pricesale." ".$vre_currency.'</div>';
						}
					}
					
					
					echo 
					"<div class='card col-md-3' ><div class='creap_wrap_pannel'><div class='creap_img_pannel'>{$rebajado}{$vre_price_str}<img src=".get_the_post_thumbnail_url(get_the_ID(),'medium')." width='100%' height='auto' alt='property thumbnail'></div><div class='card-body'><h5 class='card-title'><a href='".get_the_permalink()."'>".get_the_title()."</a></h5><p><img src='".plugins_url( 'public/images/home.png', dirname(__FILE__) )."' width='15px' alt='plazas de parking'>{$vre_type}-{$service_str}</p><p class='card-text'><img src='".plugins_url( 'public/images/location.png', dirname(__FILE__) )."' width='15px' alt='plazas de parking'> {$vre_ciy}</p></div><div class=' text-center'><span class='pull-left price-div-pannel'>{$vre_property_banos} <a title='".__("Baths",'vacational-real-estate')."'> <img src='".plugins_url( 'public/images/bath.png', dirname(__FILE__) )."' width='18px' alt='plazas de parking'></a></span><span>{$vre_property_rooms} <a title='".__("Rooms",'vacational-real-estate')."'> <img src='".plugins_url( 'public/images/bed.png', dirname(__FILE__) )."' width='18px' alt='plazas de parking'></a></span><span class='pull-right'>{$vre_prop_mtconstruido}  <a title='".__("Squares",'vacational-real-estate')."'> <img src='".plugins_url( 'public/images/square.png', dirname(__FILE__) )."' width='18px' alt='plazas de parking'></a></span></div></div></div>";
				 
				endwhile;
			}
			
		echo '<div style="clear:both"></div>';
			
		echo '</div>';
		
		echo $after_widget;
	}


}

$my_widget= new vre_Last_Properties_Widget();