<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\CustomCssCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;

/**
* 
*/
class CustomCssController extends BaseController
{
    public $settings;

    public $callbacks;
    public $css_callbacks;

	public $subpages = array();


	public function register()
	{
		if ( ! $this->activated( 'custom_css_manager' ) ) return;

		$this->settings = new SettingsApi();

        $this->callbacks = new AdminCallbacks();

        $this->css_callbacks = new CustomCssCallbacks();

        $this->setSubpages();

        $this->setSettings();

		$this->setSections();

		$this->setFields();

        $this->settings->addSubPages( $this->subpages )->register();
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_editor' ) );
        add_action( 'wp_head', array( $this, 'getweb_head_hook_css' ) );
    }
    public function setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'getweb_plugin', 
				'page_title' => 'Custom Design',
				'menu_title' => 'Custom Design',
				'capability' => 'manage_options', 
				'menu_slug' => 'getweb_css', 
				'callback' => array( $this->callbacks, 'CustomCss' )
			)
		);
    }
    public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'getweb_plugin_css_settings',
				'option_name' => 'getweb_custom_css',
				'callback' => array( $this->css_callbacks, 'getweb_sanitize_custom_css' )
			),
			array(
				'option_group' => 'getweb_plugin_css_settings',
				'option_name' => 'getweb_custom_js',
				'callback' => array( $this->css_callbacks, 'getweb_sanitize_custom_css' )
			)
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'getweb_css_index',
				'title' => '<h4>Custom CSS Manager</h4>',
				'callback' => array( $this->css_callbacks, 'cssSectionManager' ),
				'page' => 'getweb_css'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			array(
				'id' => 'getweb_custom_css',
				'title' => 'Insert your Custom CSS',
				'callback' => array( $this->css_callbacks, 'textAreaField' ),
				'page' => 'getweb_css',
				'section' => 'getweb_css_index',
				'args' => array(
					'option_name' => 'getweb_custom_css',
					'label_for' => 'getweb_custom_css',
					'placeholder' => 'Insert your Custom CSS',
				)
			),
			array(
				'id' => 'getweb_custom_js',
				'title' => 'Insert your Custom JS',
				'callback' => array( $this->css_callbacks, 'textAreaJSField' ),
				'page' => 'getweb_css',
				'section' => 'getweb_css_index',
				'args' => array(
					'option_name' => 'getweb_custom_js',
					'label_for' => 'getweb_custom_js',
					'placeholder' => 'Insert your Custom JS',
				)
			),
			
		);

		$this->settings->setFields( $args );
    }
    public function enqueue_editor()
	{
		wp_enqueue_style( 'editor-css', $this->plugin_url . 'assets/getweb_ace.css', array(), '1.0.0', 'all' );
        wp_enqueue_script( 'editor-js', $this->plugin_url . 'assets/ace/ace.js',array('jquery'), '1.2.1', true );
        wp_enqueue_script( 'getweb-editor-script', $this->plugin_url . 'assets/getweb_custom_css.js', array('jquery'), '1.0.0', true );

	}
	function getweb_head_hook_css() {
		require_once( "$this->plugin_path/templates/customCss/custom_head_css.php" );
		require_once( "$this->plugin_path/templates/customCss/custom_head_js.php" );
	}
}