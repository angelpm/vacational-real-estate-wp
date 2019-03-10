/**
 * JQuery Admin section for Complete Real Estate WP
 *
 * Control tigger for season tax, manager calendar
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @author URI: https://webdesign.portfoliobox.net/
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */
 
jQuery(document).ready(function() {
								
	
	jQuery('#wpapselectproperty').live('change',function(e){
	
		var temp=jQuery(this).find('option:selected')[0];	
		var selects = jQuery('#wpapselectproperty option:selected').attr('data-url');
		window.location.href = selects;
	});


	jQuery('.vre-content-calendar #wpapReservar').live("click",function(e){
					
								
		var property_id=jQuery('#wpapselectproperty').val();
		var FechaDesde=jQuery('#FechaDesdeReserva').val();
		var FechaHasta=jQuery('#FechaHastaReserva').val();
		var user_id=jQuery('#select_user_reserv').val();
		var year=jQuery('#actual_year').val();
		var email=jQuery('#bookingemail').val();
		var phone=jQuery('#bookingphone').val();
		
	
		if(!user_id || !property_id || !FechaHasta || !FechaDesde || !email || !phone)
		{
			if(!property_id)
			{
				alert('debe seleccionar antes la propiedad');
			}else if(!user_id)
			{
				alert('debe seleccionar el usuario');
			}else
			{
				alert('debe seleccionar el usuario');
			}
				
		}else
		{
		
			jQuery.ajax({
					url : dcms_vars.ajaxurl,
					type: 'post',
					data: {
						action : 'creap_booking_date_property',
						property_id:property_id,
						FechaDesde:FechaDesde,
						FechaHasta:FechaHasta,
						user_id:user_id,
						year:year,
						email:email,
						phone:phone
					},
					beforeSend: function(){
						//link.html('Cargando ...');
					},
					success: function(resultado){
						
						/*jQuery('#divblackwindowreserve_result').html(resultado);
						jQuery('.selectedday').addClass('checked');
						jQuery('.selectedlast').addClass('checked');
						jQuery('.selectedfirst').addClass('checked')
						
						jQuery('.selectedday').removeClass('selectedday');
						jQuery('.selectedlast').removeClass('selectedlast');
						jQuery('.selectedfirst').removeClass('selectedfirst');*/
		
					}
		
				});
		}
		
		return false;
																   
	});

	jQuery('#wpapEliminar').live('click',function(e){
											
		date_from=jQuery('#FechaDesdeReserva').val();
		date_to=jQuery('#FechaHastaReserva').val();	
		booking_id=jQuery('#hide_booking_id').val();
		
		jQuery.ajax({
				url : dcms_vars.ajaxurl,
				type: 'post',
				data: {
					action : 'creap_delete_booking_property',
					bookingid:booking_id,
					
				},
				beforeSend: function(){
					//link.html('Cargando ...');
				},
				success: function(resultado){
					
					var bookings= jQuery('#divmonthscalendar .activereserved[data-booking-id="'+booking_id+'"]').each(function() {
						jQuery('#divmonthscalendar .activereserved[data-booking-id="'+booking_id+'"]').removeClass('activereserved');
					 });
					
					jQuery('#divblackwindowreserve').fadeOut();
					
				}
	
			});

		return false;
	});


	jQuery('#vre_ckrent_holidays').click(function(e){

		if(jQuery(this).prop('checked')==true)
		{
			jQuery('#sectionseasons').show();
			jQuery('#vre_property_price_night').show();
			
		}else
		{	
			jQuery('#vre_property_price_night').val('');
			jQuery('#vre_property_price_night').hide();
			jQuery('#sectionseasons').hide();
			
		}
	
	});


	jQuery('#vre_ckrent_month').click(function(e){

		if(jQuery(this).prop('checked')==true)
		{
			jQuery('#vre_property_price_month').show();
			
		}else
		{
			jQuery('#vre_property_price_month').val('');
			jQuery('#vre_property_price_month').hide();
		}
	
	});


	jQuery('.creap_select_service_admin').click(function(e){
	
		if(jQuery(this).prop('checked')==true)
		{
			var str_id="#"+jQuery(this).attr('id');
			jQuery('.creap_select_service_admin').prop('checked',false);
			jQuery(str_id).prop('checked',true);
		}
		
	});

jQuery('.fullcalendar .divdayadm').live("click",function(e){
//console.log(jQuery('.selectedfirst').length);
	//comprobamos si existe seleccionado el primer dia
	
	//if is reserved dont do nothing
	if(jQuery(this).hasClass('checked'))
	{
	
	//if is booked we give the option to delete booking
	}else if(jQuery(this).hasClass('activereserved'))
	{
		
		jQuery('#wpapEliminar').show();
		jQuery('#wpapReservar').hide();
		jQuery('#divblackwindowreserve').fadeIn();
		
		if(jQuery(this).attr('data-booking-id').length>0)
			jQuery('#divblackwindowreserve_result #hide_booking_id').val(jQuery(this).attr('data-booking-id'));
		
		if(jQuery(this).attr('data-phone').length>0)
			jQuery('#bookingphone').val(jQuery(this).attr('data-phone'));
		
		if(jQuery(this).attr('data-email').length>0)
			jQuery('#bookingemail').val(jQuery(this).attr('data-email'));
			
		jQuery('#FechaDesdeReserva').prop('disabled', true);
		jQuery('#FechaHastaReserva').prop('disabled', true);
		user_id=(jQuery(this).attr('data-user'));
		date_from=(jQuery(this).attr('data-datefrom'));
		date_to=(jQuery(this).attr('data-dateto'));
		jQuery('#select_user_reserv').val(user_id);
		
		jQuery('#FechaDesdeReserva').val(date_from);
		jQuery('#FechaHastaReserva').val(date_to);
		
		
		//jQuery('#wpapReservar').hide();
		
	}else
	{
		//if date from isnt checked, add class
		if(jQuery('.selectedfirst').length==0)
		{
			
			jQuery(this).addClass('selectedfirst');
			
		}else
		{
			jQuery('#bookingphone').val("");
			jQuery('#bookingemail').val("");
										
			jQuery('#wpapReservar').show();
			jQuery('#wpapEliminar').hide();
			//Obtenemos el strtime del primer dia seleccionado
			ordenfirst=jQuery('.selectedfirst').attr('data-orden');
			
			ordenhover=	jQuery(this).attr('data-orden');
			
			for(j=ordenfirst;j<ordenhover;j++)
			{
				//console.log('#daypromp'+j);
				if(jQuery('#daypromp'+j).length>0)
				{
					jQuery('#daypromp'+j).addClass('selectedday');		
					
				}
				
			}
			
			mydate=jQuery('.selectedfirst').attr('data-actual');
			
			
			
				if(mydate.length && mydate.length>0)
				{
					mydate=mydate.split('-');
						if(mydate && mydate.length>0 && mydate[1].length<2)
							mydate[1]="0"+mydate[1];
						
						if(mydate && mydate.length>0 && mydate[2].length<2)
							mydate[2]="0"+mydate[2];
							
					newdate=mydate[0]+"-"+mydate[1]+"-"+mydate[2];
					
					jQuery('#FechaDesdeReserva').val(newdate);
					
					jQuery('#daypromp'+j).addClass('selectedlast');
					
					
					jQuery('#divblackwindowreserve').fadeIn();
					
					mydate=jQuery('.selectedlast').attr('data-actual');
					
					mydate=mydate.split('-');
					
						if(mydate[1].length<2)
							mydate[1]="0"+mydate[1];
						
						if(mydate[2].length<2)
							mydate[2]="0"+mydate[2];
							
					newdate=mydate[0]+"-"+mydate[1]+"-"+mydate[2];
					
					jQuery('#FechaHastaReserva').val(newdate);
				}
			
			
			
			
			
			
			
			
		}
	}
	
	return false;
});


jQuery('.vre-content-calendar .monthlink').live('click',function(e){ 

	e.preventDefault();	
		
		
			
	month=parseInt(jQuery('#actual_month').val());
	year=parseInt(jQuery('#actual_year').val());
	post_id=jQuery("#inputpost_id").val();
		
	
		if(jQuery('#num_month').length>0)
		{
			num_month=parseInt(jQuery('#num_month').val());
		}
		
		if(jQuery(this).attr("id")=='nextmonthlink')
		{
			
			month= (month +num_month);
			
				if(month>12)
				{
					year=year+1;
					month= (month)%13 +1;
				}else
				{
					month= (month)%13;
				}
				
			
				if(month==0) 
					month=1;

			direction="next";
			
			
		}else
		{
			if(num_month==12)
			{
				mont=1; 
				year=year-1;
			}else
			{
				dif=num_month-month;
				month= (month -num_month);
			
			
				if(month<1)
				{
					month=12-dif;
					year=year-1;
				}
			}
			
			direction='prev';
			
		}
		
	
		jQuery.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'creap_getmonths_property',
				month:month,
				year:year,
				direction: direction,
				post_id:post_id,
				num_month:num_month
				
			},
			success: function(resultado){
				
				jQuery('#divmonthscalendar').html(resultado);		
			}

		});
		
	return false;
});

	jQuery('.cancelbotomm').on('click',function(){
			jQuery('#divblackwindowreserve').hide();
			jQuery('.selectedday').removeClass('selectedday');
			jQuery('.selectedlast').removeClass('selectedlast');
			jQuery('.selectedfirst').removeClass('selectedfirst');
			return false;
	});
	
	jQuery('.vre_deletebutton').live('click',function(){
	
		//jQuery('#table_seasons tr:last').remove();
		idseason= jQuery(this).attr('id').substr(18);
		jQuery('#div_inputs_deleted').append('<input type="hidden" name="deleteseason_id[]" value="'+idseason+'">');
		jQuery(this).parent().parent().remove();
		return false;
		
	});
	jQuery('.newbutton').live('click',function(){
		jQuery('#table_seasons').append('<tr><td width="20%" valign="top"><input type="text" name="vre_season_name[]" ></td><td width="20%" valign="top"><input type="date" name="vre_date_from[]"></td><td width="20%" valign="top">	<input type="date" name="vre_date_to[]"></td><td width="10%" valign="top"><input type="number" name="vre_pricepernight[]"></td><td width="5%" valign="top"><input type="checkbox" name="vre_check_anual" checked="checked"></td><td width="10%" valign="top"></td></tr>');
	});
	
	});
	
	jQuery(function($){
			var frame,
			metaBox = $('#gallery_property_metabox.postbox'), 
		  	addImgLink = metaBox.find('.upload-custom-img'),
		  	delImgLink = metaBox.find( '.delete-custom-img'),
		  	imgContainer = metaBox.find( '.vre-meta-image-preview'),
		  	imgIdInput = metaBox.find( '.custom-img-id' );
		
		
			
		
		
			
			jQuery('.linkdeletediv').live( 'click', function() {
				 $( this ).parent().parent().remove();
								
								return false;
				});
		
			jQuery('#meta-image-button').click(function(event) 
			{
				event.preventDefault();
			
				if ( frame ) 
				{
					frame.open();
				  	return;
				}
			 
				// Create a new media frame
				frame = wp.media({
					  title: 'Selecciona la Imagen',
					  button: {
						text: 'Selecciona la Imagen'
					  },
					  multiple: true  // Set to true to allow multiple files to be selected
					});
					
				// When an image is selected in the media frame...
				frame.on( 'select', function() 
				{
					// Get media attachment details from the frame state
					var attachment = frame.state().get('selection').map( 
		
						function( attachment ) {
		
							attachment.toJSON();
							return attachment;
		
					});
					
					var i;
		
				    for (i = 0; i < attachment.length; ++i) 
				    {
						
						$('.vre-meta-image-preview').first().after(
							'<div class="myplugin-image-preview vre-meta-image-preview"><div class="deletediv"><a class="linkdeletediv" href=""  id="propertywp-link-'+attachment[i].attributes.id +'"><b>X</b></a></div><img src="' + attachment[i].attributes.sizes.thumbnail.url + '" > <input type="hidden" id="myplugin-image-input'+attachment[i].attributes.id +'"  name="vre_attachment_id_array[]" value="'+attachment[i].attributes.id+'" ></div>'
							);
							
					}
				});
				
				frame.open();
			});
		});
	