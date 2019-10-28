<div class="wrap">
	<?php html_head('Active Feature'); ?>
	<?php settings_errors(); ?>
  <form method="post" action="options.php">
	  <?php
	  settings_fields( 'getweb_plugin_settings' );
	  do_settings_sections( 'getweb_plugin' );
	  submit_button();
	  ?>
  </form>
	<?php html_footer(); ?>
</div>