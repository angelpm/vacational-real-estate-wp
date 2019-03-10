
jQuery(document).ready(function() {

	


jQuery('.divcontentcalendar .monthlink').live("click",function(e){
		e.preventDefault();	
		var actmonth=jQuery('#selectmonth').val();
		var actyear=jQuery('#selectyear').val();
		var post_id=jQuery('#post_id_calendar').val();
		
						   
		actyear=parseInt(actyear);
		
		if(jQuery(this).attr("id")=="nextmonthlink")
		{
			actmonth= parseInt(actmonth)+1;
			
			if(actmonth>12)
			{
				actmonth=1;
				actyear=actyear+1;
			}
		}else
		{
			actmonth= parseInt(actmonth)-1;
			if(actmonth<1)
			{
				actmonth=12;
				actyear=actyear-1;
			}
			
		}
		
		jQuery.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_readmore',
				month: actmonth,
				year: actyear,
				post_id: post_id
			},
			beforeSend: function(){
				//link.html('Cargando ...');
			},
			success: function(resultado){
				 jQuery('#containercalendar').html(resultado);		
			}

		});
		
		
		});


jQuery('.vre-content-calendar .selectmonth').live("change",function(e){
										
		e.preventDefault();
	 	//link = $(this);
	 	//id   = link.attr('href').replace(/^.*#more-/,'');
		var actmonth=jQuery('#selectmonth').val();
		var year=jQuery('#selectyear').val();
		
		jQuery.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'creap_display_month_widget',
				month: actmonth,
				year: year
			},
			beforeSend: function(){
				//link.html('Cargando ...');
			},
			success: function(resultado){
				 jQuery('#containercalendar').html(resultado);		
			}

		});
											
	});
	
	

jQuery('.vre-content-calendar .selectyear').live("change",function(e){
										
		e.preventDefault();
	 	//link = $(this);
	 	//id   = link.attr('href').replace(/^.*#more-/,'');
		var actmonth=jQuery('#selectmonth').val();
		var actyear=jQuery('#selectyear').val();
		
		jQuery.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'creap_display_month_widget',
				id_post: actmonth,
				actyear: actyear
			},
			
			success: function(resultado){
				 jQuery('#containercalendar').html(resultado);		
			}

		});
											
	});


jQuery( ".vre-tab-menu-single input" ).click(function() {
	var temp=jQuery(this).attr("data-id");
	jQuery(".tab-content").hide();
	jQuery( "#"+temp ).show();
});

jQuery( "#leftarrow" ).click(function() {
		var activ=jQuery( ".active" );
		var antinac=jQuery( ".active" ).prev('.inactive');
		console.log(antinac);
		jQuery( '#rightarrow').removeClass('desenfocado');
		if(activ.length>0 && antinac.length>0)
		{
			activ.removeClass('active');
			activ.addClass('inactive');
			activ.addClass('imaggallery');
			antinac.removeClass('imaggallery');
			antinac.removeClass('inactive');
			antinac.addClass('active');
		}else
		{
			jQuery( this).addClass('desenfocado');
		}
		return false;
	
	});
	jQuery( "#rightarrow" ).click(function() {
	
		var activ=jQuery( ".active" );
		var siginac=jQuery( ".active" ).next('.inactive');
		console.log(siginac);
		jQuery( '#leftarrow').removeClass('desenfocado');
		if(activ.length>0 && siginac.length>0)
		{
			activ.removeClass('active');
			activ.addClass('inactive');
			activ.addClass('imaggallery');
			siginac.removeClass('imaggallery');
			siginac.removeClass('inactive');
			siginac.addClass('active');
		}else
		{
			jQuery( this).addClass('desenfocado');
		}
		//console.log(siginac);
		//jQuery( ".active" ).removeClass('active');
		//jQuery( ".inactive" ).first().removeClass('imaggallery').addClass('active');
		return false;
	
	});



});

