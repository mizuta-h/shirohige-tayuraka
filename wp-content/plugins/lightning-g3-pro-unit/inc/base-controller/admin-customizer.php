<?php

/*
  Customizer Setting
/*-------------------------------------------*/
add_action( 'customize_register', 'lightning_base_controll_customize_register' );
function lightning_base_controll_customize_register( $wp_customize ) {

	$wp_customize->add_setting(
		'section_base_setting',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new VK_Custom_Html_Control(
			$wp_customize,
			'section_base_setting',
			array(
				'label'            => __( 'Section Base Setting', 'lightning-g3-pro-unit' ),
				'section'          => 'lightning_design',
				'type'             => 'text',
				'custom_title_sub' => '',
				'custom_html'      => __( 'This setting was can set specific setting from the each page. If this setting was not reflect that please check edit screen of now displaying post.', 'lightning-g3-pro-unit' ),
				'priority'         => 700,
			)
		)
	);

	$choices = array(
		'no'      => __( 'No section base', 'lightning-g3-pro-unit' ),
		'use'     => __( 'Use section base', 'lightning-g3-pro-unit' ),
	);

	$wp_customize->add_setting(
		'lightning_theme_options[section_base]',
		array(
			'default'           => 'default',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lightning_theme_options[section_base]',
		array(
			'label'    => '',
			'section'  => 'lightning_design',
			'settings' => 'lightning_theme_options[section_base]',
			'type'     => 'select',
			'choices'  => $choices,
			'priority' => 701,
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'lightning_theme_options[section_base]', array(
			'selector'        => '.main-section',
			'render_callback' => '',
		)
	);
}
