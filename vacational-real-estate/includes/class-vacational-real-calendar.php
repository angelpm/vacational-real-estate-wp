<?php
/**
 * Class Calendar for Complete Real Estate WP
 *
 * Add calendar widget with bookings, seasons and price
 *
 * @version:    1.0.0
 * @author      Angel Porras <robinwebdesign@hotmail.com>
 * @author URI: https://webdesign.portfoliobox.net/
 * @license:    GPL2
 * @license URI:https://www.gnu.org/licenses/gpl-2.0.html
 */
 
 
 
class Creap_booking_calendar_real_estate extends WP_Widget
{

	/**
	 * The plugin name
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public $name = 'WP Booking Calendar';
	
	/**
	 * The Widget title
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $title    The Widget title.
	 */
	public $title = 'Booking Calendar';
	
	/**
	 * The Widget description
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $description    The Widget title.
	 */
	public $description = 'Booking Calendar for Complete Real Estate';
	
	/**
	 * The Widget  options
	 *
	 * @since    1.0.0
     * @access   public
	 * @var      string array    $control_options    The Widget options.
	 */
	public $control_options = array();
	
	/**
	 * calendar days labels
	 *
	 * @since    1.0.0
	* @access   private
	 * @var      string array    $dayLabels    days labels.
	 */
	 
	private $dayLabels = array();
	
	/**
	 * calendar month labels
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string array    $montLabels    month labels.
	 */
	private $montLabels = array();
     
	
	/**
	 * current year
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      integer    $currentYear   current year.
	 */
    private $currentYear=0;
    
	
	/**
	 * current month
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      integer    $currentMonth   current month.
	 */
    private $currentMonth=0;
    
	/**
	 * current day
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      integer    $currentDay   current day.
	 */
    private $currentDay=0;
    
	/**
	 * current date
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      integer    $currentDate   current date.
	 */
    private $currentDate=null;
    
	/**
	 *  number days of a month
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      integer    $daysInMonth   number days of a month.
	 */
    private $daysInMonth=0;
	
	
	/**
	 *  number days of a month
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object  array   $reservas   bookings.
	 */
	private $bookings=array();
     
	/**
	*  number days of a month
	*
	* @since    1.0.0
	* @access   private
	* @var      object  array   $reservas   bookings.
	*/
	private $seasons=array();
     
