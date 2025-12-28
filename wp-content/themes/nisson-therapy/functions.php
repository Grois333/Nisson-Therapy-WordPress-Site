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
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
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
 * Add CSS class to menu item link (not the li)
 * This allows adding classes like "btn btn-primary" to menu items in Appearance > Menus
 * The classes will be applied to the <a> tag, not the <li> tag
 */
function nisson_therapy_nav_menu_css_class( $classes, $item, $args ) {
	// Remove btn classes from li - we'll add them to the link instead
	$classes = array_filter( $classes, function( $class ) {
		return strpos( $class, 'btn' ) === false;
	} );
	return $classes;
}
add_filter( 'nav_menu_css_class', 'nisson_therapy_nav_menu_css_class', 10, 3 );

/**
 * Add CSS class to menu item link
 */
function nisson_therapy_nav_menu_link_attributes( $atts, $item, $args ) {
	// Check if menu item has specific classes
	if ( ! empty( $item->classes ) ) {
		$classes = array_filter( $item->classes );
		if ( ! empty( $classes ) ) {
			// Find btn classes and add them to the link
			$btn_classes = array();
			foreach ( $classes as $class ) {
				if ( strpos( $class, 'btn' ) !== false ) {
					$btn_classes[] = $class;
				}
			}
			if ( ! empty( $btn_classes ) ) {
				$existing_class = isset( $atts['class'] ) ? $atts['class'] : '';
				$atts['class'] = trim( $existing_class . ' ' . implode( ' ', $btn_classes ) );
			}
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'nisson_therapy_nav_menu_link_attributes', 10, 3 );

/**
 * Allow SVG uploads in media library
 */
function nisson_therapy_allow_svg_upload( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'nisson_therapy_allow_svg_upload' );

/**
 * Make WordPress recognize SVG as image type
 */
function nisson_therapy_svg_is_image( $result, $path ) {
	if ( $result !== null ) {
		return $result;
	}
	$ext = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
	if ( $ext === 'svg' ) {
		return true;
	}
	return $result;
}
add_filter( 'file_is_displayable_image', 'nisson_therapy_svg_is_image', 10, 2 );

/**
 * Fix SVG attachment metadata
 */
function nisson_therapy_fix_svg_metadata( $metadata, $attachment_id ) {
	if ( get_post_mime_type( $attachment_id ) === 'image/svg+xml' ) {
		$svg_path = get_attached_file( $attachment_id );
		if ( $svg_path && file_exists( $svg_path ) ) {
			$svg_content = @file_get_contents( $svg_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( $svg_content && preg_match( '/<svg[^>]*width=["\']([^"\']+)["\']/', $svg_content, $width_match ) && preg_match( '/<svg[^>]*height=["\']([^"\']+)["\']/', $svg_content, $height_match ) ) {
				$width = (int) $width_match[1];
				$height = (int) $height_match[1];
				$metadata['width'] = $width;
				$metadata['height'] = $height;
			} else {
				// Default SVG dimensions if not specified
				$metadata['width'] = 512;
				$metadata['height'] = 512;
			}
		}
	}
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'nisson_therapy_fix_svg_metadata', 10, 2 );

/**
 * Fix SVG display in media library
 */
function nisson_therapy_fix_svg_thumbnails() {
	echo '<style>
		.attachment-266x266, .thumbnail img {
			width: 100% !important;
			height: auto !important;
		}
	</style>';
}
add_action( 'admin_head', 'nisson_therapy_fix_svg_thumbnails' );

/**
 * Ensure SVG logos display correctly
 */
function nisson_therapy_custom_logo_output( $html, $blog_id = 0 ) {
	// Get the logo ID
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( ! $custom_logo_id ) {
		return $html;
	}

	// Get the attachment file URL
	$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
	if ( ! $logo_url ) {
		return $html;
	}

	// Check if it's an SVG by file extension
	$file_ext = strtolower( pathinfo( $logo_url, PATHINFO_EXTENSION ) );
	if ( $file_ext === 'svg' ) {
		// For SVG, output directly using the URL (WordPress will handle it as img src)
		// But we can also try to inline it for better control
		$logo_path = get_attached_file( $custom_logo_id );
		if ( $logo_path && file_exists( $logo_path ) ) {
			$svg_content = file_get_contents( $logo_path );
			if ( $svg_content ) {
				// Clean up SVG content
				$svg_content = preg_replace( '/<\?xml[^>]*\?>/i', '', $svg_content );
				$svg_content = preg_replace( '/<!DOCTYPE[^>]*>/i', '', $svg_content );
				$svg_content = trim( $svg_content );
				
				// Add class to SVG for styling
				if ( strpos( $svg_content, 'class=' ) === false ) {
					$svg_content = preg_replace( '/<svg/i', '<svg class="custom-logo-svg"', $svg_content );
				}
				
				// Create the logo link
				$home_url = esc_url( home_url( '/' ) );
				$html = sprintf(
					'<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
					$home_url,
					$svg_content
				);
			}
		}
	}

	return $html;
}
add_filter( 'get_custom_logo', 'nisson_therapy_custom_logo_output', 10, 2 );

/**
 * Enqueue theme styles and scripts
 */
function nisson_therapy_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	// Enqueue Google Fonts (Poppins)
	wp_enqueue_style(
		'nisson-therapy-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	// Main stylesheet
	wp_enqueue_style(
		'nisson-therapy-style',
		get_stylesheet_uri(),
		array( 'nisson-therapy-fonts' ),
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

	// Intro block styles
	wp_enqueue_style(
		'nisson-therapy-intro',
		get_template_directory_uri() . '/blocks/intro/intro.css',
		array(),
		$theme_version
	);

	// Services block styles
	wp_enqueue_style(
		'nisson-therapy-services',
		get_template_directory_uri() . '/blocks/services/services.css',
		array(),
		$theme_version
	);

	// FAQ block styles and scripts
	wp_enqueue_style(
		'nisson-therapy-faq',
		get_template_directory_uri() . '/blocks/faq/faq.css',
		array(),
		$theme_version
	);
	wp_enqueue_script(
		'nisson-therapy-faq',
		get_template_directory_uri() . '/blocks/faq/faq.js',
		array(),
		$theme_version,
		true
	);

	// Services block JavaScript
	wp_enqueue_script(
		'nisson-therapy-services',
		get_template_directory_uri() . '/blocks/services/services.js',
		array(),
		$theme_version,
		true
	);

	// CTA block styles
	wp_enqueue_style(
		'nisson-therapy-cta',
		get_template_directory_uri() . '/blocks/cta/cta.css',
		array(),
		$theme_version
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

	// Intro block styles in editor
	wp_enqueue_style(
		'nisson-therapy-intro-editor',
		get_template_directory_uri() . '/blocks/intro/intro.css',
		array(),
		$theme_version
	);

	// Services block styles in editor
	wp_enqueue_style(
		'nisson-therapy-services-editor',
		get_template_directory_uri() . '/blocks/services/services.css',
		array(),
		$theme_version
	);

	// CTA block styles in editor
	wp_enqueue_style(
		'nisson-therapy-cta-editor',
		get_template_directory_uri() . '/blocks/cta/cta.css',
		array(),
		$theme_version
	);

	// About block styles in editor
	wp_enqueue_style(
		'nisson-therapy-about-editor',
		get_template_directory_uri() . '/blocks/about/about.css',
		array(),
		$theme_version
	);

	// Approach block styles in editor
	wp_enqueue_style(
		'nisson-therapy-approach-editor',
		get_template_directory_uri() . '/blocks/approach/approach.css',
		array(),
		$theme_version
	);

	// CTA Image block styles in editor
	wp_enqueue_style(
		'nisson-therapy-cta-image-editor',
		get_template_directory_uri() . '/blocks/cta-image/cta-image.css',
		array(),
		$theme_version
	);

	// Services Page block styles in editor
	wp_enqueue_style(
		'nisson-therapy-services-page-editor',
		get_template_directory_uri() . '/blocks/services-page/services-page.css',
		array(),
		$theme_version
	);

	// Services List block styles in editor
	wp_enqueue_style(
		'nisson-therapy-services-list-editor',
		get_template_directory_uri() . '/blocks/services-list/services-list.css',
		array(),
		$theme_version
	);

	// Services Content block styles in editor
	wp_enqueue_style(
		'nisson-therapy-services-content-editor',
		get_template_directory_uri() . '/blocks/services-content/services-content.css',
		array(),
		$theme_version
	);

	// FAQ block styles in editor
	wp_enqueue_style(
		'nisson-therapy-faq-editor',
		get_template_directory_uri() . '/blocks/faq/faq.css',
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
					'philosophy_line'  => 'Explore your inner world safely, with curiosity and compassion for all parts of you.',
					'button_link'      => array(
						'url'   => '#',
						'title' => 'Learn more',
					),
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

	// Intro Block
	$intro_template_file = get_template_directory() . '/blocks/intro/intro.php';
	if ( file_exists( $intro_template_file ) ) {
		$intro_block_args = array(
			'name'            => 'nt-intro-section',
			'title'           => __( '📝 Intro Section', 'nisson-therapy' ),
			'description'     => __( 'Introduction section with name, quote, image, and content in two columns.', 'nisson-therapy' ),
			'render_template' => $intro_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'admin-users',
			'keywords'        => array( 'intro', 'about', 'introduction', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/intro/intro.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'intro_name'    => 'Mary DiOrio, Therapist',
						'intro_quote'   => 'Seeing yourself differently is often the first step to living the life you truly want to live.',
						'intro_content' => '<p>You may be functioning on the outside, but inside it might feel noisy, tense, or fragmented.</p><p>Parts of you want relief, parts want control, parts want to shut things down, and other parts are just exhausted.</p>',
					),
				),
			),
		);

		$intro_block_result = acf_register_block_type( $intro_block_args );

		if ( function_exists( 'register_block_type' ) && $intro_block_result ) {
			register_block_type(
				'acf/nt-intro-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $intro_block_result['attributes'] ) ? $intro_block_result['attributes'] : array(),
				)
			);
		}

		if ( $intro_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: Intro block registered as acf/nt-intro-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register Intro block. Check ACF Pro is active.' );
		}
	}

	// Services Block
	$services_template_file = get_template_directory() . '/blocks/services/services.php';
	if ( file_exists( $services_template_file ) ) {
		$services_block_args = array(
			'name'            => 'nt-services-section',
			'title'           => __( '🎴 Services Section', 'nisson-therapy' ),
			'description'     => __( 'Services section with repeater cards that have scroll-based blur effects.', 'nisson-therapy' ),
			'render_template' => $services_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'grid-view',
			'keywords'        => array( 'services', 'cards', 'repeater', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/services/services.css',
			'enqueue_script'  => get_template_directory_uri() . '/blocks/services/services.js',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'services_cards' => array(
							array(
								'title'       => 'Learn to tame Anxiety',
								'description' => 'I also specialize in treating anxiety that often stems from difficult life transitions such as divorce, bereavement, sudden career changes, and many cumulative causes.',
							),
						),
					),
				),
			),
		);

		$services_block_result = acf_register_block_type( $services_block_args );

		if ( function_exists( 'register_block_type' ) && $services_block_result ) {
			register_block_type(
				'acf/nt-services-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $services_block_result['attributes'] ) ? $services_block_result['attributes'] : array(),
				)
			);
		}

		if ( $services_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: Services block registered as acf/nt-services-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register Services block. Check ACF Pro is active.' );
		}
	}

	// CTA Block
	$cta_template_file = get_template_directory() . '/blocks/cta/cta.php';
	if ( file_exists( $cta_template_file ) ) {
		$cta_block_args = array(
			'name'            => 'nt-cta-section',
			'title'           => __( '📞 CTA Section', 'nisson-therapy' ),
			'description'     => __( 'Call-to-action section with title, subtitle, description, and button.', 'nisson-therapy' ),
			'render_template' => $cta_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'megaphone',
			'keywords'        => array( 'cta', 'call to action', 'contact', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/cta/cta.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'cta_title'       => 'Schedule a Free Consultation',
						'cta_subtitle'    => 'A brief call to see if we are a good fit.',
						'cta_description' => 'All conversations are confidential and handled with the utmost care.',
					),
				),
			),
		);

		$cta_block_result = acf_register_block_type( $cta_block_args );

		if ( function_exists( 'register_block_type' ) && $cta_block_result ) {
			register_block_type(
				'acf/nt-cta-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $cta_block_result['attributes'] ) ? $cta_block_result['attributes'] : array(),
				)
			);
		}

		if ( $cta_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: CTA block registered as acf/nt-cta-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register CTA block. Check ACF Pro is active.' );
		}
	}

	// About Block
	$about_template_file = get_template_directory() . '/blocks/about/about.php';
	if ( file_exists( $about_template_file ) ) {
		$about_block_args = array(
			'name'            => 'nt-about-section',
			'title'           => __( '👤 About Section', 'nisson-therapy' ),
			'description'     => __( 'About section with top H1 title, main title, subtitle, WYSIWYG content, and circular image.', 'nisson-therapy' ),
			'render_template' => $about_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'admin-users',
			'keywords'        => array( 'about', 'bio', 'introduction', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/about/about.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'about_top_title' => 'Are You Ready for Change?',
						'about_title'     => 'About Mary DiOrio (She, Her, Her\'s)',
						'about_subtitle'  => 'Licensed Psychotherapist • Online Therapy in NY, NJ, OR & FL',
						'about_content'   => '<p><strong>I wasn\'t always a therapist.</strong></p><p>My first degree was a BA in Business and I worked for the City of Portland.</p>',
					),
				),
			),
		);

		$about_block_result = acf_register_block_type( $about_block_args );

		if ( function_exists( 'register_block_type' ) && $about_block_result ) {
			register_block_type(
				'acf/nt-about-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $about_block_result['attributes'] ) ? $about_block_result['attributes'] : array(),
				)
			);
		}

		if ( $about_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: About block registered as acf/nt-about-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register About block. Check ACF Pro is active.' );
		}
	}

	// Approach Block
	$approach_template_file = get_template_directory() . '/blocks/approach/approach.php';
	if ( file_exists( $approach_template_file ) ) {
		$approach_block_args = array(
			'name'            => 'nt-approach-section',
			'title'           => __( '🎯 Approach Section', 'nisson-therapy' ),
			'description'     => __( 'Approach section with dark blue top section and card grid below.', 'nisson-therapy' ),
			'render_template' => $approach_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'grid-view',
			'keywords'        => array( 'approach', 'cards', 'grid', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/approach/approach.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'approach_title'    => 'My Approach',
						'approach_subtitle' => 'Inside each of us are different "parts": protective, wounded, or reactive, each doing its best to help.',
						'approach_cards'    => array(
							array(
								'title'       => 'Identify and understand internal parts',
								'description' => 'We work together to recognize and understand the different parts within you.',
							),
						),
					),
				),
			),
		);

		$approach_block_result = acf_register_block_type( $approach_block_args );

		if ( function_exists( 'register_block_type' ) && $approach_block_result ) {
			register_block_type(
				'acf/nt-approach-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $approach_block_result['attributes'] ) ? $approach_block_result['attributes'] : array(),
				)
			);
		}

		if ( $approach_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: Approach block registered as acf/nt-approach-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register Approach block. Check ACF Pro is active.' );
		}
	}

	// CTA Image Block
	$cta_image_template_file = get_template_directory() . '/blocks/cta-image/cta-image.php';
	if ( file_exists( $cta_image_template_file ) ) {
		$cta_image_block_args = array(
			'name'            => 'nt-cta-image-section',
			'title'           => __( '🖼️ CTA with Image', 'nisson-therapy' ),
			'description'     => __( 'Call-to-action section with background image, title, and button.', 'nisson-therapy' ),
			'render_template' => $cta_image_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'format-image',
			'keywords'        => array( 'cta', 'call to action', 'image', 'background', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/cta-image/cta-image.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'cta_image_title' => 'Ready to see yourself in a different light?',
						'cta_image_button' => array(
							'url'   => '#',
							'title' => 'Schedule A Free 15 Minute Consultation',
						),
					),
				),
			),
		);

		$cta_image_block_result = acf_register_block_type( $cta_image_block_args );

		if ( function_exists( 'register_block_type' ) && $cta_image_block_result ) {
			register_block_type(
				'acf/nt-cta-image-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $cta_image_block_result['attributes'] ) ? $cta_image_block_result['attributes'] : array(),
				)
			);
		}

		if ( $cta_image_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: CTA Image block registered as acf/nt-cta-image-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register CTA Image block. Check ACF Pro is active.' );
		}
	}

	// Services Page Block
	$services_page_template_file = get_template_directory() . '/blocks/services-page/services-page.php';
	if ( file_exists( $services_page_template_file ) ) {
		$services_page_block_args = array(
			'name'            => 'nt-services-page-section',
			'title'           => __( '📄 Services Page', 'nisson-therapy' ),
			'description'     => __( 'Services page with H1 header and content section (WYSIWYG with bold text and bullet points).', 'nisson-therapy' ),
			'render_template' => $services_page_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'admin-page',
			'keywords'        => array( 'services', 'page', 'content', 'wysiwyg', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/services-page/services-page.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'services_top_title' => 'Learn to tame Anxiety',
						'services_content'   => '<h2>Learn to understand your emotions, soothe your nervous system, and move through life with greater confidence and clarity.</h2><p>Anxiety can make even the simplest moments feel overwhelming.</p>',
					),
				),
			),
		);

		$services_page_block_result = acf_register_block_type( $services_page_block_args );

		if ( function_exists( 'register_block_type' ) && $services_page_block_result ) {
			register_block_type(
				'acf/nt-services-page-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $services_page_block_result['attributes'] ) ? $services_page_block_result['attributes'] : array(),
				)
			);
		}

		if ( $services_page_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: Services Page block registered as acf/nt-services-page-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register Services Page block. Check ACF Pro is active.' );
		}
	}

	// Services List Block
	$services_list_template_file = get_template_directory() . '/blocks/services-list/services-list.php';
	if ( file_exists( $services_list_template_file ) ) {
		$services_list_block_args = array(
			'name'            => 'nt-services-list-section',
			'title'           => __( '📋 Services List', 'nisson-therapy' ),
			'description'     => __( 'List of services/therapies with optional descriptions. Shows 2 per row if no descriptions, 1 per row if descriptions exist.', 'nisson-therapy' ),
			'render_template' => $services_list_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'list-view',
			'keywords'        => array( 'services', 'list', 'therapies', 'practices', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/services-list/services-list.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'services_list_title' => 'Therapies & Practices I use to help you',
						'services_list_items' => array(
							array(
								'title'       => 'Cognitive Behavioral Therapy (CBT)',
								'description' => '',
							),
							array(
								'title'       => 'Emotion Focused Therapy (EFT)',
								'description' => '',
							),
						),
					),
				),
			),
		);

		$services_list_block_result = acf_register_block_type( $services_list_block_args );

		if ( function_exists( 'register_block_type' ) && $services_list_block_result ) {
			register_block_type(
				'acf/nt-services-list-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $services_list_block_result['attributes'] ) ? $services_list_block_result['attributes'] : array(),
				)
			);
		}

		if ( $services_list_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: Services List block registered as acf/nt-services-list-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register Services List block. Check ACF Pro is active.' );
		}
	}

	// Services Content Block
	$services_content_template_file = get_template_directory() . '/blocks/services-content/services-content.php';
	if ( file_exists( $services_content_template_file ) ) {
		$services_content_block_args = array(
			'name'            => 'nt-services-content-section',
			'title'           => __( '👤 Services Content', 'nisson-therapy' ),
			'description'     => __( 'Content section with image on left, text on right, and CTA button.', 'nisson-therapy' ),
			'render_template' => $services_content_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'admin-users',
			'keywords'        => array( 'services', 'content', 'image', 'text', 'cta', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/services-content/services-content.css',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'services_content_text' => '<p>Mary DiOrio is a licensed psychotherapist specializing in depression and anxiety treatment.</p>',
						'services_content_button' => array(
							'url'   => '#',
							'title' => 'Schedule A Free 15 Minute Consultation',
						),
					),
				),
			),
		);

		$services_content_block_result = acf_register_block_type( $services_content_block_args );

		if ( function_exists( 'register_block_type' ) && $services_content_block_result ) {
			register_block_type(
				'acf/nt-services-content-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $services_content_block_result['attributes'] ) ? $services_content_block_result['attributes'] : array(),
				)
			);
		}

		if ( $services_content_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: Services Content block registered as acf/nt-services-content-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register Services Content block. Check ACF Pro is active.' );
		}
	}

	// FAQ Block
	$faq_template_file = get_template_directory() . '/blocks/faq/faq.php';
	if ( file_exists( $faq_template_file ) ) {
		$faq_block_args = array(
			'name'            => 'nt-faq-section',
			'title'           => __( '❓ FAQ Section', 'nisson-therapy' ),
			'description'     => __( 'FAQ section with H1 header and accordion-style questions and answers.', 'nisson-therapy' ),
			'render_template' => $faq_template_file,
			'category'        => 'nisson-therapy',
			'icon'            => 'editor-help',
			'keywords'        => array( 'faq', 'questions', 'answers', 'accordion', 'nisson', 'therapy' ),
			'supports'        => array(
				'align' => false,
				'anchor' => true,
			),
			'enqueue_style'   => get_template_directory_uri() . '/blocks/faq/faq.css',
			'enqueue_script'  => get_template_directory_uri() . '/blocks/faq/faq.js',
			'mode'            => 'preview',
			'example'         => array(
				'attributes' => array(
					'mode' => 'preview',
					'data' => array(
						'faq_top_title' => 'Frequently Asked Questions',
						'faq_items'     => array(
							array(
								'question' => 'Is therapy right for me?',
								'answer'   => '<p>Working with a therapist can help provide insight, support, and new strategies for all types of life challenges.</p>',
							),
						),
					),
				),
			),
		);

		$faq_block_result = acf_register_block_type( $faq_block_args );

		if ( function_exists( 'register_block_type' ) && $faq_block_result ) {
			register_block_type(
				'acf/nt-faq-section',
				array(
					'render_callback' => 'acf_render_block_callback',
					'attributes'     => isset( $faq_block_result['attributes'] ) ? $faq_block_result['attributes'] : array(),
				)
			);
		}

		if ( $faq_block_result ) {
			error_log( 'NISSON THERAPY SUCCESS: FAQ block registered as acf/nt-faq-section' );
		} else {
			error_log( 'NISSON THERAPY ERROR: Failed to register FAQ block. Check ACF Pro is active.' );
		}
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

		acf_add_options_sub_page(
			array(
				'page_title'  => __( 'Footer Settings', 'nisson-therapy' ),
				'menu_title'  => __( 'Footer', 'nisson-therapy' ),
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

