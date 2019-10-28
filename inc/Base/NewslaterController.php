<?php
/**
 * @package  getwebPlugin
 */

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\NewslaterCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;


class NewslaterController extends BaseController
{

  public $settings;

  public $callbacks;

  public $news_callbacks;

  public $subpages = array();


  public function register()
  {
    if (!$this->activated('activate_newslater')) return;

    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();

    $this->news_callbacks = new NewslaterCallbacks();

    $this->setSubpages();

    $this->setSettings();

    $this->setSections();

    $this->setFields();

    $this->settings->addSubPages($this->subpages)->register();
    //Post Type
    add_action('init', array(&$this, 'getweb_newslater_custom_post_type'), 1000);
    //Column
    add_filter('manage_getweb-newslater_posts_columns', array(&$this, 'getweb_set_newslater_columns'));
    add_action('manage_getweb-newslater_posts_custom_column', array(&$this, 'getweb_newslater_custom_column'), 10, 2);
    //Email Meta
    add_action('add_meta_boxes', array(&$this, 'getweb_newslater_add_meta_box'), 1000);
    add_action('save_post', array(&$this, 'getweb_save_newslater_email_data'), 1000);

    //shortcode
    add_shortcode( 'show_newslater_form', array( &$this, 'getweb_newslater_form'), 1000 );
    //save contact
    add_action('wp_ajax_nopriv_getweb_save_user_newslater', array(&$this, 'getweb_save_newslater'));
    add_action('wp_ajax_getweb_save_user_newslater', array(&$this, 'getweb_save_newslater'));

    //add_action('phpmailer_init', array( &$this,'mailtrap'));


  }

  public function setSubpages()
  {
    $this->subpages = array(
      array(
        'parent_slug' => 'getweb_plugin',
        'page_title' => 'Newslater',
        'menu_title' => 'Newslater',
        'capability' => 'manage_options',
        'menu_slug' => 'getweb_newslater',
        'callback' => array($this->callbacks, 'newslaterForm')
      )
    );
  }

  public function setSettings()
  {
    $args = array(
      array(
        'option_group' => 'getweb_plugin_newslater_settings',
        'option_name' => 'news_form_process_message',
        'callback' => array($this->news_callbacks, 'cmSanitize')
      ),
      array(
        'option_group' => 'getweb_plugin_newslater_settings',
        'option_name' => 'news_success_message',
      ),
      array(
        'option_group' => 'getweb_plugin_newslater_settings',
        'option_name' => 'news_error_message',
      ),
      array(
        'option_group' => 'getweb_plugin_newslater_settings',
        'option_name' => 'news_email_error',
      ),
    );

    $this->settings->setSettings($args);
  }

  public function setSections()
  {
    $args = array(
      array(
        'id' => 'getweb_newslater_index',
        'title' => '<h4>Newslater Manager</h4>',
        'callback' => array($this->news_callbacks, 'cmSectionManager'),
        'page' => 'getweb_newslater'
      )
    );

    $this->settings->setSections($args);
  }

  public function setFields()
  {
    $args = array(

      array(
        'id' => 'form_process_message',
        'title' => 'Form Process Message',
        'callback' => array($this->news_callbacks, 'textField'),
        'page' => 'getweb_newslater',
        'section' => 'getweb_newslater_index',
        'args' => array(
          'option_name' => 'news_form_process_message',
          'label_for' => 'news_form_process_message',
          'placeholder' => 'Submission in process, please wait..',

        )
      ),
      array(
        'id' => 'news_success_message',
        'title' => 'Success Message',
        'callback' => array($this->news_callbacks, 'textField'),
        'page' => 'getweb_newslater',
        'section' => 'getweb_newslater_index',
        'args' => array(
          'option_name' => 'news_success_message',
          'label_for' => 'news_success_message',
          'placeholder' => 'Message Successfully submitted, thank you!',
        )
      ),

      array(
        'id' => 'news_error_message',
        'title' => 'Error Message',
        'callback' => array($this->news_callbacks, 'textField'),
        'page' => 'getweb_newslater',
        'section' => 'getweb_newslater_index',
        'args' => array(
          'option_name' => 'news_error_message',
          'label_for' => 'news_error_message',
          'placeholder' => 'There was a problem with the Contact Form, please try again!',

        )
      ),
      array(
        'id' => 'news_email_error',
        'title' => 'Email Error Message',
        'callback' => array($this->news_callbacks, 'textField'),
        'page' => 'getweb_newslater',
        'section' => 'getweb_newslater_index',
        'args' => array(
          'option_name' => 'news_email_error',
          'label_for' => 'news_email_error',
          'placeholder' => 'Your Email is Required',

        )
      ),
    );

    $this->settings->setFields($args);
  }

