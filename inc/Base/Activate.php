<?php
/**
 * @package  getwebPlugin
 */
namespace Inc\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();

		$default = array();

		if ( ! get_option( 'getweb_plugin' ) ) {
			update_option( 'getweb_plugin', $default );
		}

		if ( ! get_option( 'getweb_plugin_cpt' ) ) {
			update_option( 'getweb_plugin_cpt', $default );
		}

		if ( ! get_option( 'getweb_plugin_tax' ) ) {
			update_option( 'getweb_plugin_tax', $default );
		}
		if ( ! get_option( 'getweb_plugin_cwm' ) ) {
			update_option( 'getweb_plugin_cwm', $default );
		}

	}
}