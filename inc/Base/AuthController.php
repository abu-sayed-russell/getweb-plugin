<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Base;

use Inc\Base\BaseController;

/**
* 
*/
class AuthController extends BaseController
{
	public function register()
	{
		if ( ! $this->activated( 'login_manager' ) ) return;

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_head', array( $this, 'add_auth_template' ) );
		add_action( 'wp_ajax_nopriv_getweb', array( $this, 'login' ) );
	}

	public function enqueue()
	{
		if ( is_user_logged_in() ) return;
		wp_enqueue_style( 'authstyle', $this->plugin_url . 'assets/auth.css' );
		wp_enqueue_script( 'authscript', $this->plugin_url . 'assets/auth.js' );

	}

	public function add_auth_template()
	{
		if ( is_user_logged_in() ) return;

		$file = $this->plugin_path . 'templates/auth/auth.php';

		if ( file_exists( $file ) ) {
			load_template( $file, true );
		}
	}
	public function login(Type $var = null)
	{
		check_ajax_referer('ajax-login-once', 'getweb_auth');

		$info = array();
		$info['user_login'] = $_POST['username'];
		$info['user_password'] = $_POST['password'];
		$info['remember'] = true;

		$user_signon = wp_signon( $info, true );
		if ( is_wp_error($user_signon) ){
			echo json_encode( array( 'status'=>false, 'message'=>'Wrong username or password.' ) );
		} else { 
			echo json_encode( array( 'status'=>true, 'message'=> 'Login successful, redirecting...' ) );
		}
		die();
	}
}