  /* CONTACT FORM */
  function getweb_newslater_custom_post_type()
  {
    $labels = array(
      'name' => 'Newslater',
      'singular_name' => 'Newslater',
      'menu_name' => 'Newslater',
      'name_admin_bar' => 'Newslater'
    );

    $args = array(
      'labels' => $labels,
      'show_ui' => true,
      'show_in_menu' => true,
      'capability_type' => 'post',
      'capabilities' => array(
        'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
      ),
      'map_meta_cap' => true,
      'hierarchical' => false,
      'menu_position' => 27,
      'menu_icon' => 'dashicons-email-alt2',
      'supports' => array('title', 'editor', 'author')
    );
    register_post_type('getweb-newslater', $args);
  }

  public function getweb_set_newslater_columns($column)
  {
    $newColumns = array();
    $newColumns['cb'] = 'cb';
    $newColumns['email'] = 'Email';
    $newColumns['date'] = 'Date';
    return $newColumns;
  }

  public function getweb_newslater_custom_column($column, $post_id)
  {

    switch ($column) {
      
      case 'email' :
        //email column
        $email = get_post_meta($post_id, '_newslater_email_value_key', true);
        echo '<a href="mailto:' . $email . '">' . $email . '</a>';
        break;
        
    }

  }

  /* CONTACT META BOXES */

  public function getweb_newslater_add_meta_box()
  {
    add_meta_box('newslater_email', 'User Email', 'getweb_newslater_email_callback', 'newslater', 'side');
  }

  public function getweb_newslater_email_callback($post)
  {
    wp_nonce_field('getweb_save_newslater_email_data', 'getweb_newslater_email_meta_box_nonce');

    $value = get_post_meta($post->ID, '_newslater_email_value_key', true);

    echo '<label for="getweb_newslater_email_field">User Email Address: </label>';
    echo '<input type="email" id="getweb_newslater_email_field" name="getweb_newslater_email_field" value="' . esc_attr($value) . '" size="25" />';
  }

  public function getweb_save_newslater_email_data($post_id)
  {

    if (!isset($_POST['getweb_newslater_email_meta_box_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['getweb_newslater_email_meta_box_nonce'], 'getweb_save_newslater_email_data')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    if (!isset($_POST['getweb_newslater_email_field'])) {
      return;
    }

    $my_data = sanitize_text_field($_POST['getweb_newslater_email`_field']);

    update_post_meta($post_id, '_newslater_email_value_key', $my_data);

  }


  function getweb_newslater_form($atts, $content = null)
  {

    //[show_newslater_form]

    //get the attributes
    $atts = shortcode_atts(
      array(),
      $atts,
      'show_newslater_form'
    );

    ob_start();
    echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/newslater-form.css\" type=\"text/css\" media=\"all\" />";
    require_once("$this->plugin_path/templates/newslater/newslater-form.php");
    echo "<script src=\"$this->plugin_url/assets/newslater-form.js\"></script>";
    return ob_get_clean();

  }

  function getweb_save_newslater()
  {
    $email = wp_strip_all_tags($_POST['email']);

    $args = array(
      'post_author' => 1,
      'post_status' => 'publish',
      'post_type' => 'getweb-newslater',
      'meta_input' => array(
        '_newslater_email_value_key' => $email,
      ),
    );

    $postID = wp_insert_post($args);

    if ($postID !== 0) {
      $to = get_bloginfo('admin_email');
      $subject = 'Sagc Newslater - ' . $email;
      $message = '';
      $headers[] = 'From: ' . get_bloginfo('name') . ' <' . $to . '>'; // 'From: Alex <me@alecaddd.com>'
      $headers[] = 'Reply-To: ' . $email . ' <' . $email . '>';
      $headers[] = 'Content-Type: text/html: charset=UTF-8';

      wp_mail($to, $subject, $message, $headers);
    }

    echo $postID;

    die();
  }
// function mailtrap($phpmailer) {
// 	$phpmailer->isSMTP();
// 	$phpmailer->Host = 'smtp.mailtrap.io';
// 	$phpmailer->SMTPAuth = true;
// 	$phpmailer->Port = 2525;
// 	$phpmailer->Username = '3322681adc4652';
// 	$phpmailer->Password = '3b8e1edd625d13';
//   }


}