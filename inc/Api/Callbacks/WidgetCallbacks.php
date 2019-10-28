<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Api\Callbacks;

class WidgetCallbacks
{

	public function cwmSectionManager()
	{
		echo 'Create as many Custom Widget as you want.';
	}

	public function cwmSanitize( $input )
	{
		$output = get_option('getweb_plugin_cwm');

		if ( isset($_POST["remove"]) ) {
			unset($output[$_POST["remove"]]);

			return $output;
		}

		if ( count($output) == 0 ) {
			$output[$input['cwm_name']] = $input;

			return $output;
		}

		foreach ($output as $key => $value) {
			if ($input['cwm_name'] === $key) {
				$output[$key] = $input;
			} else {
				$output[$input['cwm_name']] = $input;
			}
		}
		
		return $output;
	}
	public function sidebarSanitize( $input ) {
		return $input;
	}
	function getweb_sanitize_social_handler( $input ){
		$output = sanitize_text_field( $input );
		$output = str_replace('@', '', $output);
		return $output;
	}

	public function textField( $args )
	{
		$name = $args['label_for'];
		$option_name = $args['option_name'];
		$value = '';

		if ( isset($_POST["edit_post"]) ) {
			$input = get_option( $option_name );
			$value = $input[$_POST["edit_post"]][$name];
		}

		echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '" required>';
	}

	public function checkboxField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$checked = false;

		if ( isset($_POST["edit_post"]) ) {
			$checkbox = get_option( $option_name );
			$checked = isset($checkbox[$_POST["edit_post"]][$name]) ?: false;
		}

		echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
	}
	function getweb_sidebar_profile() {
		$picture = esc_attr( get_option( 'profile_picture' ) );
		if( empty($picture) ){
			echo '<button type="button" class="button button-secondary" value="Upload Profile Picture" id="upload-button"><span class="getweb-icon-button dashicons-before dashicons-format-image"></span> Upload Profile Picture</button><input type="hidden" id="profile-picture" name="profile_picture" value="" />';
		} else {
			echo '<button type="button" class="button button-secondary" value="Replace Profile Picture" id="upload-button"><span class="getweb-icon-button dashicons-before dashicons-format-image"></span> Replace Profile Picture</button><input type="hidden" id="profile-picture" name="profile_picture" value="'.$picture.'" /> <button type="button" class="button button-secondary" value="Remove" id="remove-picture"><span class="getweb-icon-button dashicons-before dashicons-no"></span> Remove</button>';
		}
	}

	public function textSidebarField( $args ) {
		$name        = $args['label_for'];
		$option_name = $args['option_name'];
		$value       = '';
		$input = get_option( $option_name );
		if (isset($input) && !empty($input)) {
			$value = $input;
		}else{
			$value       = '';
		}
		echo '<div class="form-group"><input type="text" class="regular-text form-control" id="' . $name . '" name="' . $option_name . '" value="' . $value . '" placeholder="' . $args['placeholder'] . '"></div>';
	}
}