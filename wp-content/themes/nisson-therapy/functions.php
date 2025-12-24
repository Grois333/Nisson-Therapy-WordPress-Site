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
 * Enqueue block editor styles and scripts
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

	// Block editor JavaScript for debugging
	wp_enqueue_script(
		'nisson-therapy-block-editor',
		get_template_directory_uri() . '/assets/js/block-editor.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		$theme_version,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'nisson_therapy_editor_styles' );

/**
 * Register custom block category for theme blocks
 * Must run early to ensure category exists before blocks register
 */
function nisson_therapy_block_category( $categories, $editor_context ) {
	// Check if category already exists
	$category_exists = false;
	foreach ( $categories as $category ) {
		if ( isset( $category['slug'] ) && $category['slug'] === 'nisson-therapy' ) {
			$category_exists = true;
			break;
		}
	}

	// Add category if it doesn't exist
	if ( ! $category_exists ) {
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
 * Must run after ACF is fully loaded
 */
function nisson_therapy_register_acf_blocks() {
	// Check if ACF Pro is active
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		// Always log this error
		error_log( 'NISSON THERAPY ERROR: ACF Pro is not active. Blocks cannot be registered. Please install and activate ACF Pro plugin.' );
		return;
	}

	// Verify template file exists
	$template_file = get_template_directory() . '/blocks/hero/hero.php';
	if ( ! file_exists( $template_file ) ) {
		error_log( 'NISSON THERAPY ERROR: Hero block template file not found: ' . $template_file );
		return;
	}

	// Hero Block - using completely new unique name
	$block_args = array(
		'name'            => 'nt-hero-section',
		'title'           => __( '🎯 Hero Section', 'nisson-therapy' ),
		'description'     => __( 'Hero section with parallax background image. Perfect for landing pages.', 'nisson-therapy' ),
		'render_template' => $template_file,
		'category'        => 'nisson-therapy',
		'icon'            => 'cover-image',
		'keywords'        => array( 'hero', 'banner', 'parallax', 'landing', 'nisson', 'therapy' ),
		'supports'        => array(
			'align' => false,
			'anchor' => true,
		),
		'enqueue_style'   => get_template_directory_uri() . '/blocks/hero/hero.css',
		'mode'            => 'preview',
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
	);

	// Register the block
	$block_result = acf_register_block_type( $block_args );

	// Also register with WordPress block registry directly (backup method)
	if ( function_exists( 'register_block_type' ) && $block_result ) {
		// This ensures WordPress also knows about the block
		register_block_type(
			'acf/nt-hero-section',
			array(
				'render_callback' => 'acf_render_block_callback',
				'attributes'     => isset( $block_result['attributes'] ) ? $block_result['attributes'] : array(),
			)
		);
	}

	// Always log registration result
	if ( $block_result ) {
		error_log( 'NISSON THERAPY SUCCESS: Hero block registered as acf/nt-hero-section' );
	} else {
		error_log( 'NISSON THERAPY ERROR: Failed to register Hero block. Check ACF Pro is active.' );
	}
}
add_action( 'acf/init', 'nisson_therapy_register_acf_blocks', 20 );

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

/**
 * Admin notice to verify ACF Pro and block registration
 */
function nisson_therapy_admin_notice() {
	// Only show on admin pages
	if ( ! is_admin() ) {
		return;
	}

	$notices = array();

	// Check 1: Is ACF Pro installed?
	if ( ! class_exists( 'ACF' ) ) {
		$notices[] = array(
			'type' => 'error',
			'message' => 'ACF (Advanced Custom Fields) plugin is NOT installed or activated. Custom blocks require ACF Pro.',
		);
	} else {
		$notices[] = array(
			'type' => 'success',
			'message' => '✓ ACF plugin is installed and active.',
		);
	}

	// Check 2: Is ACF Pro (not just free version)?
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		$notices[] = array(
			'type' => 'error',
			'message' => 'ACF PRO is required for blocks. The free version does not support blocks. Please install ACF Pro.',
		);
	} else {
		$notices[] = array(
			'type' => 'success',
			'message' => '✓ ACF Pro block functions are available.',
		);
	}

	// Check 3: Is the theme active?
	$current_theme = wp_get_theme();
	if ( $current_theme->get( 'Name' ) !== 'Nisson Therapy' ) {
		$notices[] = array(
			'type' => 'warning',
			'message' => 'Current theme is: ' . $current_theme->get( 'Name' ) . '. Nisson Therapy theme must be activated for blocks to work.',
		);
	} else {
		$notices[] = array(
			'type' => 'success',
			'message' => '✓ Nisson Therapy theme is active.',
		);
	}

	// Check 4: Is block registered?
	if ( function_exists( 'acf_has_block_type' ) ) {
		$block_name = 'acf/nt-hero-section';
		$block_registered = acf_has_block_type( $block_name );
		
		if ( $block_registered ) {
			$notices[] = array(
				'type' => 'success',
				'message' => '✓ Hero block is registered: ' . $block_name,
			);
		} else {
			$notices[] = array(
				'type' => 'error',
				'message' => '✗ Hero block is NOT registered. Block name: ' . $block_name,
			);
		}
	} else {
		$notices[] = array(
			'type' => 'warning',
			'message' => 'Cannot check block registration - acf_has_block_type() function not available.',
		);
	}

	// Check 5: Does template file exist?
	$template_file = get_template_directory() . '/blocks/hero/hero.php';
	if ( file_exists( $template_file ) ) {
		$notices[] = array(
			'type' => 'success',
			'message' => '✓ Hero block template file exists.',
		);
	} else {
		$notices[] = array(
			'type' => 'error',
			'message' => '✗ Hero block template file NOT found: ' . $template_file,
		);
	}

	// Check 6: Are field groups registered?
	if ( function_exists( 'acf_get_local_field_groups' ) ) {
		$field_groups = acf_get_local_field_groups();
		$hero_group_found = false;
		foreach ( $field_groups as $group ) {
			if ( isset( $group['key'] ) && $group['key'] === 'group_nt_hero_section_v2' ) {
				$hero_group_found = true;
				break;
			}
		}
		
		if ( $hero_group_found ) {
			$notices[] = array(
				'type' => 'success',
				'message' => '✓ Hero block field group is registered.',
			);
		} else {
			$notices[] = array(
				'type' => 'error',
				'message' => '✗ Hero block field group is NOT registered. Found ' . count( $field_groups ) . ' field groups total.',
			);
		}
	}

	// Display all notices
	?>
	<div class="notice notice-info" style="padding: 15px; margin: 20px 0;">
		<h2 style="margin-top: 0;">🔍 Nisson Therapy Block Diagnostics</h2>
		<ul style="list-style: none; padding-left: 0;">
			<?php foreach ( $notices as $notice ) : 
				$icon = '';
				$color = '';
				switch ( $notice['type'] ) {
					case 'success':
						$icon = '✓';
						$color = '#46b450';
						break;
					case 'error':
						$icon = '✗';
						$color = '#dc3232';
						break;
					case 'warning':
						$icon = '⚠';
						$color = '#ffb900';
						break;
					default:
						$icon = 'ℹ';
						$color = '#2271b1';
				}
				?>
				<li style="padding: 8px 0; border-bottom: 1px solid #eee;">
					<strong style="color: <?php echo esc_attr( $color ); ?>;"><?php echo esc_html( $icon ); ?></strong>
					<?php echo esc_html( $notice['message'] ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<p style="margin-bottom: 0;">
			<strong>Block Name:</strong> <code>acf/nt-hero-section</code><br>
			<strong>Category:</strong> <code>nisson-therapy</code><br>
			<strong>Template:</strong> <code><?php echo esc_html( str_replace( ABSPATH, '', $template_file ) ); ?></code>
		</p>
		<p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
			<strong>🔍 Troubleshooting:</strong><br>
			1. Open a page/post in the editor<br>
			2. Press <code>F12</code> to open browser console<br>
			3. Look for messages starting with "Nisson Therapy:"<br>
			4. Try searching for "Hero" or "nt-hero-section" in the block inserter<br>
			5. Clear browser cache and reload the editor<br>
			6. Check if the block appears when you type "/hero" in the editor
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'nisson_therapy_admin_notice' );

