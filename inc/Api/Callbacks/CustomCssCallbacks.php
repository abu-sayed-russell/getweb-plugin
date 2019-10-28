<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Api\Callbacks;

class CustomCssCallbacks
{

	public function cssSectionManager()
	{
		echo '';
	}
	public function getweb_sanitize_custom_css( $input ){
		$output = esc_textarea( $input );
		return $input;
	}
	

	public function textAreaField( $args )
	{
		$name        = $args['label_for'];
		$option_name = $args['option_name'];
		$css = get_option( $option_name );
		$css = ( empty($css) ? '/* Getweb Theme Custom CSS */' : $css );
		echo '<div id="customCss">'.$css.'</div><textarea style="display:none;visibility:hidden;" class="regular-text" id="getweb_css" name="' . $option_name . '"  placeholder="' . $args['placeholder'] . '">'.$css.'</textarea>';
	}

	public function textAreaJSField( $args )
	{
		$name        = $args['label_for'];
		$option_name = $args['option_name'];
		$js = get_option( 'getweb_custom_js' );
		$js = ( empty($js) ? '/* Getweb Theme Custom JS */' : $js );
		echo '<div id="customJS">'.$js.'</div><textarea style="display:none;visibility:hidden;" class="regular-text" id="getweb_js" name="' . $option_name . '"  placeholder="' . $args['placeholder'] . '">'.$js.'</textarea>';
	}
}