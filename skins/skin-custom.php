<?php
namespace UltimateElementor\Modules\Posts\Skins;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use UltimateElementor\Modules\Posts\TemplateBlocks\Build_Post_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Skin_Custom extends Skin_Base {

	/**
	 * Query object
	 *
	 * @since 1.0.0
	 * @var object $query
	 */
	public static $query;

	/**
	 * Query object
	 *
	 * @since 1.0.0
	 * @var object $query_obj
	 */
	public static $query_obj;

	/**
	 * Rendered Settings
	 *
	 * @since 1.0.0
	 * @var object $_render_attributes
	 */
	public $_render_attributes;

	/**
	 * UAE Skin Class
	 *
	 * @since 1.0.0
	 * @var object $_uae_skin_style
	 */
	private $_uae_skin_style;

	/**
	 * UAE Widget Settings
	 *
	 * @since 1.0.0
	 * @var object $_uae_skin_style
	 */
	public $_settings;

	/**
	 * UAE Skin ID
	 *
	 * @since 1.0.0
	 * @var object $_uae_skin_style
	 */
	public $_skin;

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
	 * Render output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_header() {}

	/**
	 * Render Main HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {

		$this->_settings  		= $this->parent->get_settings();
		$this->_skin      		= $this->get_id();
		$category 				= ( isset( $_POST['category'] ) ) ? $_POST['category'] : '';
		self::$query_obj 		= new Build_Post_Query( $this->_skin, $this->_settings, $category );

		self::$query_obj->query_posts();

		self::$query 			= self::$query_obj->get_query();

		?>
			<div class="uael-post__header">
				<?php
					echo $this->get_header();
				?>
			</div>
			<div class="uael-post__body">
				<?php
					echo $this->get_body();
				?>
			</div>
			<div class="uael-post__footer">
				<?php
					echo $this->get_footer();
				?>
			</div>
		<?php
	}

	/**
	 * Get Footer.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_footer() {}

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
	 * Get Wrapper Classes.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_slider_attr() {

		if ( 'carousel' !== $this->get_instance_value( 'post_structure' ) ) {
			return;
		}

		$is_rtl      = is_rtl();
		$direction   = $is_rtl ? 'rtl' : 'ltr';
		$show_dots   = ( in_array( $this->get_instance_value( 'navigation' ), [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $this->get_instance_value( 'navigation' ), [ 'arrows', 'both' ] ) );

		$slick_options = [
			'slidesToShow'   => ( $this->get_instance_value( 'slides_to_show' ) ) ? absint( $this->get_instance_value( 'slides_to_show' ) ) : 4,
			'slidesToScroll' => ( $this->get_instance_value( 'slides_to_scroll' ) ) ? absint( $this->get_instance_value( 'slides_to_scroll' ) ) : 1,
			'autoplaySpeed'  => ( $this->get_instance_value( 'autoplay_speed' ) ) ? absint( $this->get_instance_value( 'autoplay_speed' ) ) : 5000,
			'autoplay'       => ( 'yes' === $this->get_instance_value( 'autoplay' ) ),
			'infinite'       => ( 'yes' === $this->get_instance_value( 'infinite' ) ),
			'pauseOnHover'   => ( 'yes' === $this->get_instance_value( 'pause_on_hover' ) ),
			'speed'          => ( $this->get_instance_value( 'transition_speed' ) ) ? absint( $this->get_instance_value( 'transition_speed' ) ) : 500,
			'arrows'         => $show_arrows,
			'dots'           => $show_dots,
			'rtl'            => $is_rtl,
			'prevArrow'      => '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="fa fa-angle-left"></i></button>',
			'nextArrow'      => '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="fa fa-angle-right"></i></button>',
		];

		if ( $this->get_instance_value( 'slides_to_show_tablet' ) || $this->get_instance_value( 'slides_to_show_mobile' ) ) {

			$slick_options['responsive'] = [];

			if ( $this->get_instance_value( 'slides_to_show_tablet' ) ) {

				$tablet_show   = absint( $this->get_instance_value( 'slides_to_show_tablet' ) );
				$tablet_scroll = ( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) ? absint( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) : $tablet_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 1024,
					'settings'   => [
						'slidesToShow'   => $tablet_show,
						'slidesToScroll' => $tablet_scroll,
					],
				];
			}

			if ( $this->get_instance_value( 'slides_to_show_mobile' ) ) {

				$mobile_show   = absint( $this->get_instance_value( 'slides_to_show_mobile' ) );
				$mobile_scroll = ( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) ? absint( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) : $mobile_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 767,
					'settings'   => [
						'slidesToShow'   => $mobile_show,
						'slidesToScroll' => $mobile_scroll,
					],
				];
			}
		}

		$this->add_render_attribute(
			'uael-post-slider',
			[
				'data-post_slider'  => wp_json_encode( $slick_options ),
				'data-equal-height' => $this->get_instance_value( 'equal_height' ),
			]
		);

		return $this->get_render_attribute_string( 'uael-post-slider' );
	}

	/**
	 * Render Get Template.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_body() {

		global $post;
		$settings            = $this->_settings;
		$skin                = 'custom';
		$wrapper             = $this->get_wrapper_classes();
		$outer_wrapper       = $this->get_outer_wrapper_classes();
		$structure           = $settings[ 'custom_post_structure'];
		$layout              = '';
		$page_id             = $post->ID;
		$filter_default_text = $this->get_instance_value( 'filters_all_text' );

		$category 			= ( isset( $_POST['category'] ) ) ? $_POST['category'] : '';
		self::$query_obj 	= new Build_Post_Query( $skin, $settings, '' );

		self::$query_obj->query_posts();
		self::$query 		= self::$query_obj->get_query();
		$count      		= 0;

		if ( null != \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}

		if ( 'masonry' == $structure ) {

			$layout = 'masonry';
		}

		$this->add_render_attribute( 'wrapper', 'class', $wrapper );
		$this->add_render_attribute( 'outer_wrapper', 'class', $outer_wrapper );
		$this->add_render_attribute( 'outer_wrapper', 'data-query-type', $settings['query_type'] );
		$this->add_render_attribute( 'outer_wrapper', 'data-structure', $structure );
		$this->add_render_attribute( 'outer_wrapper', 'data-layout', $layout );
		$this->add_render_attribute( 'outer_wrapper', 'data-page', $page_id );
		$this->add_render_attribute( 'outer_wrapper', 'data-skin', $skin );
		$this->add_render_attribute( 'outer_wrapper', 'data-filter-default', $filter_default_text );

		$default_template 	= $this->get_instance_value( 'uae_post_skin_template' );
		$template 			= $default_template;
		$template 			= apply_filters( 'uae_post_action_template', $template );
		$template 			= $this->get_current_ID( $template );

		if (
			'yes' == $this->get_instance_value( 'default_filter_switch' ) &&
			'' != $this->get_instance_value( 'default_filter' )
		) {
			$this->add_render_attribute( 'outer_wrapper', 'data-default-filter', $this->get_instance_value( 'default_filter' ) );
		}

		?>
			<div <?php echo $this->get_render_attribute_string( 'outer_wrapper' ); ?> <?php echo $this->get_slider_attr(); ?> >
				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
					<?php
						while ( self::$query->have_posts() ) {
							self::$query->the_post();

							$is_featured = false;

							if ( 0 == $count && 'featured' === $this->get_instance_value( 'post_structure' ) ) {
								$is_featured = true;
							}

							if ( ! $template ) return;
							$markup = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template );

							?>
								<div class="uael-post-wrapper <?php echo ( $is_featured ) ? 'uael-post-wrapper-featured' : ''; ?>">
									<?php echo $markup; ?>
								</div>
							<?php
							$count++;
						}

						wp_reset_postdata();
					?>
				</div>

				<?php if ( 'infinite' == $this->get_instance_value( 'pagination' ) ) { ?>
					<div class="uael-post-inf-loader">
						<div class="uael-post-loader-1"></div>
						<div class="uael-post-loader-2"></div>
						<div class="uael-post-loader-3"></div>
					</div>
				<?php } ?>
			</div>
		<?php
	}
}

// Add a custom skin for the UAE POSTS widget.
add_action( 'elementor/widget/uael-posts/skins_init', function( $widget ) {
    $widget->add_skin( new Skin_Custom( $widget ) );
});
