<?php
/**
 * UAE Posts custom Skin.
 *
 * @package UAE_POSTS_CUSTOM_SKINS
 */

namespace UltimateElementor\Modules\Posts\TemplateBlocks;

use UltimateElementor\Modules\Posts\TemplateBlocks\Skin_Style;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Classic
 */
class Skin_Classic extends Skin_Style {


	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Get featured image.
	 *
	 * Returns the featured image HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_featured_image_featured_post() {

		$settings = self::$settings;

		echo 'into custom skin file';
        
        // echo '<pre>';
        // var_dump($settings);
        // die();
	}

}

