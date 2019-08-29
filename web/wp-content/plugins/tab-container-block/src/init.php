<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function tab_container_block_cgb_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'tab_container_block-cgb-style-css', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'tab_container_block-cgb-block-js', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'tab_container_block-cgb-block-editor-css', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'tab_container_block-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'cgb/block-tab-container-block', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			//'style'         => 'tab_container_block-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'tab_container_block-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'tab_container_block-cgb-block-editor-css',
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'tab_container_block_cgb_block_assets' );


/**
 * Adds the menu section to the bottom of the content of each post.
 *
 * @param string $content Content of the post.
 */
function tcb_content_filter( $content ) {
	// get all div tags of class "wp-block-cgb-block-tab-container-block".
	$tag_regex = '/<[^>]*class="[^"]*\bwp-block-cgb-block-tab-container-block\b[^"]*"[^>]*>/i';
	preg_match_all( $tag_regex, $content, $matches );

	if ( ! $matches ) {
		return $content;
	}

	$menu = '<div class="tab-container-block-menu"><ul>';

	// grab the data-menu-title and id from each tag to construct the menu.
	foreach ( $matches[0] as $match ) {
		preg_match( '/id="([^"]*)"/i', $match, $id );
		preg_match( '/data-menu-title="([^"]*)"/i', $match, $menu_title );

		$menu .= '<li><a href="#' . $id[1] . '">' . $menu_title[1] . '</a></li>';
	}

	$menu .= '</ul></div>';

	// add the menu markup to the end of $content.
	return $content . $menu;
}
add_filter( 'the_content', 'tcb_content_filter' );
