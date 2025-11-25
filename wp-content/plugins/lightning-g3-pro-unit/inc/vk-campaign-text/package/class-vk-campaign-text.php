<?php
/**
 * VK Campaign Text
 *
 * @package Lightning G3 Pro Unit
 */

use VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions;

if ( ! class_exists( 'VK_Campaign_Text' ) ) {
	/**
	 * VK Campaign Text
	 */
	class VK_Campaign_Text {
		/**
		 * Constructor.
		 */

		public static $version = '0.1.0';

		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'resister_customize' ) );
			add_action( 'wp_head', array( __CLASS__, 'enqueue_style' ), 5 );
			add_action( 'after_setup_theme', array( __CLASS__, 'change_old_option' ) );
			add_action( 'wp', array( __CLASS__, 'launch_action' ) );

			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_css' ) );
			add_filter( 'vk_css_tree_shaking_handles', array( __CLASS__, 'css_tree_shaking_handles' ) );
		}

		public static function load_css() {
			if ( apply_filters( 'vk_campaign_text_print_css', false ) ) {
				$path    = wp_normalize_path( dirname( __FILE__ ) );
				$css_url = str_replace( wp_normalize_path( ABSPATH ), site_url() . '/', $path ) . '/css/vk-campaign-text.css';
				wp_enqueue_style( 'vk-campaign-text', $css_url, array(), self::$version );
			}
		}

		public static function css_tree_shaking_handles( $vk_css_tree_shaking_handles ) {
			$vk_css_tree_shaking_handles = array_merge(
				$vk_css_tree_shaking_handles,
				array(
					'vk-campaign-text'
				)
			);

			return $vk_css_tree_shaking_handles;
		}

		/**
		 * Launch Action
		 */
		public static function launch_action() {
			global $vk_campaign_text_hook_point;
			global $vk_campaign_text_display_position_array;
			$options = get_option( 'vk_campaign_text' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			// 保存されている出力位置
			$position = $options['display_position'];
			global $vk_campaign_text_display_position_array;
			if ( ! $vk_campaign_text_hook_point ) {
				if ( 'show_in_front_page' === $options['display'] && is_front_page() || 'show_in_full_page' === $options['display'] ) {
					foreach ( (array) $vk_campaign_text_display_position_array[ $position ]['hookpoint'] as $hook_point ) {
						add_action( $hook_point, array( __CLASS__, 'display_html' ) );
					}
				}
			} else {
				foreach ( (array) $vk_campaign_text_hook_point as $hook_point ) {
					add_action( $hook_point, array( __CLASS__, 'display_html' ) );
				}
			}
		}

		/**
		 * Change Old Option.
		 */
		public static function change_old_option() {
			$options = get_option( 'vk_campaign_text' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			if ( true === $options['display'] ) {
				$options['display'] = 'show_in_full_page';
				update_option( 'vk_campaign_text', $options );
			} elseif ( false === $options['display'] ) {
				$options['display'] = 'hide';
				update_option( 'vk_campaign_text', $options );
			}
		}

		/**
		 * Default Option.
		 */
		public static function default_option() {
			global $vk_campaign_text_display_position_array;
			$display_position = key( array_slice( $vk_campaign_text_display_position_array, 0, 1, true ) );

			$options = get_option( 'vk_campaign_text' );

			$display_position_array = array_keys( $vk_campaign_text_display_position_array );
			if ( ! empty( $options['display_position'] ) && ! in_array( $options['display_position'], $display_position_array, true ) ) {
				$options['display_position'] = $display_position;
				update_option( 'vk_campaign_text', $options );
			}

			$args = array(
				'display'                       => 'hide',
				'display_position'              => $display_position,
				'icon'                          => '',
				'main_text'                     => '',
				'main_text_color'               => '#fff',
				'main_background_color'         => '#eab010',
				'main_background_pattern'       => 'fill',
				'button_text'                   => '',
				'button_text_color'             => '#4c4c4c',
				'button_background_color'       => '#fff',
				'button_text_hover_color'       => '#fff',
				'button_background_hover_color' => '#eab010',
				'button_url'                    => '',
				'link_target'                   => false,
			);
			return $args;
		}

		/**
		 * Customizer.
		 *
		 * @param \WP_Customize_Manager $wp_customize Customizer.
		 */
		public static function resister_customize( $wp_customize ) {
			global $vk_campaign_text_prefix;
			global $vk_campaign_text_display_position_array;
			$display_position = key( array_slice( $vk_campaign_text_display_position_array, 0, 1, true ) );

			$icon_description = '';
			if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'ex_and_link' ) ) {
				$icon_description = VkFontAwesomeVersions::ex_and_link();
			}

			$wp_customize->add_section(
				'vk_campaign_text_setting',
				array(
					'title'    => $vk_campaign_text_prefix . __( 'Campaign Text', 'lightning-g3-pro-unit' ),
					'priority' => 513,
				)
			);

			// Diaplay Setting.
			$wp_customize->add_setting(
				'vk_campaign_text[display]',
				array(
					'default'           => 'hide',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => '',
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[display]',
				array(
					'label'    => __( 'Display Campaign Text', 'lightning-g3-pro-unit' ),
					'section'  => 'vk_campaign_text_setting',
					'settings' => 'vk_campaign_text[display]',
					'type'     => 'select',
					'choices'  => array(
						'hide'               => __( 'Hide', 'lightning-g3-pro-unit' ),
						'show_in_front_page' => __( 'Show in Front Page', 'lightning-g3-pro-unit' ),
						'show_in_full_page'  => __( 'Show in Full Page', 'lightning-g3-pro-unit' ),
					),
				)
			);

			$wp_customize->add_setting(
				'vk_campaign_text[display_position]',
				array(
					'default'           => $display_position,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => '',
				)
			);

			global $vk_campaign_text_display_position_array;
			foreach ( $vk_campaign_text_display_position_array as $key => $value ) {
				$choices[ $key ] = $value['label'];
			}

			$wp_customize->add_control(
				'vk_campaign_text[display_position]',
				array(
					'label'       => __( 'Display Position', 'lightning-g3-pro-unit' ),
					'section'     => 'vk_campaign_text_setting',
					'settings'    => 'vk_campaign_text[display_position]',
					'type'        => 'select',
					'choices'     => $choices,
					'description' => __( '* If you save and reload after making changes, the position of campaign text will change.', 'lightning-g3-pro-unit' ),
				)
			);

			// Icon.
			$wp_customize->add_setting(
				'vk_campaign_text[icon]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[icon]',
				array(
					'label'       => __( 'Icon', 'lightning-g3-pro-unit' ),
					'section'     => 'vk_campaign_text_setting',
					'settings'    => 'vk_campaign_text[icon]',
					'type'        => 'text',
					'description' => __( 'To choose your favorite icon, and entire html.', 'lightning-g3-pro-unit' ) . '<br>' . $icon_description,
				)
			);

			// Main Text.
			$wp_customize->add_setting(
				'vk_campaign_text[main_text]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[main_text]',
				array(
					'label'    => _x( 'Text', 'campaign text', 'lightning-g3-pro-unit' ),
					'section'  => 'vk_campaign_text_setting',
					'settings' => 'vk_campaign_text[main_text]',
					'type'     => 'text',
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'vk_campaign_text[main_text]',
				array(
					'selector'        => '.vk-campaign-text .vk-campaign-text_text',
					'render_callback' => '',
				)
			);

			// Main Text Color.
			$wp_customize->add_setting(
				'vk_campaign_text[main_text_color]',
				array(
					'default'           => '#fff',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_campaign_text[main_text_color]',
					array(
						'label'    => __( 'Text Color', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_campaign_text_setting',
						'settings' => 'vk_campaign_text[main_text_color]',
					)
				)
			);

			// Main Background Color.
			$wp_customize->add_setting(
				'vk_campaign_text[main_background_color]',
				array(
					'default'           => '#eab010',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_campaign_text[main_background_color]',
					array(
						'label'    => __( 'Background Color', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_campaign_text_setting',
						'settings' => 'vk_campaign_text[main_background_color]',
					)
				)
			);

			// Background Pattern Setting.
			// @since 0.5.0
			$wp_customize->add_setting(
				'vk_campaign_text[main_background_pattern]',
				array(
					'default'           => 'fill',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => '',
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[main_background_pattern]',
				array(
					'label'    => __( 'Background Pattern', 'lightning-g3-pro-unit' ),
					'section'  => 'vk_campaign_text_setting',
					'settings' => 'vk_campaign_text[main_background_pattern]',
					'type'     => 'select',
					'choices'  => array(
						'fill'   => __( 'Fill', 'lightning-g3-pro-unit' ),
						'stripe' => __( 'Stripe', 'lightning-g3-pro-unit' ),
					),
				)
			);

			// Button Text.
			$wp_customize->add_setting(
				'vk_campaign_text[button_text]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[button_text]',
				array(
					'label'    => __( 'Button Text', 'lightning-g3-pro-unit' ),
					'section'  => 'vk_campaign_text_setting',
					'settings' => 'vk_campaign_text[button_text]',
					'type'     => 'text',
				)
			);

			// Button Text Color.
			$wp_customize->add_setting(
				'vk_campaign_text[button_text_color]',
				array(
					'default'           => '#4c4c4c',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_campaign_text[button_text_color]',
					array(
						'label'    => __( 'Button Text Color', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_campaign_text_setting',
						'settings' => 'vk_campaign_text[button_text_color]',
					)
				)
			);

			// Button Background Color.
			$wp_customize->add_setting(
				'vk_campaign_text[button_background_color]',
				array(
					'default'           => '#fff',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_campaign_text[button_background_color]',
					array(
						'label'    => __( 'Button Background Color', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_campaign_text_setting',
						'settings' => 'vk_campaign_text[button_background_color]',
					)
				)
			);

			// Button Text Hover Color.
			$wp_customize->add_setting(
				'vk_campaign_text[button_text_hover_color]',
				array(
					'default'           => '#fff',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_campaign_text[button_text_hover_color]',
					array(
						'label'    => __( 'Button Text Hover Color', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_campaign_text_setting',
						'settings' => 'vk_campaign_text[button_text_hover_color]',
					)
				)
			);

			// Button Background Color.
			$wp_customize->add_setting(
				'vk_campaign_text[button_background_hover_color]',
				array(
					'default'           => '#fff',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'vk_campaign_text[button_background_hover_color]',
					array(
						'label'    => __( 'Button Background Hover Color', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_campaign_text_setting',
						'settings' => 'vk_campaign_text[button_background_hover_color]',
					)
				)
			);

			// Link URL.
			$wp_customize->add_setting(
				'vk_campaign_text[button_url]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'esc_url',
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[button_url]',
				array(
					'label'    => __( 'Link URL', 'lightning-g3-pro-unit' ),
					'section'  => 'vk_campaign_text_setting',
					'settings' => 'vk_campaign_text[button_url]',
					'type'     => 'text',
				)
			);

			// Link Target.
			$wp_customize->add_setting(
				'vk_campaign_text[link_target]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_boolean' ),
				)
			);

			$wp_customize->add_control(
				'vk_campaign_text[link_target]',
				array(
					'label'    => __( 'Open in New Tab', 'lightning-g3-pro-unit' ),
					'section'  => 'vk_campaign_text_setting',
					'settings' => 'vk_campaign_text[link_target]',
					'type'     => 'checkbox',
				)
			);
		}

		/**
		 * Get Option
		 */
		public static function get_option() {
			$options = get_option( 'vk_campaign_text' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );
			$options = apply_filters( 'vk_campaign_text_options', $options );
			return $options;
		}

		/**
		 * Enqueue Style.
		 */
		public static function enqueue_style() {
			global $vk_campaign_text_hook_style;

			$options = self::get_option();

			$main_text_color               = $options['main_text_color'];
			$main_bg_color                 = apply_filters( 'vk_campaign_text_main_background_color', $options['main_background_color'] );
			$button_text_color             = $options['button_text_color'];
			$button_bg_color               = $options['button_background_color'];
			$button_text_hover_color       = $options['button_text_hover_color'];
			$button_background_hover_color = $options['button_background_hover_color'];

			$dynamic_css  = '.vk-campaign-text{';
			$dynamic_css .= 'color:' . $main_text_color . ';';
			$dynamic_css .= 'background-color:' . $main_bg_color . ';';

			if ( isset( $options['main_background_pattern'] ) && 'stripe' === $options['main_background_pattern'] ) {
				$dynamic_css .= 'background-image: repeating-linear-gradient( 45deg, ' . $main_bg_color . ', ' . $main_bg_color . ' 7px,rgba(0,0,0,0.08) 7px,rgba(0,0,0,0.08) calc(2 * 7px));';
			}

			$dynamic_css .= '}';
			$dynamic_css .= '.vk-campaign-text_btn,';
			$dynamic_css .= '.vk-campaign-text_btn:link,';
			$dynamic_css .= '.vk-campaign-text_btn:visited,';
			$dynamic_css .= '.vk-campaign-text_btn:focus,';
			$dynamic_css .= '.vk-campaign-text_btn:active{';
			$dynamic_css .= 'background:' . $button_bg_color . ';';
			$dynamic_css .= 'color:' . $button_text_color . ';';
			$dynamic_css .= '}';
			$dynamic_css .= 'a.vk-campaign-text_btn:hover{';
			$dynamic_css .= 'background:' . $button_background_hover_color . ';';
			$dynamic_css .= 'color:' . $button_text_hover_color . ';';
			$dynamic_css .= '}';
			$dynamic_css .= '.vk-campaign-text_link,';
			$dynamic_css .= '.vk-campaign-text_link:link,';
			$dynamic_css .= '.vk-campaign-text_link:hover,';
			$dynamic_css .= '.vk-campaign-text_link:visited,';
			$dynamic_css .= '.vk-campaign-text_link:active,';
			$dynamic_css .= '.vk-campaign-text_link:focus{';
			$dynamic_css .= 'color:' . $main_text_color . ';';
			$dynamic_css .= '}';
			wp_add_inline_style( $vk_campaign_text_hook_style, $dynamic_css );
		}

		/**
		 * Display HTML.
		 */
		public static function display_html() {
			$campaign_html = '';

			$allowed_html = array(
				'div'  => array(
					'class' => array(),
				),
				'a'    => array(
					'class'  => array(),
					'href'   => array(),
					'target' => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'i'    => array(
					'class' => array(),
				),

			);

			$options = self::get_option();

			if ( isset( $options['display'] ) && 'hide' !== $options['display'] ) {

				$icon = '';
				if ( ! empty( $options['icon'] ) ) {
					if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'get_icon_tag' ) ) {
						$icon = VkFontAwesomeVersions::get_icon_tag( $options['icon'] );
					}
				}

				$main_text   = apply_filters( 'vk_campaign_text_main_text', $options['main_text'] );
				$button_text = $options['button_text'];
				$button_url  = $options['button_url'];
				$link_target = ! empty( $options['link_target'] ) ? ' target="_blank"' : '';

				$campaign_html .= '<div class="vk-campaign-text">';
				if ( empty( $button_text ) ) {
					if ( ! empty( $button_url ) ) {
						$campaign_html .= '<a class="vk-campaign-text_link" href="' . $button_url . '"' . $link_target . '>';
						$campaign_html .= '<span class="vk-campaign-text_text">' . $icon . $main_text . '</span>';
						$campaign_html .= '</a>';
					} else {
						$campaign_html .= '<span class="vk-campaign-text_text">' . $icon . $main_text . '</span>';
					}
				} else {
					$campaign_html .= '<span class="vk-campaign-text_text">' . $icon . $main_text . '</span>';
					if ( ! empty( $button_url ) ) {
						$campaign_html .= '<a class="vk-campaign-text_btn" href="' . $button_url . '"' . $link_target . '>' . $button_text . '</a>';
					} else {
						$campaign_html .= '<span class="vk-campaign-text_btn">' . $button_text . '</span>';
					}
				}
				$campaign_html .= '</div>';
			}

			echo wp_kses( $campaign_html, $allowed_html );
		}
	}
	$VK_Campaign_Text = new VK_Campaign_Text();

}
