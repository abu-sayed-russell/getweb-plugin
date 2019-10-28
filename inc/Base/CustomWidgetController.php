<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\WidgetCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;

/**
* 
*/
class CustomWidgetController extends BaseController
{
	public $settings;

	public $callbacks;

	public $cwm_callbacks;

	public $subpages = array();

	public $custom_post_types = array();

	public function register()
	{
		if ( ! $this->activated( 'widget_manager' ) ) return;
		require_once( "$this->plugin_path/templates/widget/widgets.php" );
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->cwm_callbacks = new WidgetCallbacks();

		$this->setSubpages();

		$this->setSettings();

		$this->setSections();

		$this->setFields();

		$this->settings->addSubPages( $this->subpages )->register();

		$this->storeCustomWidget();

		// if ( ! empty( $this->custom_widget_types ) ) {
        //     add_action( 'widgets_init', array( $this, 'registerCustomWidget' ) );
		// }
        if (! empty($this->custom_widget_types)) {
            add_action('widgets_init', array( &$this, 'registerCustomWidget'), 1000);
        }
		add_action( 'admin_enqueue_scripts', array( $this, 'sidebar_enqueue' ) );
	}

	public function setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'getweb_plugin', 
				'page_title' => 'Custom Widget', 
				'menu_title' => 'Custom Widget', 
				'capability' => 'manage_options', 
				'menu_slug' => 'getweb_cwm', 
				'callback' => array( $this->callbacks, 'adminWidget' )
			),
			array(
				'parent_slug' => 'getweb_plugin',
				'page_title' => 'Sidebar Profile',
				'menu_title' => 'Sidebar Profile',
				'capability' => 'manage_options',
				'menu_slug' => 'getweb_sidebar',
				'callback' => array( $this->callbacks, 'WidgetSidebar' )
			),
		);
	}

	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'getweb_plugin_cwm_settings',
				'option_name' => 'getweb_plugin_cwm',
				'callback' => array( $this->cwm_callbacks, 'cwmSanitize' )
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'profile_picture',
				'callback' => array( $this->cwm_callbacks, 'sidebarSanitize' )
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'getweb_first_name',
				'callback' => ""
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'getweb_last_name',
				'callback' => ""
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'getweb_profile_desc',
				'callback' => ""
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'getweb_profile_fb',
				'callback' => array( $this->cwm_callbacks, 'getweb_sanitize_social_handler' )
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'getweb_profile_twitter',
				'callback' => array( $this->cwm_callbacks, 'getweb_sanitize_social_handler' )
			),
			array(
				'option_group' => 'getweb_plugin_sidebar_settings',
				'option_name' => 'getweb_profile_gplus',
				'callback' => array( $this->cwm_callbacks, 'getweb_sanitize_social_handler' )
			),
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'getweb_cwm_index',
				'title' => '<h4>Custom Widget Manager</h4>',
				'callback' => '',
				'page' => 'getweb_cwm'
			),
			array(
				'id' => 'getweb_sidebar_index',
				'title' => '<h4>Custom Sidebar Manager</h4>',
				'callback' => '',
				'page' => 'getweb_sidebar'
			),
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			array(
				'id' => 'cwm_name',
				'title' => 'Widget Name',
				'callback' => array( $this->cwm_callbacks, 'textField' ),
				'page' => 'getweb_cwm',
				'section' => 'getweb_cwm_index',
				'args' => array(
					'option_name' => 'getweb_plugin_cwm',
					'label_for' => 'cwm_name',
					'placeholder' => 'eg. Widget Name',
					'array' => 'cwm_name'
				)
			),
			array(
				'id' => 'widget_id',
				'title' => 'Widget ID',
				'callback' => array( $this->cwm_callbacks, 'textField' ),
				'page' => 'getweb_cwm',
				'section' => 'getweb_cwm_index',
				'args' => array(
					'option_name' => 'getweb_plugin_cwm',
					'label_for' => 'widget_id',
					'placeholder' => 'eg. Widget ID  (ID should be LOWERCASE)',
					'array' => 'cwm_name'
				)
			),
			array(
				'id' => 'description',
				'title' => 'Description',
				'callback' => array( $this->cwm_callbacks, 'textField' ),
				'page' => 'getweb_cwm',
				'section' => 'getweb_cwm_index',
				'args' => array(
					'option_name' => 'getweb_plugin_cwm',
					'label_for' => 'description',
					'placeholder' => 'eg. Widget Description',
					'array' => 'cwm_name'
				)
            ),
			array(
				'id' => 'class',
				'title' => 'Widget Class',
				'callback' => array( $this->cwm_callbacks, 'textField' ),
				'page' => 'getweb_cwm',
				'section' => 'getweb_cwm_index',
				'args' => array(
					'option_name' => 'getweb_plugin_cwm',
					'label_for' => 'class',
					'placeholder' => 'eg. Widget Class',
					'array' => 'cwm_name'
				)
            ),
			array(
				'id' => 'before_widget',
				'title' => 'Before Widget Class',
				'callback' => array( $this->cwm_callbacks, 'textField' ),
				'page' => 'getweb_cwm',
				'section' => 'getweb_cwm_index',
				'args' => array(
					'option_name' => 'getweb_plugin_cwm',
					'label_for' => 'before_widget',
					'placeholder' => 'eg. Before Widget',
					'array' => 'cwm_name'
				)
            ),
            array(
				'id' => 'before_title',
				'title' => 'Before Title Class',
				'callback' => array( $this->cwm_callbacks, 'textField' ),
				'page' => 'getweb_cwm',
				'section' => 'getweb_cwm_index',
				'args' => array(
					'option_name' => 'getweb_plugin_cwm',
					'label_for' => 'before_title',
					'placeholder' => 'eg. Before Title',
					'array' => 'cwm_name'
				)
            ),
            array(
				'id' => 'profile_picture',
				'title' => 'Upload Profile Picture',
				'callback' => array( $this->cwm_callbacks, 'getweb_sidebar_profile' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'profile_picture',
					'label_for' => 'profile_picture',

				)
            ),
            array(
				'id' => 'getweb_first_name',
				'title' => 'First Name',
				'callback' => array( $this->cwm_callbacks, 'textSidebarField' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'getweb_first_name',
					'label_for' => 'getweb_first_name',
					'placeholder' => 'eg. First Name',

				)
            ),
            array(
				'id' => 'getweb_last_name',
				'title' => 'Last Name',
				'callback' => array( $this->cwm_callbacks, 'textSidebarField' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'getweb_last_name',
					'label_for' => 'getweb_last_name',
					'placeholder' => 'eg. Last Name',

				)
            ),
            array(
				'id' => 'getweb_profile_desc',
				'title' => 'Description',
				'callback' => array( $this->cwm_callbacks, 'textSidebarField' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'getweb_profile_desc',
					'label_for' => 'getweb_profile_desc',
					'placeholder' => 'eg. Description',

				)
            ),
            array(
				'id' => 'getweb_profile_fb',
				'title' => 'Facebook Handler',
				'callback' => array( $this->cwm_callbacks, 'textSidebarField' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'getweb_profile_fb',
					'label_for' => 'getweb_profile_fb',
					'placeholder' => 'eg. Facebook Handler',

				)
            ),
            array(
				'id' => 'getweb_profile_twitter',
				'title' => 'Twitter Handler',
				'callback' => array( $this->cwm_callbacks, 'textSidebarField' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'getweb_profile_twitter',
					'label_for' => 'getweb_profile_twitter',
					'placeholder' => 'eg. Twitter Handler',

				)
            ),
            array(
				'id' => 'getweb_profile_gplus',
				'title' => 'Google+ Handler',
				'callback' => array( $this->cwm_callbacks, 'textSidebarField' ),
				'page' => 'getweb_sidebar',
				'section' => 'getweb_sidebar_index',
				'args' => array(
					'option_name' => 'getweb_profile_gplus',
					'label_for' => 'getweb_profile_gplus',
					'placeholder' => 'eg. Google+ Handler',

				)
            ),
		);

		$this->settings->setFields( $args );
	}

	public function storeCustomWidget()
	{
		$options = get_option( 'getweb_plugin_cwm' ) ?: array();

		foreach ($options as $option) {
            $this->custom_widget_types[] =  array(
				'name'          => $option['cwm_name'],
                'id'            => $option['widget_id'],    // ID should be LOWERCASE  ! ! !
                'description'   => !empty($option['description']) ? $option['description'] : '',
                'class'         => !empty($option['class']) ? $option['class'] : '',
                'before_widget' => $option["before_widget"],
                'after_widget'  => $option["after_widget"],
                'before_title'  => $option["before_title"],
                'after_title'   => $option["after_title"],
            );
            
        }
        
	}

	public function registerCustomWidget()
	{
		$options = get_option( 'getweb_plugin_cwm' ) ?: array();
		foreach ( $options as $cwm_name) {
			$args['name']          = $cwm_name['cwm_name'];
			$args['id']            = $cwm_name['widget_id'];    // ID should be LOWERCASE  ! ! !
			$args['description']   = !empty($cwm_name['description']) ? $cwm_name['description'] : '';
			$args['class']         = !empty($cwm_name['class']) ? $cwm_name['class'] : '';
			$args['before_widget'] = '<div class="widget %2$s clearfix '.$cwm_name["before_widget"].'"><div class="dynamic-widgets-wrapper"><div class="dynamic-tiltle">';
			$args['after_widget']  = '</div></div></div>';
			$args['before_title']  = '<div class="widget-title '.$cwm_name["before_title"].'"><h3 class="dynamic-block-title">';
			$args['after_title']   = '</h3></div>';
			register_sidebar( $args );
		}
	}

	public function sidebar_enqueue()
	{
		wp_enqueue_style( 'getweb-sidebar-css', $this->plugin_url . 'assets/css/sidebar.css', array(), '1.0.0', 'all' );
		wp_enqueue_script( 'getweb-sidebar-script', $this->plugin_url . 'assets/js/sidebar.js', array('jquery'), '1.0.0', true );

	}
	
}