<?php

/**
 * Register custom Custom Navigation Menus.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_nav_menus
 */
function mins_register_menus() {

	register_nav_menus(
		array(
			'footer'	=> esc_html__( 'Footer', 'mins' )
		)
	);

}
add_action( 'after_setup_theme', 'mins_register_menus' );

/**
 * Add image size for hero image
 *
 * @link https://codex.wordpress.org/Function_Reference/add_image_size
 */
function mins_add_image_size() {

	add_image_size( 'hero', 2400, 1320, array( 'center', 'center' ) );

}
add_action( 'after_setup_theme', 'mins_add_image_size' );

/**
 * Remove primer navigation and add mins navigation
 */
function mins_navigation() {
	wp_dequeue_script( 'primer-navigation' );
	wp_enqueue_script( 'mins-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array( 'jquery' ), '20120206', true );
}
add_action( 'wp_print_scripts', 'mins_navigation', 100 );

/**
 * Add mobile menu to header
 *
 * @link https://codex.wordpress.org/Function_Reference/get_template_part
 */
function mins_add_mobile_menu() {
	get_template_part( 'templates/parts/mobile-menu' );
}
add_action( 'primer_header', 'mins_add_mobile_menu', 0 );

/**
 * Update custom header arguments
 *
 * @param $args
 * @return mixed
 */
function primer_update_custom_header_args( $args ) {
	$args['width'] = 2400;
	$args['height'] = 1320;

	return $args;
}
add_filter( 'primer_custom_header_args', 'primer_update_custom_header_args' );

/**
 * Register hero sidebar
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function mins_register_hero_sidebar() {
	register_sidebar( array(
		'name'          => __( 'Hero', 'mins' ),
		'id'            => 'hero',
		'description'   => __( 'The hero appears in the hero widget area on the front page', 'mins' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mins_register_hero_sidebar' );

/**
 * Get header image with image size
 *
 * @return false|string
 */
function mins_get_header_image() {
	$image_size = (int) get_theme_mod( 'full_width' ) === 1 ? 'hero-2x' : 'hero';
	$custom_header = get_custom_header();
	
	if ( ! empty( $custom_header->attachment_id ) ) {
		$image = wp_get_attachment_image_url( $custom_header->attachment_id, $image_size );
		if ( getimagesize( $image ) ) {
			return $image;
		}
	}
	$header_image = get_header_image();

	return $header_image;
}

/**
 * Remove sidebar
 *
 */
function mins_remove_widgets() {

	unregister_sidebar( 'sidebar-1' );
	unregister_sidebar( 'sidebar-2' );

}

add_action( 'widgets_init', 'mins_remove_widgets', 11 );

function mins_update_fonts() {
	return array(
		'Roboto',
		'Abril Fatface',
		'Raleway'
	);
}
add_filter( 'primer_fonts', 'mins_update_fonts' );

function mins_custom_logo() {
	return array(
		'height'      => 86,
		'width'       => 400,
		'flex-height' => false,
		'flex-width'  => false
	);
}
add_filter( 'primer_custom_logo_args', 'mins_custom_logo' );

/**
 * Update font types
 *
 * @return array
 */
function mins_update_font_types() {
    return array(
        array(
            'name'    => 'primary_font',
            'label'   => __( 'Primary Font', 'primer' ),
            'default' => 'Roboto',
            'css'     => array(
	            '.comment-list .comment-author, .comment-list .comment-metadata, #respond, .featured-content .entry-title, .featured-content .read-more, button, a.button, input, select, textarea, legend, .site-title, .site-description, .widget-title, .entry-meta, .event-meta, .sermon-meta, .location-meta, .person-meta, .post-format, article.format-link .entry-title, label, .more-link, .entry-footer, .widget p, .widget ul, .widget ol, h1, h2' => array( 'font-family' => '"%s", sans-serif' )
	        ),
            'weight'   => array(
                100, 300, 700
            )
        ),
        array(
            'name'    => 'secondary_font',
            'label'   => __( 'Secondary Font', 'primer' ),
            'default' => 'Roboto',
            'css'     => array(
				'body, input, select, textarea, .hero-widget div.textwidget, .widget, .widget p, .widget ul, .widget ol, .entry-content p, .entry-summary p, h3, h4, h5, h6' => array( 'font-family' => '"%s", sans-serif' )
            )
        ),
    );
}
add_action( 'primer_font_types', 'mins_update_font_types' );

function escapade_font_weight( $weights, $font ) {

    return ( 'Roboto' === $font ) ? [ 700, 300, 100 ] : $weights;

}
add_filter( 'primer_font_weights', 'escapade_font_weight', 10, 2 );

/**
 * Add Social links to primary navigation area.
 *
 * @action primer_after_header
 */
function mins_add_social_to_header(){

	if ( has_nav_menu( 'social' ) ) :

		get_template_part( 'templates/parts/social-menu' );

	endif;

}
add_action( 'primer_after_header', 'mins_add_social_to_header', 30 );

/**
 * Remove customizer features added by the parent theme that are not applicable to this theme
 * 
 * @action after_setup_theme
 */
function mins_remove_customizer_features($wp_customize){

	$wp_customize->remove_section('layout');

}
add_action( 'customize_register', 'mins_remove_customizer_features', 30 );

/**
 * Update colors
 * 
 * @action primer_colors
 */
function mins_colors() {
	return array(
		array(
			'name'    => 'background_color',
			'default' => '#f4f4f4',
		),
		array(
			'name'    => 'link_color',
			'label'   => __( 'Link Color', 'primer' ),
			'default' => '#62b6cb',
			'css'     => array(
				'a, a:visited, .entry-footer a, .sticky .entry-title a:before, .footer-widget-area .footer-widget .widget a' => array(
					'color' => '%1$s',
				)
			),
			'rgba_css' => array(
				'a:hover, a:visited:hover, .entry-footer a:hover' => array(
					'color' => 'rgba(%1$s, 0.75)',
				)
			),
		),
		array(
			'name'    => 'main_text_color',
			'label'   => __( 'Text Color', 'primer' ),
			'default' => '#181818',
			'css'     => array(
				'body, .site-content, .site-content h1, .site-content h2, .site-content h3, .site-content h4, .site-content h5, .site-content h6, .site-content p, .site-content blockquote, legend, .footer-widget-area .footer-widget .widget-title, .footer-widget-area .footer-widget .widget, h1, h2, h3, h4, h5, h6, blockquote, blockquote p, blockquote cite' => array(
					'color' => '%1$s',
				),
				'.footer-widget-area .footer-widget .widget li a' => array(
					'border-color' => '%1$s',
				),
				'.hero-area .hero-widget h2.widget-title:after, .hentry .page-title:after, .hentry .entry-title:after, .footer-widget-area .footer-widget .widget-title:after' => array(
					'background-color' => '%1$s',
				),
			),
		),
		array(
			'name'    => 'button_text_color',
			'label'   => __( 'Call to Action Color', 'primer' ),
			'default' => '#181818',
			'css'     => array(
				'button, a.button, a.button:visited, input[type="button"], input[type="reset"], input[type="submit"], a.button:after, .footer-widget-area .footer-widget .widget a.button' => array(
					'color' => '%1$s',
				)
			),
		),
		array(
			'name'    => 'w_bg',
			'label'   => __( 'Widget Background', 'primer' ),
			'default' => '#fff',
			'css'     => array(
				'.site-footer' => array(
					'background-color' => '%1$s',
				),
			),
		),
	);
}
add_action( 'primer_colors', 'mins_colors', 30 );

define( 'NO_HEADER_TEXT', true );

function mins_color_schemes() {
	return array(
		'seafoam' => array(
			'label'  => esc_html__( 'Seafoam', 'primer' ),
			'colors' => array(
				'background_color'        => '#c9ede3',
				'link_color'              => '#c96050',
				'main_text_color'         => '#013f39',
				'w_bg'                    => '#b5d6cd',
				'button_text_color'       => '#013f39'
			),
		),
		'wheat' => array(
			'label'  => esc_html__( 'Wheat', 'primer' ),
			'colors' => array(
				'background_color'        => '#eae9dc',
				'link_color'              => '#ff4f4f',
				'main_text_color'         => '#5e2c00',
				'w_bg'                    => '#d3d2c7',
				'button_text_color'       => '#5e2c00'
			),
		),
		'melancholy' => array(
			'label'  => esc_html__( 'Wheat', 'primer' ),
			'colors' => array(
				'background_color'        => '#b1b9bf',
				'link_color'              => '#4e5972',
				'main_text_color'         => '#eae0c7',
				'w_bg'                    => '#a0a7ac',
				'button_text_color'       => '#4e5972'
			),
		),
		'foliage' => array(
			'label'  => esc_html__( 'Foliage', 'primer' ),
			'colors' => array(
				'background_color'        => '#a7caa9',
				'link_color'              => '#fff1c6',
				'main_text_color'         => '#4c684e',
				'w_bg'                    => '#97b698',
				'button_text_color'       => '#d15e5e'
			),
		),
		'ocean' => array(
			'label'  => esc_html__( 'Deep Sea', 'primer' ),
			'colors' => array(
				'background_color'        => '#051a5b',
				'link_color'              => '#fff1c6',
				'main_text_color'         => '#75ceda',
				'w_bg'                    => '#051752',
				'button_text_color'       => '#f6faef'
			),
		),
		'negative' => array(
			'label'  => esc_html__( 'Negative', 'primer' ),
			'colors' => array(
				'background_color'        => '#181818',
				'link_color'              => '#ccc',
				'main_text_color'         => '#fff',
				'w_bg'                    => '#212121',
				'button_text_color'       => '#fff'
			),
		),
		'immke' => array(
			'label'  => esc_html__( 'Immke', 'primer' ),
			'colors' => array(
				'background_color'        => '#010e68',
				'link_color'              => '#fced4b',
				'main_text_color'         => '#f700ef',
				'w_bg'                    => '#43039e',
				'button_text_color'       => '#fced4b'
			),
		),
	);
}
add_filter( 'primer_color_schemes', 'mins_color_schemes' );

function mins_add_default_header_image($array) {
	$array['default-image'] = get_stylesheet_directory_uri() . '/assets/images/default-header.jpg';
	
	return $array;
}
add_filter( 'primer_custom_header_args', 'mins_add_default_header_image', 20 );


/**
 * Move navigation
 * 
 * @action primer_after_header
 */
function mins_move_nav_markup() {

	remove_action( 'primer_after_header', 'primer_add_primary_navigation', 20);

	add_action( 'primer_header', 'primer_add_primary_navigation', 21);
	 
}
add_action( 'init', 'mins_move_nav_markup', 30 );

/**
 * Display hero in the header
 *
 * @action primer_after_header
 */
function mins_add_hero(){
	if( is_front_page() ) {
		get_template_part( 'templates/parts/hero' );
	}
}
add_action( 'primer_after_header', 'mins_add_hero', 30 );

function mins_add_search_menu ( $items, $args ) {

	if( 'primary' === $args -> theme_location ) {
		$items .= '<li class="menu-item menu-item-search">';
		$items .= '<a href="#" class="search-toggle"><span class="genericon genericon-search"></span></a>';
		$items .= get_search_form(false);
		$items .= '</li>';
	}
return $items;
}
add_filter('wp_nav_menu_items','mins_add_search_menu',10,2);