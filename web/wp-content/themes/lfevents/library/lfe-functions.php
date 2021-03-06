<?php
/**
 * LFEvents helper functions
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

/**
 * Says whether it's the lfeventsci pantheon instance. But does not change body class.
 */
function is_lfeventsci() {
	if ( 'lfeventsci' === $_ENV['PANTHEON_SITE_NAME'] ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Gets related LFEvents for current post.  Only returns Events for the current year.
 *
 * @param int $parent_id ID of top parent post of the Event.
 *
 * @return array
 */
function lfe_get_related_events( $parent_id ) {
	$related_events = array();

	$related_events_override = get_post_meta( $parent_id, 'lfes_related_events', true );

	if ( $related_events_override ) {
		$args = array(
			'post_type'     => 'page',
			'post_parent'   => 0,
			'post__in'      => explode( ',', $related_events_override ), // ignores current post.
			'no_found_rows' => true,  // used to improve performance.
			'meta_query'    => array(
				array(
					'key'     => 'lfes_event_has_passed',
					'compare' => '!=',
					'value'   => '1',
				),
			),
			'orderby'       => 'meta_value',
			'meta_key'      => 'lfes_date_start',
			'order'         => 'ASC',
		);

	} else {
		$term = wp_get_post_terms( $parent_id, 'lfevent-category', array( 'fields' => 'ids' ) );

		if ( empty( ! $term ) ) {
			$term_if_present = $term[0];
		} else {
			$term_if_present = '';
		}

		$args = array(
			'post_type'      => 'page',
			'post_parent'    => 0,
			'no_found_rows'  => true,  // used to improve performance.
			'post__not_in'   => array( $parent_id ), // ignores current post.
			'tax_query'      => array(
				array(
					'taxonomy' => 'lfevent-category',
					'field'    => 'term_id',
					'terms'    => $term_if_present,
				),
			),
			'meta_query'     => array(
				array(
					'key'     => 'lfes_event_has_passed',
					'compare' => '!=',
					'value'   => '1',
				),
			),
			'orderby'        => 'meta_value',
			'meta_key'       => 'lfes_date_start',
			'order'          => 'ASC',
			'posts_per_page' => 2,
		);

	}

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$related_events[] = array( 'ID' => get_the_ID() );
		}
	}
	wp_reset_postdata(); // Restore original Post Data.

	return $related_events;

}

/**
 * Gets all archives of a particular LFEvent.
 *
 * @param int $parent_id ID of top parent post of the Event.
 *
 * @return array
 */
function lfe_get_archive( $parent_id ) {
	global $wpdb;
	$parent_post = get_post( $parent_id );

	$myposts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $wpdb->posts
			WHERE (post_type like %s
			OR post_type = 'page')
			AND post_parent = 0
			AND post_status = 'Publish'
			AND post_name = %s
			AND id <> %d
			ORDER BY post_type ASC",
			'lfevent%',
			$parent_post->post_name,
			$parent_id
		)
	);

	return $myposts;
}

/**
 * Generates the "Other Events" menu item.
 *
 * @param int    $parent_id ID of top parent post of the Event.
 * @param string $background_style sets the solid or gradient background color.
 * @param string $menu_text_color color of the txt on the topnav.
 */