	private $princday;
	/**
	 *  container widget
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object  array   $args   container widget.
	 */
	public $args = array(
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );
	
	
	/**
	 * construct for register  functions
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	function __construct()
	{
		
		$widget_options = array(
			'classname' => __CLASS__,	
			'description' => $this->description,
			);
			
		parent::__construct( __CLASS__, $this->name,$widget_options,$this->control_options);
		
		
		$options = get_option('vre_propertywp_options');
		
		
		$this->montLabels = array(__("Enero",'vacational-real-estate'),__("Febrero",'vacational-real-estate'),__("Marzo",'vacational-real-estate'),__("Abril",'vacational-real-estate'),__("Mayo",'vacational-real-estate'),__("Junio",'vacational-real-estate'),__("Julio",'vacational-real-estate'),__("Agosto",'vacational-real-estate'),__("Septiembre",'vacational-real-estate'),__("Octubre",'vacational-real-estate'),__("Noviembre",'vacational-real-estate'),__("Diciembre",'vacational-real-estate'));
		
			if(!empty($options['creap_day_week']) && ($options['creap_day_week']=="Domingo"))
			{
				$this->dayLabels = array(__("Dom",'vacational-real-estate'),__("Lun",'vacational-real-estate'),__("Mar",'vacational-real-estate'),__("Mie",'vacational-real-estate'),__("Jue",'vacational-real-estate'),__("Vie",'vacational-real-estate'),__("Sab",'vacational-real-estate'));
				
				$this->princday="Sabado";
			}else
			{
				$this->dayLabels = array(__("Lun",'vacational-real-estate'),__("Mar",'vacational-real-estate'),__("Mie",'vacational-real-estate'),__("Jue",'vacational-real-estate'),__("Vie",'vacational-real-estate'),__("Sab",'vacational-real-estate'),__("Dom",'vacational-real-estate'));
				
				$this->princday="Domingo";
			}
			
		$numfirstday= date('N', mktime(0, 0, 0, $this->currentMonth, 1, $this->currentYear))-1;
		
		
		add_action('wp_ajax_nopriv_creap_display_month_widget',array($this,'creap_display_month_widget'));
		add_action('wp_ajax_creap_display_month_widget',array($this,'creap_display_month_widget'));
		
		//ajax para actualizar el calendario
		add_action('wp_ajax_nopriv_creap_getmonths_property',array($this,'creap_getmonths_property'));
		add_action('wp_ajax_creap_getmonths_property',array($this,'creap_getmonths_property'));
		
		//ajax para realizar la reserva
		add_action('wp_ajax_nopriv_creap_booking_date_property',array($this,'creap_booking_date_property'));
		add_action('wp_ajax_creap_booking_date_property',array($this,'creap_booking_date_property'));

		//ajax para actualizar el calendario
		add_action('wp_ajax_nopriv_creap_delete_booking_property',array($this,'creap_delete_booking_property'));
		add_action('wp_ajax_creap_delete_booking_property',array($this,'creap_delete_booking_property'));
		
	}
	
	
	/**
	 * construct for register  functions
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_delete_booking_property()
	{
		global $wpdb;
		
			if(!empty($_POST['bookingid']) && is_numeric($_POST['bookingid']) )
			{
				$table_name = $wpdb->prefix . 'creapbookingwp';
				
				$wpdb->delete( $table_name, array( 'booking_id' => intval($_POST['bookingid']) ) );

			}
			
		wp_die();
	}
	
	
	/**
	 * construct for register  functions
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_booking_date_property()
	{
		global $wpdb;
		
		if(!empty($_POST['creap_property_id']) && is_numeric($_POST['creap_property_id']) && !empty($_POST['creap_phone']) && is_numeric($_POST['creap_phone'])  && is_numeric($_POST['creap_user_id'])  && is_numeric($_POST['creap_year']) && is_numeric($_POST['creap_year']))
		{
		
		
			$property_id=intval($_POST['creap_property_id']);
			$fechaDesde=sanitize_text_field($_POST['creap_fecha_desde']);
			$fechaHasta=sanitize_text_field($_POST['creap_fecha_hasta']);
			$user_id=intval($_POST['creap_user_id']);
			$phone=intval($_POST['creap_phone']);
			$email=intval($_POST['creap_email']);
			
			$year=intval($_POST['creap_year']);
			$tempDate = explode('-', $fechaDesde);
			$tempDate2 = explode('-', $fechaHasta);
			$phone=sanitize_text_field($_POST['creap_movil']);

  			
				if(checkdate($tempDate[1], $tempDate[2], $tempDate[0]) && checkdate($tempDate2[1], $tempDate2[2], $tempDate2[0]))
				{
			
					$table_name = $wpdb->prefix . 'creapbookingwp';
					
					$wpdb->insert( 
							$table_name, 
							array( 
								'creap_season_id' => 1,
								'creap_user_id'=>$user_id,
								'creap_phone'=>$phone,
								'creap_property_id'=>$property_id,
								'creap_pricexnight'=>'1',
								'vre_date_from'=>$fechaDesde,
								'creap_phone'=>$phone,
								'creap_email'=>$email,
								'vre_date_to'=>$fechaHasta,
								'creap_year'=>$year
								 
							) 
						);
						
					if($wpdb)
						echo "Se ha realizado la Reserva con exito";
				}
				
			
		}
		
		wp_die();
		
	}
	
	/**
	 * Function for display booking page displaying calendar twelve month
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_getmonths_property()
	{
		global $post;
		global $wpdb;
	
		include  get_theme_file_path ('/includes/class-vacational-real-calendar.php' );
		
		$my_widget = new Creap_booking_calendar_real_estate();
		$price_base=0;
		$seasonbid=0;
		$seasonstmp=array();
		$actmonth=false;
		$actyear=false;
		$num_month=3;
				
			//check if month and year is not empty
			if(!empty($_POST['month']) && is_numeric($_POST['month']) && !empty($_POST['year']) && is_numeric($_POST['year']) && !empty($_POST['post_id']) && is_numeric($_POST['post_id']) )
			{
				$actmonth=intval($_POST['month']);
				$actyear=intval($_POST['year']);
				
				//Check if property Id exist
				
				$property_id=intval($_POST['post_id']);
				//obtenemos las fechas de temporadas		
				$seasons = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vreseasons WHERE post_id = '".$property_id."'"); 
				
				
					foreach($seasons as $season)
					{
						//si se trata del precio base no lo añadimos a las temporadas
						if($season->name=='price_base')
						{
							$price_base=$season->pricepernight;
							$seasonbid=$season->season_id;
						}else
						{
							$seasonstmp[]=$season;
						}
						
					}
				//we get bookins from actual month
				$bookings=$this->creap_get_booking_month($actmonth,$property_id);   
				//print_r($bookings);
				
				//check if exists number of month to display
				if(!empty($_POST['num_month']) && is_numeric($_POST['num_month']))
					$num_month=intval($_POST['num_month']);
						
				
				
				
				
				if($num_month && $num_month>0)
				{
				
					?>
					<input type="hidden" id="actual_month" value="<?php echo $actmonth?>" />
					<input type="hidden" id="actual_year" value="<?php echo $actyear?>" />
					<input type="hidden" id="num_month" value="<?php echo $num_month?>" />
					<?php
					$tempyear=$actyear;
					$tempmonth=$actmonth;
					
					for($j=0; $j<$num_month; $j++)
					{
						?>
						<div class="divmonthcalendar">
							<?php
							$my_widget->CreapdisplayMonthProperty($tempmonth,$tempyear,$property_id);
							
							$tempmonth=$tempmonth+ 1;
							
								if($tempmonth>12)
								{
									$tempmonth=1;
									$tempyear=$tempyear+1;
								}
							?>
						</div>
						<?php
					}
				}
				
			}	
			
		wp_die();
	}
	
	
	/**
	 * Display calendar given month
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_display_month_widget()
	{
		
		if(!empty($_POST['month']) && is_numeric($_POST['month']))
		{
			$month=intval($_POST['month']);
			$post_id=intval($_POST['post_id']);
			$this->creap_display_month_property_widget($month,null,$post_id);
		}
			
		wp_die();
	}	

	
	/**
	 * register this widget
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	static function register_this_widget()
	{
		register_widget(__CLASS__);
	}
	
	
	/**
	 * Add booking dates to class calendar
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_add_bookingdates($datef,$dateto,$user_login,$bookingid,$username,$phone)
	{
				
		$this->bookings[]=array($datef,$dateto,$user_login,$bookingid,$username,$phone);
	}
	
	/**
	 * Add season dates to class calendar
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_add_seasonsdates($datef,$dateto,$seasonid,$price)
	{
				
		$this->seasons[]=array($datef,$dateto,$seasonid,$price);
	}
	
	/**
	 * return seasons dates
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_get_seasonsdates()
	{
				
		return $this->seasons;
		
	}
	
	
	/**
	 * return bookings property
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function creap_get_reservedates()
	{
				 
		return $this->bookings;
	}
	
	
	/**
	 * delete bookings property
	 * 
	 * @since    1.0.0
	 * @access    public
	 */
	public function delete_reservedates()
	{
		$this->bookings=array();
	}
	
