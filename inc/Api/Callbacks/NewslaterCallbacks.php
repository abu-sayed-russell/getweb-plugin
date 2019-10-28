<?php
/**
 * @package  getwebPlugin
 */

namespace Inc\Api\Callbacks;

class NewslaterCallbacks {

	public function cmSectionManager() {
		echo '<p>Use this <strong>shortcode</strong> to activate the Newslater inside a Page or a Post</p>';
      echo '<p><code>[show_newslater_form]</code></p>';
	}

	public function cmSanitize( $input ) {
		return $input;
	}
	public function textField( $args ) {
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