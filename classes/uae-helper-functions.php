<?php
/**
 * UAE & ELementor Pro supportive Common function.
 *
 * @package UAE Posts Custom Skins
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Validate if Elementor Pro plugin activate
 * 
 * @package UAE Posts Custom Skins
 * @since 1.0.0
 */
if ( ! function_exists( 'is_elementor_pro_activate' ) ) {

	function is_elementor_pro_activate() {

		if ( defined( 'ELEMENTOR_PRO__FILE__' ) ) {
			return true;
		}

		return false;
	}
}

/**
 * Validate if UAE Pro plugin activate
 * 
 * @package UAE Posts Custom Skins
 * @since 1.0.0
 */
if ( ! function_exists( 'is_uae_pro_activate' ) ) {

	function is_uae_pro_activate() {

		if ( defined( 'UAEL_FILE' ) ) {
			return true;
		}

		return false;
	}
}
