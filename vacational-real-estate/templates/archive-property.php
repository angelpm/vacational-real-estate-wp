<?php
/**
 * Template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */

get_header();
?>

<div id="wraper" class="content-area container-page">
    <div class="site-content">
    	<div class="vre-property-content">
        	<?php the_archive_title('<h2>','</h2>'); ?>
	        <?php
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	        $meta_query=array(
    	        'relation' => 'AND',
        	    array(
            	    'key' => 'vre_prop_deshabilitado',
                	'value' => 'off',
                     )
		        );

			if(!empty($_GET))
			{
				foreach($_GET as $index=> $opt)
				{
					$temp_index=sanitize_text_field( $index);

					if(substr($temp_index,0,13)=="vre_prop_tipo")
					{
						$idterm= substr($index,17,4);
						$termsid[]=$temp_index;

					}elseif(!empty($_GET[$index]))
					{
						/*$idterm= substr($index,17,4);
						$termsidtipo[]=$idterm;*/
						$meta_query[]=array(
							'key' => $index,
							'value' => $opt
						);
					}

				}

				if(!empty($termsid))
				{
					$filter_get[]=__('Caracteristicas','vacational-real-estate');
					$tax_query[]=array('taxonomy' => 'creap_features_tax',
						'terms'    => $termsid);
				}

				if(!empty($termsidtipo))
				{
					$filter_get[]=__('Tipo','vacational-real-estate');
					$tax_query[]=array('taxonomy' => 'vre_city_tax',
						'terms'    => $termsid);
				}
			}

			$args=array(
				'post_type' => 'vre_property_cp',
				'posts_per_page' => 10,
				'orderby'=> 'menu_order',
				'paged'=>$paged,
				'meta_query' => $meta_query,
				'tax_query' => $tax_query
			);
			$loop = new WP_Query($args);
			$vre_saved_opt = get_option('vre_propertywp_options');

			while ( $loop->have_posts() ) : $loop->the_post();

				$vre_prop_year=get_post_meta(get_the_ID(), 'vre_prop_year', true);
				$vre_property_rooms=get_post_meta(get_the_ID(), 'vre_property_rooms', true);
				$creap_property_parking=get_post_meta(get_the_ID(), 'creap_property_parking', true);
				$vre_property_service=get_post_meta(get_the_ID(), 'vre_property_service', true);
				$vre_property_price_night=get_post_meta(get_the_ID(), 'vre_property_price_night', true);
				$vre_property_pricesale=get_post_meta(get_the_ID(), 'vre_property_pricesale', true);
				$vre_prop_referencia=get_post_meta($post->ID, 'vre_prop_referencia', true);

				$vre_rent= ($vre_property_service && ($vre_property_service==VRE_RENT_PROPERTY || $vre_property_service==VRE_RENT_SALE_PROPERTY))? true:false;
				$vre_sale= ($vre_property_service && ($vre_property_service==VRE_SALE_PROPERTY || $vre_property_service==VRE_RENT_SALE_PROPERTY))? true:false;
				$price_sale_str= (!empty($vre_property_pricesale) && ($vre_property_pricesale>0))? $vre_property_pricesale:"";
				$price_sale_str= ($price_sale_str && $vre_saved_opt['vre_currency_position']==VRE_CURRENCY_POSITION_LEFT)? $vre_saved_opt['vre_currency']." ".$price_sale_str:$price_sale_str." ".$vre_saved_opt['vre_currency'];

				$price_rent_str=(!empty($vre_property_price_night) && ($vre_property_price_night>0))?$vre_property_price_night:"";
				$price_rent_str= ($price_rent_str && $vre_saved_opt['vre_currency_position']==VRE_CURRENCY_POSITION_LEFT)? $vre_saved_opt['vre_currency']." ".$price_rent_str:$price_rent_str." ".$vre_saved_opt['vre_currency'];

				if(!empty($vre_prop_referencia))
					$vre_prop_referencia="#".$vre_prop_referencia."-";

				?>
				<div class="vre-property-listing ">
					<div class="vre-title-property-div">
						<a href="<?php echo get_permalink()?>" title=" <?php  the_title_attribute( 'echo=0' )?>" rel="bookmark"><?php   the_title("<h4>$vre_prop_referencia",'</h4>')?></a>

					</div>
					<div class="vre-advice-property-listing">
						<?php

						$advice=array();
						if(has_filter('vre_add_advice_property_listing')):
							$advice=apply_filters('vre_add_advice_property_listing',$advice);
							_e($advice,'vacational-real-estate');
						endif;
						?>
					</div>
					<div class="row-no-gutters">
            <div class="vre-div-image-sm-list" >
                <div class="vre-top-image-advice">
                    <?php if($vre_sale):?>
                        <?php _e('Sale','vacational-real-estate'); ?>
                        <?php if($vre_rent) echo ("/"); _e('Rent','vacational-real-estate');?>
                    <?php elseif($vre_rent): ?>
                        <?php _e('Rent','vacational-real-estate');?>
                    <?php endif; ?>
                </div>

                <?php if ( has_post_thumbnail() ): ?>
                    <?php
                    $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), array('300','200') );
                    $image = $image_url[0];
                    ?>
                    <img src="<?php echo  $image; ?>" width="100%" height="auto" alt="<?php the_title_attribute( 'echo=0' ) ?>" />
                <?php endif; ?>
            </div>

            <div class="vre-div-text-sm-list">
                <?php
                $vre_prop_route=get_post_meta($post->ID, 'vre_prop_route', true);
                $vre_prop_postal_code=get_post_meta($post->ID, 'vre_prop_postal_code', true);
                $vre_prop_mtconstruido=get_post_meta($post->ID, 'vre_prop_mtconstruido', true);
                $creap_property_parking=get_post_meta($post->ID, 'creap_property_parking', true);
                $vre_prop_locality=get_post_meta($post->ID, 'vre_prop_locality', true);
                $vre_property_rooms=get_post_meta($post->ID, 'vre_property_rooms', true);
                $vre_property_banos=get_post_meta($post->ID, 'vre_property_banos', true);
                $vre_catname=get_the_category( $post->ID);

                if(!empty($vre_catname) && (isset($vre_catname[0]->name)))
                    $vre_catname=$vre_catname[0]->name;
                ?>
                  <div class="vre-feature-property-sm" >
                    <div style="margin-bottom:4px;">
                      <img style=" float:left; margin-right: 4px;" src="<?php echo plugins_url( 'public/images/home.png', dirname(__FILE__) );?>" width="22px" alt="<?php __('Home','vacational-real-estate') ?>">
                      <?php esc_html_e($vre_catname,'vacational-real-estate'); ?>
                    </div>
                    <div  style="margin-bottom:4px;">
                    	<img style=" float:left; margin-right: 4px;" src="<?php echo plugins_url( 'public/images/location.png', dirname(__FILE__) );?>" width="16px" alt="<?php __('Location property','vacational-real-estate') ?>"><?php esc_html_e($vre_prop_locality,'vacational-real-estate');?><br />
                      <?php echo wp_trim_words( get_the_content(), 10, '...' ); ?>
                    </div>
                  </div>
                  <div class="vre-price-div-content" style=" border-left:2px solid #4d97ad; padding:0% 2%;">
                    <div class="vre-div-section-prices">
                     <?php if(!empty($vre_sale) && ($vre_sale)):
										  _e('Sale:','vacational-real-estate');
											echo '<br />';
											echo '<div class="vre_property_prices">';
											esc_html_e($price_sale_str,'vacational-real-estate');
											echo '</div>';
											echo '<br />';
											?>
										<?php endif; ?>
                    <?php if(!empty($vre_rent) && ( $vre_rent)): ?>
                        <?php $seasons = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vreseasons WHERE name='price_base' AND post_id = '".$post->ID."'"); ?>
                        <?php _e('Rent From','vacational-real-estate'); echo '<br />'; ?>
                        <?php if(!empty($seasons) && isset($seasons[0]->pricepernight)):?>
                            <?php echo '<span class="vre_property_rooms">'; ?>
                            <?php esc_html_e($price_rent_str,'vacational-real-estate'); ?>
                            <?php esc_html_e($seasons[0]->pricepernight,'vacational-real-estate'); ?>
                            <?php echo '</span>'; ?>
                        <?php else: ?>
                        <?php echo '<div class="vre_property_prices">'; ?>
                        <?php esc_html_e($price_rent_str,'vacational-real-estate'); ?>
                        <?php echo '</div>'; ?>
                        <?php endif; ?>
                    <?php elseif(!empty($vre_property_price_night) || !empty($vre_property_price_month)): ?>

                        <span class='vre_property_rooms'>
                            <?php
                            esc_html_e($price_rent_str,'vacational-real-estate');
                            ?>
                        </span>
                    <?php endif;?>
                		<br />
                		<button type="submit"  onclick="location.href='<?php echo get_permalink()?>';" class="vre-buttom-book"><?php _e('Book','vacational-real-estate'); ?></button>
                	</div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="vre-div-icons-features">
              	<div class="width25">
              		 <a href=""><img src="<?php echo plugins_url( 'public/images/square.png', dirname(__FILE__) );?>" width="18px" alt="<?php _e('Surface','vacational-real-estate'); ?>">
              		 <?php esc_html_e($vre_prop_mtconstruido,'vacational-real-estate'); ?> m<sup>2</sup></a>
              	</div>
              	<div  class="width25">

              		<a href=""><img src="<?php echo plugins_url( 'public/images/car.png', dirname(__FILE__) );?>" width="24px" alt="<?php _e('parking','vacational-real-estate'); ?>">
              		<?php esc_html_e($creap_property_parking,'vacational-real-estate');?></a>

              	</div>
              	<div  class="width25">

              		<a href=""><img src="<?php echo plugins_url( 'public/images/bed.png', dirname(__FILE__) );?>" width="24px" alt="<?php _e('Bedroom','vacational-real-estate'); ?>"> 						<?php esc_html_e($vre_property_rooms,'vacational-real-estate');?></a>
              	</div>
              	<div class="width25">

              		<a href=""><img src="<?php echo plugins_url( 'public/images/bath.png', dirname(__FILE__) );?>" width="24px" alt="<?php _e('bathroom','vacational-real-estate'); ?>">
              		<?php esc_html_e($vre_property_banos,'vacational-real-estate');?></a>
              	</div>

              	<div style="clear:both"></div>
              </div><!-- .vre-div-icons-features -->
            <div style="clear:both"></div>

          </div>
	       </div><!-- .propertydiv -->
				<?php
				do_action('vre_after_property_list_content',1);


        endwhile;

        $big = 999999;
        echo paginate_links( array('base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),	'format' => '?paged=%#%','current' => max( 1, get_query_var('paged') ),	'total' => $loop->max_num_pages	) );
        wp_reset_postdata();

        ?>

        </div><!-- /.blog-main -->
        <?php if ( is_active_sidebar( 'vre-propertylist-sidebar' ) ) : ?>
        <div id="sidebar" class="sidebar">
        <?php dynamic_sidebar( 'vre-propertylist-sidebar' ); ?>
        </div>
    <?php endif; ?>

    <div style="clear:both"></div>
  </div><!-- /.row -->
 </div>
<?php get_footer(); ?>