function lfe_get_other_events( $parent_id, $background_style, $menu_text_color ) {
	$related_events = lfe_get_related_events( $parent_id );

	echo '<li class="page_item page_item_has_children other-events">';
	if ( is_lfeventsci() ) {
		echo '<a>View All Events</a>';
	} else {
		echo '<a>查看所有活动<br>View All Events</a>';
	}
	echo '<ul class="children" style="' . esc_html( $background_style ) . '">';
	echo '<li><a href="https://events.linuxfoundation.org/"><div class="other-logo-wrapper">
	<img width="109" height="36" src="' . get_stylesheet_directory_uri() . '/dist/assets/images/' . foundationpress_asset_path( 'lf-logo-' . $menu_text_color . '.svg' ) . '"><span class="other-seperator ' . $menu_text_color . '">Events</span></div><span class="other-text">All Upcoming Events</span></a></li>'; //phpcs:ignore

	foreach ( $related_events as $p ) {
		$logo = get_post_meta( $p['ID'], 'lfes_' . $menu_text_color . '_logo', true );
		if ( $logo ) {
			$event_link_content = '<img src="' . wp_get_attachment_url( $logo ) . '" alt="' . get_the_title( $p['ID'] ) . '">';
		} else {
			$event_link_content = get_the_title( $p['ID'] );
		}

		echo '<li><a href="' . esc_url( lfe_get_event_url( $p['ID'] ) ) . '">' . $event_link_content . '</a></li>'; //phpcs:ignore
	}

	$term = wp_get_post_terms( $parent_id, 'lfevent-category', array( 'fields' => 'all' ) );

	if ( ! empty( $term ) ) {
		echo '<li><a href="https://events.linuxfoundation.org/about/calendar/archive/?_sft_lfevent-category=' . urlencode( $term[0]->slug ) . '"><span class="subtext">Past ' . esc_html( $term[0]->name ) . '</span></a></li>';
	} else {
		echo '<li><a href="https://events.linuxfoundation.org/about/calendar/archive/"><span class="subtext">All Past Events</span></a></li>';
	}

	$extra_link_text = get_post_meta( $parent_id, 'lfes_extra_vae_link_text', true );
	$extra_link_url  = get_post_meta( $parent_id, 'lfes_extra_vae_link_url', true );
	if ( $extra_link_text && $extra_link_url ) {
		echo '<li class="external-link"><a target="_blank" href="' . esc_attr( $extra_link_url ) . '"><span class="subtext">'; //phpcs:ignore
		echo esc_html( $extra_link_text ) . ' ';
		echo esc_html( get_template_part( 'template-parts/svg/external-link' ) );
		echo '</span></a></li>';
	}

	echo '</ul></li>';
}

/**
 * Returns markup for child pages for the Event menu.
 *
 * @param int     $parent_id Parent ID for Event.
 * @param string  $post_type Post type for Event.
 * @param string  $background_style sets the background color.
 * @param boolean $footer Outputs footer navigation style.
 */
function lfe_get_event_menu( $parent_id, $post_type, $background_style, $footer = null ) {
	global $wpdb, $post;

	// first find which pages we need to exclude.
	$exclude     = $wpdb->get_results( $wpdb->prepare( "select post_id from $wpdb->postmeta left join $wpdb->posts on post_id = id where meta_key = 'lfes_hide_from_menu' and meta_value = 1 and post_type = %s;", $post->post_type ), ARRAY_A );
	$exclude_ids = '';
	foreach ( $exclude as $ex ) {
		$exclude_ids .= $ex['post_id'] . ',';
	}

	// then get the pages we need.
	$args  = array(
		'child_of'     => $parent_id,
		'sort_order'   => 'ASC',
		'sort_column'  => 'menu_order',
		'hierarchical' => 1,
		'title_li'     => '',
		'exclude'      => $exclude_ids,
		'post_type'    => $post_type,
		'post_status'  => 'publish',
		'echo'         => false,
	);
	$pages = wp_list_pages( $args );
	$pages = explode( '</li>', $pages );
	$count = 0;

	if ( $footer ) {
		// output the menu at one level suitable for the footer.
		foreach ( $pages as $page ) {
			if ( strstr( $page, '<ul class=\'children\'>' ) ) {
				$page = explode( '<ul class=\'children\'>', $page );
				unset( $page[0] );
				$page = implode( '<ul class=\'children\' style=\'' . esc_html( $background_style ) . '\'>', $page );
			}
			$pages[ $count ] = $page;
			$count++;
		}
		$pages = implode( '</li>', $pages );
		$pages = strip_tags( $pages, '<li><a><br><span>' );
		$pages = str_replace( '<br>', ' ', $pages );

	} else {

		// now we remove the hyperlink for elements who have children.
		foreach ( $pages as $page ) {
			if ( strstr( $page, '<ul class=\'children\'>' ) ) {
				$page    = explode( '<ul class=\'children\'>', $page );
				$page[0] = preg_replace( '/(<[^>]+) href=".*?"/i', '$1 href="#"', $page[0] );
				if ( count( $page ) == 3 ) {
					$page[1] = preg_replace( '/(<[^>]+) href=".*?"/i', '$1 href="#"', $page[1] );
				}
				$page = implode( '<ul class=\'children\' style=\'' . esc_html( $background_style ) . '\'>', $page );
			}
			$pages[ $count ] = $page;
			$count++;
		}
		$pages = implode( '</li>', $pages );
		$pages = strip_tags( $pages, '<li><a><br><ul>' );

	}

	return $pages; //phpcs:ignore
}

