<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LFEvents
 * @subpackage LFEvents/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LFEvents
 * @subpackage LFEvents/admin
 * @author     Your Name <email@example.com>
 */
class LFEvents_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $lfevents    The ID of this plugin.
	 */
	private $lfevents;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Array of lfevent custom post types that are in use
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $post_types    Array of lfevent custom post types that are in use
	 */
	private $post_types;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $lfevents       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $lfevents, $version ) {

		$this->lfevents   = $lfevents;
		$this->version    = $version;
		$this->post_types = lfe_get_post_types();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LFEvents_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LFEvents_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lfevents, plugin_dir_url( __FILE__ ) . 'css/lfevents-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in LFEvents_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The LFEvents_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lfevents, plugin_dir_url( __FILE__ ) . 'js/lfevents-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the Block Editor.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script( $this->lfevents, plugin_dir_url( __FILE__ ) . 'js/lfevents-editor-only.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-data', 'jquery' ), $this->version, false );
	}

	/**
	 * Registers the custom post types
	 */
	public function new_cpts() {

		$opts = array(
			'labels'       => array(
				'name'          => __( 'About Pages' ),
				'singular_name' => __( 'About Page' ),
				'all_items'     => __( 'All About Pages' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'menu_icon'    => 'dashicons-info',
			'rewrite'      => array( 'slug' => 'about' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes', 'author' ),
		);

		register_post_type( 'lfe_about_page', $opts );

		$opts = array(
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'hierarchical'  => true,
			'menu_icon'     => 'dashicons-admin-page',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes', 'author' ),
			'menu_position' => 30,
		);

		$current_year = gmdate( 'Y' );
		for ( $x = 2016; $x <= $current_year; $x++ ) {
			$opts['labels']  = array(
				'name'          => $x . ' Events',
				'singular_name' => $x . ' Event',
				'all_items'     => 'All ' . $x . ' Events',
			);
			$opts['rewrite'] = array( 'slug' => 'archive/' . $x );

			register_post_type( 'lfevent' . $x, $opts );
		}

		$opts = array(
			'labels'             => array(
				'name'          => __( 'Speakers' ),
				'singular_name' => __( 'Speaker' ),
				'all_items'     => __( 'All Speakers' ),
			),
			'show_in_rest'       => true,
			'public'             => false, // not publicly viewable.
			'publicly_queryable' => false, // not publicly queryable.
			'show_ui'            => true, // But still show admin UI.
			'menu_icon'          => 'dashicons-groups',
			'rewrite'            => array( 'slug' => 'speakers' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', 'revisions' ),
		);

		register_post_type( 'lfe_speaker', $opts );

		$opts = array(
			'labels'             => array(
				'name'          => __( 'Sponsors' ),
				'singular_name' => __( 'Sponsor' ),
				'all_items'     => __( 'All Sponsors' ),
			),
			'show_in_rest'       => true,
			'public'             => false, // not publicly viewable.
			'publicly_queryable' => false, // not publicly queryable.
			'show_ui'            => true, // But still show admin UI.
			'menu_icon'          => 'dashicons-star-filled',
			'rewrite'            => array( 'slug' => 'sponsors' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'author', 'revisions' ),
		);

		register_post_type( 'lfe_sponsor', $opts );

		$opts = array(
			'labels'             => array(
				'name'          => __( 'Community Events' ),
				'singular_name' => __( 'Community Event' ),
				'all_items'     => __( 'All Community Events' ),
			),
			'public'             => false, // not publicly viewable.
			'publicly_queryable' => false, // not publicly queryable.
			'show_ui'            => true, // But still show admin UI.
			'has_archive'        => false,
			'show_in_nav_menus'  => false,
			'show_in_rest'       => true,
			'hierarchical'       => true,
			'menu_icon'          => 'dashicons-admin-site',
			'rewrite'            => array( 'slug' => 'community' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'author' ),
		);

		register_post_type( 'lfe_community_event', $opts );
	}

	/**
	 * Changes the "Pages" labels to "Events"
	 */
	public function change_page_label() {
		global $wp_post_types;
		$labels                     = &$wp_post_types['page']->labels;
		$labels->name               = 'Events';
		$labels->singular_name      = 'Event';
		$labels->add_new_item       = 'Add Event';
		$labels->edit_item          = 'Edit Event';
		$labels->new_item           = 'New Event';
		$labels->view_item          = 'View Event';
		$labels->all_items          = 'All Events';
		$labels->view_items         = 'View Events';
		$labels->search_items       = 'Search Events';
		$labels->not_found          = 'No Events found';
		$labels->not_found_in_trash = 'No Events found in Trash';
		$labels->archives           = 'Event Archives';
		$labels->attributes         = 'Event Attributes';
		$labels->menu_name          = 'Events';
		$labels->name_admin_bar     = 'Event';

	}

	/**
	 * Registers the LFEvent categories
	 */
	public function register_event_categories() {
		$labels = array(
			'name'          => _x( 'Event Categories', 'taxonomy general name' ),
			'singular_name' => _x( 'Event Category', 'taxonomy singular name' ),
		);
		$args   = array(
			'labels'             => $labels,
			'show_in_rest'       => true,
			'hierarchical'       => true,
			'public'             => false, // not publicly viewable.
			'publicly_queryable' => false, // not publicly queryable.
			'show_ui'            => true, // But still show admin UI.
		);

		register_taxonomy( 'lfevent-category', $this->post_types, $args );

		$labels = array(
			'name'              => _x( 'Event Countries', 'taxonomy general name' ),
			'singular_name'     => _x( 'Event Country', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Countries' ),
			'all_items'         => __( 'All Countries' ),
			'parent_item'       => __( 'Parent Continent' ),
			'parent_item_colon' => __( 'Parent Continent:' ),
			'edit_item'         => __( 'Edit Country' ),
			'update_item'       => __( 'Update Country' ),
			'add_new_item'      => __( 'Add New Country' ),
			'new_item_name'     => __( 'New Country Name' ),
			'menu_name'         => __( 'Event Countries' ),
		);

		$args = array(
			'labels'             => $labels,
			'show_in_rest'       => true,
			'hierarchical'       => true,
			'public'             => false, // not publicly viewable.
			'publicly_queryable' => false, // not publicly queryable.
			'show_ui'            => true, // But still show admin UI.
		);

		register_taxonomy( 'lfevent-country', array_merge( $this->post_types, array( 'lfe_community_event' ) ), $args );
	}

	/**
	 * Registers the extra sidebar for post types
	 *
	 * See meta control docs https://melonpan.io/wordpress-plugins/post-meta-controls/
	 *
	 * @param array $sidebars    Existing sidebars in Gutenberg.
	 */
	public function create_sidebar( $sidebars ) {
		// First we define the sidebar with it's tabs, panels and settings.
		$palette = array(
			'dark-fuschia'     => '#6e1042',
			'dark-violet'      => '#411E4F',
			'dark-indigo'      => '#1A267D',
			'dark-blue'        => '#17405c',
			'dark-aqua'        => '#0e5953',
			'dark-green'       => '#0b5329',

			'light-fuschia'    => '#AD1457',
			'light-violet'     => '#6C3483',
			'light-indigo'     => '#4653B0',
			'light-blue'       => '#2874A6',
			'light-aqua'       => '#148f85',
			'light-green'      => '#117a3d',

			'dark-chartreuse'  => '#3d5e0f',
			'dark-yellow'      => '#878700',
			'dark-gold'        => '#8c7000',
			'dark-orange'      => '#784e12',
			'dark-umber'       => '#6E2C00',
			'dark-red'         => '#641E16',

			'light-chartreuse' => '#699b23',
			'light-yellow'     => '#b0b000',
			'light-gold'       => '#c29b00',
			'light-orange'     => '#c2770e',
			'light-umber'      => '#b8510d',
			'light-red'        => '#922B21',
		);

		$sidebar = array(
			'id'              => 'lfevent-sidebar-event',
			'id_prefix'       => 'lfes_',
			'label'           => __( 'Event Settings' ),
			'post_type'       => $this->post_types,
			'data_key_prefix' => 'lfes_',
			'icon_dashicon'   => 'format-gallery',
			'tabs'            => array(
				array(
					'label'  => __( 'Tab label' ),
					'panels' => array(
						array(
							'label'        => __( 'General' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'textarea',
									'id'            => 'description',
									'data_type'     => 'meta',
									'data_key'      => 'description',
									'label'         => __( 'Event description' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => 'The Cloud Native Computing Foundation’s flagship conference gathers adopters and technologists from leading open source and cloud native communities in San Diego, California from November 18-21, 2019. Join Kubernetes, Prometheus, Envoy, CoreDNS, containerd, Fluentd, OpenTracing, gRPC, rkt, CNI, Jaeger, Notary, TUF, Vitess, NATS, Linkerd, Helm, Rook, Harbor, etcd, Open Policy Agent, and CRI-O as the community gathers for four days to further the education and advancement of cloud native computing.',
								),
								array(
									'type'          => 'text',
									'id'            => 'date_start',
									'data_type'     => 'meta',
									'data_key'      => 'date_start',
									'label'         => __( 'Event start date' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'text',
									'id'            => 'date_end',
									'data_type'     => 'meta',
									'data_key'      => 'date_end',
									'label'         => __( 'Event end date' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'text',
									'id'            => 'external_url',
									'data_type'     => 'meta',
									'data_key'      => 'external_url',
									'label'         => __( 'URL to External Event site' ),
									'help'          => __( 'Set this value only when the Event site is located on an external site.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => 'https://www.cloudfoundry.org/event/summit/',
								),
								array(
									'type'          => 'checkbox',
									'id'            => 'visa_request',
									'data_type'     => 'meta',
									'data_key'      => 'visa_request',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'List on general visa request form' ),
								),
								array(
									'type'          => 'checkbox',
									'id'            => 'travel_fund_request',
									'data_type'     => 'meta',
									'data_key'      => 'travel_fund_request',
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'List on general travel fund form' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'salesforce_id',
									'data_type'     => 'meta',
									'data_key'      => 'salesforce_id',
									'label'         => __( 'SalesForce ID' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => 'a0A2M00000VcC9HUAV',
								),
								array(
									'type'          => 'text',
									'id'            => 'dropdown_title',
									'data_type'     => 'meta',
									'data_key'      => 'dropdown_title',
									'label'         => __( 'Dropdown Title' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'help'          => __( 'Set this value to override the Event title in the form dropdowns.' ),
									'default_value' => '',
									'placeholder'   => 'Open Source Summit + Embedded Linux Conference',
								),
							),
						),
						array(
							'label'        => __( 'Design' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'image',
									'id'            => 'white_logo',
									'data_type'     => 'meta',
									'data_key'      => 'white_logo',
									'label'         => __( 'White logo' ),
									'register_meta' => true,
									'ui_border_top' => true,
								),
								array(
									'type'          => 'image',
									'id'            => 'black_logo',
									'data_type'     => 'meta',
									'data_key'      => 'black_logo',
									'label'         => __( 'Black logo' ),
									'register_meta' => true,
									'ui_border_top' => false,
								),
								array(
									'type'          => 'color',
									'id'            => 'menu_color',
									'data_type'     => 'meta',
									'data_key'      => 'menu_color',
									'label'         => __( 'Menu Background Color' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '#222222',
									'alpha_control' => false,
									'palette'       => $palette,
								),
								array(
									'type'          => 'color',
									'id'            => 'menu_color_2',
									'data_type'     => 'meta',
									'data_key'      => 'menu_color_2',
									'label'         => __( 'Menu Gradient Color' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => 'transparent',
									'alpha_control' => false,
									'palette'       => $palette,
								),
								array(
									'type'          => 'color',
									'id'            => 'menu_color_3',
									'data_type'     => 'meta',
									'data_key'      => 'menu_color_3',
									'label'         => __( 'Menu Dropdown Color' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => 'transparent',
									'alpha_control' => false,
									'palette'       => $palette,
								),
								array(
									'type'          => 'radio',
									'id'            => 'menu_text_color',
									'data_type'     => 'meta',
									'data_key'      => 'menu_text_color',
									'label'         => __( 'Menu Text Color' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => 'white',
									'alpha_control' => false,
									'options'       => array(
										'white' => __( 'White' ),
										'black' => __( 'Black' ),
									),
								),
								array(
									'type'          => 'image',
									'id'            => 'favicon',
									'data_type'     => 'meta',
									'data_key'      => 'favicon',
									'label'         => __( 'Favicon' ),
									'help'          => __( 'Should be a 32x32 png file. Use realfavicongenerator.net.' ),
									'register_meta' => true,
									'ui_border_top' => true,
								),
								array(
									'type'          => 'range',
									'id'            => 'overlay_strength',
									'data_type'     => 'meta',
									'data_key'      => 'overlay_strength',
									'label'         => __( 'Subpage Hero Overlay Strength', 'my_plugin' ),
									'help'          => __( '100% is a normal color overlay; 0% is no overlay; 150% extra strong overlay', 'my_plugin' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => 100,
									'step'          => 1,
									'min'           => 0,
									'max'           => 150,
								),
							),
						),
						array(
							'label'        => __( 'Location' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'checkbox',
									'id'            => 'virtual',
									'data_type'     => 'meta',
									'data_key'      => 'virtual',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'Virtual' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'city',
									'data_type'     => 'meta',
									'data_key'      => 'city',
									'label'         => __( 'City' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'Paris' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'venue',
									'data_type'     => 'meta',
									'data_key'      => 'venue',
									'label'         => __( 'Venue' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'San Diego Convention Center' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'street_address',
									'data_type'     => 'meta',
									'data_key'      => 'street_address',
									'label'         => __( 'Street Address' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( '2635 Homestead Rd' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'postal_code',
									'data_type'     => 'meta',
									'data_key'      => 'postal_code',
									'label'         => __( 'Postal Code' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( '95051' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'region',
									'data_type'     => 'meta',
									'data_key'      => 'region',
									'label'         => __( 'Province/State' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'CA' ),
								),
							),
						),
						array(
							'label'        => __( 'Call for Proposal' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'checkbox',
									'id'            => 'cfp_active',
									'data_type'     => 'meta',
									'data_key'      => 'cfp_active',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => true,
									'use_toggle'    => false,
									'input_label'   => __( 'CFP for Event' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'cfp_date_start',
									'data_type'     => 'meta',
									'data_key'      => 'cfp_date_start',
									'label'         => __( 'CFP start date' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'text',
									'id'            => 'cfp_date_end',
									'data_type'     => 'meta',
									'data_key'      => 'cfp_date_end',
									'label'         => __( 'CFP end date' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
							),
						),
						array(
							'label'        => __( 'Social' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'text',
									'id'            => 'hashtag',
									'data_type'     => 'meta',
									'data_key'      => 'hashtag',
									'label'         => __( 'Hashtag for event' ),
									'help'          => __( 'Write hashtag here or other text to be associated with the social media icons.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( '#KubeCon' ),
								),
								array(
									'type'          => 'image',
									'id'            => 'wechat',
									'data_type'     => 'meta',
									'data_key'      => 'wechat',
									'label'         => __( 'WeChat QR code' ),
									'register_meta' => true,
									'ui_border_top' => true,
								),
								array(
									'type'          => 'text',
									'id'            => 'linkedin',
									'data_type'     => 'meta',
									'data_key'      => 'linkedin',
									'label'         => __( 'LinkedIn URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://www.linkedin.com/company/cloud-native-computing-foundation' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'qq',
									'data_type'     => 'meta',
									'data_key'      => 'qq',
									'label'         => __( 'QQ URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'http://v.qq.com/vplus/dbc4895dfc0a6ec609ad9e42a10507e0/videos' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'youtube',
									'data_type'     => 'meta',
									'data_key'      => 'youtube',
									'label'         => __( 'YouTube URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://www.youtube.com/channel/UCvqbFHwN-nwalWPjPUKpvTA' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'facebook',
									'data_type'     => 'meta',
									'data_key'      => 'facebook',
									'label'         => __( 'Facebook URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://www.facebook.com/CloudNativeComputingFoundation/' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'twitter',
									'data_type'     => 'meta',
									'data_key'      => 'twitter',
									'label'         => __( 'Twitter URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://twitter.com/CloudNativeFdn' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'instagram',
									'data_type'     => 'meta',
									'data_key'      => 'instagram',
									'label'         => __( 'Instagram URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://www.instagram.com/linux_foundation/' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'twitch',
									'data_type'     => 'meta',
									'data_key'      => 'twitch',
									'label'         => __( 'Twitch URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://www.twitch.tv/cloudnativefdn' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'slack',
									'data_type'     => 'meta',
									'data_key'      => 'slack',
									'label'         => __( 'Slack URL' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://slack.cncf.io/' ),
								),
							),
						),
						array(
							'label'        => __( 'Homepage Tile' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'text',
									'id'            => 'cta_register_url',
									'data_type'     => 'meta',
									'data_key'      => 'cta_register_url',
									'label'         => __( 'CTA Register URL' ),
									'help'          => __( 'This CTA button will be displayed until the Event end date.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://events.linuxfoundation.org/kubecon-cloudnativecon-north-america/register/' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'cta_speak_url',
									'data_type'     => 'meta',
									'data_key'      => 'cta_speak_url',
									'label'         => __( 'CTA Speak URL' ),
									'help'          => __( 'This CTA button will be displayed during the Call For Proposal date range.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://events.linuxfoundation.org/kubecon-cloudnativecon-north-america/cfp/' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'cta_sponsor_url',
									'data_type'     => 'meta',
									'data_key'      => 'cta_sponsor_url',
									'label'         => __( 'CTA Sponsor URL' ),
									'help'          => __( 'This CTA button will be displayed whenever a url is provided and until the following end date, if one is provided:' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://events.linuxfoundation.org/kubecon-cloudnativecon-north-america/sponsors/become-and-sponsor/' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'cta_sponsor_date_end',
									'data_type'     => 'meta',
									'data_key'      => 'cta_sponsor_date_end',
									'label'         => __( 'CTA Sponsor end date' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'text',
									'id'            => 'cta_schedule_url',
									'data_type'     => 'meta',
									'data_key'      => 'cta_schedule_url',
									'label'         => __( 'CTA Schedule URL' ),
									'help'          => __( 'This CTA button will be displayed whenever a url is provided.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'https://events.linuxfoundation.org/kubecon-cloudnativecon-north-america/schedule/' ),
								),
							),
						),
						array(
							'label'        => __( 'Newsletter' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'textarea',
									'id'            => 'form_title',
									'data_type'     => 'meta',
									'data_key'      => 'form_title',
									'label'         => __( 'Form Title text' ),
									'help'          => __( 'If this is set, this will override default form title.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'Join our mailing list to hear all the latest about events, news and more.' ),
								),
								array(
									'type'          => 'textarea',
									'id'            => 'form_privacy',
									'data_type'     => 'meta',
									'data_key'      => 'form_privacy',
									'label'         => __( 'Privacy Notice text' ),
									'help'          => __( 'If this is set, this will override default privacy link. Use markdown to include privacy policy link.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'The Linux Foundation uses the information you provide to us to contact you about upcoming events. You may unsubscribe from these communications at any time. For more information, please see our [Privacy Policy](https://www.linuxfoundation.org/privacy/).' ),
								),
							),
						),
						array(
							'label'        => __( 'Alert Bar' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'textarea',
									'id'            => 'alert_text',
									'data_type'     => 'meta',
									'data_key'      => 'alert_text',
									'label'         => __( 'Alert Message' ),
									'help'          => __( 'Links should be written in markdown format [like this](https://google.com)' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
								),
								array(
									'type'          => 'text',
									'id'            => 'alert_expiry_date',
									'data_type'     => 'meta',
									'data_key'      => 'alert_expiry_date',
									'label'         => __( 'Expiry Date' ),
									'help'          => __( 'Optional' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'color',
									'id'            => 'alert_text_color',
									'data_type'     => 'meta',
									'data_key'      => 'alert_text_color',
									'label'         => __( 'Text Color' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '#222222',
									'alpha_control' => false,
									'palette'       => array(
										'white' => '#ffffff',
										'black' => '#000000',
									),
								),
								array(
									'type'          => 'color',
									'id'            => 'alert_background_color',
									'data_type'     => 'meta',
									'data_key'      => 'alert_background_color',
									'label'         => __( 'Background Color' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '#222222',
									'alpha_control' => false,
									'palette'       => $palette,
								),

							),
						),
						array(
							'label'        => __( 'Advanced' ),
							'initial_open' => false,
							'settings'     => array(
								array(
									'type'          => 'radio',
									'id'            => 'hide_from_listings',
									'data_type'     => 'meta',
									'data_key'      => 'hide_from_listings',
									'label'         => __( 'Hide from Homepage and Calendars' ),
									'help'          => __( 'This will hide the Event from the homepage and calendars.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => 'show',
									'options'       => array(
										'show' => __( 'Show' ),
										'hide' => __( 'Hide' ),
									),
								),
								array(
									'type'          => 'radio',
									'id'            => 'event_has_passed',
									'data_type'     => 'meta',
									'data_key'      => 'event_has_passed',
									'label'         => __( 'Event Has Passed' ),
									'help'          => __( 'This value will update automatically so no need to touch it.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '0',
									'options'       => array(
										'1' => __( 'True' ),
										'0' => __( 'False' ),
									),
								),
								array(
									'type'          => 'text',
									'id'            => 'related_events',
									'data_type'     => 'meta',
									'data_key'      => 'related_events',
									'label'         => __( 'Related Events Override' ),
									'help'          => __( 'This is a comma-delimited list of Event IDs that, when set, will be listed instead of the normal related Events in the View All Events menu.' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( '1365,2122,3112' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'extra_vae_link_text',
									'data_type'     => 'meta',
									'data_key'      => 'extra_vae_link_text',
									'label'         => __( 'Extra View All Events Link' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'CNCF Homepage' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'extra_vae_link_url',
									'data_type'     => 'meta',
									'data_key'      => 'extra_vae_link_url',
									'label'         => __( 'Link URL' ),
									'help'          => __( 'This extra link will appear second in the View All Events dropdown.' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'https://www.cncf.io/' ),
								),
							),
						),
					),
				),
			),
		);

		// Push the $sidebar we just assigned to the variable
		// to the array of $sidebars that comes in the function argument.
		$sidebars[] = $sidebar;

		$sidebar = array(
			'id'              => 'lfevent-sidebar-page',
			'id_prefix'       => 'lfes_',
			'label'           => __( 'Page Settings' ),
			'post_type'       => $this->post_types,
			'data_key_prefix' => 'lfes_',
			'icon_dashicon'   => 'media-spreadsheet',
			'tabs'            => array(
				array(
					'label'  => __( 'Tab label' ),
					'panels' => array(
						array(
							'label'        => __( 'General' ),
							'initial_open' => true,
							'settings'     => array(
								array(
									'type'          => 'checkbox',
									'id'            => 'hide_from_menu',
									'data_type'     => 'meta',
									'data_key'      => 'hide_from_menu',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'Hide from Event menu' ),
									'help'          => __( 'This will stop this particular page from showing on the Event top navigation menu.' ),
								),
								array(
									'type'          => 'checkbox',
									'id'            => 'hide_header',
									'data_type'     => 'meta',
									'data_key'      => 'hide_header',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'Hide normal subpage heading', 'my_plugin' ),
									'help'          => __( 'This is useful when creating alternate landing pages for the event.' ),
								),
								array(
									'type'          => 'checkbox',
									'id'            => 'splash_page',
									'data_type'     => 'meta',
									'data_key'      => 'splash_page',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'Splash Page' ),
									'help'          => __( 'This will make the page display a minimal topnav appropriate for an event splash page.' ),
								),
							),
						),
					),
				),
			),
		);

		// Push the $sidebar we just assigned to the variable
		// to the array of $sidebars that comes in the function argument.
		$sidebars[] = $sidebar;

		$sidebar = array(
			'id'              => 'lfe-speaker-sidebar',
			'id_prefix'       => 'lfes_speaker_',
			'label'           => __( 'Speaker Details' ),
			'post_type'       => array( 'lfe_speaker' ),
			'data_key_prefix' => 'lfes_speaker_',
			'icon_dashicon'   => 'admin-users',
			'tabs'            => array(
				array(
					'label'  => __( 'Tab label' ),
					'panels' => array(
						array(
							'label'    => __( 'Speaker Details' ),
							'settings' => array(
								array(
									'type'          => 'text',
									'id'            => 'title',
									'data_type'     => 'meta',
									'data_key'      => 'title',
									'label'         => __( 'Title, Company' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'Title, Company' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'linkedin',
									'data_type'     => 'meta',
									'data_key'      => 'linkedin',
									'label'         => __( 'LinkedIn URL' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'https://www.linkedin.com/in/username/' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'twitter',
									'data_type'     => 'meta',
									'data_key'      => 'twitter',
									'label'         => __( 'Twitter URL' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'https://twitter.com/username' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'website',
									'data_type'     => 'meta',
									'data_key'      => 'website',
									'label'         => __( 'Website URL' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'https://cncf.io' ),
								),
							),
						),
					),
				),
			),
		);

		// Push the $sidebar we just assigned to the variable
		// to the array of $sidebars that comes in the function argument.
		$sidebars[] = $sidebar;

		$sidebar = array(
			'id'              => 'lfe-sponsor-sidebar',
			'id_prefix'       => 'lfes_sponsor_',
			'label'           => __( 'Sponsor Details' ),
			'post_type'       => array( 'lfe_sponsor' ),
			'data_key_prefix' => 'lfes_sponsor_',
			'icon_dashicon'   => 'star-filled',
			'tabs'            => array(
				array(
					'label'  => __( 'Tab label' ),
					'panels' => array(
						array(
							'label'    => __( 'Sponsor Details' ),
							'settings' => array(
								array(
									'type'          => 'text',
									'id'            => 'url',
									'data_type'     => 'meta',
									'data_key'      => 'url',
									'label'         => __( 'Forwarding URL' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => __( 'https://cloud.google.com/' ),
								),
								array(
									'type'          => 'range',
									'id'            => 'size',
									'data_type'     => 'meta',
									'data_key'      => 'size',
									'label'         => __( 'Size Adjustment %' ),
									'help'          => __( '100% is a normal sized logo; 80% is smaller; 120% is larger' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => 100,
									'step'          => 1,
									'min'           => 50,
									'max'           => 150,
								),
							),
						),
					),
				),
			),
		);

		// Push the $sidebar we just assigned to the variable
		// to the array of $sidebars that comes in the function argument.
		$sidebars[] = $sidebar;

		$sidebar = array(
			'id'              => 'lfevent-sidebar',
			'id_prefix'       => 'lfes_community_',
			'label'           => __( 'Event Settings' ),
			'post_type'       => 'lfe_community_event',
			'data_key_prefix' => 'lfes_community_',
			'icon_dashicon'   => 'list-view',
			'tabs'            => array(
				array(
					'label'  => __( 'Tab label' ),
					'panels' => array(
						array(
							'label'        => __( 'General' ),
							'initial_open' => true,
							'settings'     => array(
								array(
									'type'          => 'checkbox',
									'id'            => 'virtual',
									'data_type'     => 'meta',
									'data_key'      => 'virtual',
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => false,
									'use_toggle'    => false,
									'input_label'   => __( 'Virtual' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'city',
									'data_type'     => 'meta',
									'data_key'      => 'city',
									'label'         => __( 'City' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => __( 'Paris' ),
								),
								array(
									'type'          => 'text',
									'id'            => 'date_start',
									'data_type'     => 'meta',
									'data_key'      => 'date_start',
									'label'         => __( 'Event start date' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'text',
									'id'            => 'date_end',
									'data_type'     => 'meta',
									'data_key'      => 'date_end',
									'label'         => __( 'Event end date' ),
									'register_meta' => true,
									'ui_border_top' => false,
									'default_value' => '',
									'placeholder'   => 'YYYY/MM/DD',
								),
								array(
									'type'          => 'text',
									'id'            => 'external_url',
									'data_type'     => 'meta',
									'data_key'      => 'external_url',
									'label'         => __( 'URL to Community Event site' ),
									'register_meta' => true,
									'ui_border_top' => true,
									'default_value' => '',
									'placeholder'   => 'https://www.cloudfoundry.org/event/summit/',
								),
							),
						),
					),
				),
			),
		);

		// Push the $sidebar we just assigned to the variable
		// to the array of $sidebars that comes in the function argument.
		$sidebars[] = $sidebar;

		// Return the $sidebars array with our sidebar now included.
		return $sidebars;

	}

	/**
	 * Adds filters to the Events listing in wp-admin
	 */
	public function event_filters() {
		global $wpdb;

		// only do this for Events.
		$post_type_listing = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
		if ( 'page' !== $post_type_listing && substr( $post_type_listing, 0, 7 ) !== 'lfevent' ) {
			return;
		}

		$post_id = isset( $_GET['admin-single-event'] ) ? (int) $_GET['admin-single-event'] : '';

		$myposts = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $wpdb->posts
				WHERE post_type like %s
				AND post_parent = 0
				AND post_status <> 'trash'
				AND post_title <> 'Auto Draft'
				ORDER BY $wpdb->posts.post_title ASC",
				$wpdb->esc_like( $post_type_listing ) . '%'
			)
		);

		echo '<select name="admin-single-event" class="event-quick-link">
		<option selected="selected" value="">Select Event</option>';
		foreach ( $myposts as $ep ) {
			$e = get_post( $ep );
			if ( $e->ID === $post_id ) {
				echo '<option value="' . esc_html( $e->ID ) . '" selected="selected">' . esc_html( $e->post_title ) . '</option>';
			} else {
				echo '<option value="' . esc_html( $e->ID ) . '">' . esc_html( $e->post_title ) . '</option>';
			}
		}
		echo '</select>';
	}

	/**
	 * Does the actual filtering of Events in the Admin listing
	 *
	 * @param object $query Existing query.
	 */
	public function event_list_filter( $query ) {
		$post_id = isset( $_GET['admin-single-event'] ) ? (int) $_GET['admin-single-event'] : '';
		if ( ! $post_id ) {
			return;
		}

		$posts_ids = array( $post_id );
		$posts_ids = array_merge( $posts_ids, get_kids( $post_id ) );

		$query->set( 'post__in', $posts_ids );
		$query->set( 'order', 'ASC' );
		$query->set( 'orderby', 'parent' );
	}

	/**
	 * If the Event has a external url set then this sets the noindex meta to true and vice versa.
	 *
	 * @param int $post_id The post id.
	 */
	public function synchronize_noindex_meta( $post_id ) {
		if ( ! in_array( get_post_type( $post_id ), $this->post_types ) ) {
			return;
		}

		$external_url = get_post_meta( $post_id, 'lfes_external_url', true );
		if ( $external_url ) {
			update_post_meta( $post_id, '_genesis_noindex', true );
		} else {
			delete_post_meta( $post_id, '_genesis_noindex' );
		}
	}

	/**
	 * Programmatically flushes the Pantheon site cache when a sponsor CPT is updated.
	 * A sponsor could potentially show up on many pages so that's why we need such a heavy reset of the cache.
	 * Also flushes the cache for all pages of an event when the sponsor-list page for that event is edited.
	 * The sponsor-list page gets included on all other pages of that event.
	 *
	 * @param int $post_id ID of post updated.
	 */
	public function reset_cache_check( $post_id ) {
		global $post;
		$post_saved = get_post( $post_id );
		if ( 'lfe_sponsor' === $post_saved->post_type && function_exists( 'pantheon_wp_clear_edge_all' ) ) {
			// a sponsor CPT has been updated so the whole site needs to be refreshed.
			pantheon_wp_clear_edge_all();
		} elseif ( 'sponsor-list' === $post_saved->post_name && in_array( $post_saved->post_type, $this->post_types ) ) {
			// the sponsor-list page has been updated so all event pages need refreshing.

			$args  = array(
				'child_of'    => $post_saved->post_parent,
				'exclude'     => $post_id,
				'post_type'   => $post_saved->post_type,
				'post_status' => 'publish',
			);
			$pages = get_pages( $args );

			$keys_to_clear = array( 'post-' . $post_saved->post_parent );
			foreach ( $pages as $p ) {
				$keys_to_clear[] = 'post-' . $p->ID;
			}

			pantheon_wp_clear_edge_keys( $keys_to_clear );
		}
	}

	/**
	 * Sync KCDs from https://community.cncf.io/ to the commmunity events CPT.
	 */
	public function sync_kcds() {
		$data = wp_remote_get( 'https://community.cncf.io/api/search/event?q=kcd' );

		if ( is_wp_error( $data ) || ( wp_remote_retrieve_response_code( $data ) != 200 ) ) {
			return false;
		}

		$remote_body = json_decode( wp_remote_retrieve_body( $data ) );
		$events = $remote_body->results;

		// delete all existing imported KCD posts.
		$args = array(
			'post_type'      => 'lfe_community_event',
			'meta_key'       => 'lfes_community_bevy_import',
			'meta_value'     => true,
			'no_found_rows'  => true,
			'posts_per_page' => 500,
			'post_status'    => 'any',
		);
		$the_query = new WP_Query( $args );
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			wp_delete_post( get_the_ID(), true );
		}

		// insert events.
		foreach ( $events as $event ) {
			// insert if end date hasn't passed.
			if ( ( time() - ( 60 * 60 * 24 ) ) < strtotime( $event->end_date_iso ) ) {
				$dt_date_start = new DateTime( $event->start_date_iso );
				$dt_date_end = new DateTime( $event->end_date_iso );

				$virtual = strpos( strtolower( $event->title ), 'virtual' ) + strpos( strtolower( $event->description_short ), 'virtual' );
				if ( 0 < $virtual || ! $event->venue_city ) {
					$virtual = true;
				} else {
					$virtual = false;
				}

				$my_post = array(
					'post_title'  => str_replace( 'KCD', 'Kubernetes Community Days', $event->title ),
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'   => 'lfe_community_event',
					'meta_input'   => array(
						'lfes_community_external_url' => $event->url,
						'lfes_community_bevy_import'  => true,
						'lfes_community_date_start'  => $dt_date_start->format( 'Y/m/d' ),
						'lfes_community_date_end'  => $dt_date_end->format( 'Y/m/d' ),
						'lfes_community_city'  => $event->venue_city,
						'lfes_community_virtual'  => $virtual,
					),
				);

				if ( ! $virtual ) {
					$matches = array();
					preg_match( '/\(([^)]+)\)/', $event->chapter_location, $matches );
					if ( 1 < count( $matches ) ) {
						$country_term = get_term_by( 'slug', strtolower( $matches[1] ), 'lfevent-country' );
						if ( $country_term ) {
							$my_post['tax_input'] = array( 'lfevent-country' => $country_term->term_id );
						}
					}
				}
				wp_insert_post( $my_post );
			}
		}

	}
}

/**
 * Returns an array of all descendents of $post_id.
 * Recursive function.
 *
 * @param int $post_id parent post.
 */
function get_kids( $post_id ) {
	global $wpdb;

	$kid_ids = array();

	$kid_posts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM $wpdb->posts
			WHERE post_parent = %d
			AND post_status <> 'trash'",
			$post_id
		)
	);

	if ( ! $kid_posts ) {
		return array();
	}

	foreach ( $kid_posts as $kid ) {
		$kid_ids[] = $kid->ID;
		$kid_ids   = array_merge( $kid_ids, get_kids( $kid->ID ) );
	}

	return $kid_ids;
}
