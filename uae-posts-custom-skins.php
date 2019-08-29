<?php
/**
 * Plugin Name: UAE - Post's Custom Skins
 * Author: Brainstorm Force, Navanath Bhosale
 * Author URI: https://brainstormforce.com
 * Version: 1.0.0
 * Description: This plugin is useful to add custom skin layouts to <strong> Ultimate Addons for Elementor (UAE)</strong> Posts widget.
 * Text Domain: uae-post-skins
 *
 * @package UAE_POSTS_CUSTOM_SKINS
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Set constants.
 */
define( 'UAE_POSTS_SKINS_FILE', __FILE__ );
define( 'UAE_POSTS_SKINS_VER', '1.0.0' );
define( 'UAE_POSTS_PLUGIN_NAME', 'UAE - Post\'s Custom Skins' );
define( 'UAE_POSTS_SKINS_DIR', plugin_dir_path( __FILE__ ) );
define( 'UAE_POSTS_SKINS_URL', plugins_url( '/', __FILE__ ) );
define( 'UAE_POSTS_SKINS_ROOT', dirname( plugin_basename( __FILE__ ) ) );

if ( ! function_exists( 'render_uae_custom_skin_setup' ) ) :

	/**
	 * UAE - Posts's Custom Skins Setup
	 *
	 * @since 1.0.0
	 */
	function render_uae_custom_skin_setup() {
		require_once UAE_POSTS_SKINS_DIR . 'classes/class-uae-post-skins-loader.php';
	}

	add_action( 'plugins_loaded', 'render_uae_custom_skin_setup' );

endif;