/**
 * Outputs the Sponsors List page for an Event if a Sponsors List page exists.
 *
 * @param int $parent_id ID of top parent post of the Event.
 */
function lfe_get_sponsors( $parent_id ) {
	global $post;
	if ( 'sponsor-list' === $post->post_name ) {
		return;
	}

	$post_types = lfe_get_post_types();

	$args = array(
		'post_type'              => $post_types,
		'post_parent'            => $parent_id,
		'name'                   => 'sponsor-list',
		'no_found_rows'          => true,  // used to improve performance.
		'update_post_meta_cache' => false, // used to improve performance.
		'update_post_term_cache' => false, // used to improve performance.
	);

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
					<?php edit_post_link( __( '(Edit Sponsors)', 'foundationpress' ), '<span class="edit-link">', '</span>' ); ?>
				</div>
			</article>
			<?php
		}
	}
	wp_reset_postdata(); // Restore original Post Data.

}

/**
 * Removes unneeded admin menu items.
 */
function lfe_custom_menu_page_removing() {
	// remove_menu_page( 'ultimate-blocks-settings' ); // don't need this anymore?
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'lfe_custom_menu_page_removing' );

/**
 * Inserts Google Tag Manager <head> code on live sites.
 */
function lfe_insert_google_tag_manager_head() {
	$domains        = "'events.linuxfoundation.org', 'www.lfasiallc.com', 'bagevent.com', 'www.cvent.com', 'events19.linuxfoundation.org', 'events19.lfasiallc.com', 'events.linuxfoundation.cn', 'events19.linuxfoundation.cn', 'www.lfasiallc.cn', 'lfasiallc.cn'";
	$current_domain = parse_url( home_url(), PHP_URL_HOST );
	$analytics_code = <<<EOD
<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-TK7D99');</script>
	<!-- End Google Tag Manager -->

EOD;

	if ( strpos( $domains, $current_domain ) && ! is_user_logged_in() ) {
		// this is a live site so output the analytics code.
		echo $analytics_code; //phpcs:ignore
	}
}

/**
 * Inserts Google Tag Manager <body> code on live sites.
 */
function lfe_insert_google_tag_manager_body() {
	$domains        = "'events.linuxfoundation.org', 'www.lfasiallc.com', 'bagevent.com', 'www.cvent.com', 'events19.linuxfoundation.org', 'events19.lfasiallc.com', 'events.linuxfoundation.cn', 'events19.linuxfoundation.cn', 'www.lfasiallc.cn', 'lfasiallc.cn'";
	$current_domain = parse_url( home_url(), PHP_URL_HOST );
	$analytics_code = <<<EOD
<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TK7D99"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

EOD;

	if ( strpos( $domains, $current_domain ) && ! is_user_logged_in() ) {
		// this is a live site so output the analytics code.
		echo $analytics_code; //phpcs:ignore
	}
}


/**
 * Inserts Event-specific favicon if set, otherwise falls back to site favicon.
 */
function lfe_insert_favicon() {
	global $parent_id;

	$out = '<link rel="icon" type="image/png" sizes="32x32" href="';

	$favicon = get_post_meta( $parent_id, 'lfes_favicon', true );
	if ( $favicon ) {
		$out .= wp_get_attachment_url( $favicon );
	} else {
		$out .= get_stylesheet_directory_uri() . '/dist/assets/images/favicons/' . foundationpress_asset_path( 'favicon-32x32.png' );
	}

	$out .= '">';

	echo $out; //phpcs:ignore
}

/**
 * Checks a string is a date.
 *
 * @param string $date suspected date.
 * @param string $format datetime format, default Y/m/d.
 */
function check_string_is_date( $date, $format = 'Y/m/d' ) {
	$d = DateTime::createFromFormat( $format, $date );
	return $d && $d->format( $format ) === $date;
}

/**
 * Makes the date pretty.  Adapted from https://9seeds.com/pretty-php-date-ranges/.
 *
 * @param datetime $start_date The start date.
 * @param datetime $end_date The end date.
 * @param string   $ch_separator The separator to use between english and chinese dates.
 */
