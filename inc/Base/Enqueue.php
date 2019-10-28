<?php
/**
 * @package  getwebPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;

/**
 *
 */
class Enqueue extends BaseController {
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	function enqueue( ) {
		$slug          = "";
		$page_includes = array( "getweb_form", "getweb_plugin","getweb_cpt","getweb_taxonomy","getweb_cwm","getweb_css","getweb_sidebar","getweb_newslater" );
		$currentPage   = $_GET['page'];
		if ( in_array( $currentPage, $page_includes ) ) {
			// enqueue all our style
			wp_enqueue_style( "getweb-bootstrap-css", $this->plugin_url . 'assets/css/bootstrap.min.css', '', WETWEB_VERSION );
			wp_enqueue_style( 'mypluginstyle', $this->plugin_url . 'assets/mystyle.css','', WETWEB_VERSION );
			wp_enqueue_style( 'getweb-style', $this->plugin_url . 'assets/css/style.css','', WETWEB_VERSION );

			// enqueue all our scripts
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_media();
			wp_enqueue_script("getweb-bootstrap-js", $this->plugin_url . 'assets/js/bootstrap.min.js', '', WETWEB_VERSION, true);
			wp_enqueue_script( 'mypluginscript', $this->plugin_url . 'assets/myscript.js' );
		}
	}
}