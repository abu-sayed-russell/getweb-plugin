<?php$custom_js = esc_attr( get_option( 'getweb_custom_js' ) );if( !empty( $custom_js ) ):	echo '<script>' . $custom_js . '</script>';endif;?>