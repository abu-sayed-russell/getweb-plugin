<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Base;

class BaseController
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public $managers = array();

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/getweb-plugin.php';

		$this->managers = array(
			'cpt_manager' => 'Activate CPT Manager',
			'taxonomy_manager' => 'Activate Taxonomy Manager',
			'widget_manager' => 'Activate Widget Manager',
			'templates_manager' => 'Activate Custom Templates',
			//'login_manager' => 'Activate Ajax Login/Signup',
			//'testimonial_manager' => 'Activate Testimonial Manager',
			//'membership_manager' => 'Activate Membership Manager',
			'activate_contact' => 'Active Contact Form',
			'activate_newslater' => 'Active Newsletter',
			'custom_css_manager' => 'Active Custom Design',
			'gutenberg_manager' => 'Active Gutenberg Option'
		);
	}

	public function activated( string $key )
	{
		$option = get_option( 'getweb_plugin' );

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}
}