<div class="wrap">
  <?php html_head('Contact option'); ?>
		<?php settings_errors(); ?>
      <form method="post" action="options.php">
		  <?php
		  settings_fields( 'getweb_plugin_form_settings' );
		  do_settings_sections( 'getweb_form' );
	    getweb_submit_button();
		  ?>
      </form>
	<?php html_footer(); ?>
</div>