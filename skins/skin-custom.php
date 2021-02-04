<?php
namespace UltimateElementor\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use UltimateElementor\Modules\Posts\Widgets;
use UltimateElementor\Modules\Posts\TemplateBlocks\Build_Post_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Custom extends Skin_Base {

	/**
	 * Rendered Skin ID
	 *
	 * @since 1.0.0
	 * @var object $skin_id
	 */
	private $skin_id;

	/**
	 * Rendered Settings
	 *
	 * @since 1.0.0
	 * @var object $_render_attributes
	 */
	public $_render_attributes;

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
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {

		$this->render_post_body();
	}

	/**
	 * Get Classes array for wrapper class.
	 *
	 * Returns the array for wrapper class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_wrapper_classes() {

		$classes = [
			'uael-post-grid__inner',
			'uael-post__columns-' . $this->get_instance_value( 'slides_to_show' ),
			'uael-post__columns-tablet-' . $this->get_instance_value( 'slides_to_show_tablet' ),
			'uael-post__columns-mobile-' . $this->get_instance_value( 'slides_to_show_mobile' ),
		];

		if ( 'masonry' === $this->get_instance_value( 'post_structure' ) ) {
			$classes[] = 'uael-post-masonry';
		}

		if ( 'infinite' === $this->get_instance_value( 'pagination' ) ) {
			$classes[] = 'uael-post-infinite-scroll';
			$classes[] = 'uael-post-infinite__event-' . $this->get_instance_value( 'infinite_event' );
		}

		return $classes;
	}

	/**
	 * Get Classes array for outer wrapper class.
	 *
	 * Returns the array for outer wrapper class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_outer_wrapper_classes() {

		$classes = [
			'uael-post-image-' . $this->get_instance_value( 'image_position' ),
			'uael-post-grid',
			'uael-posts',
		];

		$classes[] = 'uael-post_structure-featured';
		$classes[] = 'uael-featured_post_structure-' . $this->get_instance_value( 'featured_post' );
		return $classes;
	}

	/**
	 * Add render attribute.
	 *
	 * Used to add attributes to a specific HTML element.
	 *
	 * The HTML tag is represented by the element parameter, then you need to
	 * define the attribute key and the attribute key. The final result will be:
	 * `<element attribute_key="attribute_value">`.
	 *
	 * Example usage:
	 *
	 * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
	 * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );`
	 * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element   The HTML element.
	 * @param array|string $key       Optional. Attribute key. Default is null.
	 * @param array|string $value     Optional. Attribute value. Default is null.
	 * @param bool         $overwrite Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return Element_Base Current instance of the element.
	 */
	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
			}

			return $this;
		}

		if ( empty( $this->_render_attributes[ $element ][ $key ] ) ) {
			$this->_render_attributes[ $element ][ $key ] = [];
		}

		settype( $value, 'array' );

		if ( $overwrite ) {
			$this->_render_attributes[ $element ][ $key ] = $value;
		} else {
			$this->_render_attributes[ $element ][ $key ] = array_merge( $this->_render_attributes[ $element ][ $key ], $value );
		}

		return $this;
	}

	/**
	 * Get render attribute string.
	 *
	 * Used to retrieve the value of the render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The element.
	 *
	 * @return string Render attribute string, or an empty string if the attribute
	 *                is empty or not exist.
	 */
	public function get_render_attribute_string( $element ) {
		if ( empty( $this->_render_attributes[ $element ] ) ) {
			return '';
		}

		$render_attributes = $this->_render_attributes[ $element ];

		$attributes = [];

		foreach ( $render_attributes as $attribute_key => $attribute_values ) {
			$attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( implode( ' ', $attribute_values ) ) );
		}

		return implode( ' ', $attributes );
	}

	
	/**
	 * Render output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.1
	 * @access public
	 */
	public function get_header() {

		$this->render_filters();
	}

	/**
	 * Get body.
	 *
	 * Returns body.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_post_body() {

		?>
			<div class="uael-post__header">
				<?php $this->get_header(); ?>
			</div>

			<div class="uael-post__body">
		<?php

			ob_start();

			global $post;
			$settings            = $this->parent->get_settings();
			$skin                = 'custom';
			$skin_id			 = 'custom';
			$wrapper             = $this->get_wrapper_classes();
			$outer_wrapper       = $this->get_outer_wrapper_classes();
			$structure           = $settings[ 'custom_post_structure'];
			$layout              = '';
			$page_id             = $post->ID;

			$category 			= ( isset( $_POST['category'] ) ) ? $_POST['category'] : '';
			$query_obj 			= new Build_Post_Query( $skin, $settings, $category );

			$query_obj->query_posts();
			$query 				= $query_obj->get_query();
			$count      		= 0;

			if ( in_array( $structure, [ 'masonry', 'normal' ] ) ) {

				if ( 'yes' == $settings[ 'custom_show_filters'] ) {

					$layout = ( 'normal' == $structure ) ? 'fitRows' : 'masonry';
				}
			}

			$this->add_render_attribute( 'wrapper', 'class', $wrapper );
			$this->add_render_attribute( 'outer_wrapper', 'class', $outer_wrapper );
			$this->add_render_attribute( 'outer_wrapper', 'data-query-type', $settings['query_type'] );
			$this->add_render_attribute( 'outer_wrapper', 'data-structure', $structure );
			$this->add_render_attribute( 'outer_wrapper', 'data-layout', $structure );
			$this->add_render_attribute( 'outer_wrapper', 'data-page', $page_id );
			$this->add_render_attribute( 'outer_wrapper', 'data-skin', 'custom' );

			?>
				<div <?php echo $this->get_render_attribute_string( 'outer_wrapper' ); ?>>
					<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
						<?php

							if ( 0 == $count ) {

								while ( $query->have_posts() ) {

									$query->the_post();

									if ( $this->get_instance_value( 'uae_post_skin_template' ) ) {

										$this->open_post_header();

										if ( function_exists( 'parse_content' ) ) {
											echo parse_content( $this->get_template() );
										}

										else echo $this->get_template();

										$this->close_post_header();
									}

									$count++;
								}

								wp_reset_postdata();
							}

						?>
					</div>
				</div>
			</div>
		<div class="uael-post__footer">
		</div>
		<?php

		echo ob_get_clean();
	}

	/**
	 * Get Filters.
	 *
	 * Returns the Filter HTML.
	 *
	 * @since 1.0.1
	 * @access public
	 */
	public function render_filters() {

		$settings       = $this->parent->get_settings();
		$skin           = 'custom';
		$tab_responsive = '';

		if ( 'yes' === $this->get_instance_value( 'tabs_dropdown' ) ) {
			$tab_responsive = ' uael-posts-tabs-dropdown';
		}

		if ( 'yes' !== $this->get_instance_value( 'show_filters' ) || 'main' === $settings['query_type'] ) {
			return;
		}

		if ( ! in_array( $this->get_instance_value( 'post_structure' ), array( 'masonry', 'normal' ), true ) ) {
			return;
		}

		$filters = $this->get_filter_values();
		$filters = apply_filters( 'uael_posts_filterable_tabs', $filters, $settings );
		$all     = $this->get_instance_value( 'filters_all_text' );

		$all_text = ( 'All' === $all || '' === $all ) ? esc_attr__( 'All', 'uael' ) : $all;

		?>
		<div class="uael-post__header-filters-wrap<?php echo esc_attr( $tab_responsive ); ?>">
			<ul class="uael-post__header-filters" aria-label="<?php esc_attr_e( 'Taxonomy Filter', 'uael' ); ?>">
				<li class="uael-post__header-filter uael-filter__current" data-filter="*"><?php echo wp_kses_post( $all_text ); ?></li>
				<?php foreach ( $filters as $key => $value ) { ?>
				<li class="uael-post__header-filter" data-filter="<?php echo '.' . esc_attr( $value->slug ); ?>" tabindex="0"><?php echo esc_attr( $value->name ); ?></li>
				<?php } ?>
			</ul>

			<?php if ( 'yes' === $this->get_instance_value( 'tabs_dropdown' ) ) { ?>
				<div class="uael-filters-dropdown">
					<div class="uael-filters-dropdown-button"><?php echo wp_kses_post( $all_text ); ?><i class="fa fa-angle-down"></i></div>

					<ul class="uael-filters-dropdown-list uael-post__header-filters">
						<li class="uael-filters-dropdown-item uael-post__header-filter uael-filter__current" data-filter="*"><?php echo wp_kses_post( $all_text ); ?></li>
						<?php foreach ( $filters as $key => $value ) { ?>
						<li class="uael-filters-dropdown-item uael-post__header-filter" data-filter="<?php echo '.' . esc_attr( $value->slug ); ?>"><?php echo esc_attr( $value->name ); ?></li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get Filter taxonomy array.
	 *
	 * Returns the Filter array of objects.
	 *
	 * @since 1.0.1
	 * @access public
	 */
	public function get_filter_values() {

		$settings       = $this->parent->get_settings();
		$skin           = 'custom';

		$post_type = $settings['post_type_filter'];

		$filter_by = $this->get_instance_value( 'tax_masonry_' . $post_type . '_filter' );

		$filter_type = $settings[ $filter_by . '_' . $post_type . '_filter_rule' ];

		$filters = $settings[ 'tax_' . $filter_by . '_' . $post_type . '_filter' ];

		// Get the categories for post types.
		$taxs = get_terms( $filter_by );

		$filter_array = array();

		if ( is_wp_error( $taxs ) ) {
			return array();
		}

		if ( empty( $filters ) || '' === $filters ) {

			$filter_array = $taxs;
		} else {

			foreach ( $taxs as $key => $value ) {

				if ( 'IN' === $filter_type ) {

					if ( in_array( $value->slug, $filters, true ) ) {

						$filter_array[] = $value;
					}
				} else {

					if ( ! in_array( $value->slug, $filters, true ) ) {

						$filter_array[] = $value;
					}
				}
			}
		}

		return $filter_array;
	}

	/**
	 * Open Post Header.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function open_post_header() {
		?>
			<div class="uael-post-wrapper <?php echo wp_kses_post( $this->get_category_name() ); ?>">
		<?php
	}

	/**
	 * Close Post Header.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function close_post_header() {
		?>
			</div>
		<?php
	}

	/**
	 * Render Get Template ID.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function get_current_ID( $id ) {
		$newid = apply_filters( 'wpml_object_id', $id, 'elementor_library', TRUE  );
		return $newid ? $newid : $id;
	}

	/**
	 * Get category name.
	 *
	 * Adds the category class.
	 *
	 * @since 1.0.1
	 * @access public
	 */
	public function get_category_name() {

		foreach ( get_the_category( get_the_ID() ) as $category ) {

			$category_name = str_replace( ' ', '-', $category->name );

			echo esc_attr( strtolower( $category_name ) ) . ' ';
		}
	}

	/**
	 * Render Get Template.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_template() {

		$settings           = $this->parent->get_settings();

		$default_template 	= $this->get_instance_value( 'uae_post_skin_template' );

		$template 			= $default_template;

		$template 			= apply_filters( 'uae_post_action_template', $template );
		$template 			= $this->get_current_ID( $template );

		if ( ! $template ) return;
		$markup = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template );

		return $markup;
	}
}

// Add a custom skin for the UAE POSTS widget.
add_action( 'elementor/widget/uael-posts/skins_init', function( $widget ) {
    $widget->add_skin( new Skin_Custom( $widget ) );
});