	/**
	 * display widget with container
	 * 
	 * @since    	1.0.0
	 * @access    	public
	 * @param    	array		$args			widget container tags
	 * @param    	object		$instance		instance object  widget
	 */
  	public function widget($args, $instance) {
  
  		$title = apply_filters( 'widget_title', $instance['title'] );
	
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		
			if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];
 
        echo '<div id="containercalendar" class="textwidget">';
 
        $this->creap_display_month_property_widget();
 
        echo '</div>';
 
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
		if ( isset( $instance[ 'title' ] ) ) 
		{
			$title = $instance[ 'title' ];
		}
		else 
		{
			$title = __( 'New title', 'wpb_widget_domain' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','vacational-real-estate'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_html_e($title,'vacational-real-estate'); ?>" />
		</p>
    <?php    
    }
  
  	/**
	 * get booking from given month 
	 * 
	 * @since		1.0.0
	 * @access		public
	 * @param		integer		$month			month of calendar to display
	 * @param		integer		$property_id	property id
	 */
	public function creap_get_booking_month($month,$property_id)
	{
		global $post;
		global $wpdb;
		
		
		$post_id=false;
		$actmont=intval(date('m'));
		
		$actyear=intval(date('Y'));
		$temp=array();
		
		$month_str="";	
		
		
			if(is_admin() && isset($property_id) && is_numeric($property_id) )
			{
				$property_id=" AND property_id= ".$property_id;
			
			
				if(is_admin() && isset($month) && is_numeric($month))
				{
					$month_str=" AND month= ".$month;
					
					$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}creapbookingwp WHERE year=".$actyear.$month_str.$property_id, OBJECT );	
			
					if($results && isset($results) && !empty($results))
					{
						$this->bookings=array();
					
						foreach($results as $reserv)
						{  
							$name="";
							$author_obj = get_user_meta( $reserv->user_id );
							$aut=get_user_by('id',$reserv->user_id) ;
							//print_r($aut->data->user_email);
								if(isset($author_obj['first_name'][0]))
									$name=$author_obj['first_name'][0];
								
								if(isset($author_obj['last_name'][0]))
									$name=$name." ".$author_obj['last_name'][0];
								
								if(isset($aut->data->user_email))
								{
									$name=$name." - ".$aut->data->user_email;
									$umeail=$aut->data->user_email;
								}
							
							$this->creap_add_bookingdates($reserv->date_from,$reserv->date_to,$reserv->user_id,$reserv->booking_id,$umeail,$reserv->phone);
							
							
							
						}
					}
					
					if( $this->bookings && count($this->bookings)>0)
					{
						
						foreach( $this->bookings as $booking)
						{
							$aux=explode("-",$booking[0]);
							$aux1=explode("-",$booking[1]);
							
							if(isset($aux[1]) && ($aux[1]) && isset($aux1[1]) && ($aux1[1]))
							{
								if($aux[1]==$month || $aux1[1]==$month)
								{
									$temp[]=$booking;
								}
							}
							
						}
					}
				
				}
			
			}
			
		
		return $temp;
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
		
		
		return $instance;
	}
	
	
	
