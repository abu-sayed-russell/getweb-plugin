<?php
/**
 * @package  getwebPlugin
 */

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\ContactCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;


class ContactController extends BaseController
{

  public $settings;

  public $callbacks;

  public $cm_callbacks;

  public $subpages = array();


  public function register()
  {
    if (!$this->activated('activate_contact')) return;

    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();

    $this->cm_callbacks = new ContactCallbacks();

    $this->setSubpages();

    $this->setSettings();

    $this->setSections();

    $this->setFields();

    $this->settings->addSubPages($this->subpages)->register();
    //Post Type
    add_action('init', array(&$this, 'getweb_contact_custom_post_type'), 1000);
    //Column
    add_filter('manage_getweb-contact_posts_columns', array(&$this, 'getweb_set_contact_columns'));
    add_action('manage_getweb-contact_posts_custom_column', array(&$this, 'getweb_contact_custom_column'), 10, 2);
    //Email Meta
    add_action('add_meta_boxes', array(&$this, 'getweb_contact_add_meta_box'), 1000);
    add_action('save_post', array(&$this, 'getweb_save_contact_email_data'), 1000);
    //Mobile Number Meta
    add_action('add_meta_boxes', array(&$this, 'getweb_contact_mobile_add_meta_box'), 1000);
    add_action('save_post', array(&$this, 'getweb_save_contact_mobile_data'), 1000);

    //shortcode
    add_shortcode( 'show_contact_form', array( &$this, 'getweb_contact_form'), 1000 );
    //save contact
    add_action('wp_ajax_nopriv_getweb_save_user_contact_form', array(&$this, 'getweb_save_contact'));
    add_action('wp_ajax_getweb_save_user_contact_form', array(&$this, 'getweb_save_contact'));

    //add_action('phpmailer_init', array( &$this,'mailtrap'));


  }

  public function setSubpages()
  {
    $this->subpages = array(
      array(
        'parent_slug' => 'getweb_plugin',
        'page_title' => 'Contact Form',
        'menu_title' => 'Contact Form',
        'capability' => 'manage_options',
        'menu_slug' => 'getweb_form',
        'callback' => array($this->callbacks, 'contactForm')
      )
    );
  }

  public function setSettings()
  {
    $args = array(
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'form_process_message',
        'callback' => array($this->cm_callbacks, 'cmSanitize')
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'success_message',
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'error_message',
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'extra_class',
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'name_error',
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'email_error',
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'message_error',
      ),
      array(
        'option_group' => 'getweb_plugin_form_settings',
        'option_name' => 'mobile_error',
      ),
    );

