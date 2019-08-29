<?php
/**
 * UAEL Post - Template.
 *
 * @package UAEL
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

// Ensure visibility.
if ( empty( $post ) ) {
	return;
}

?>

<?php do_action( 'uael_single_post_before_wrap', get_the_ID(), $settings ); ?>

<div class="uael-post-wrapper <?php echo $this->get_masonry_classes(); ?> <?php echo ( $is_featured ) ? 'uael-post-wrapper-featured' : ''; ?>">
	<div class="uael-post__bg-wrap">

		<div class="uael-post__inner-wrap">

			<div class="uael-post__content-wrap">
                <?php
                    echo 'into content post custom file';
                ?>
			</div>
			<?php do_action( 'uael_single_post_after_content_wrap', get_the_ID(), $settings ); ?>

		</div>

		<?php do_action( 'uael_single_post_after_inner_wrap', get_the_ID(), $settings ); ?>

	</div>
</div>
<?php do_action( 'uael_single_post_after_wrap', get_the_ID(), $settings ); ?>
