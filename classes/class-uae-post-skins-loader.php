<?php
/**
 * Initialize UAE Posts Custom Skins
 *
 * @package UAE_POSTS_CUSTOM_SKINS
 * @since 1.0.0
 */

if ( ! class_exists( 'UAE_Posts_Skin_Loader' ) ) :

	/**
	 * UAE Posts Custom Skins Loader
	 *
	 * @since 1.0.0
	 */
	class UAE_Posts_Skin_Loader {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

            require_once UAE_POSTS_SKINS_DIR . 'classes/uae-helper-functions.php';
            
            if ( ! is_uae_pro_activate() || ! is_elementor_pro_activate() ) {
				add_action( 'admin_notices', array( $this, 'install_activate_plugin_notice' ), 1 );
				return;
			}

			add_action( 'elementor_pro/init', array( $this, 'uae_post_custom_skins_init' ) );

			add_action('elementor/widgets/register', array( $this, 'uae_post_custom_skins' ) );
        }

		/**
		 * Add Admin Notice.
         * 
         * @since 1.0.0
		 */
		public function install_activate_plugin_notice() {
			printf( __( '<div class="notice notice-error is-dismissible"> <p> <strong> Ultimate Addons for Elementor (UAE) </strong> and <strong> Elementor Pro </strong> needs to be active for you to use currently installed <strong> %1$s </strong> plugin. </p> </div>', 'uae-post-skins' ), UAE_POSTS_PLUGIN_NAME );
		}

		/**
		 * Add Custom Document.
         * 
         * @since 1.0.0
		 */
		function uae_post_custom_skins_init() {

			wp_enqueue_script( 'uael-custom-post-skins', UAE_POSTS_SKINS_URL . 'assets/js/uael-custom-post.js', array( 'jquery' ), UAE_POSTS_SKINS_VER );

			// Load Document type.
			require_once UAE_POSTS_SKINS_DIR . 'theme-builder/init.php';
		}

		/**
		 * Get Custom Skins.
         * 
         * @since 1.0.0
		 */
		function uae_post_custom_skins(){
			require_once UAE_POSTS_SKINS_DIR . 'skins/skin-custom.php';
		}
	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	UAE_Posts_Skin_Loader::get_instance();

endif;