    $this->settings->setSettings($args);
  }

  public function setSections()
  {
    $args = array(
      array(
        'id' => 'getweb_form_index',
        'title' => '<h4>Contact Form Manager</h4>',
        'callback' => array($this->cm_callbacks, 'cmSectionManager'),
        'page' => 'getweb_form'
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
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'form_process_message',
          'label_for' => 'form_process_message',
          'placeholder' => 'Submission in process, please wait..',

        )
      ),
      array(
        'id' => 'success_message',
        'title' => 'Success Message',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'success_message',
          'label_for' => 'success_message',
          'placeholder' => 'Message Successfully submitted, thank you!',
        )
      ),

      array(
        'id' => 'error_message',
        'title' => 'Error Message',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'error_message',
          'label_for' => 'error_message',
          'placeholder' => 'There was a problem with the Contact Form, please try again!',

        )
      ),
      array(
        'id' => 'name_error',
        'title' => 'Name Error Message',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'name_error',
          'label_for' => 'name_error',
          'placeholder' => 'Your Name is Required',

        )
      ),
      array(
        'id' => 'email_error',
        'title' => 'Email Error Message',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'email_error',
          'label_for' => 'email_error',
          'placeholder' => 'Your Email is Required',

        )
      ),
      array(
        'id' => 'message_error',
        'title' => 'Message Error',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'message_error',
          'label_for' => 'message_error',
          'placeholder' => 'Your Message is Required',

        )
      ),
      array(
        'id' => 'mobile_error',
        'title' => 'Mobile Error',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'mobile_error',
          'label_for' => 'mobile_error',
          'placeholder' => 'Your Mobile Number is Required',

        )
      ),

      array(
        'id' => 'extra_class',
        'title' => 'Extra Class',
        'callback' => array($this->cm_callbacks, 'textField'),
        'page' => 'getweb_form',
        'section' => 'getweb_form_index',
        'args' => array(
          'option_name' => 'extra_class',
          'label_for' => 'extra_class',
          'placeholder' => 'eg. Extra Class',
        )
      ),
    );

    $this->settings->setFields($args);
  }

  /* CONTACT FORM */
  function getweb_contact_custom_post_type()
  {
    $labels = array(
      'name' => 'Messages',
      'singular_name' => 'Message',
      'menu_name' => 'Messages',
      'name_admin_bar' => 'Message'
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
      'menu_position' => 26,
      'menu_icon' => 'dashicons-email-alt',
      'supports' => array('title', 'editor', 'author')
    );
    register_post_type('getweb-contact', $args);
  }

  public function getweb_set_contact_columns()
  {
    $newColumns = array();
    $newColumns['cb'] = 'cb';
    $newColumns['title'] = 'Full Name';
    $newColumns['message'] = 'Message';
    $newColumns['email'] = 'Email';
    $newColumns['mobile'] = 'Mobile';
    $newColumns['date'] = 'Date';
    return $newColumns;
  }

  public function getweb_contact_custom_column($column, $post_id)
  {

    switch ($column) {

      case 'message' :
        echo get_the_excerpt();
        break;

      case 'email' :
        //email column
        $email = get_post_meta($post_id, '_contact_email_value_key', true);
        echo '<a href="mailto:' . $email . '">' . $email . '</a>';
        break;

      case 'mobile' :
        //email column
        $mobile = get_post_meta($post_id, '_contact_mobile_value_key', true);
        echo '<a href="tel:' . $mobile . '">' . $mobile . '</a>';
        break;
    }

  }

  /* CONTACT META BOXES */

  public function getweb_contact_add_meta_box()
  {
    add_meta_box('contact_email', 'User Email', 'getweb_contact_email_callback', 'getweb-contact', 'side');
  }

  public function getweb_contact_email_callback($post)
  {
    wp_nonce_field('getweb_save_contact_email_data', 'getweb_contact_email_meta_box_nonce');

    $value = get_post_meta($post->ID, '_contact_email_value_key', true);

    echo '<label for="getweb_contact_email_field">User Email Address: </label>';
    echo '<input type="email" id="getweb_contact_email_field" name="getweb_contact_email_field" value="' . esc_attr($value) . '" size="25" />';
  }

  public function getweb_save_contact_email_data($post_id)
  {

    if (!isset($_POST['getweb_contact_email_meta_box_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['getweb_contact_email_meta_box_nonce'], 'getweb_save_contact_email_data')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    if (!isset($_POST['getweb_contact_email_field'])) {
      return;
    }

    $my_data = sanitize_text_field($_POST['getweb_contact_email_field']);

    update_post_meta($post_id, '_contact_email_value_key', $my_data);

  }

  public function getweb_contact_mobile_add_meta_box()
  {
    add_meta_box('contact_mobile', 'User Mobile', 'getweb_contact_mobile_callback', 'getweb-contact', 'side', 'default');
  }

  public function getweb_contact_mobile_callback($post)
  {
    wp_nonce_field('getweb_save_contact_mobile_data', 'getweb_contact_mobile_meta_box_nonce');

    $value = get_post_meta($post->ID, '_contact_mobile_value_key', true);

    echo '<label for="getweb_contact_mobile_field">User Mobile: </label>';
    echo '<input type="text" id="getweb_contact_mobile_field" name="getweb_contact_mobile_field" value="' . esc_attr($value) . '" size="25" />';
  }

  public function getweb_save_contact_mobile_data($post_id)
  {

    if (!isset($_POST['getweb_contact_mobile_meta_box_nonce'])) {
      return;
    }

    if (!wp_verify_nonce($_POST['getweb_contact_mobile_meta_box_nonce'], 'getweb_save_contact_mobile_data')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    if (!isset($_POST['getweb_contact_mobile_field'])) {
      return;
    }

    $my_data = sanitize_text_field($_POST['getweb_contact_mobile_field']);

    update_post_meta($post_id, '_contact_mobile_value_key', $my_data);

  }

  function getweb_contact_form($atts, $content = null)
  {

    //[show_contact_form]

    //get the attributes
    $atts = shortcode_atts(
      array(),
      $atts,
      'show_contact_form'
    );

    ob_start();
    echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/contact-form.css\" type=\"text/css\" media=\"all\" />";
    require_once("$this->plugin_path/templates/contact/contact-form.php");
    echo "<script src=\"$this->plugin_url/assets/contact-form.js\"></script>";
    return ob_get_clean();

  }

  function getweb_save_contact()
  {
    $title = wp_strip_all_tags($_POST['name']);
    $email = wp_strip_all_tags($_POST['email']);
    $mobile = wp_strip_all_tags($_POST['mobile']);
    $message = wp_strip_all_tags($_POST['message']);

    $args = array(
      'post_title' => $title,
      'post_content' => $message,
      'post_author' => 1,
      'post_status' => 'publish',
      'post_type' => 'getweb-contact',
      'meta_input' => array(
        '_contact_email_value_key' => $email,
        '_contact_mobile_value_key' => $mobile,
      ),
    );

    $postID = wp_insert_post($args);

    if ($postID !== 0) {
      $to = get_bloginfo('admin_email');
      $subject = 'Sagc Contact Form - ' . $title;

      $headers[] = 'From: ' . get_bloginfo('name') . ' <' . $to . '>'; // 'From: Alex <me@alecaddd.com>'
      $headers[] = 'Reply-To: ' . $title . ' <' . $email . '>';
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