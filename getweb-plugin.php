<?php
/**
 * @package  getwebPlugin
 */
/*
Plugin Name: Getweb plugin
Plugin URI: https://wordpress.org/plugins/gutenberg-custom-post
Description: Dynamically add custom post type and custom taxonomy type.
Version: 1.0.0
Author: R S RUSSELL
Author URI: https://facebook.com/with.rain79
License: GPLv2
Text Domain: getweb-plugin
*/


// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );
define( 'WETWEB_VERSION', '1.0' );
include('inc/Helper/helper.php');
// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_getweb_plugin() {
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_getweb_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_getweb_plugin() {
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_getweb_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Inc\\Init' ) ) {
	Inc\Init::registerServices();
}