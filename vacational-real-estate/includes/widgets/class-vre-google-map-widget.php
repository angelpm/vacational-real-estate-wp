<?php
/*
 * Theme Name:  Complete Google Maps Properties
 * Theme URI:   https://webdesign.portfoliobox.net/
 * Description:  Complete Real Estate WP plugin Wordpress. Plugin para la administracion de alquiler y venta de propiedades. Con la posibilidad de gestionar las reservas
 * Version:    1.0.0 
 * Text Domain: creap-theme-real-estate
 * Author:  	Angel Porras <robinwebdesign@hotmail.com>
 * Author URI: https://webdesign.portfoliobox.net/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

class Creap_google_map_properties extends WP_Widget{
	
	public $name='Complete Google Maps Properties';
	
	public $description="Complete Google Maps Properties";
	
	public $control_options = array();
	
	
	function __construct()
	{
		$widget_options=array(
			'classname'		=>__CLASS__,
			'description'	=>$this->description
		);	
		
		parent::__construct(__CLASS__,$this->name,$widget_options,$this->control_options);
	}
	
	static function register_this_widget()
	{
		register_widget(__CLASS__);	
	}
	
	
// The widget form (for the backend )
	public function form( $instance ) {
		
		if ( isset( $instance[ 'vre_title_map_widget' ] ) ) 
		{
			$title = $instance[ 'vre_title_map_widget' ];
		}
		else 
		{
			$title = __( 'New title', 'wpb_widget_domain' );
		}
		
		// Set widget defaults
		$defaults = array(
			'vre_title_map_widget'    => '',
			'creap_lat_map_widget'    => '36.5499735',
			'creap_lng_map_widget'    => '-4.6499248',
			
			
		);
		
		$num_rows=4;
		$num_columns=6;
		
		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php // Widget Title ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'vre_title_map_widget' ) ); ?>"><?php _e( 'Widget Title', 'vacational-real-estate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vre_title_map_widget' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vre_title_map_widget' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'creap_lat_map_widget' ) ); ?>"><?php _e( 'Latitud', 'vacational-real-estate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'creap_lat_map_widget' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'creap_lat_map_widget' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'creap_lng_map_widget' ) ); ?>"><?php _e( 'Longitud', 'vacational-real-estate' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'creap_lng_map_widget' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'creap_lng_map_widget' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>



		

	<?php }
	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['vre_title_map_widget']    = isset( $new_instance['vre_title_map_widget'] ) ? wp_strip_all_tags( $new_instance['vre_title_map_widget'] ) : '';
		$instance['creap_lat_map_widget']    = isset( $new_instance['creap_lat_map_widget'] ) ? wp_strip_all_tags( $new_instance['creap_lat_map_widget'] ) : '';
		$instance['creap_lng_map_widget']    = isset( $new_instance['creap_lng_map_widget'] ) ? wp_strip_all_tags( $new_instance['creap_lng_map_widget'] ) : '';
		
		return $instance;
	}
	
	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		// Check the widget options
		$title    = isset( $instance['vre_title_map_widget'] ) ? apply_filters( 'widget_title', $instance['vre_title_map_widget'] ) : '';
		$creap_lat_map_widget    = isset( $instance['creap_lat_map_widget'] ) ? $instance['creap_lat_map_widget'] : '';
		$creap_lng_map_widget    = isset( $instance['creap_lng_map_widget'] ) ? $instance['creap_lng_map_widget'] : '';
		
		
		// WordPress core before_widget hook (always include )
		
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';
			// Display widget title if defined
			if ( !empty($title )) {
				echo $before_title . $title . $after_title;
			}
			
			
							
		
		?>
        <div id="map" style="width:100%; float:right; height:600px;"></div>
            
        <script>
		
var map;
var historicalOverlay;

function initMap() 
{
	    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    map.setTilt(45);
        
    // Multiple Markers
    
	<?php
	$markers_str="";
	$info_str="";
	$posts_array = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type' => 'vre_property_cp',
			
			)
		);
		
		$icon="";
		
		foreach($posts_array as $postm)
		{
		
			$lat=get_post_meta($postm->ID, 'vre_prop_lat', true);
			$lng=get_post_meta($postm->ID, 'vre_prop_lng', true);
			$featured_img_url = get_the_post_thumbnail_url($postm->ID,'thumbnail'); 
			
			if(!empty($lat) && !empty($lng))
			{	
				$markers_str=$markers_str. '["London Eye, London",'.$lat.','.$lng.'],';
			
				$info_str=$info_str."['<div clas=\"info_content\"><h5>".$postm->post_title."</h5><p><img src=\"".$featured_img_url."\" width=\"200px\" height=\"auto\" alt=\"thumbnail\"></p></div>'],";
			}
		}
		
		
		?>
        
       
   
	var markers=[<?php echo $markers_str; ?>];
	
                        
    // Info Window Content
    var infoWindowContent = [<?php echo $info_str; ?> ];
	
	console.log( markers);
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });

}

		</script>    

                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4LZAdOeSmA0aQklrxqzix3n7gR2LE0J8&callback=initMap"   async defer></script>
                
       <?php
				 
				 
		
			
		echo '<div style="clear:both;"></div></div>';
		// WordPress core after_widget hook (always include )
		
	}


}

$my_widget= new Creap_google_map_properties();