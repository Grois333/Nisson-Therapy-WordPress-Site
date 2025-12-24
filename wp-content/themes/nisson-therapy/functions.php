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
 * Only shows if there are issues
 */
function nisson_therapy_admin_notice() {
	// Only show on admin pages
	if ( ! is_admin() ) {
		return;
	}

	// Check if user has dismissed the notice
	$dismissed = get_user_meta( get_current_user_id(), 'nisson_therapy_notice_dismissed', true );
	if ( $dismissed ) {
		return;
	}

	$notices = array();
	$has_errors = false;

	// Check 1: Is ACF Pro installed?
	if ( ! class_exists( 'ACF' ) ) {
		$has_errors = true;
		$notices[] = array(
			'type' => 'error',
			'message' => 'ACF (Advanced Custom Fields) plugin is NOT installed or activated. Custom blocks require ACF Pro.',
		);
	} else {
		// Check if it's actually the Pro version
		if ( ! defined( 'ACF_PRO' ) || ! ACF_PRO ) {
			$has_errors = true;
			$notices[] = array(
				'type' => 'warning',
				'message' => '⚠ ACF_PRO constant not set. This might be the free version, which does not support blocks.',
			);
		}
	}

	// Check 2: Is ACF Pro (not just free version)?
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		$has_errors = true;
		$notices[] = array(
			'type' => 'error',
			'message' => 'ACF PRO is required for blocks. The free version does not support blocks. Please install ACF Pro.',
		);
	}

	// Check 3: Is the theme active?
	$current_theme = wp_get_theme();
	if ( $current_theme->get( 'Name' ) !== 'Nisson Therapy' ) {
		$has_errors = true;
		$notices[] = array(
			'type' => 'warning',
			'message' => 'Current theme is: ' . $current_theme->get( 'Name' ) . '. Nisson Therapy theme must be activated for blocks to work.',
		);
	}

	// Check 4: Is block registered?
	if ( function_exists( 'acf_has_block_type' ) ) {
		$block_name = 'acf/nt-hero-section';
		$block_registered = acf_has_block_type( $block_name );
		
		if ( ! $block_registered ) {
			$has_errors = true;
			$notices[] = array(
				'type' => 'error',
				'message' => '✗ Hero block is NOT registered. Block name: ' . $block_name,
			);
		}
	}

	// Check 5: Does template file exist?
	$template_file = get_template_directory() . '/blocks/hero/hero.php';
	if ( ! file_exists( $template_file ) ) {
		$has_errors = true;
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
		
		if ( ! $hero_group_found ) {
			$has_errors = true;
			$notices[] = array(
				'type' => 'error',
				'message' => '✗ Hero block field group is NOT registered. Found ' . count( $field_groups ) . ' field groups total.',
			);
		}
	}

	// Check 7: ACF Plugin path and assets
	$acf_path_found = false;
	$acf_actual_path = '';
	
	if ( defined( 'ACF_PATH' ) ) {
		$acf_path = ACF_PATH;
		$notices[] = array(
			'type' => 'info',
			'message' => 'ACF_PATH constant: ' . $acf_path,
		);
	} else {
		// Try to find ACF Pro manually
		$possible_paths = array(
			WP_PLUGIN_DIR . '/advanced-custom-fields-pro/',
			WP_PLUGIN_DIR . '/acf-pro/',
			WP_PLUGIN_DIR . '/advanced-custom-fields/',
		);
		
		foreach ( $possible_paths as $path ) {
			if ( file_exists( $path . 'acf.php' ) ) {
				$acf_path = $path;
				$notices[] = array(
					'type' => 'info',
					'message' => 'Found ACF at: ' . str_replace( ABSPATH, '', $path ),
				);
				break;
			}
		}
	}

	if ( isset( $acf_path ) ) {
		// Check multiple possible asset locations
		$asset_paths = array(
			'assets/build/js/pro/acf-pro-blocks.min.js',
			'assets/js/pro/acf-pro-blocks.min.js',
			'pro/assets/js/acf-pro-blocks.min.js',
			'assets/js/acf-pro-blocks.min.js',
		);

		$asset_found = false;
		$found_path = '';

		foreach ( $asset_paths as $asset_path ) {
			$full_path = $acf_path . $asset_path;
			if ( file_exists( $full_path ) ) {
				$asset_found = true;
				$found_path = $asset_path;
				break;
			}
		}

		if ( ! $asset_found ) {
			$has_errors = true;
			$notices[] = array(
				'type' => 'error',
				'message' => '✗ ACF Pro JavaScript assets NOT found. Checked paths: ' . implode( ', ', $asset_paths ),
			);
		}
	} else {
		$has_errors = true;
		$notices[] = array(
			'type' => 'error',
			'message' => '✗ Cannot locate ACF Pro plugin directory.',
		);
	}

	// Only show notice if there are errors
	if ( ! $has_errors || empty( $notices ) ) {
		return;
	}

	// Display all notices
	?>
	<div class="notice notice-warning" style="padding: 15px; margin: 20px 0; position: relative;">
		<button type="button" class="notice-dismiss" onclick="this.parentElement.style.display='none'; fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'action=nisson_therapy_dismiss_notice&nonce=<?php echo wp_create_nonce( 'dismiss_notice' ); ?>'})" style="position: absolute; top: 10px; right: 10px; border: none; background: transparent; cursor: pointer; font-size: 16px;">×</button>
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
	</div>
	<?php
}
add_action( 'admin_notices', 'nisson_therapy_admin_notice' );

/**
 * Handle notice dismissal via AJAX
 */
function nisson_therapy_dismiss_notice() {
	check_ajax_referer( 'dismiss_notice', 'nonce' );
	update_user_meta( get_current_user_id(), 'nisson_therapy_notice_dismissed', true );
	wp_send_json_success();
}
add_action( 'wp_ajax_nisson_therapy_dismiss_notice', 'nisson_therapy_dismiss_notice' );