function jb_verbose_date_range( $start_date = '', $end_date = '', $ch_separator = ' ' ) {

	$date_range    = '';
	$date_range_ch = '';

	// If only one date, or dates are the same set to FULL verbose date.
	if ( empty( $start_date ) || empty( $end_date ) || ( $start_date->format( 'MjY' ) == $end_date->format( 'MjY' ) ) ) { // FjY == accounts for same day, different time.
		$start_date_pretty    = $start_date->format( 'M j, Y' );
		$end_date_pretty      = $end_date->format( 'M j, Y' );
		$start_date_pretty_ch = $start_date->format( 'Y年m月j' );
		$end_date_pretty_ch   = $end_date->format( 'm月j日' );
	} else {
		 // Setup basic dates.
		$start_date_pretty    = $start_date->format( 'M j' );
		$end_date_pretty      = $end_date->format( 'j, Y' );
		$start_date_pretty_ch = $start_date->format( 'Y年m月j' );
		$end_date_pretty_ch   = $end_date->format( 'j日' );
		// If years differ add suffix and year to start_date.
		if ( $start_date->format( 'Y' ) != $end_date->format( 'Y' ) ) {
			$start_date_pretty .= $start_date->format( ', Y' );
		}

		// If months differ add suffix and year to end_date.
		if ( $start_date->format( 'M' ) != $end_date->format( 'M' ) ) {
			$end_date_pretty    = $end_date->format( 'M ' ) . $end_date_pretty;
			$end_date_pretty_ch = $end_date->format( 'm月' ) . $end_date_pretty_ch;
		}
	}

	// build date_range return string.
	if ( ! empty( $start_date ) ) {
		  $date_range    .= $start_date_pretty;
		  $date_range_ch .= $start_date_pretty_ch;
	}

	// check if there is an end date and append if not identical.
	if ( ! empty( $end_date ) ) {
		if ( $end_date_pretty != $start_date_pretty ) {
			  $date_range    .= '–' . $end_date_pretty;
			  $date_range_ch .= '–' . $end_date_pretty_ch;
		} else {
			$date_range_ch .= '日';
		}
	}
	if ( is_lfeventsci() ) {
		return $date_range;
	} else {
		return $date_range_ch . $ch_separator . $date_range;
	}
}


/**
 * Changes the ellipses after the excerpt.
 *
 * @param string $more more text.
 */
