<?php 
/**
 * @package  getwebPlugin
 */
namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;

/**
* 
*/
class GutenbergController extends BaseController
{



	public function register()
	{
		if ( ! $this->activated( 'gutenberg_manager' ) ) return;
		add_action( 'init', array($this,'getwebgutenberg_default_colors') );
    }
    /**
 * Custom Gutenberg functions
 */

function getwebgutenberg_default_colors()
{
    add_theme_support(
        'editor-color-palette',
        array(
            array(
                'name' => 'White',
                'slug' => 'white',
                'color' => '#ffffff'
            ),
            array(
                'name' => 'Black',
                'slug' => 'black',
                'color' => '#000000'
            ),
            array(
                'name' => 'Pink',
                'slug' => 'pink',
                'color' => '#ff4444'
            )
        )
    );

    add_theme_support( 
        'editor-font-sizes', 
        array(
            array(
                'name' => 'Small-12',
                'size' => 12,
                'slug' => 'small'
            ),
            array(
                'name' => 'Normal-16',
                'size' => 16,
                'slug' => 'normal'
            ),
            array(
                'name' => 'Large-36',
                'size' => 36,
                'slug' => 'large'
            ),
            array(
                'name' => 'Huge-50',
                'size' => 50,
                'slug' => 'huge'
            )
        ) 
    );
}
}