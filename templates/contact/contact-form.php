<?php
$form_process_message = empty(get_option( 'form_process_message' )) ? 'Submission in process, please wait..' : get_option( 'form_process_message' );
$success_message = empty(get_option( 'success_message' )) ? 'Message Successfully submitted, thank you!' : get_option( 'success_message' );
$error_message = empty(get_option( 'error_message' )) ? 'There was a problem with the Contact Form, please try again!' : get_option( 'error_message' );
$name_error = empty(get_option( 'name_error' )) ? 'Your Name is Required' : get_option( 'name_error' );
$email_error = empty(get_option( 'email_error' )) ? 'Your Email is Required' : get_option( 'email_error' );
$message_error = empty(get_option( 'message_error' )) ? 'Your Message is Required' : get_option( 'message_error' );
$mobile_error = empty(get_option( 'mobile_error' )) ? 'Your Mobile Number is Required' : get_option( 'mobile_error' );
$extra_class = empty(get_option( 'extra_class' )) ? '' : get_option( 'extra_class' );

?>
<form id="getwebContactForm" class="getweb-contact-form <?php echo $extra_class; ?>" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
  <div class="text-left">
    <small class="text-info form-control-msg js-form-submission"><?php echo $form_process_message; ?></small>
    <small class="text-danger form-control-msg js-form-error"><?php echo $error_message; ?></small>
    <div class="successful-popup-wrap" id="contactPopUp" style="display: none">
      <div class="successful-popup">
        <a href="#" class="close-icon"><i class="fa fa-times-circle"></i></a>
        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'thanku.png'; ?>" alt="check">
        <h3 class="popup_title">Thank You!</h3>
        <p class="popup_message"><?php echo $success_message; ?></p>
        <a class="success-popup" href="<?php echo site_url(); ?>">Skill Advisor</a>
      </div>
    </div>
  </div>
  <div class="contract-from-group">
    <input type="text" class="inp-from" placeholder="Your Name" id="name" name="name">
    <small class="text-danger form-control-msg"><?php echo $name_error; ?></small>
  </div>

  <div class="contract-from-group">
    <input type="email" class="inp-from" placeholder="Your Email" id="email" name="email">
    <small class="text-danger form-control-msg"><?php echo $email_error; ?></small>
  </div>
  <div class="contract-from-group">
    <input type="text" class="inp-from" placeholder="Your Mobile" id="mobile" name="mobile">
    <small class="text-danger form-control-msg"><?php echo $mobile_error; ?></small>
  </div>

  <div class="contract-from-group">
    <textarea name="message" id="message" class="inp-from txt-box" placeholder="Your Message"></textarea>
    <small class="text-danger form-control-msg"><?php echo $message_error; ?></small>
  </div>
  <div class="contract-from-group">
    <button type="stubmit" class="btn btn-default btn-lg btn-getweb-form contact-button">Send Message</button>
  </div>
</form>