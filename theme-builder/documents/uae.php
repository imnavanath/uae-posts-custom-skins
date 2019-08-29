<?php
namespace ElementorPro\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class UAE_Post_Skin extends Theme_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['condition_type'] = 'general';
    	$properties['location'] = 'archive';

		return $properties;
	}

	public function get_name() {
		return 'post_skin';
	}

	public static function get_title() {
		return __( 'Post Skin', 'uae-post-skins' );
	}

	public static function get_preview_as_default() {
		return '';
	}

	public static function get_preview_as_options() {
		return array_merge(
			[
				'',
			],
			Single::get_preview_as_options()
		);
	}
}
