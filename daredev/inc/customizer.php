<?php
/**
 * Customizer Options
 *
 * @package DareDev
 */
add_action( 'customize_register', 'daredev_register_theme_customizer' );
/*
 * Register Our Customizer Stuff Here
 */
function daredev_register_theme_customizer( $wp_customize ) {
	if ( daredev_setting( 'custom_scripts' ) ) {

		// Create custom panel.
		$wp_customize->add_panel( 'text_blocks',
			array(
				'priority'       => 500,
				'theme_supports' => '',
				'title'          => __( 'Custom scripts', 'daredev' ),
				'description'    => __( 'Add your custom scripts here.', 'daredev' ),
			) );

		// API Keys
		$wp_customize->add_section( 'dd_api_keys',
			array(
				'title'       => __( 'API Keys', 'daredev' ),
				'description' => __( 'Add your 3rd party API keys here.', 'daredev' ),
				'panel'       => 'text_blocks',
				'priority'    => 10,
			) );
		// Google Maps API Key
		// Add setting
		$wp_customize->add_setting( 'dd_gmaps_api_key',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text',
			) );
		// Add control
		$wp_customize->add_control( new WP_Customize_Control(
				$wp_customize,
				'dd_gmaps_api_key',
				array(
					'label'       => __( 'Your Google Maps API key', 'daredev' ),
					'description' => __( 'See how you can get your Google Maps API key <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">here</a> (Opens in new window).',
						'daredev' ),
					'section'     => 'dd_api_keys',
					'settings'    => 'dd_gmaps_api_key',
					'type'        => 'text',
				)
			)
		);

		// Header Scripts
		$wp_customize->add_section( 'dd_header_scripts',
			array(
				'title'       => __( 'Header scripts', 'daredev' ),
				'description' => __( 'Scripts that go before the <code>&lt;/head&gt;</code> of your site.', 'daredev' ),
				'panel'       => 'text_blocks',
				'priority'    => 10,
			) );
		// Add setting
		$wp_customize->add_setting( 'dd_header_scripts',
			array(
				'default' => '',
			) );
		// Add control
		$wp_customize->add_control( new WP_Customize_Control(
				$wp_customize,
				'dd_header_scripts',
				array(
					'label'    => __( 'Your scripts', 'daredev' ),
					'section'  => 'dd_header_scripts',
					'settings' => 'dd_header_scripts',
					'type'     => 'textarea',
				)
			)
		);

		// Body Scripts
		$wp_customize->add_section( 'dd_body_scripts',
			array(
				'title'       => __( 'Body scripts', 'daredev' ),
				'description' => __( 'Scripts that go right after the <code>&lt;body&gt;</code> of your site.',
					'daredev' ),
				'panel'       => 'text_blocks',
				'priority'    => 10,
			) );
		// Add setting
		$wp_customize->add_setting( 'dd_body_scripts',
			array(
				'default' => '',
			) );
		// Add control
		$wp_customize->add_control( new WP_Customize_Control(
				$wp_customize,
				'dd_body_scripts',
				array(
					'label'    => __( 'Your scripts', 'daredev' ),
					'section'  => 'dd_body_scripts',
					'settings' => 'dd_body_scripts',
					'type'     => 'textarea',
				)
			)
		);

		// Footer Scripts
		$wp_customize->add_section( 'dd_footer_scripts',
			array(
				'title'       => __( 'Footer scripts', 'daredev' ),
				'description' => __( 'Scripts that go right before the <code>&lt;/body&gt;</code> of your site.',
					'daredev' ),
				'panel'       => 'text_blocks',
				'priority'    => 10,
			) );
		// Add setting
		$wp_customize->add_setting( 'dd_footer_scripts',
			array(
				'default' => '',
			) );
		// Add control
		$wp_customize->add_control( new WP_Customize_Control(
				$wp_customize,
				'dd_footer_scripts',
				array(
					'label'    => __( 'Your scripts', 'daredev' ),
					'section'  => 'dd_footer_scripts',
					'settings' => 'dd_footer_scripts',
					'type'     => 'textarea',
				)
			)
		);

		// Sanitize text
		function sanitize_text( $text ) {
			return sanitize_text_field( $text );
		}
	}
}