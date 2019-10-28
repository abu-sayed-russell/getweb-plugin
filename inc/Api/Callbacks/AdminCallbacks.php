<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminDashboard()
	{
		return require_once( "$this->plugin_path/templates/admin/admin.php" );
	}

	public function adminCpt()
	{
		return require_once( "$this->plugin_path/templates/cpt/cpt.php" );
	}

	public function adminTaxonomy()
	{
		return require_once( "$this->plugin_path/templates/taxonomy/taxonomy.php" );
	}

	public function adminWidget()
	{
		return require_once( "$this->plugin_path/templates/widget/widget.php" );
	}
	public function WidgetSidebar()
	{
		return require_once( "$this->plugin_path/templates/widget/widget_sidebar.php" );
	}
	public function contactForm()
	{
		return require_once( "$this->plugin_path/templates/contact/contact.php" );
	}
	public function newslaterForm()
	{
		return require_once( "$this->plugin_path/templates/newslater/newslater_form.php" );
	}
	public function CustomCss()
	{
		return require_once( "$this->plugin_path/templates/customCss/customCss.php" );
	}
	public function adminGallery()
	{
		echo "<h1>Gallery Manager</h1>";
	}

	public function adminTestimonial()
	{
		echo "<h1>Testimonial Manager</h1>";
	}

	public function adminTemplates()
	{
		echo "<h1>Templates Manager</h1>";
	}

	public function adminAuth()
	{
		echo "<h1>Templates Manager</h1>";
	}

	public function adminMembership()
	{
		echo "<h1>Membership Manager</h1>";
	}

	public function adminChat()
	{
		echo "<h1>Chat Manager</h1>";
	}
}