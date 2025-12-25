<?php
/**
 * ACF Field Groups Registration
 * 
 * Register all ACF field groups directly in PHP code
 * This ensures fields are available immediately on deployment
 *
 * @package NissonTherapy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Hero Block ACF Fields
 */
function nisson_therapy_register_hero_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_nt_hero_section_v2',
			'title'  => 'NT Hero Section Fields',
			'fields' => array(
				array(
					'key'           => 'field_nt_hero_subheadline',
					'label'         => 'Subheadline',
					'name'          => 'subheadline',
					'type'          => 'text',
					'instructions'  => 'Small text above the main headline',
					'required'      => 0,
					'default_value' => '',
					'placeholder'   => 'Telehealth for New York, New Jersey, Florida, and Oregon',
				),
				array(
					'key'           => 'field_nt_hero_headline',
					'label'         => 'Headline',
					'name'          => 'headline',
					'type'          => 'text',
					'instructions'  => 'Main headline text',
					'required'      => 1,
					'default_value' => '',
					'placeholder'   => 'Time to See Yourself In A Different',
				),
				array(
					'key'           => 'field_nt_hero_highlighted_text',
					'label'         => 'Highlighted Text',
					'name'          => 'highlighted_text',
					'type'          => 'text',
					'instructions'  => 'Text to highlight within the headline (will be wrapped with special styling)',
					'required'      => 0,
					'default_value' => '',
					'placeholder'   => 'Light?',
				),
				array(
					'key'           => 'field_nt_hero_philosophy_line',
					'label'         => 'Philosophy Line',
					'name'          => 'philosophy_line',
					'type'          => 'text',
					'instructions'  => 'Text to display after the headline (e.g., "Explore your inner world safely, with curiosity and compassion for all parts of you.")',
					'required'      => 0,
					'default_value' => 'Explore your inner world safely, with curiosity and compassion for all parts of you.',
					'placeholder'   => 'Explore your inner world safely, with curiosity and compassion for all parts of you.',
				),
				array(
					'key'           => 'field_nt_hero_button_link',
					'label'         => 'Button Link',
					'name'          => 'button_link',
					'type'          => 'link',
					'instructions'  => 'Link for the call-to-action button (appears after philosophy line)',
					'required'      => 0,
					'return_format' => 'array',
				),
				array(
					'key'           => 'field_nt_hero_background_image',
					'label'         => 'Background Image',
					'name'          => 'background_image',
					'type'          => 'image',
					'instructions'  => 'Background image for the hero section',
					'required'      => 0,
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_nt_hero_enable_parallax',
					'label'         => 'Enable Parallax',
					'name'          => 'enable_parallax',
					'type'          => 'true_false',
					'instructions'  => 'Enable parallax scrolling effect on the background image',
					'required'      => 0,
					'default_value' => 1,
					'ui'            => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/nt-hero-section',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);
}

/**
 * Register Header Settings ACF Fields
 */
function nisson_therapy_register_header_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_nisson_header_settings',
			'title'  => 'Nisson Header Settings',
			'fields' => array(
				array(
					'key'           => 'field_nisson_header_cta_text',
					'label'        => 'CTA Button Text',
					'name'         => 'header_cta_text',
					'type'         => 'text',
					'instructions' => 'Text for the header call-to-action button',
					'required'     => 0,
					'default_value' => 'Get Started',
					'placeholder'  => 'Get Started',
				),
				array(
					'key'           => 'field_nisson_header_cta_link',
					'label'         => 'CTA Button Link',
					'name'          => 'header_cta_link',
					'type'          => 'url',
					'instructions'  => 'URL for the header call-to-action button',
					'required'      => 0,
					'default_value' => '/contact',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'theme-settings',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);
}

/**
 * Register all ACF field groups
 * Run after blocks are registered
 */
function nisson_therapy_register_all_acf_fields() {
	nisson_therapy_register_hero_fields();
	nisson_therapy_register_header_fields();
}
add_action( 'acf/init', 'nisson_therapy_register_all_acf_fields', 30 );

