<div class="container wrap_vre_admin_page" ><!-- div container .wrap_vre_admin_page -->
				
								
    <h2><?php _e('Complete Real Estate WP Admin Menu','vacational-real-estate'); ?></h2>
    <div class="">
        <div class="vre_menu_admin_page" style="float:left; width:24%; margin-right:2%;">
        <h3><?php _e('Menu','vacational-real-estate');?></h3>
        <ul>
        <li><a href=""><?php _e('General','vacational-real-estate');?></a></li>
        </ul>
        </div>
    
    
        <div class="dbx-content" style="float:left; width:74%;"><!-- div container .content -->
            <form action="<?php echo $vre_action_url ?>" method="post">
            
            <input type="hidden" name="vre_submitted" value="1" />
            <?php wp_nonce_field('vre-complete-realestate'); ?>

           
            <h3><?php _e('Opciones','vacational-real-estate');?></h3>
            <p><?php _e('Selecciona opcion de Venta, Alquiler o ambos.','vacational-real-estate'); ?></p>
            
            <input type="checkbox" name="vre_service_option[]" value="<?php _e('Sale','vacational-real-estate'); ?>"  <?php  checked( $vre_service_option, 'S', true )?> class="vre_select_service_admin" id="vre_sale_option" />
            <label for="posts"><?php _e('Solo Venta','vacational-real-estate'); ?> </label> <br />
            
            <input type="checkbox" name="vre_service_option[]"  <?php  checked( $vre_service_option, 'R', true )?> value="<?php _e('Rent','vacational-real-estate'); ?>" class="vre_select_service_admin" id="vre_rent_option" />
            <label for="pages"><?php _e('Solo Alquiler','vacational-real-estate');?> </label> <br />
            
            <input type="checkbox" name="vre_service_option[]" value="<?php _e('Sellandrent','vacational-real-estate'); ?>"  <?php  checked( $vre_service_option, 'RS', true )?> class="vre_select_service_admin" id="vre_both_option" />
            <label for="pages"><?php _e('Venta y Alquiler','vacational-real-estate'); ?> </label> <br />
            <br />
            <?php _e('Moneda','vacational-real-estate'); ?>  
            <input type="input" name="vre_currency" size="2" value="<?php esc_html_e($vre_currency,'vacational-real-estate'); ?>" /><br /><br />
            
            <?php _e('Posicion de la Moneda','vacational-real-estate'); ?> 
            <select name="vre_currency_position">
                <option  <?php  selected( $vre_currency_position, 'D', true )?>><?php _e('Derecha','vacational-real-estate'); ?></option>
                <option <?php  selected( $vre_currency_position, 'I', true )?>><?php _e('Izquierda','vacational-real-estate'); ?></option>
            </select>
            <br /><br />
            <?php _e('Thousands Separator','vacational-real-estate'); ?>  
            <input type="input" name="vre_separator_thousand" size="2" value="<?php esc_html_e($vre_separator_thousand,'vacational-real-estate'); ?>" /><br /><br />
            
            <br /><br />
            <?php _e('Numbers Decimal','vacational-real-estate'); ?>  
            <input type="input" name="vre_number_decimals" size="2" value="<?php esc_html_e($vre_number_decimals,'vacational-real-estate'); ?>" /><br /><br />
            
            <?php _e('Inicio de la Semana','vacational-real-estate'); ?> 
            <select name="vre_day_week">
                <option <?php  selected( $vre_day_week, 'L', true )?>><?php _e('Lunes','vacational-real-estate'); ?></option>
                <option <?php  selected( $vre_day_week, 'D', true )?>><?php _e('Domingo','vacational-real-estate'); ?></option>
            </select>
            
            <h3><?php _e('Opciones de Reserva','vacational-real-estate'); ?></h3>
            
            <p><?php _e('&iquest;Quieres administrar calendario para las propiedades en Alquiler?','vacational-real-estate'); ?></p>
            
            <input type="checkbox" name="vre_calendar_option" <?php  checked( $vre_calendar_option, 'on', true )?>  />
            <label for="vre_calendar_option"><?php _e('Calendario de Propiedades','vacational-real-estate'); ?> </label> <br />
            <br />
            
            <h3><?php _e('API Google Map','vacational-real-estate'); ?></h3>
            <p><?php _e('Para manejar la API del mapa de google debe insertar su Google API Key','vacational-real-estate'); ?> </p>
            <input  size="100" type="text" name="vre_option_apikey" value="<?php esc_html_e($vre_option_apikey,'vacational-real-estate'); ?>" >
            <p><?php _e('&iquest;Sabe como obtener una  Google API Key?','vacational-real-estate'); ?><a href="https://developers.google.com/maps/documentation/javascript/get-api-key?hl=ES"><strong>click</strong></a></p><br />
            
            <p><?php _e('Latitud por defecto (coordenadas)','vacational-real-estate'); ?> </p>
            <input  size="30" type="text" name="vre_option_latitud" value="<?php esc_html_e($vre_option_latitud,'vacational-real-estate'); ?>" >
            
            <p><?php _e('Longitud por defecto (coordenadas)','vacational-real-estate'); ?> </p>
            <input  size="30" type="text" name="vre_option_longitud" value="<?php esc_html_e($vre_option_longitud,'vacational-real-estate'); ?>" >
            
            <p><?php _e('Zoom por defecto ','vacational-real-estate'); ?> </p>
            <input  size="30" type="text" name="vre_option_zoom" value="<?php esc_html_e($vre_option_zoom,'vacational-real-estate'); ?>" >
            
            <br />
            
            <input type="submit" name="Submit"	value="<?php _e('Actualizar','vacational-real-estate'); ?>" />
            
            </form>
        </div><!--  .content -->
        <div style="clear:both"></div>
     </div><!--  .row -->
</div><!-- .wrap_vre_admin_page -->