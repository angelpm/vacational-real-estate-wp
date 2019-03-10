<?php
/**
 * Vre_Property_Gallery Class
 *
 * manage the gallery for the custom post property. Add a gallery pictures for each property custom post
 *
 * @package    vacational-real-estate
 * @subpackage vacational-real-estate/includes
 * @author     Your Name <email@example.com>
 */

class Vre_Property_Gallery {


	/**
	 * The post type to enable Gallery
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array string    $version    1.0.0.
	 */
	private $post_type;
	
	
	/**
	 * Initialize the class and add actions
	 * Add gallery metabox for custom post property
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function  __construct()
	{	
		$post_type=array("vre_property_cp");
		
		//register function for metabox for gallery property 
		add_action( 'add_meta_boxes', array($this,'gallery_property_add_meta_boxes') );
		
		//enqueue the scripts
		add_action( 'admin_enqueue_scripts', array($this,'gallery_property_admin_scripts') );
		
		//register the save function gallery
		add_action( 'save_post', array($this,'gallery_property_save') );
	
	}
	
	
	/**
	 * Function callback for add  Gallery metabox for de Custom Post Property 
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function gallery_property_add_meta_boxes()
	{
		add_meta_box('gallery_property_metabox',__('Galleria'),array($this,'gallery_property_code'),'vre_property_cp',	'normal','default');
		
	}
	
	
	/**
	 * Display section (metabox) Gallery for de Custom Post Property 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param      object    $post    Actual post
	 */
	public function gallery_property_code($post)
	{
		//generate nonce field for submit security
		wp_nonce_field( 'vre_property_gallery_save_submit', 'property_gallery_save_nonce' );
		
		//get actual gallery meta for the post
    	$metas_thumbnail = get_post_meta( $post->ID,'vre_property_thumbnail_id' ); 
		
		echo'<p>'._e('Gallery','vacational-real-estate').'</p>
			<div class="vre-meta-image-preview"></div>';

		if(!empty($metas_thumbnail))
		{
					
			foreach($metas_thumbnail as $img)
			{
				
				$imgsrc= wp_get_attachment_image( $img,'thumbnail', "" ); 
				
				echo '<div class="vre-meta-image-preview">
					<div class="deletediv">
					<a href="" class="linkdeletediv" id="propertywp-link-'.esc_html($img,'vacational-real-estate').'">
					X</a></div>'.wp_get_attachment_image( $img,'thumbnail', "" ).
					'<input type="hidden" id="myplugin-image-input'.esc_html($img,'vacational-real-estate').'"  name="vre_attachment_id_array[]" 
					value="'.esc_html($img,'vacational-real-estate').'" ></div>';
			
			}	
			
		}
				
				
		echo '</div><br clear="all" /><input type="button" id="meta-image-button" class="button" value="'.__('Choose or Upload an Image','vacational-real-estate').'" />';
		

	}


	/**
	 * function for save images Gallery for de Custom Post Property 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param      object    $post_id    Actual post ID
	 */	
	public function gallery_property_save($post_id)
	{
		
		// if this fails, check_admin_referer() will automatically print a "failed" page and die.
		if ( ! empty( $_POST['vre_attachment_id_array'] ) && check_admin_referer(  'vre_property_gallery_save_submit', 'property_gallery_save_nonce'  ) ) 
		{
			
			//delete all the attached images from property
			delete_post_meta( $post_id,  "vre_property_thumbnail_id" );
			
			
			
			if(!empty($_POST['vre_attachment_id_array']))
			{
				//filter array images src
				$args = array(
				  'vre_attachment_id_array'    => array(
					  'name' => 'vre_attachment_id_array',
					  'filter' => FILTER_SANITIZE_STRING,
					  )
				);
				
				$form_inputs = filter_input(INPUT_POST, 'vre_attachment_id_array', FILTER_DEFAULT , FILTER_REQUIRE_ARRAY); 
				
				if($form_inputs)
				{
				
					foreach( $form_inputs as $cpi_image ) 
					{
						// Update the meta field if we have data
						if( $cpi_image ) 
						{
							add_post_meta( $post_id,  "vre_property_thumbnail_id", $cpi_image );
		
						} 
					}
				}
				
			}
			
		}
	}
	
	
	
}

//create a new instace of class
$custom_post_gallery = new Vre_Property_Gallery();

