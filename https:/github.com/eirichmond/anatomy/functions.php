<?php
/**
 * Anatomy functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Anatomy 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'anatomy_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return void
	 */
	function anatomy_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'anatomy_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'anatomy_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return void
	 */
	function anatomy_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'anatomy_editor_style' );

// Enqueues the theme stylesheet on the front.
if ( ! function_exists( 'anatomy_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return void
	 */
	function anatomy_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'anatomy-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'anatomy-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'anatomy_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'anatomy_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return void
	 */
	function anatomy_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'anatomy' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'anatomy_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'anatomy_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return void
	 */
	function anatomy_pattern_categories() {

		register_block_pattern_category(
			'anatomy_page',
			array(
				'label'       => __( 'Pages', 'anatomy' ),
				'description' => __( 'A collection of full page layouts.', 'anatomy' ),
			)
		);

		register_block_pattern_category(
			'anatomy_post-format',
			array(
				'label'       => __( 'Post formats', 'anatomy' ),
				'description' => __( 'A collection of post format patterns.', 'anatomy' ),
			)
		);
	}
endif;
add_action( 'init', 'anatomy_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'anatomy_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return void
	 */
	function anatomy_register_block_bindings() {
		register_block_bindings_source(
			'anatomy/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'anatomy' ),
				'get_value_callback' => 'anatomy_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'anatomy_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'anatomy_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Anatomy 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function anatomy_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

/**
 * Globally disable the Block Directory.
 */
remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );

/**
 * Globally disable the Pattern Directory.
 */
add_filter( 'should_load_remote_block_patterns', '__return_false' );

// Load custom block style registrations.
// $anatomy_custom_block_styles_file = get_theme_file_path( 'includes/register-custom-block-styles.php' );
// if ( file_exists( $anatomy_custom_block_styles_file ) ) {
// 	include_once $anatomy_custom_block_styles_file;
// }