function new_excerpt_more( $more ) {
	return '<span class="excerpt-ellipses">&hellip;</span>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

/**
 * Inserts structured data into Event head according to https://developers.google.com/search/docs/data-types/event.
 * Only does this for the topmost Event page.
 */
function lfe_insert_structured_data() {
	global $post;

	if ( ! is_object( $post ) ) {
		return;
	}

	if ( $post->post_parent || 'page' != $post->post_type ) {
		return;
	}

	$date_start = get_post_meta( $post->ID, 'lfes_date_start', true );
	if ( check_string_is_date( $date_start ) ) {
		$dt_date_start = new DateTime( $date_start );
		$dt_date_end   = new DateTime( get_post_meta( $post->ID, 'lfes_date_end', true ) );
	}
	$country = wp_get_post_terms( $post->ID, 'lfevent-country' );
	if ( $country ) {
		$country = $country[0]->name;
	}

	$image_url = get_post_meta( $post->ID, '_social_image_url', true );
	if ( ! $image_url ) {
		$image_url = get_the_post_thumbnail_url();
	}

	$out = '';

	$out .= '<script type="application/ld+json">';
	$out .= '{';
	$out .= '"@context": "http://schema.org/",';
	$out .= '"@type": "Event",';
	$out .= '"name": "' . esc_html( $post->post_title ) . '",';
	if ( $date_start && check_string_is_date( $date_start ) ) {
		$out .= '"startDate": "' . $dt_date_start->format( 'Y-m-d' ) . '",';
		$out .= '"endDate": "' . $dt_date_end->format( 'Y-m-d' ) . '",';
	}
	$out .= '"location": {';
	$out .= '  "@type": "Place",';
	$out .= '  "name": "' . esc_html( get_post_meta( $post->ID, 'lfes_venue', true ) ) . '",';
	$out .= '  "address": {';
	$out .= '	"@type": "PostalAddress",';
	$out .= '	"streetAddress": "' . esc_html( get_post_meta( $post->ID, 'lfes_street_address', true ) ? get_post_meta( $post->ID, 'lfes_street_address', true ) : '' ) . '",';
	$out .= '	"addressLocality": "' . esc_html( get_post_meta( $post->ID, 'lfes_city', true ) ? get_post_meta( $post->ID, 'lfes_city', true ) : '' ) . '",';
	$out .= '	"postalCode": "' . esc_html( get_post_meta( $post->ID, 'lfes_postal_code', true ) ? get_post_meta( $post->ID, 'lfes_postal_code', true ) : '' ) . '",';
	$out .= '	"addressRegion": "' . esc_html( get_post_meta( $post->ID, 'lfes_region', true ) ? get_post_meta( $post->ID, 'lfes_region', true ) : '' ) . '",';
	$out .= '	"addressCountry": "' . esc_html( $country ? $country : '' ) . '"';
	$out .= '  }';
	$out .= '},';
	$out .= '	"image": [ ';
	$out .= '	  "' . esc_html( $image_url ) . '"';
	$out .= '	 ],';
	$out .= '	"description": "' . esc_html( get_post_meta( $post->ID, 'lfes_description', true ) ) . '"';
	$out .= '}';
	$out .= '</script>';

	echo $out; //phpcs:ignore
}

/**
 * Wraps the logic for redirecting to 3rd-party Event sites.
 *
 * @param int $post_id Post id.
 */
function lfe_get_event_url( $post_id ) {
	$url = get_post_meta( $post_id, 'lfes_external_url', true );
	if ( $url ) {
		return $url;
	} else {
		return get_permalink( $post_id );
	}
}

/**
 * Sets the except length.
 *
 * @param int $length Number of words.
 */
function custom_excerpt_length( $length ) {
	return 18;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/**
 * Fix preconnect and preload to better optimize loading. Preconnect is priority, must have crossorigin; Prefetch just opens connection.
 *
 * @param string $hints returns hints.
 * @param string $relation_type returns priority.
 */
function change_to_preconnect_resource_hints( $hints, $relation_type ) {

	if ( 'preconnect' === $relation_type ) {
		$hints[] = array(
			'crossorigin' => '',
			'href'        => '//www.googletagmanager.com',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'change_to_preconnect_resource_hints', 10, 2 );

add_filter( 'emoji_svg_url', '__return_false' );


/* Will only run on front end of site */
if ( ! is_admin() ) {
	/**
	 * Make all JS defer onload (in conjunction with moving jQuery to footer).
	 *
	 * @param string $url the URL.
	 */
	function defer_parsing_of_js( $url ) {
		if ( false === strpos( $url, '.js' ) ) {
			return $url;
		}
		if ( strpos( $url, 'jquery-3.5.1.min.js' ) && ! is_front_page() ) {
			return $url;
		}
		return str_replace( ' src', ' defer src', $url );
	}
	add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );
}

add_filter( 'the_seo_framework_image_generation_params', 'my_tsf_custom_image_generation_args', 10, 3 );
/**
 * Adjusts image generation parameters for snackables.  It will get the snackable from the parent page.
 *
 * @link https://theseoframework.com/docs/api/filters/#append-image-generators-for-social-images
 *
 * @param array      $params  : [
 *    string  size:     The image size to use.
 *    boolean multi:    Whether to allow multiple images to be returned.
 *    array   cbs:      The callbacks to parse. Ideally be generators, so we can halt remotely.
 *    array   fallback: The callbacks to parse. Ideally be generators, so we can halt remotely.
 * ].
 * @param array|null $args    The query arguments. Contains 'id' and 'taxonomy'.
 *                            Is null when query is autodetermined.
 * @param string     $context The filter context. Default 'social'.
 *                            May be (for example) 'breadcrumb' or 'article' for structured data.
 * @return array $params
 */
function my_tsf_custom_image_generation_args( $params = array(), $args = null, $context = 'social' ) {

	// Let's not mess with non-social sharing images.
	if ( 'social' !== $context ) {
		return $params;
	}

	$has_parent = false;

	if ( null === $args ) {
		// In the loop.
		if ( is_singular() ) {
			// We don't trust WP in giving the right ID in the loop.
			$has_parent = wp_get_post_parent_id( the_seo_framework()->get_the_real_ID() );
		}
	} else {
		// Out the loop. Use $args to evaluate the query...
		if ( ! $args['taxonomy'] ) {
			// Singular.
			$has_parent = wp_get_post_parent_id( $args['id'] );
		}
	}

	if ( $has_parent ) {
		$params['cbs'] = array_merge(
			array( '_parent' => 'my_tsf_get_parent_social_meta_image' ),
			$params['cbs']
		);
	}

	return $params;
}

/**
 * Generates image URL and ID via my_get_image_value.
 *
 * @generator
 *
 * @param array|null $args The query arguments. Accepts 'id' and 'taxonomy'.
 *                         Leave null to autodetermine query.
 * @param string     $size The size of the image to get.
 * @yield array : {
 *    string url: The image URL location,
 *    int    id:  The image ID,
 * }
 */
function my_tsf_get_parent_social_meta_image( $args = null, $size = 'full' ) {

	$tsf = the_seo_framework();
	// Obtain the post parent ID...
	$post_id    = isset( $args['id'] ) ? $args['id'] : $tsf->get_the_real_ID();
	$parent_id  = wp_get_post_parent_id( $post_id );
	$parent2_id = wp_get_post_parent_id( $parent_id );

	if ( $parent2_id ) {
		$parent_id = $parent2_id;
	}

	yield array(
		'url' => $tsf->get_post_meta_item( '_social_image_url', $parent_id ),
		'id'  => $tsf->get_post_meta_item( '_social_image_id', $parent_id ),
	);
}


// The WP REST API is cached heavily by Pantheon so we need to explicitly exclude certain calls from the cache.
// From https://pantheon.io/docs/mu-plugin#wp-rest-api-code-classlanguage-textwp-jsoncode-endpoints-cache.
$regex_json_path_patterns = array(
	'#^/wp-json/post-meta-controls/v1/?#',
	'#^/wp-json/wp/v2/lfe_sponsor?#',
	'#^/wp-json/wp/v2/lfe_speaker?#',

);
foreach ( $regex_json_path_patterns as $regex_json_path_pattern ) {
	if ( preg_match( $regex_json_path_pattern, $_SERVER['REQUEST_URI'] ) ) { //phpcs:ignore
		// re-use the rest_post_dispatch filter in the Pantheon page cache plugin.
		add_filter( 'rest_post_dispatch', 'filter_rest_post_dispatch_send_cache_control', 12, 2 );

		/**
		 * Re-define the send_header value with any custom Cache-Control header.
		 *
		 * @param obj $response Response object.
		 * @param obj $server Server object.
		 */
		function filter_rest_post_dispatch_send_cache_control( $response, $server ) {
			$server->send_header( 'Cache-Control', 'no-cache, must-revalidate, max-age=0' );
			return $response;
		}
		break;
	}
}


/**
 * Returns a banner saying the event has passed for past events.
 *
 * @param int $parent_id Parent ID.
 */
function lfe_passed_event_banner( $parent_id ) {
	$post_type = get_post_type( $parent_id );
	echo 'This event has passed. ';
	if ( 'page' !== $post_type ) {
		$parent       = get_post( $parent_id );
		$slug         = $parent->post_name;
		$latest_event = get_page_by_path( $slug, OBJECT, 'page' );

		if ( $latest_event && 'publish' === $latest_event->post_status ) {
			$event_has_passed = get_post_meta( $latest_event->ID, 'lfes_event_has_passed', true );

			if ( ! $event_has_passed ) {
				echo 'Please visit the upcoming <a style="color:inherit;text-decoration:underline;" href="' . esc_url( get_permalink( $latest_event->ID ) ) . '">' . esc_html( get_the_title( $latest_event->ID ) ) . '.</a>';
				return;
			}
		}
	}
	$term = wp_get_post_terms( $parent_id, 'lfevent-category', array( 'fields' => 'all' ) );
	if ( ! empty( $term ) && $term[0] ) {
		echo 'View the upcoming <a style="color:inherit;text-decoration:underline;" href="https://events.linuxfoundation.org/about/calendar/?_sft_lfevent-category=' . urlencode( $term[0]->slug ) . '"> ' . esc_html( $term[0]->name ) . '.</a>';
	} else {
		echo 'View upcoming <a style="color:inherit;text-decoration:underline;" href="https://events.linuxfoundation.org/">Linux Foundation events.</a>';
	}
}


/**
 * Gets HTML for an alert bar inserted at the top of Events when set.
 *
 * @param int $parent_id Parent ID.
 */
function lfe_event_alert_bar( $parent_id ) {

	// if no alert text is set, return.
	$alert_text = get_post_meta( $parent_id, 'lfes_alert_text', true );
	if ( ! $alert_text ) {
		return;
	}

	// check for expiry date and expired date.
	$expiry_date = get_post_meta( $parent_id, 'lfes_alert_expiry_date', true );
	if ( $expiry_date ) {
		$dt_expiry          = new DateTime( $expiry_date );
		$dt_expiry_1d_after = new DateTime( $expiry_date );
		$dt_expiry_1d_after->add( new DateInterval( 'P1D' ) );
		$dt_now = new DateTime( 'now' );
		if ( $dt_expiry_1d_after < $dt_now ) {
			// alert has expired.
			return;
		}
	}

	// get alert text color or set default.
	$alert_text_color = get_post_meta( $parent_id, 'lfes_alert_text_color', true ) ? get_post_meta( $parent_id, 'lfes_alert_text_color', true ) : '#FFFFFF';

	// get alert background color or set default.
	$alert_background_color = get_post_meta( $parent_id, 'lfes_alert_background_color', true ) ? get_post_meta( $parent_id, 'lfes_alert_background_color', true ) : '#0082ad';

	$out  = '<div class="event-alert-bar" style="color: ' . esc_html( $alert_text_color ) . '; background-color: ' . esc_html( $alert_background_color ) . ';">';
	$out .= preg_replace( '/\[(.*?)]\((https?.*?)\)/', '<a href="$2">$1</a>', $alert_text );
	$out .= '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-double-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="icon--inline small-margin-left"><path fill="currentColor" d="M363.8 264.5L217 412.5c-4.7 4.7-12.3 4.7-17 0l-19.8-19.8c-4.7-4.7-4.7-12.3 0-17L298.7 256 180.2 136.3c-4.7-4.7-4.7-12.3 0-17L200 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17zm-160-17L57 99.5c-4.7-4.7-12.3-4.7-17 0l-19.8 19.8c-4.7 4.7-4.7 12.3 0 17L138.7 256 20.2 375.7c-4.7 4.7-4.7 12.3 0 17L40 412.5c4.7 4.7 12.3 4.7 17 0l146.8-148c4.7-4.7 4.7-12.3 0-17z" class=""></path></svg>';
	$out .= '</div>';

	echo $out; //phpcs:ignore
}

/**
 * A function to test if the page should display non-event menu.
 */
function show_non_event_menu() {
	if ( get_post_meta( get_the_ID(), 'lfes_splash_page', true ) || 'lfe_about_page' == get_post_type() || 'post' == get_post_type() || is_404() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * All enqueued styles have dns-prefetch added to them. This changes it to preconnect for extra zip.
 *
 * @param string $urls array of urls.
 * @param string $relation_type returns priority.
 */
function dns_prefetch_to_preconnect( $urls, $relation_type ) {
	global $wp_scripts, $wp_styles;

	$unique_urls = array();
	$domain      = '';
	if ( isset( $_SERVER['SERVER_NAME'] ) ) {
		$domain = sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) );
	}

	foreach ( array( $wp_scripts, $wp_styles ) as $dependencies ) {
		if ( $dependencies instanceof WP_Dependencies && ! empty( $dependencies->queue ) ) {
			foreach ( $dependencies->queue as $handle ) {
				if ( ! isset( $dependencies->registered[ $handle ] ) ) {
					continue;
				}

					$dependency = $dependencies->registered[ $handle ];
					$parsed     = wp_parse_url( $dependency->src );

				if ( ! empty( $parsed['host'] ) && ! in_array( $parsed['host'], $unique_urls ) && $parsed['host'] !== $domain ) {

						$unique_urls[] = $parsed['scheme'] . '://' . $parsed['host'];
				}
			}
		}
	}

	if ( 'dns-prefetch' === $relation_type ) {
			$urls = array();
	}

	if ( 'preconnect' === $relation_type ) {

		$urls = array();

		// add custom urls to preconnect.
		$add_urls = array(
			'https://js.hscollectedforms.net',
			'https://js.hs-banner.com',
			'https://js.hs-analytics.net',
			'https://js.hsforms.net',
			'https://js.hs-scripts.com',
		);

		// add them to the urls list.
		foreach ( $add_urls as $add_url ) {
			array_push( $unique_urls, $add_url );
		}

		// add crossorigin, remove protocol.
		foreach ( $unique_urls as $url ) {
			$url = array(
				'crossorigin',
				'href' => str_replace( array( 'http:', 'https:' ), '', $url ),
			);

			// add to urls array.
			array_push( $urls, $url );
		}
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'dns_prefetch_to_preconnect', 0, 2 );

/**
 * Remove tags support from posts
 */
function lfe_theme_unregister_tags() {
	unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}
add_action( 'init', 'lfe_theme_unregister_tags' );
