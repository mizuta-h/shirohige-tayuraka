<?php

require dirname( __FILE__ ) . '/class-preset-manager.php';

add_action( 'customize_register', 'lightning_customize_register_adv_design', 11 );
function lightning_customize_register_adv_design( $wp_customize ) {

	/**********************************************
	 * Color Setting
	 */

	// Link Color Heading.
	$wp_customize->add_setting(
		'text_color_header',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new VK_Custom_Html_Control(
			$wp_customize,
			'text_color_header',
			array(
				'label'            => '',
				'section'          => 'colors',
				'type'             => 'text',
				'custom_title_sub' => __( 'Text color', 'lightning-g3-pro-unit' ),
				'custom_html'      => '',
				'priority'         => 600,
			)
		)
	);

	// Link Text Color ( Default ).
	$wp_customize->add_setting(
		'lightning_theme_options[link_text_color]',
		array(
			'default'           => '#337ab7',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lightning_theme_options[link_text_color]',
			array(
				'label'    => __( 'Link text color ( default )', 'lightning-g3-pro-unit' ),
				'section'  => 'colors',
				'settings' => 'lightning_theme_options[link_text_color]',
				'priority' => 600,
			)
		)
	);

	// Link Text Color ( Hover ).
	$wp_customize->add_setting(
		'lightning_theme_options[link_text_color_hover]',
		array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'lightning_theme_options[link_text_color_hover]',
			array(
				'label'    => __( 'Link text color ( hover )', 'lightning-g3-pro-unit' ),
				'section'  => 'colors',
				'settings' => 'lightning_theme_options[link_text_color_hover]',
				'priority' => 600,
			)
		)
	);

	/**********************************************
	 * Text size
	 */

	// Link Color Heading.
	$wp_customize->add_setting(
		'text_size_header',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new VK_Custom_Html_Control(
			$wp_customize,
			'text_size_header',
			array(
				'label'            => __( 'Text size', 'lightning-g3-pro-unit' ),
				'section'          => 'lightning_design',
				'type'             => 'text',
				'custom_title_sub' => '',
				'custom_html'      => '',
				'priority'         => 600,
			)
		)
	);

	$wp_customize->add_setting(
		'lightning_theme_options[size_text_body]',
		array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new VK_Custom_Text_Control(
			$wp_customize,
			'size_text_body',
			array(
				'label'       => __( 'Body font size', 'lightning-g3-pro-unit' ),
				'section'     => 'lightning_design',
				'settings'    => 'lightning_theme_options[size_text_body]',
				'type'        => 'number',
				'priority'    => 600,
				'description' => '',
				'input_after' => __( 'px', 'lightning-g3-pro-unit' ),
			)
		)
	);

	/**********************************************
	 * Container Size Setting
	 */

	$wp_customize->add_section(
		'lightning_container',
		array(
			'title'    => __( 'Container Setting', 'lightning-g3-pro-unit' ),
			'panel'    => 'lightning_layout',
			'priority' => 100,
		)
	);

	$break_points = array( 'xs', 'sm', 'md', 'lg', 'xl' );
	foreach ( $break_points as $size ) {

		$wp_customize->add_setting(
			'lightning_theme_options[container_size][' . $size . ']',
			array(
				'default'           => '',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new VK_Custom_Text_Control(
				$wp_customize,
				'layout_size[' . $size . ']',
				array(
					'label'       => __( 'Container size', 'lightning-g3-pro-unit' ) . ' [' . $size . '] ',
					'section'     => 'lightning_container',
					'settings'    => 'lightning_theme_options[container_size][' . $size . ']',
					'type'        => 'number',
					'description' => '',
					'input_after' => __( 'px', 'lightning-g3-pro-unit' ),
				)
			)
		);

	}
}

/**
 * Lightning common dynamic css
 *
 * @return string
 */
function lightning_get_common_adv_inline_css() {
	$options = get_option( 'lightning_theme_options' );

	$dynamic_css = '';

	$break_points = array(
		'xs' => '',
		'sm' => '576px',
		'md' => '768px',
		'lg' => '992px',
		'xl' => '1200px',

	);
	foreach ( $break_points as $key => $value ) {
		if ( ! empty( $options['container_size'][ $key ] ) ) {
			$dynamic_css .= '
			/* Lightning Container Size */
			@media (min-width: ' . $value . '){
				:root{
					--vk-width-container : ' . $options['container_size'][ $key ] . 'px;
				}
				.container {
					max-width: ' . $options['container_size'][ $key ] . 'px;
				}
			}
			';
		}
	}

	$dynamic_css .= ':root {';
	if ( ! empty( $options['size_text_body'] ) ) {
		$dynamic_css .= '--vk-size-text: ' . esc_html( $options['size_text_body'] ) . 'px;';
	}
	$dynamic_css .= '}';

	$dynamic_css .= '.main-section {';
	if ( ! empty( $options['link_text_color'] ) ) {
		$dynamic_css .= '--vk-color-text-link: ' . esc_html( $options['link_text_color'] ) . ';';
	}
	if ( ! empty( $options['link_text_color_hover'] ) ) {
		$dynamic_css .= '--vk-color-text-link-hover: ' . esc_html( $options['link_text_color_hover'] ) . ';';
	}
	$dynamic_css .= '}';

	// delete before after space
	$dynamic_css = trim( $dynamic_css );
	// convert tab and br to space
	$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
	// Change multiple spaces to single space
	$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );
	return $dynamic_css;
}

function lightning_add_common_adv_dynamic_css() {
	$dynamic_css = lightning_get_common_adv_inline_css();
	wp_add_inline_style( 'lightning-common-style', $dynamic_css );
}
add_action( 'wp_enqueue_scripts', 'lightning_add_common_adv_dynamic_css', 11 );

function lightning_add_common_adv_dynamic_css_to_editor() {
	$dynamic_css = lightning_get_common_adv_inline_css();
	wp_add_inline_style( 'lightning-common-editor-gutenberg', $dynamic_css );
}
add_action( 'enqueue_block_editor_assets', 'lightning_add_common_adv_dynamic_css_to_editor' );
