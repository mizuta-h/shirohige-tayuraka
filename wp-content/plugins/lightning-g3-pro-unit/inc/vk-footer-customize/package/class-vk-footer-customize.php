<?php
/**
 * Footer Customize
 *
 * @package Lightning Pro
 */

if ( ! class_exists( 'VK_Footer_Customize' ) ) {

	/**
	 * Footer Customize Class
	 */
	class VK_Footer_Customize {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'resister_customize' ) );
			add_action( 'wp_head', array( __CLASS__, 'enqueue_style' ), 5 );
			add_filter( 'lightning_footer_widget_area_count', array( __CLASS__, 'set_footter_widget_area_count' ) );
			add_filter( 'lightning_get_class_names', array( $this, 'change_nav_class' ) );
		}
		/**
		 * Defualt Options
		 */
		public static function options() {

			$default = array(
				'footer_background_color'  => '',
				'footer_text_color'        => '',
				'footer_image'             => '',
				'footer_image_repeat'      => 'no-repeat',
				'footer_image_justify'     => 'default',
				'footer_widget_area_count' => 3,
			);
			$options = get_option( 'vk_footer_option' );
			$options = wp_parse_args( $options, $default );
			return apply_filters( 'lightning_get_footer_options', $options, 'lightning_get_footer_options' );
		}

		/**
		 * Register Customize
		 *
		 * @param \WP_Customize_Manager $wp_customize Customizer.
		 */
		public static function resister_customize( $wp_customize ) {

			$default = self::options();

			global $vk_footer_widgrt_selector;
			global $vk_footer_option_name;
			global $vk_footer_customize_prefix;
			global $vk_footer_customize_priority;
			if ( ! $vk_footer_customize_priority ) {
				$vk_footer_customize_priority = 540;
			}
			$priority = $vk_footer_customize_priority + 1;

			// add section.
			$wp_customize->add_section(
				'lightning_footer',
				array(
					'title'    => $vk_footer_customize_prefix . __( 'Footer settings', 'lightning-g3-pro-unit' ),
					'priority' => $vk_footer_customize_priority,
				)
			);

			// Footer Setting Heading.
			$wp_customize->add_setting(
				'footer-setting',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'footer-setting',
					array(
						'label'            => __( 'Footer Style Setting', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_footer',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
						'priority'         => $priority,
						'active_callback'  => 'is_default_footer',
					)
				)
			);

			function is_default_footer( $control ) {
				$is_fse = false;
				if ( $control->manager->get_setting( 'lightning_theme_options[block_template_footer]' ) ){
					if ( empty( $control->manager->get_setting( 'lightning_theme_options[block_template_footer]' )->value() ) ) {
						$is_fse = false;
					} else {
						$is_fse = true;
					}
				}
				return ! $is_fse;
			}

			// Footer Nav Align.
			$wp_customize->add_setting(
				'vk_footer_option[footer_nav_align]',
				array(
					'default'           => 'left',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				'vk_footer_option[footer_nav_align]',
				array(
					'label'           => __( 'Footer Nav Align', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_footer',
					'settings'        => 'vk_footer_option[footer_nav_align]',
					'type'            => 'select',
					'choices'         => array(
						'left'   => __( 'Left', 'lightning-g3-pro-unit' ),
						'center' => __( 'Center', 'lightning-g3-pro-unit' ),
						'right'  => __( 'Right', 'lightning-g3-pro-unit' ),
					),
					'priority'        => $priority,
					'active_callback' => 'is_default_footer',
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'vk_footer_option[footer_nav_align]',
				array(
					'selector'        => '.footer-nav-list',
					'render_callback' => '',
				)
			);

			// Footer Background Color.
			$wp_customize->add_setting(
				'vk_footer_option[footer_background_color]',
				array(
					'default'           => null,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_footer_option[footer_background_color]',
					array(
						'label'           => __( 'Footer Background Color', 'lightning-g3-pro-unit' ),
						'section'         => 'lightning_footer',
						'settings'        => 'vk_footer_option[footer_background_color]',
						'priority'        => $priority,
						'active_callback' => 'is_default_footer',
					)
				)
			);

			// Footer Text Color.
			$wp_customize->add_setting(
				'vk_footer_option[footer_text_color]',
				array(
					'default'           => null,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_footer_option[footer_text_color]',
					array(
						'label'           => __( 'Footer Text Color', 'lightning-g3-pro-unit' ),
						'section'         => 'lightning_footer',
						'settings'        => 'vk_footer_option[footer_text_color]',
						'priority'        => $priority,
						'active_callback' => 'is_default_footer',
					)
				)
			);

			// Footer Image.
			$wp_customize->add_setting(
				'vk_footer_option[footer_image]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'vk_footer_option[footer_image]',
					array(
						'label'           => __( 'Footer Image', 'lightning-g3-pro-unit' ),
						'section'         => 'lightning_footer',
						'settings'        => 'vk_footer_option[footer_image]',
						'priority'        => $priority,
						'active_callback' => 'is_default_footer',
					)
				)
			);

			// Footer Image Repeat.
			$wp_customize->add_setting(
				'vk_footer_option[footer_image_repeat]',
				array(
					'default'           => 'no-repeat',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_choice' ),
				)
			);

			$wp_customize->add_control(
				'vk_footer_option[footer_image_repeat]',
				array(
					'label'           => __( 'Footer Image Repeat', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_footer',
					'settings'        => 'vk_footer_option[footer_image_repeat]',
					'type'            => 'select',
					'choices'         => array(
						'no-repeat' => __( 'No Repeat', 'lightning-g3-pro-unit' ),
						'repeat-x'  => __( 'Repeat X', 'lightning-g3-pro-unit' ),
						'repeat-y'  => __( 'Repeat Y', 'lightning-g3-pro-unit' ),
						'repeat'    => __( 'Repeat X and Y', 'lightning-g3-pro-unit' ),
					),
					'priority'        => $priority,
					'active_callback' => 'is_default_footer',
				)
			);

			// Fotter Image Justify.
			$wp_customize->add_setting(
				'vk_footer_option[footer_image_justify]',
				array(
					'default'           => 'default',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_choice' ),
				)
			);

			$wp_customize->add_control(
				'vk_footer_option[footer_image_justify]',
				array(
					'label'           => __( 'Footer Image Justify', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_footer',
					'settings'        => 'vk_footer_option[footer_image_justify]',
					'type'            => 'select',
					'choices'         => array(
						'default'              => __( 'Default', 'lightning-g3-pro-unit' ),
						'cover'                => __( 'Cover', 'lightning-g3-pro-unit' ),
						'justify-bottom'       => __( 'Justify Bottpm', 'lightning-g3-pro-unit' ),
						'justify-left-bottom'  => __( 'Justify Left Bottom', 'lightning-g3-pro-unit' ),
						'justify-right-bottom' => __( 'Justify Right Bottom', 'lightning-g3-pro-unit' ),
					),
					'priority'        => $priority,
					'active_callback' => 'is_default_footer',
				)
			);

			// Footer Upper Widget Area Heading.
			$wp_customize->add_setting(
				'footer-widget-setting',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'footer-widget-setting',
					array(
						'label'            => __( 'Footer Widget Setting', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_footer',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
						'priority'         => $priority,
						'active_callback'  => 'is_default_footer',
					)
				)
			);

			// Number of Footer Widget area.
			$wp_customize->add_setting(
				$vk_footer_option_name . '[footer_widget_area_count]',
				array(
					'default'           => 3,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_number' ),
				)
			);
			$wp_customize->add_control(
				$vk_footer_option_name . '[footer_widget_area_count]',
				array(
					'label'           => __( 'Footer Widget Area Count', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_footer',
					'settings'        => $vk_footer_option_name . '[footer_widget_area_count]',
					'type'            => 'select',
					'choices'         => array(
						1 => __( '1 column', 'lightning-g3-pro-unit' ),
						2 => __( '2 column', 'lightning-g3-pro-unit' ),
						3 => __( '3 column', 'lightning-g3-pro-unit' ),
						4 => __( '4 column', 'lightning-g3-pro-unit' ),
						6 => __( '6 column', 'lightning-g3-pro-unit' ),
					),
					'description'     => __( '* If you save and reload after making changes, the number of the widget area setting panels  will increase or decrease.', 'lightning-g3-pro-unit' ),
					'priority'        => $priority,
					'active_callback' => 'is_default_footer',
				)
			);
			$wp_customize->selective_refresh->add_partial(
				$vk_footer_option_name . '[footer_widget_area_count]',
				array(
					'selector'        => $vk_footer_widgrt_selector,
					'render_callback' => '',
				)
			);
		}

		/**
		 * Enqueue Styles
		 */
		public static function enqueue_style() {

			$options = self::options();

			$bg_color   = $options['footer_background_color'];
			$text_color = $options['footer_text_color'];
			$image      = $options['footer_image'];
			$repeat     = $options['footer_image_repeat'];
			$justify    = $options['footer_image_justify'];

			$dynamic_css = '';

			if ( ! empty( $bg_color ) || ! empty( $text_color ) || ! empty( $image ) ) {
				$dynamic_css .= '.site-footer {';

				if ( ! empty( $bg_color ) ) {
					$dynamic_css .= 'background-color:' . $bg_color . ';';
				}

				if ( ! empty( $text_color ) ) {
					$dynamic_css .= 'color:' . $text_color . ';';
				}

				if ( ! empty( $image ) ) {

					$dynamic_css .= 'background-image:url("' . $image . '");';

					if ( ! empty( $repeat ) ) {
						if ( 'no-repeat' === $repeat ) {
							$dynamic_css .= 'background-repeat:no-repeat;';
						} elseif ( 'repeat-x' === $repeat ) {
							$dynamic_css .= 'background-repeat:repeat-x;';
						} elseif ( 'repeat-y' === $repeat ) {
							$dynamic_css .= 'background-repeat:repeat-y;';
						} elseif ( 'repeat' === $repeat ) {
							$dynamic_css .= 'background-repeat:repeat;';
						}
					}

					if ( ! empty( $justify ) ) {
						if ( 'cover' === $justify ) {
							// Cover の場合の CSS.
							$dynamic_css .= 'background-position:center;';
							$dynamic_css .= 'background-size:cover;';
						} elseif ( 'justify-bottom' === $justify ) {
							// 下揃え の場合の CSS.
							$dynamic_css .= 'background-position:bottom;';
						} elseif ( 'justify-left-bottom' === $justify ) {
							// 左下揃え の場合の CSS.
							$dynamic_css .= 'background-position:bottom left;';
						} elseif ( 'justify-right-bottom' === $justify ) {
							// 右下揃えの場合の CSS.
							$dynamic_css .= 'background-position:bottom right;';
						}
					}
				}

				$dynamic_css .= '}';

				if ( ! empty( $text_color ) ) {
					// $dynamic_css .= '.site-footer .nav li a,';
					// $dynamic_css .= '.site-footer .widget a,';
					// $dynamic_css .= '.site-footer a {';
					// $dynamic_css .= 'color:' . $text_color . ';';
					// $dynamic_css .= '}';
					$dynamic_css .= '.site-footer {
						--vk-color-text-body: ' . $text_color . ';
						--vk-color-text-link: ' . $text_color . ';
						--vk-color-text-link-hover: ' . $text_color . ';
					}';
				}

				if ( $bg_color ) {
					if ( class_exists( 'VK_Helpers' ) ) {
						$mode = VK_Helpers::color_mode_check( $bg_color );
						if ( 'dark' === $mode['mode'] ) {
							$dynamic_css .= '.site-footer {
								--vk-color-border: rgba(255, 255, 255, 0.2);
								--vk-color-border-hr: rgba(255, 255, 255, 0.1);
								--vk-color-border-image: rgba(70, 70, 70, 0.9);
								--vk-color-text-meta: rgba( 255,255,255,0.6);
								--vk-color-text-light: rgba( 255,255,255,0.6);
								--vk-color-border-light: rgba( 255,255,255,0.1);
								--vk-color-border-zuru: rgba(0, 0, 0, 0.2);
								--vk-color-bg-accent: rgba( 255,255,255,0.07);
								--vk-color-accent-bg: rgba( 255,255,255,0.05);
							}
							';
						}
					}
				}

				global $vk_footer_customize_hook_style;
				wp_add_inline_style( $vk_footer_customize_hook_style, $dynamic_css );
			}
		}

		/**
		 * Footer Widget Area Count.
		 *
		 * @param int $footer_widget_area_count Footer Widget Area Count.
		 */
		public static function set_footter_widget_area_count( $footer_widget_area_count ) {
			global $vk_footer_option_name;
			$footer_widget_area_count = 3;
			$options                  = get_option( $vk_footer_option_name );
			if ( ! empty( $options['footer_widget_area_count'] ) ) {
				$footer_widget_area_count = (int) $options['footer_widget_area_count'];
			}
			return $footer_widget_area_count;
		}


		/**
		 * Change Footer nav class
		 *
		 * @param array $class_names : class name array.
		 * @return array $class_names : class name array.
		 */
		public static function change_nav_class( $class_names ) {
			if ( isset( $class_names['footer-nav-list'] ) ) {
				$options = get_option( 'vk_footer_option' );
				if ( isset( $options['footer_nav_align'] ) ) {
					if ( 'center' === $options['footer_nav_align'] ) {
						$class_names['footer-nav-list'][] = 'footer-nav-list--align--center';
					} elseif ( 'right' === $options['footer_nav_align'] ) {
						$class_names['footer-nav-list'][] = 'footer-nav-list--align--right';
					}
				}
			}

			return $class_names;
		}
	}
	$vk_footer_customize = new VK_Footer_Customize();
}
