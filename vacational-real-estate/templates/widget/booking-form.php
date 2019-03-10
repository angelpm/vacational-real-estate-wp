
<?php
if(is_user_logged_in())
{

    ?>
    <!-- div  booking form, only for logged users -->
    <div class="div-form-single-property">


        <?php
        $fecha_desde="";
        $fecha_hasta="";
        //print_r(date('d/m/Y'));

        if(!empty($_POST['fecha_desde'])  && !empty($_POST['fecha_hasta']) )
        {
          $current_user = wp_get_current_user();
          $email="";

            if(isset($current_user->user_email) && ($current_user->user_email))
              $email=$current_user->user_email;

          $fecha_desde=sanitize_text_field($_POST['fecha_desde']);
          $fecha_hasta=sanitize_text_field($_POST['fecha_hasta']);

            if($fecha_desde>=$_POST['fecha_hasta'])
            {
              ?>
              <div class="error_form">
                <?php _e('La fecha final debe ser posterior a la fecha inicial','vacational-real-estate');?>
              </div>
              <?php
            }elseif($fecha_desde>=date('d/m/Y') && $fecha_hasta<=date('d/m/Y'))
            {
            ?>
              <div class="error_form">
                <?php _e('Las fechas deben ser mayor a la actual','vacational-real-estate'); ?>
              </div>
              <?php
            }else
            {
              $firstname=sanitize_text_field($_POST['firstname']);
              $lastname=sanitize_text_field($_POST['lastname']);
              $movil=sanitize_text_field($_POST['movil']);
              $admin_email = get_option( 'admin_email' );
              $to = $admin_email;
              $subject = get_site_url().' - Formulario de Reserva: '.$name;

              $body="Un nuevo usuario se ha puesto en contacto a traves del formulario de reserva<br><br>";
              $body = $body."Nombre: ".$firstname." ". $lastname."<br>";
              $body = $body."Email: ".$email."<br>";
              $body = $body."Telefono: ".$movil."<br>";
              $body = $body."Fecha Desde: ".$fecha_desde."<br>";
              $body = $body."Fecha Hasta: ".$fecha_hasta."<br>Mensaje:<br>";
              $body = $body.sanitize_text_field($_POST['message']);
              $headers = array('Content-Type: text/html; charset=UTF-8');

              if($result=wp_mail( $to, $subject, $body, $headers ))
              {
                _e('<div class="succes_form">','vacational-real-estate');
                  _e('El mensaje se ha enviado con exito.','vacational-real-estate');
                _e('</div>','vacational-real-estate');
              }

            }
        }

        global $current_user;
        get_currentuserinfo();
        ?>

        <form action="" method="post">

            <input type="hidden" name="firstname" value="<?php echo $current_user->user_firstname ?>" required/>
            <input type="hidden" name="lastname" value="<?php echo $current_user->user_lastname  ?>" required />
            <input type="hidden" name="email" value="<?php echo $current_user->user_email  ?>" required/>
            <div style=" border-top:1px solid #CCC; border-bottom:1px solid #CCC;  padding:10px;">
              <div style="float:left; width:22%; padding-right: 2%;">
                Fecha de Reservas
              </div>
              <div style="float:left; width:22%; padding-right: 2%;">
                <label><?php _e('Desde','vacational-real-estate')?></label>
                <input style="height:40px; " type="date" name="fecha_desde" value="<?php echo $fecha_desde; ?>" required/>
              </div>
              <div  style="float:left;width:22%;padding-right: 2%;">
                <label><?php _e('Hasta','vacational-real-estate')?></label>
                <input type="date"  style="height:40px; " name="fecha_hasta"   value="<?php echo $fecha_hasta; ?>" required/>
              </div>
              <div  style="float:left;width:25%;">
                <input type="submit" style=" margin-top:24px; height:40px;" name="booking_form" value="<?php _e('Update Booking','vacational-real-estate')?>" /><br />
              </div>
              <br clear="both">
            </div>

        </form>
    </div>

  <?php }
