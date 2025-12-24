<?php
/**
 * Nisson Therapy Theme Functions
 *
 * @package NissonTherapy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup
 */
function nisson_therapy_setup() {
	// Add theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'menus' );
	add_theme_support( 'align-wide' );

	// Register navigation menus
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'nisson-therapy' ),
			'footer'  => esc_html__( 'Footer Menu', 'nisson-therapy' ),
		)
	);
}
add_action( 'after_setup_theme', 'nisson_therapy_setup' );

/**
 * Enqueue theme styles and scripts
 */
function nisson_therapy_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	// Main stylesheet
	wp_enqueue_style(
		'nisson-therapy-style',
		get_stylesheet_uri(),
		array(),
		$theme_version
	);

	// Theme CSS
	wp_enqueue_style(
		'nisson-therapy-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$theme_version
	);

	// Theme JavaScript
	wp_enqueue_script(
		'nisson-therapy-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		$theme_version,
		true
	);

	// Parallax script for hero section
	wp_enqueue_script(
		'nisson-therapy-parallax',
		get_template_directory_uri() . '/assets/js/parallax.js',
		array(),
		$theme_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'nisson_therapy_scripts' );

/**
 * Enqueue block editor styles
 */
function nisson_therapy_editor_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );
	
	// Editor-specific styles
	wp_enqueue_style(
		'nisson-therapy-editor',
		get_template_directory_uri() . '/assets/css/editor.css',
		array(),
		$theme_version
	);
	
	// Hero block styles in editor (so preview matches frontend)
	wp_enqueue_style(
		'nisson-therapy-hero-editor',
		get_template_directory_uri() . '/blocks/hero/hero.css',
		array(),
		$theme_version
	);
	
	// Main theme styles in editor (for consistent preview)
	wp_enqueue_style(
		'nisson-therapy-main-editor',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$theme_version
	);
}
add_action( 'enqueue_block_editor_assets', 'nisson_therapy_editor_styles' );

/**
 * Register custom block category for theme blocks
 */
function nisson_therapy_block_category( $categories, $editor_context ) {
	if ( ! empty( $editor_context->post ) ) {
		array_unshift(
			$categories,
			array(
				'slug'  => 'nisson-therapy',
				'title' => __( 'Nisson Therapy Blocks', 'nisson-therapy' ),
				'icon'  => null,
			)
		);
	}
	return $categories;
}
add_filter( 'block_categories_all', 'nisson_therapy_block_category', 10, 2 );

/**
 * Register ACF Blocks
 */
function nisson_therapy_register_acf_blocks() {
	// Check if ACF is active
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	// Hero Block
	acf_register_block_type(
		array(
			'name'            => 'nisson-hero',
			'title'           => __( '🎯 Hero Section', 'nisson-therapy' ),
			'description'     => __( 'Hero section with parallax background image. Perfect for landing pages.', 'nisson-therapy' ),
			'render_template' => get_template_directory() . '/blocks/hero/hero.php',
			'category'        => 'nisson-therapy',
			'icon'            => array(
				'background' => '#60a5fa',
				'foreground' => '#ffffff',
				'src'        => 'cover-image',
			),
			'keywords'        => array( 'hero', 'banner', 'parallax', 'landing', 'nisson' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/hero/hero.css',
			'mode'            => 'preview', // Show preview that matches frontend
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'subheadline'      => 'Telehealth for New York, New Jersey, Florida, and Oregon',
						'headline'         => 'Time to See Yourself In A Different',
						'highlighted_text' => 'Light?',
						'button_text'      => 'Learn more',
						'button_link'      => '#',
						'enable_parallax'  => true,
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'nisson_therapy_register_acf_blocks' );

/**
 * Add ACF Options Page for Header Settings
 */
function nisson_therapy_acf_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'Theme Settings', 'nisson-therapy' ),
				'menu_title' => __( 'Theme Settings', 'nisson-therapy' ),
				'menu_slug'  => 'theme-settings',
				'capability' => 'edit_posts',
			)
		);

		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'Header Settings', 'nisson-therapy' ),
				'menu_title'  => __( 'Header', 'nisson-therapy' ),
				'parent_slug' => 'theme-settings',
			)
		);
	}
}
add_action( 'acf/init', 'nisson_therapy_acf_options_page' );

/**
 * Load ACF field groups from PHP
 */
require_once get_template_directory() . '/inc/acf-fields.php';

/**
 * Disable ACF JSON sync - we're using PHP registration only
 */
function nisson_therapy_disable_acf_json() {
	return false;
}
add_filter( 'acf/settings/save_json', 'nisson_therapy_disable_acf_json' );
add_filter( 'acf/settings/load_json', '__return_false' );

