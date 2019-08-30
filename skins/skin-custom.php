<?php
namespace UltimateElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use UltimateElementor\Base\Common_Widget;
use UltimateElementor\Modules\Posts\TemplateBlocks\Skin_Init;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Custom extends Skin_Base {

	/**
	 * Get Skin Slug.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_id() {
		return 'custom';
	}

	/**
	 * Get Skin Title.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Custom', 'uae-post-skins' );
	}

	/**
	 * Register controls on given actions.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls_actions() {

		parent::_register_controls_actions();

		add_action( 'elementor/element/uael-posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/uael-posts/section_layout/after_section_end', [ $this, 'register_sections_after' ] );
	}	

	/**
	 * Register controls callback.
	 *
	 * @param Widget_Base $widget Current Widget object.
	 * @since 1.0.0
	 * @access public
	 */
	public function register_controls( Widget_Base $widget ) {

		$this->parent = $widget;

		$this->add_control(
			'uae_post_skin_template',
			[
				'label' => __( 'Select a Template', 'uae-post-skins' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_skin_template(),
			]
		);

		$this->remove_control( 'post_structure' );
	}

	/**
	 * Register controls callback.
	 *
	 * @param Widget_Base $widget Current Widget object.
	 * @since 1.0.0
	 * @access public
	 */
	public function register_sections_after( Widget_Base $widget ) {

		$this->parent = $widget;
	}

	/**
	 * Get current post ID.
	 *
	 * @param Widget_Base $widget Current Widget object.
	 * @since 1.0.0
	 * @access private
	 */
	private function get_post_id() {
		return $this->skin_id;
    }

	/**
	 * Get custom UAE post skins.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_skin_template() {
		global $wpdb;
		$templates = $wpdb->get_results( 
			"SELECT $wpdb->term_relationships.object_id as ID, $wpdb->posts.post_title as post_title FROM $wpdb->term_relationships
				INNER JOIN $wpdb->term_taxonomy ON
					$wpdb->term_relationships.term_taxonomy_id=$wpdb->term_taxonomy.term_taxonomy_id
				INNER JOIN $wpdb->terms ON 
					$wpdb->term_taxonomy.term_id=$wpdb->terms.term_id AND $wpdb->terms.slug='post_skin'
				INNER JOIN $wpdb->posts ON
					$wpdb->term_relationships.object_id=$wpdb->posts.ID"
		);

		$options = [ '' => '' ];

		foreach ( $templates as $template ) {
			$options[ $template->ID ] = $template->post_title;
		}

		return $options;
	}

	/**
	 * Render Main HTML.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	public function render() {

		$settings = $this->parent->get_settings();

		$skin = Skin_Init::get_instance( $this->get_id() );

		// echo $this->get_id();
		// echo 'Nav';

		// echo '<pre>';
		// var_dump($settings);
		// wp_die();

		echo $skin->render( $this->get_id(), $settings, $this->parent->get_id() );
	}
}

// Add a custom skin for the UAE POSTS widget.
add_action( 'elementor/widget/uael-posts/skins_init', function( $widget ) {
    $widget->add_skin( new Skin_Custom( $widget ) );
});
