<div class="wrap">
	<?php html_head('Widget Generator'); ?>
	<?php settings_errors(); ?>
  <ul class="nav nav-tabs">
    <li class="<?php echo !isset($_POST["edit_post"]) ? 'active' : '' ?>"><a href="#tab-1">Your Custom Widget</a></li>
    <li class="<?php echo isset($_POST["edit_post"]) ? 'active' : '' ?>">
      <a href="#tab-2">
		  <?php echo isset($_POST["edit_post"]) ? 'Edit' : 'Add' ?> Custom Widget
      </a>
    </li>
    <li><a href="#tab-3">Export</a></li>
    <li><a href="#tab-4">Export</a></li>
  </ul>

  <div class="tab-content">
    <div id="tab-1" class="tab-pane <?php echo !isset($_POST["edit_post"]) ? 'active' : '' ?>">

      <h3>Manage Your Widget</h3>

		<?php
		$options = get_option( 'getweb_plugin_cwm' ) ?: array();


		echo '<table class="cpt-table"><tr><th>ID</th><th>Sidebar Name</th><th>Description</th><th class="text-center">Actions</th></tr>';

		foreach ($options as $option) {


			echo "<tr><td>{$option['widget_id']}</td><td>{$option['cwm_name']}</td><td>{$option['description']}</td><td class=\"text-center\">";

			echo '<form method="post" action="" class="inline-block">';
			echo '<input type="hidden" name="edit_post" value="' . $option['cwm_name'] . '">';
			submit_button( 'Edit', 'primary small', 'submit', false);
			echo '</form> ';

			echo '<form method="post" action="options.php" class="inline-block">';
			settings_fields( 'getweb_plugin_cwm_settings' );
			echo '<input type="hidden" name="remove" value="' . $option['cwm_name'] . '">';
			submit_button( 'Delete', 'delete small', 'submit', false, array(
				'onclick' => 'return confirm("Are you sure you want to delete this Custom Post Type? The data associated with it will not be deleted.");'
			));
			echo '</form></td></tr>';
		}

		echo '</table>';
		?>

    </div>

    <div id="tab-2" class="tab-pane <?php echo isset($_POST["edit_post"]) ? 'active' : '' ?>">
      <form method="post" action="options.php">
		  <?php
		  settings_fields( 'getweb_plugin_cwm_settings' );
		  do_settings_sections( 'getweb_cwm' );
		  submit_button();
		  ?>
      </form>
    </div>

    <div id="tab-3" class="tab-pane">
      <h3>Export Your Custom Widget</h3>
		<?php foreach ($options as $option) { ?>

          <h3><?php echo $option['cwm_name']; ?></h3>

          <pre class="prettyprint">
function getweb_widgets_<?php echo $option['widget_id']; ?>()
{
	register_sidebar(array(
		'name' => __('<?php echo $option['cwm_name']; ?>', 'text-domain'),
		'id' => '<?php echo $option['widget_id']; ?>',
		'before_widget' => <?php echo $option['before_widget']; ?>,
		'after_widget' => '',
		'before_title' => <?php echo $option['before_title']; ?>,
		'after_title' => '',
	));
}
add_action( 'widgets_init', 'dynamic_widgets_init' );
			</pre>

		<?php } ?>


    </div>
  </div>
	<?php html_footer(); ?>
</div>