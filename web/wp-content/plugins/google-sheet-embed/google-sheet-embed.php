<?php
/**
 * Plugin Name:     Google Sheet Embed
 * Description:     Embed a Google Sheet
 * Version:         0.1.0
 * Author:          The WordPress Contributors
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     google-sheet-embed
 *
 * @package         lf
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function lf_google_sheet_embed_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "lf/google-sheet-embed" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'lf-google-sheet-embed-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'lf-google-sheet-embed-block-editor', 'google-sheet-embed' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'lf-google-sheet-embed-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'lf-google-sheet-embed-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'lf/google-sheet-embed', array(
		'editor_script' => 'lf-google-sheet-embed-block-editor',
		'editor_style'  => 'lf-google-sheet-embed-block-editor',
		'style'         => 'lf-google-sheet-embed-block',
	) );
}
add_action( 'init', 'lf_google_sheet_embed_block_init' );