	/**
	 * get seasons from given month 
	 * 
	 * @since    	1.0.0
	 * @access   	public
	 * @param		integer		$month			month of calendar to display
	 */
	public function creap_get_season_month($month=null,$post_id)
	{
		global $post;
		global $wpdb;
		
		$tmp=array();
		
		$seasons=false;
		
		if(!empty($_POST['action']) &&  !empty($_POST['post_id']))
		{ 
			$post_id=intval($_POST['post_id']);
			//print_r($post_id);
		
		}else if(!empty($_GET['creap_property_id']) && is_numeric($_GET['creap_property_id']))
		{
			$post_id=intval($_GET['creap_property_id']);
		}
		
	
		if(isset($post_id) && ($post_id))
		{
			$table_name = $wpdb->prefix . 'vreseasons';
			$seasons = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE post_id = '".$post_id."'");

		}
	
		return $seasons;
	
	}
	
	
	/**
	 * construct the unique counter identify for days in a year
	 * 
	 * @since    	1.0.0
	 * @access    	public
	 */
	private function creap_count_days_year()
	{
		$cont="00";
				
		if($this->currentMonth>1)
		{
			switch ($this->currentMonth) 
			{
				case 2:
					$cont=31;
					break;
				case 3:
					$cont=59;
					break;
				case 4:
					$cont=90;
					break;
				case 5:
					$cont=120;
					break;
				case 6:
					$cont=151;
					break;
				case 7:
					$cont=181;
					break;
				case 8:
					$cont=202;
					break;
				case 9:
					$cont=233;
					break;
				case 10:
					$cont=263;
					break;
				case 11:
					$cont=294;
					break;
				case 12:
					$cont=324;
					break;
			}
		}
		
		$cont=$this->currentYear.$cont;
		
		return $cont;
				
	}
	
	
	/**
	 * display month booking  from property
	 * 
	 * @since    	1.0.0
	 * @access    	public
	 * @param		integer		$month			month of calendar to display
	 * @param		integer		$year			year of calendar to display
	 */
	public function CreapdisplayMonthProperty($month=null,$year=null)
	{
		global $post;
		
		
		$primera=false;
		
	  if(is_admin())
	  {
		if(!empty($_POST['action']) && ($_POST['action']=="creap_display_month_widget"))
		{ 
			$post_id=intval($_POST['post_id']);
			//print_r($post_id);
		}else if(!empty($_GET['creap_property_id']) && is_numeric($_GET['creap_property_id']))
		{
			$post_id=intval($_GET['creap_property_id']);
			
		}else if(!empty($_POST['post_id']) && is_numeric($_POST['post_id']))
		{
			$post_id=intval($_POST['post_id']);
			
		}elseif(isset($post->ID))
		{
			$post_id=$post->ID;
		}
		
		if(!empty($_GET['creap_year']) && is_numeric($_GET['creap_year'])){
	
			$year = intval($_GET['creap_year']);
		 
		}elseif($year)
		{
		
		 
		}elseif(!empty($_POST['creap_year']) && is_numeric($_POST['creap_year'])){
	
			$year = intval($_POST['creap_year']);
			
		 
		}else if(!$year){
			
			
			$year = date("Y",time());  
		 
		}          
	   
		if(!$month && isset($_GET['month'])){
	
			$month = intval($_GET['month']);
		 
		}else if(!$month){
	
			$month = date("m",time());
		 
		}      
		
		$seasons=$this->creap_get_season_month(null,$post_id); 
		$bookings=$this->creap_get_booking_month($month,$post_id);   
		
		
		$monthint=(int)$month;
		$nexmonth=$monthint%12+1;
		$prevmonth=$monthint%12-1;
		 
			if($prevmonth<=0)
				$prevmonth=12;
				
		$monthstr=$this->montLabels[$monthint-1];
		
		
		$this->currentYear=$year;
		 
		$this->currentMonth=$month;
		 
		$this->daysInMonth=$this->_daysInMonth($month,$year);     
		
		$seg=false;
		   
		$j=0;          
		
		echo "<h3>".$monthstr."-".$this->currentYear."</h3>";			
						
			foreach($this->dayLabels as $key => $dl)
			{
			?>
				<div id="daypromp<?php echo $key ?>" class="divdayadmtxt"><div class="daytxtadm"><?php echo $dl ?></div></div>
			<?php
			}
			?>
			<br>
            <div style="clear:both;"></div>
			<div class="divcontentdaysnumber">
			<?php
				$cont="00";
				
				$cont= $this->creap_count_days_year();
				
				for( $i=0; $i<$this->daysInMonth; $i++ )
				{	
					$cont=$cont+1;
					$str=$this->currentYear.'-'.sprintf("%02d", $this->currentMonth).'-'.sprintf("%02d", $i+1);
					$time=strtotime($str);
					$style="";
					$user="";
					
					
						if($seasons && count($seasons)>0)
						{
							foreach($seasons as $key=> $season)
							{
							 
								if($time>=$season->strtotime_from && $time<=$season->strtotime_to || ($season->anual && $season->month==$month && $season->day_from<=($i+1) && $season->day_to>=($i+1)))
								{
								
									$style="class='activefirst'";	
									$title=__("Temporada ").$key.__(" - Precio por Noche: ").$season->pricepernight." &euro;";
									
								}elseif(!$style)
								{
									$style="";
									$primera=false;
									$user="";
									$title="";
								}
							}
						}
					
					$style2="";
					$title="";
					$bookidstr="";
					$email=" data-email='' ";
					$phone=" data-phone='' ";
					
						if($bookings && count($bookings)>0)
						{
							
							/*if($this->currentMonth==5)
							{
								print_r($bookings);
							}*/
							foreach($bookings as $key=> $booking)
							{
							
								if(isset($booking[0]) && isset($booking[1]) && isset($booking[3]))
								{
									if($str>=$booking[0] && $str<=$booking[1])
									{
										
										$bookidstr=" data-booking-id='".$booking[3]."'";
										$style2=" activereserved ";	
										
										
											if(isset($booking[4]) && isset($booking[5]))
											{
												$title=__("Reservado: ".$booking[4]." - Telf:".$booking[5]);
												$email=" data-email='".$booking[4]."'";
												$phone=" data-phone='".$booking[5]."'";
											}
										
									}
								}
							}
						}
					
					$corp=$i+1;
						//print_r($bookings);
						
						if(!$style)
						{
						?>
							<div id="daypromp<?php echo $cont;?>" class="divdayadm <?php echo $style2; ?>" <?php echo $email ?> <?php echo $phone ?> data-actual="<?php echo $str; ?>" <?php echo $bookidstr; ?> data-user="<?php if (isset($reserva[2])) echo $reserva[2]; ?>" data-orden="<?php echo $cont;?>"  data-datefrom="<?php if (isset($season->strtotime_from)) echo $season->strtotime_from; ?>"  data-dateto="<?php if (isset($season->strtotime_to)) echo $season->strtotime_to; ?>" data-strtime="<?php echo $time; ?>"><?php echo $user; ?><a href="#"   title="<?php esc_html_e($title,'vacational-real-estate'); ?>"  <?php echo $style; ?>><?php esc_html_e(($i+1),'vacational-real-estate'); ?></a></div>
							<?php
						}else
						{
						?>
							<div id="daypromp<?php echo $cont;?>" class="divdayadm" data-orden="<?php echo $cont;?>" <?php echo $email ?> <?php echo $phone ?> data-actual="<?php echo $str; ?>" <?php echo $bookidstr; ?>  data-user="<?php if (isset($reserva[2])) echo $reserva[2]; ?>"  data-datefrom="<?php  if (isset($season->strtotime_from)) echo $season->strtotime_from; ?>" data-dateto="<?php  if (isset($season->strtotime_to)) echo $season->strtotime_to; ?>" data-strtime="<?php echo $time; ?>"><?php echo $user; ?><a href="#" <?php echo $style; ?>  title="<?php esc_html_e($title,'vacational-real-estate'); ?>"><?php esc_html_e(($i+1),'vacational-real-estate'); ?></a></div>
							<?php
						}
						
					
						
						if($this->dayLabels[$j]=="Dom")
						{
							$seg=true;
							?>
							<br clear="all">
							<?php
						}
	
	
					$j++;
					
					if($j>6)
						$j=0;
						
				}
		?>
        <br clear="all" />
        </div>
		<?php
		}
	}
	
	
	/**
	 * display month booking widget  from property
	 * 
	 * @since    1.0.0
	 * @access    public
	 * @param		integer		$month			month of calendar to display
	 * @param		integer		$year			year of calendar to display
	 * @param		integer		$post_id		property id
	 */
	public function creap_display_month_property_widget($month=null,$year=null,$post_id=null)
	{
		global $post;
		
		
		$primera=false;
      	
	    
			//Si no se introducjo el año ponemos el actual 
			if(!$year)
			{
				if(!empty($_POST['creap_year']) && is_numeric($_POST['creap_year']))
				{
					$year=intval($_POST['creap_year']);
				}else
				{
					$year = date("Y",time());  
				}
			}          
			   
			//Si no se introducjo el mes ponemos el actual
			if(!$month)
			{
				if(!empty($_POST['month']) && is_numeric($_POST['month']))
				{
					$month=intval($_POST['month']);
				}else
				{
					$month = date("m",time());  
				}
				
			}  
			
			if(!$post_id && !empty($_POST['month']) && is_numeric($_POST['month']))
			{
				$post_id=intval($_POST['post_id']);
			}
		
		$reserves=$this->creap_get_season_month($month,$post_id);   
		
		$bookings=$this->creap_get_booking_month($month,$post_id); 
		//print_r($bookings);
		
		$monthint=(int)$month;
		$nexmonth=$monthint%12+1;
		$prevmonth=$monthint%12-1;
		 
			if($prevmonth<=0)
				$prevmonth=12;
		
		
		$monthstr=$this->montLabels[$monthint];
		
        
		$this->currentYear=$year;
         
        $this->currentMonth=$month;
         
        $this->daysInMonth=$this->_daysInMonth($month,$year);     
    	
		$seg=false;
		
		$options = get_option('vre_propertywp_options');
		//print_r($options['creap_day_week']);
		
		
		
			if(!empty($options['creap_day_week']) && ($options['creap_day_week']=="Domingo"))
			{
				$this->dayLabels = array(__("Dom",'vacational-real-estate'),__("Lun",'vacational-real-estate'),__("Mar",'vacational-real-estate'),__("Mie",'vacational-real-estate'),__("Jue",'vacational-real-estate'),__("Vie",'vacational-real-estate'),__("Sab",'vacational-real-estate'));
				$princday="Sabado";
			}else
			{
				$this->dayLabels = array(__("Lun",'vacational-real-estate'),__("Mar",'vacational-real-estate'),__("Mie",'vacational-real-estate'),__("Jue",'vacational-real-estate'),__("Vie",'vacational-real-estate'),__("Sab",'vacational-real-estate'),__("Dom",'vacational-real-estate'));
				$princday="Domingo";
			}
			
		$numfirstday= date('N', mktime(0, 0, 0, $this->currentMonth, 1, $this->currentYear))-1;
		
		?>
        <div id="containercalendar">
            <div class="vre-content-calendar" style="max-width:230px; margin-left:auto; margin-right:auto; text-align:center" class="calendarproperty">
                <input type="hidden" value="<?php echo $post_id ?>" name="post_id_calendar" id="post_id_calendar">
                    <div  style="width:100%;">
                        <div style="border-bottom:2px solid #0681b4;"></div>   
                        <div class="vre-calendar-dates">
                        <?php
                            $j=0;          
                            foreach($this->dayLabels as $key => $dl)
                            {
                            ?>
                                <div id="daypromp<?php echo $key; ?>" class="divday"><div class="daytxt"><?php  esc_html_e(($dl),'vacational-real-estate'); ?></div></div>
                            <?php
                            }
                            ?>
                            
                            <br><div style="clear:all"></div>
                            <?php
							
							$z=0;
							
                            for( $i=0; $z<$this->daysInMonth; $i++ )
                            {	
								if($i<$numfirstday)
								{
									?>
                                    <div id="daypromp<?php echo $j; ?>" class="divday"><div class="divnumber"><a href="#" '.$style.' ></a></div></div>
                                    <?php
								}else
								{
									$z++;
								
								
									$str=$this->currentYear.'-'.$this->currentMonth.'-'.($i+1);
									$time=strtotime($str);
									$style="";
									$user="";
									$reserved="";
									$title_reserved="";
									
										if($bookings && count($bookings)>0)
										{
											foreach($bookings as $key  => $booking)
											{
												$str2=$this->currentYear.'-'.sprintf("%02d", $this->currentMonth).'-'.sprintf("%02d", ($i+1));
												//print_r($str2);
												if($str2>=$booking[0] && $str2<=$booking[1])
												{
													$reserved=" reserved ";
													$title_reserved="Reservada";
												}
											}
											
											if($reserved && $reserved!="")
											{
											?>
												<div id="daypromp<?php echo $j; ?>" class="divday"><div class="divnumber <?php echo $reserved; ?>"><a href="#" title="<?php _e($title_reserved,'vacational-real-estate'); ?>"><?php  esc_html_e($i+1,'vacational-real-estate'); ?></a></div></div>
											<?php
											}else
											{
											?>
												<div id="daypromp<?php echo $j; ?>" class="divday"><div class="divnumber <?php echo $reserved; ?>"><a href="#"><?php  esc_html_e($i+1,'vacational-real-estate'); ?></a></div></div>
											<?php
											}
											
										
										}else
										{
											if($this->bookings && count($this->bookings)>0)
											{
												foreach($this->bookings as $key  => $booking)
												{
													if($time>=$booking[0] && $time<=$booking[1])
													{
														$style="class='activefirst'";	
														$title=__('Temporada: ').$key." - ".__('Precio por Noche: ').$reserva[2]." &euro;";
														
													}else if(!$style)
													{
														$title="Hola";
														$style="";
														$primera=false;
														$user="";
													}
												}
											}
											
											if(!$style)
											{
											?>
												<div id="daypromp<?php echo $j; ?>" class="divday"><?php echo $user; ?><div class="divnumber"><a href="#" '.$style.' ><?php  esc_html_e($z,'vacational-real-estate'); ?></a></div></div>
											<?php
											}else
											{
											?>
												<div id="daypromp<?php echo $j; ?>" class="divday"><?php echo $user; ?><div class="divnumber" ><a href="#" <?php echo $style; ?> title="<?php esc_html_e($title,'vacational-real-estate');?>"><?php esc_html_e($i+1,'vacational-real-estate'); ?></a></div></div>
											<?php
											}
										}
										
										if($this->dayLabels[$j]==$princday)
										{
											$seg=true;
											?>
											<br clear="all">
											<?php
										}
									
									
									$j++;
									if($j>6)
									$j=0;
								}
                            }
                            ?> 
                    <br clear="all">
                    
                    <div style="text-align:center;margin-left:auto; margin-right:auto; margin-top:10px;">
                        <a id="prevmonthlink" class="monthlink" href="#" ><img src="<?php echo plugins_url( 'public/images/leftarrows.png', dirname(__FILE__) ); ?>" height="auto" width="18" style="margin:0px;" alt="left arrow" /></a>
                            
                            <select  class='selectmonth' id='selectmonth' name='selectmonth' >
                            <?php 
                            
                                $i=1;
                            
                                foreach($this->montLabels as $ml)
                                {
                                    if($monthint==$i)
                                    {
                                    ?>
                                        <option value="<?php esc_html_e($i,'vacational-real-estate');?>" selected='selected'><?php esc_html_e($ml,'vacational-real-estate'); ?></option>
                                    <?php
                                    }else
                                    {
                                    ?>
                                        <option value="<?php esc_html_e($i,'vacational-real-estate'); ?>"><?php esc_html_e($ml,'vacational-real-estate'); ?></option>
                                    <?php
                                    }
                                    $i++;
                                }
                            ?>	 
                            </select>
                               
                            <select  class='selectyear' id='selectyear' name='selectyear' >
                                 <?php
                                 for($i=$year;$i<$year+5;$i++)
                                 {
                                    ?>
                                    <option><?php esc_html_e($i,'vacational-real-estate');?></option>
                                    <?php
                                    
                                 }
                                 ?>
                            </select>
                            
                        <a  id="nextmonthlink"   class="monthlink"  href="#"  ><img src="<?php echo plugins_url( 'public/images/rightarrows.png', dirname(__FILE__) ); ?>" height="auto" style="margin:0px;" width="18" alt="left arrow" /></a>
                        <br clear="all" />
                    </div>
                </div>
            </div>
        </div>
        <br clear="all" />
    </div> 
				<?php       
        
	}
  

	/**
	 * get weeks from a given month
	 *  
	 * private
	 * @since   	1.0.0
	 * @access    	public
	 * @param		integer		$month			month of calendar to display
	 * @param		integer		$year			year of calendar to display
	 */
	 
	private function _weeksInMonth($month=null,$year=null){
         
        if( null==($year) ) {
            $year =  date("Y",time()); 
        }
         
        if(null==($month)) {
            $month = date("m",time());
        }
         
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
         
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
         
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
         
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
         
        if($monthEndingDay<$monthStartDay){
             
            $numOfweeks++;
         
        }
         
        return $numOfweeks;
    }
	
	
	/**
	 * get days froma given month
	 *  
	 * @since    	1.0.0
	 * @access   	private
	 * @param		integer		$month			month of calendar to display
	 * @param		integer		$year			year of calendar to display
	 */
	private function _daysInMonth($month=null,$year=null){
         
        if(null==($year))
            $year =  date("Y",time()); 
 
        if(null==($month))
            $month = date("m",time());
             
        return date('t',strtotime($year.'-'.$month.'-01'));
    }

}

//instance the object 
$my_widget = new Creap_booking_calendar_real_estate();

