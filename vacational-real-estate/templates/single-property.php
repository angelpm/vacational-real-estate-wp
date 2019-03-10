<?php
/**
 * Template for displaying all single posts property
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class=" vre-wrap-perproperty">
			<div class="top-booking-form">
				<?php
						require( plugin_dir_path( __FILE__ ) .'widget/booking-form.php');
				 ?>
			</div>
			<br clear="both">
      <div style=" float:left; width:63%; margin-right:1%; ">

				<?php
        $metas = get_post_meta( $post->ID,'vre_property_thumbnail_id' );

				if (have_posts()) :
					while (have_posts()) : the_post();

						$vre_prop_route=get_post_meta($post->ID, 'vre_prop_route', true);
						$vre_prop_postal_code=get_post_meta($post->ID, 'vre_prop_postal_code', true);
						$vre_prop_mttotal=get_post_meta($post->ID, 'vre_prop_mttotal', true);
						$vre_property_rooms=get_post_meta($post->ID, 'vre_property_rooms', true);
						$vre_property_banos=get_post_meta($post->ID, 'vre_property_banos', true);
						$vre_property_service=get_post_meta($post->ID, 'vre_property_service', true);
						$vre_prop_locality=get_post_meta($post->ID, 'vre_prop_locality', true);
						$vre_prop_level_2=get_post_meta($post->ID, 'vre_prop_level_2', true);
						$vre_catname=get_the_category( $post->ID);
						$vre_catname=$vre_catname[0]->name;
						$vre_property_reduced=get_post_meta($post->ID, 'vre_property_reduced', true);
						$vre_property_price_night=false;
						$vre_property_price_month=false;
						$vre_ckrent_holidays=get_post_meta($post->ID, 'vre_ckrent_holidays', true);
						$vre_ckrent_month=get_post_meta($post->ID, 'vre_ckrent_month', true);
						$vre_property_pricesale=get_post_meta(get_the_ID(), 'vre_property_pricesale', true);
						$vre_prop_referencia=get_post_meta($post->ID, 'vre_reference', true);
						$vre_prop_mtconstruido=get_post_meta($post->ID, 'vre_prop_mtconstruido', true);
						$vre_prop_mttotal=get_post_meta($post->ID, 'vre_prop_mttotal', true);
						$vre_prop_year=get_post_meta($post->ID, 'vre_prop_year', true);
						$vre_prop_deshabilitado=get_post_meta($post->ID, 'vre_prop_deshabilitado', true);
						$property_orientacion=get_post_meta($post->ID, 'property_orientacion', true);
						$creap_prop_ocupado=get_post_meta($post->ID, 'creap_prop_ocupado', true);
						$vre_property_rooms=get_post_meta($post->ID, 'vre_property_rooms', true);
						$creap_property_parking=get_post_meta($post->ID, 'creap_property_parking', true);

						$vre_property_reduced=get_post_meta($post->ID, 'vre_property_reduced', true);
						$property_noche=get_post_meta($post->ID, 'property_noche', true);
						$property_precioper=get_post_meta($post->ID, 'property_precioper', true);
						$vre_property_rooms=get_post_meta($post->ID, 'vre_property_rooms', true);
						$vre_property_banos=get_post_meta($post->ID, 'vre_property_banos', true);

						if($vre_property_service && $vre_property_service!="venta")
						{
							$vre_property_price_night=get_post_meta($post->ID, 'vre_property_price_night', true);
							$vre_property_price_month=get_post_meta($post->ID, 'vre_property_price_month', true);
						}

						$vre_rent= ($vre_property_service && ($vre_property_service==VRE_RENT_PROPERTY || $vre_property_service==VRE_RENT_SALE_PROPERTY))? true:false;
						$vre_sale= ($vre_property_service && ($vre_property_service==VRE_SALE_PROPERTY || $vre_property_service==VRE_RENT_SALE_PROPERTY))? true:false;
						$price_sale_str= (!empty($vre_property_pricesale) && ($vre_property_pricesale>0))? $vre_property_pricesale:"";
						$price_sale_str= ($price_sale_str && $vre_saved_opt['vre_currency_position']==VRE_CURRENCY_POSITION_LEFT)? $vre_saved_opt['vre_currency']." ".$price_sale_str:$price_sale_str." ".$vre_saved_opt['vre_currency'];

						$price_rent_str=(!empty($vre_property_price_night) && ($vre_property_price_night>0))?$vre_property_price_night:"";
						$price_rent_str= ($price_rent_str && $vre_saved_opt['vre_currency_position']==VRE_CURRENCY_POSITION_LEFT)? $vre_saved_opt['vre_currency']." ".$price_rent_str:$price_rent_str." ".$vre_saved_opt['vre_currency'];

						$options = get_option('vre_propertywp_options');
						?>
						<div class="vre-single-property-div" id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
			        <div class="vre-single-gallery">
			        	<?php if($vre_property_reduced):?>
			                <span class="offerdiv"><?php _e('Reduced','vacational-real-estate'); ?></span>
			          <?php endif; ?>
		            <a href="" id="rightarrow" class="vre-galleryarrow">
		                <img src="<?php echo plugins_url( 'public/images/rightarrow.png', dirname(__FILE__) );?>" width="35px"  height="auto" alt="arrow" />
		            </a>
		            <a href="" id="leftarrow" class="vre-galleryarrow" >
		                <img src="<?php echo plugins_url( 'public/images/leftarrow.png', dirname(__FILE__) );?>" width="35px"  height="auto" alt="arrow" />
		            </a>

		            <?php $srcimg=get_the_post_thumbnail_url(get_the_ID(), 'full' ); ?>
		            <?php if(!empty($srcimg)): ?>
		                <img class="active " src="<?php esc_html_e($srcimg,'vacational-real-estate'); ?>" width="100%" height="auto" alt="image property" />
		            <?php endif; ?>
		            <?php if(!empty($metas)): ?>
		                <?php foreach($metas as $img): ?>
		                    <?php $src=( wp_get_attachment_image_src( $img,'vre-property-img-full' )); ?>
		                    <?php if (!empty($src) && isset($src[0])): ?>
		                        <img class="imaggallery inactive" src="'.$src.'" width="100%" height="auto" alt="foto galeria">
		                    <?php endif; ?>
		                <?php endforeach; ?>
			          <?php endif; ?>

			        </div><!-- .vre-single-gallery -->
							<div class="vre-features">
                <div class="vre-div-icons-features" style="">
                  <h2 ><?php the_title(); ?></h2>
                	<div style="font-size:14px; ">
                    <div class="width50" style="color:#9a9a9a">
                        <img src="<?php echo plugins_url( 'public/images/location.png', dirname(__FILE__) );?>" width="18px" alt="<?php _e('Area','vacational-real-estate'); ?>">
                        <?php esc_html_e($vre_prop_level_2,'vacational-real-estate');  ?> / <?php esc_html_e($vre_prop_locality,'vacational-real-estate'); ?>
                    </div>
                    <div class="" style="float:right; margin-right:10px;">

                    <?php if(!empty($vre_sale) && ($vre_sale)): ?>
   <?php _e('Sale Price:','vacational-real-estate'); ?>
                       <div class="vre_property_prices">
                           <?php esc_html_e($price_sale_str,'vacational-real-estate'); ?>
                       </div>
                    <?php endif; ?>
                    <?php if(!empty($vre_rent) && ( $vre_rent)): ?>
                        <?php _e('Rent From','vacational-real-estate'); echo '<br />'; ?>

                        <?php echo '<div class="vre_property_prices">'; ?>
                        <?php esc_html_e($price_rent_str,'vacational-real-estate'); ?>
                        <?php echo '</div>'; ?>

                    <?php endif; ?>
                    </div>

				            <div class="width50 ">
				            <?php if(($vre_property_service==VRE_RENT_PROPERTY) || ($vre_property_service==VRE_RENT_SALE_PROPERTY)): ?>
				                <?php if($vre_ckrent_holidays && $vre_ckrent_holidays=="on"): ?>
				                    <img class="margin_both_10" src="<?php echo plugins_url( 'public/images/check.png', dirname(__FILE__) )?>" height="12" width="16" align="check" />
				                    <?php _e('Holiday','vacational-real-estate'); ?>
				                <?php endif;?>
				                <?php if($vre_ckrent_month && $vre_ckrent_month=="on"): ?>
				                    <img  src="<?php echo plugins_url( 'public/images/check.png', dirname(__FILE__) )?>" height="12" width="16" align="check" />
				                    <?php _e('Long Season','vacational-real-estate'); ?>
				                <?php endif; ?>
				            <?php endif; ?>
				            </div>

                    <br clear="all" />
                  </div><!-- .vre-features -->
                  <br clear="all" />

                <div style=" min-height:250px;">
                  <div id="content1"  style="clear:left; border-top:1px solid #abc;padding: 20px;">
                    <div class="divgeneraldata" >

                        <?php if($vre_property_service==VRE_SALE_PROPERTY):?>
                            <?php _e("Sell", 'vacational-real-estate'); ?>
                        <?php elseif($vre_property_service==VRE_RENT_PROPERTY):?>
                            <?php _e("Rent", 'vacational-real-estate'); ?>
                        <?php elseif($vre_property_service==VRE_RENT_SALE_PROPERTY):?>
                            <?php _e("Rent &amp; Sale", 'vacational-real-estate'); ?>
                        <?php endif; ?>

                    </div>
                    <div class="divgeneraldata" >
                        <?php _e('Reference: ', 'vacational-real-estate');?>
                        <?php esc_html_e($vre_prop_referencia,'vacational-real-estate');  ?>
                    </div>
                    <div class="divgeneraldata" >
                        <?php _e('Year: ', 'vacational-real-estate');?>
                        <?php esc_html_e($vre_prop_year,'vacational-real-estate'); ?>
                    </div>
                    <div class="divgeneraldata"  >
                        <?php _e('Parking: ', 'vacational-real-estate'); ?>
                        <?php esc_html_e($creap_property_parking,'vacational-real-estate');?>
                    </div>

                    <div class="divgeneraldata" >
                        <?php _e('Orientati&oacute;n: ', 'vacational-real-estate'); ?>
                        <?php esc_html_e($property_orientacion,'vacational-real-estate'); ?>
                    </div>
                    <div class="divgeneraldata" >
                        <?php _e('Bath: ', 'vacational-real-estate'); ?>
                        <?php esc_html_e($vre_property_banos,'vacational-real-estate'); ?>
                    </div>
                    <div class="divgeneraldata" >
                        <?php _e('Rooms: ', 'vacational-real-estate'); ?>
                        <?php esc_html_e($vre_property_rooms,'vacational-real-estate'); ?>
                    </div>
                    <div class="divgeneraldata" >
                        <?php _e('Parking: ', 'vacational-real-estate'); ?>
                        <?php esc_html_e($vre_property_banos,'vacational-real-estate'); ?>
                    </div>
                    <div class="divgeneraldata" >
                        <?php _e('Surface: ', 'vacational-real-estate'); ?>
                        <?php esc_html_e($vre_prop_mttotal,'vacational-real-estate'); ?> m<sup>2</sup>
                    </div>
                    <br clear="all" />
                  </div>

                  <div id="content2" class="tab-content" style="display:none;clear:left; border-top:1px solid #abc;padding: 20px 0 0;">
	                  <?php
	                  $terms = wp_get_post_terms( $post->ID, 'creap_features_tax', array("fields" => "all"));
	                  //print_r($terms);

	                      foreach($terms as $category)
	                      {
	                          //print_r($category);
	                          ?>
	                          <div class="wpinput-form">
	                              <label for="creap_<?php  esc_html_e($category->slug,'vacational-real-estate'); ?>"  style="float:left; font-weight:100"><input type="checkbox" name="prop_animales"  disabled="disabled" checked="checked"  style="height:20px;float:left; width:45px;"/><?php esc_html_e($category->name,'vacational-real-estate'); ?></label>
	                          </div> <br clear="all" />
	                          <?php
	                      }

	                  ?>
                  </div>

                  <div id="content3" class="tab-content" style="display:none;clear:left; border-top:1px solid #abc;padding: 20px 0 0;">
                      <?php the_content( __( 'Continuar Leyendo <span class="meta-nav">&rarr;</span>', 'vacational-real-estate' ) ); ?>
                  </div>
                </div>
            </div>
					</div>


					<div>
						<div class="proper-title-sec">
							<h4><?php _e('Situaci&oacute;n', 'wpb_widget_domain' ); ?></h4>
						</div>
						<?php
						$prop_lat=get_post_meta($post->ID, 'vre_prop_lat', true);
						$prop_lng=get_post_meta($post->ID, 'vre_prop_lng', true);

							if($prop_lat && $prop_lng)
							{
							?>
							 <div id="googleMap" style="width:98%; height:400px; margin:1%;"></div>

							 <script>


								  function initAutocomplete() {


									var myLatLng = {lat: <?php echo $prop_lat; ?>, lng: <?php echo $prop_lng; ?>};

									  var map = new google.maps.Map(document.getElementById('googleMap'), {
										zoom: 10,
										center: myLatLng
									  });

									  var marker = new google.maps.Marker({
										position: myLatLng,
										map: map,
										title: 'Hello World!'
									  });

								  }

								</script>
								<?php
								if(isset($options['vre_option_apikey']) && ($options['vre_option_apikey']))
								{
								?>
									<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $options['vre_option_apikey']; ?>&libraries=places&callback=initAutocomplete"
									 async defer></script>
								 <?php
								}else
								{
								?>
									<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&callback=initAutocomplete"    async defer></script>
								 <?php
								}


							}
							?>
					</div>
				</div>

			<?php endwhile; ?>
		<?php else :?>
		<?php endif; ?>

		</div><!-- /.blog-main -->

    <div id="div_sidebar_singleproperty" style="float:right; max-width:260px;">
			<br clear="all" />
      <div class="div_form_single_property">
        <h4><?php _e('Formulario de Contacto','vacational-real-estate')?></h4>
        <?php

				if(!empty($_POST['email_contact']) && is_email($_POST['email_contact']) && !empty($_POST['message_contact']) && ($_POST['message_contact']))
				{
					$affair_contact=sanitize_text_field($_POST['affair_contact']);
					$movil=sanitize_text_field($_POST['movil_contact']);
					$admin_email = get_option( 'admin_email' );
					$to = $admin_email;
					$subject = get_site_url().' - Formulario de contacto: '.$affair_contact;
					$body="Un nuevo usuario se ha puesto en contacto a traves del formulario de contacto<br><br>";
					$body = $body."Asunto: ".$affair_contact."<br>";
					$body = $body."Telefono: ".$movil."<br>";

					$body = $body.sanitize_text_field($_POST['message_contact']);
					$headers = array('Content-Type: text/html; charset=UTF-8');

						if($result=wp_mail( $to, $subject, $body, $headers ))
						{
							_e('<div class="succes_form">','vacational-real-estate');
							_e('El mensaje se ha enviado con exito.','vacational-real-estate');
							_e('</div>','vacational-real-estate');
						}

				}
				?>
        <form action=""  method="post">
            <label><?php _e('Asunto','vacational-real-estate')?></label>
            <input type="text" name="affair_contact"  /><br />
            <label><?php _e('Movil','vacational-real-estate')?></label>
            <input type="text" name="movil_contact"  /><br />
            <label><?php _e('Email','vacational-real-estate')?></label>
            <input type="text" name="email_contact" required /><br />
            <label><?php _e('Mensaje','vacational-real-estate')?></label>
            <textarea name="message_contact" required ></textarea><br />

            <input type="submit" name="contact_form" value="<?php _e('Enviar','vacational-real-estate')?>" /><br />

        </form>
    </div>
  </div>


 </div>
<div style="clear:both;"></div>
</main>
</div><!-- /.row -->

	<?php get_footer(); ?>
