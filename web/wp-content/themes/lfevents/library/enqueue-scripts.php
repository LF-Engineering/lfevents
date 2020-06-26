<?php
/**
 * Enqueue all styles and scripts
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style }
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

// Check to see if rev-manifest exists for CSS and JS static asset revisioning
// https://github.com/sindresorhus/gulp-rev/blob/master/integration.md.
if ( ! function_exists( 'foundationpress_asset_path' ) ) :
	/**
	 * Comment
	 *
	 * @param string $filename Comment.
	 */
	function foundationpress_asset_path( $filename ) {
		$filename_split = explode( '.', $filename );
		$dir            = end( $filename_split );
		$manifest_path  = dirname( dirname( __FILE__ ) ) . '/dist/assets/' . $dir . '/rev-manifest.json';

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
		} else {
			$manifest = array();
		}

		if ( array_key_exists( $filename, $manifest ) ) {
			return $manifest[ $filename ];
		}
		return $filename;
	}
endif;


if ( ! function_exists( 'foundationpress_scripts' ) ) :
	/**
	 * Comment.
	 */
	function foundationpress_scripts() {

		// Enqueue the main Stylesheet.
		wp_enqueue_style( 'main-stylesheet', get_stylesheet_directory_uri() . '/dist/assets/css/' . foundationpress_asset_path( 'app.css' ), array(), filemtime( get_template_directory() . '/dist/assets/css/' . foundationpress_asset_path( 'app.css' ) ), 'all' );

		// Deregister the jquery version bundled with WordPress.
		wp_deregister_script( 'jquery' );

		// jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
		wp_enqueue_script( 'jquery', get_stylesheet_directory_uri() . '/src/assets/js/jquery/' . foundationpress_asset_path( 'jquery-3.4.1.min.js' ), array(), '3.4.1', false );

		// Deregister the jquery-migrate version bundled with WordPress.
		wp_deregister_script( 'jquery-migrate' );

		// Enqueue Foundation scripts.
		wp_enqueue_script( 'foundation', get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path( 'app.js' ), array( 'jquery' ), filemtime( get_template_directory() . '/dist/assets/js/' . foundationpress_asset_path( 'app.js' ) ), true );

		// Enqueue forms scripts.
		wp_enqueue_script( 'sfmc-forms', get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path( 'sfmc-forms.js' ), array( 'jquery' ), filemtime( get_template_directory() . '/dist/assets/js/' . foundationpress_asset_path( 'sfmc-forms.js' ) ), true );
		wp_enqueue_script( 'recaptcha', 'https://www.recaptcha.net/recaptcha/api.js', array(), 1, true );

		// Add the comment-reply library on pages where it is necessary.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

	add_action( 'wp_enqueue_scripts', 'foundationpress_scripts' );
endif;
