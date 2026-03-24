<?php

/**
 * Register stylesheet referenced by the `animcurve` block style.
 *
 * Note: The `style_handle` used in `register_block_style()` must match the
 * registered style handle here.
 *
 * @since Anatomy 1.1
 *
 * @return void
 */
function anatomy_register_animcurve_block_style_assets() {
	// Register only. WordPress will enqueue this style when the corresponding
	// block style (`is-style-animcurve`) is actually used.
	wp_register_style(
		'anatomy-animcurve',
		get_parent_theme_file_uri( 'assets/css/animcurve.css' ),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'init', 'anatomy_register_animcurve_block_style_assets' );

/**
 * Front-end fallback enqueue for the animcurve block style.
 *
 * WordPress should enqueue block-style assets automatically, but if the site
 * uses caching or on-demand loading paths that prevent the default loader
 * from running, this ensures the stylesheet is still enqueued only when a
 * `core/group` block uses the `animcurve` style.
 *
 * @since Anatomy 1.1
 *
 * @param string $block_content Block HTML.
 * @param array  $block Block data.
 * @return string
 */
function anatomy_render_block_enqueue_animcurve_style( $block_content, $block ) {
	if ( ! isset( $block['blockName'] ) || 'core/group' !== $block['blockName'] ) {
		return $block_content;
	}

	$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
	$style = isset( $attrs['style'] ) ? $attrs['style'] : '';

	// `register_block_style()` ultimately produces `is-style-{$name}` in the class list.
	$class_name = isset( $attrs['className'] ) ? (string) $attrs['className'] : '';

	if (
		'animcurve' === $style ||
		strpos( $class_name, 'is-style-animcurve' ) !== false ||
		strpos( (string) $block_content, 'is-style-animcurve' ) !== false
	) {
		wp_enqueue_style( 'anatomy-animcurve' );
	}

	return $block_content;
}
add_filter( 'render_block', 'anatomy_render_block_enqueue_animcurve_style', 10, 2 );

/**
 * Registers the `animcurve` block style.
 *
 * This adds the `is-style-animcurve` class to the selected block in the
 * rendered output.
 *
 * @since Anatomy 1.1
 *
 * @return void
 */
function anatomy_register_animcurve_block_style() {
	register_block_style(
		'core/group',
		array(
			'name'         => 'animcurve',
			'label'        => __( 'Animcurve', 'anatomy' ),
			'style_handle' => 'anatomy-animcurve',
		)
	);
}
add_action( 'init', 'anatomy_register_animcurve_block_style' );

