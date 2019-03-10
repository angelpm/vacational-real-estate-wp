<table cellpadding="2%" cellspacing="2%" width="100%" height="100%">
    	<tr>
        <td width="40%" valign="top">
        	<table  width="100%" height="100%" cellpadding="10px" cellspacing="10px">
            <tr>
            	<td><?php _e('Pais','vacational-real-estate');?>: <input type="text" name="vre_prop_country" id="prop_country" value="<?php esc_html_e($prop_country,'vacational-real-estate');   ?>" ></td>
            </tr>
            <tr>
        		<td><?php _e('Direccion','vacational-real-estate');?> : <input type="text" name="vre_prop_route" id="prop_route"  value="<?php esc_html_e($vre_prop_route,'vacational-real-estate'); ?>" ></td>
            </tr>
            <tr>
        		<td>
                <?php _e('Ciudad','vacational-real-estate');?>
                <input type="text" name="vre_prop_level_2" id="prop_level_2"  value="<?php esc_html_e($prop_level_2,'vacational-real-estate'); ?>" >
        		<input type="hidden" name="vre_prop_level_1" id="prop_level_1"  value="<?php esc_html_e($prop_level_1,'vacational-real-estate');  ?>" >
                <input type="hidden" name="vre_prop_lat" id="prop_lat"  value="<?php esc_html_e($prop_lat,'vacational-real-estate');   ?>" >
                <input type="hidden" name="vre_prop_lng" id="prop_lng"  value="<?php esc_html_e($prop_lng,'vacational-real-estate'); ?>" >
                </td>
            </tr>
            <tr>
        		<td><?php _e('Localidad','vacational-real-estate');?> : <input type="text" name="vre_prop_locality" id="prop_locality"  value="<?php esc_html_e($vre_prop_locality,'vacational-real-estate');  ?>" ></td>
            </tr>
            
            <tr>
        		<td><?php _e('Numero','vacational-real-estate');?> : <input type="text" name="vre_prop_postal_code" id="prop_street_number"   value="<?php esc_html_e($vre_prop_postal_code,'vacational-real-estate'); ?>"></td>
            </tr>
            <tr>
        		<td><?php _e('C.P.','vacational-real-estate');?>  <input type="text" name="vre_prop_postal_code" id="prop_postal_code"   value="<?php esc_html_e($prop_postal_code,'vacational-real-estate'); ?>"></td>
            </tr>
            </table>
        </td>
        <td  width="60%">
			
                <input id="pac-input" class="controls" type="text" placeholder="<?php _e('Direccion','vacational-real-estate');?>">
            
    		<div id="map"></div>
        </td>
        </tr>
    </table>
    
    <?php 
	
		$creap_option_latitud=!empty($opt_plugin['creap_option_latitud'])? $opt_plugin['creap_option_latitud']:"36.8688";
		$creap_option_longitud=!empty($opt_plugin['creap_option_longitud'])? $opt_plugin['creap_option_longitud']:"-4.0000";
		$creap_option_zoom=!empty($opt_plugin['creap_option_zoom'])? $opt_plugin['creap_option_zoom']:"8";
		
		?>
		<script>
        
        
        function initAutocomplete() 
		{
			
			var map = new google.maps.Map(document.getElementById('map'), {
				center: {lat: <?php esc_html_e($creap_option_latitud) ?> , lng: <?php  esc_html_e($creap_option_longitud) ?>},
				zoom: <?php esc_html_e($creap_option_zoom) ?>,
				mapTypeId: 'roadmap'
			});
        
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        
        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
        	searchBox.setBounds(map.getBounds());
        });
		
		        
        var markers = [];
		
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() 
        {
			var places = searchBox.getPlaces();
			
			if (places.length == 0) 
			{
				return;
			}
        
			if(places[0].address_components.length>0)
			{
				
				var temp= places[0].address_components;
			
				jQuery('#prop_lat').val(places[0].geometry.location.lat()); //Latitud
				jQuery('#prop_lng').val(places[0].geometry.location.lng()); //Longitud
				
				
				for(var i=0;i<places[0].address_components.length;i++)
				{
					
				
					if(temp[i].types[0]=="country") //pais
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_country').val(temp[i].long_name);
					}
					
					if(temp[i].types[0]=="route") //direccion
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_route').val(temp[i].long_name);
					}
					
					if(temp[i].types[0]=="locality") //ciudad
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_locality').val(temp[i].long_name);
					}
					
					if(temp[i].types[0]=="administrative_area_level_1") //comunidad
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_level_1').val(temp[i].long_name);
					}
					
					if(temp[i].types[0]=="administrative_area_level_2") //provincia
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_level_2').val(temp[i].long_name);
					}
					
					if(temp[i].types[0]=="postal_code") //codigo postal
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_postal_code').val(temp[i].long_name);
					}
					if(temp[i].types[0]=="street_number") //numero de calle
					{
						//console.log(temp[i].short_name);
						jQuery('#prop_street_number').val(temp[i].long_name);
					}
				
				
				
				}
			}
        
			// Clear out the old markers.
			markers.forEach(function(marker) {
				marker.setMap(null);
			});
			markers = [];
        
			// For each place, get the icon, name and location.
			var bounds = new google.maps.LatLngBounds();
			
			places.forEach(function(place) 
			{
			
				if (!place.geometry) {
				  console.log("Returned place contains no geometry");
				  return;
				}
				var icon = {
				  url: place.icon,
				  size: new google.maps.Size(81, 81),
				  origin: new google.maps.Point(0, 0),
				  anchor: new google.maps.Point(17, 34),
				  scaledSize: new google.maps.Size(25, 25)
				};
        
				// Create a marker for each place.
				var marker = new google.maps.Marker({
				  map: map,
				  icon: '<?php echo plugins_url('images/location2.png',__DIR__);  ?>',
				  title: place.name,
				  draggable: true,
				  position: place.geometry.location
				});
				
				marker.addListener('dragend', function() {
				
					jQuery('#prop_lat').val(this.position.lat());
					jQuery('#prop_lng').val(this.position.lng());
				  });
				markers.push(marker);
       
				if (place.geometry.viewport) 
				{
				  // Only geocodes have viewport.
				  bounds.union(place.geometry.viewport);
				} else {
				  bounds.extend(place.geometry.location);
				}
        	});
        
			map.fitBounds(bounds);
			
			
		});
		
		<?php
		if(isset($creap_option_latitud) && is_numeric($creap_option_latitud) && isset($creap_option_longitud) && is_numeric($creap_option_longitud))
		{
		?>
			var myLatLng = {lat: <?php esc_html_e($creap_option_latitud) ?>, lng: <?php esc_html_e($creap_option_longitud) ?>};
			
			
			
		<?php
		}else
		{
			?>
			var myLatLng = {lat: 36.8688, lng: -4.0000};
			<?php	
		}
		?>
		// Create a marker for each place.
			var marker = new google.maps.Marker({
			  map: map,
			  icon: '<?php echo plugins_url('images/location2.png',__DIR__);  ?>',
			  title: "situacion",
			  draggable: true,
			  position: myLatLng
			});
			
			marker.addListener('dragend', function() {
				
					jQuery('#prop_lat').val(this.position.lat());
					jQuery('#prop_lng').val(this.position.lng());
				  });
			markers.push(marker);
			
			marker.setMap(map);
			
	
}
        
        </script>
		<?php
        if(!empty($opt_plugin['vre_option_apikey']))
        {
			$apikey="";
			$apikey=sanitize_text_field($opt_plugin['vre_option_apikey']);
        ?>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4wj45CDw0RRiWxFqqm7ClhnACud7Ocsc&libraries=places&callback=initAutocomplete"
             async defer></script>
         <?php
        }else
        {
        ?>
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=initAutocomplete"    async defer></script>
         <?php
        }