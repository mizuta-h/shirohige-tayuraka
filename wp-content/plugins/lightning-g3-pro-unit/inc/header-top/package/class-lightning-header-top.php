<?php
/**
 * Lightning Header Top
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

use VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions;

if ( ! class_exists( 'Lightning_Header_Top' ) ) {
	/**
	 * Lightning Header Top
	 */
	class Lightning_Header_Top {

		/**
		 * Version Number
		 *
		 * @var string
		 */
		public static $version = '0.1.1';

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'resister_customize' ) );
			add_action( 'lightning_site_header_prepend', array( __CLASS__, 'header_top_prepend_item' ), 11 );
			add_action( 'after_setup_theme', array( __CLASS__, 'header_top_add_menu' ) );
			$options = get_option( 'lightning_theme_options' );
			add_action( 'wp_head', array( __CLASS__, 'render_style' ), 5 );

			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_css' ), 9 );
			add_filter( 'vk_css_tree_shaking_handles', array( __CLASS__, 'css_tree_shaking_handles' ) );
		}

		/**
		 * Header Top Style URL
		 *
		 * @return string $css_url : css url
		 */
		public static function style_url() {
			$path    = wp_normalize_path( dirname( __FILE__ ) );
			$css_url = str_replace( wp_normalize_path( ABSPATH ), site_url() . '/', $path ) . '/css/header-top.css';
			return $css_url;
		}

		/**
		 * Load CSS function
		 *
		 * @return void
		 */
		public static function load_css() {
			if ( apply_filters( 'vk_header_top_print_css', true ) ) {
				$css_url = self::style_url();
				wp_enqueue_style( 'vk-header-top', $css_url, array(), self::$version );
			}
		}

		/**
		 * Set css tree shaking
		 *
		 * @param array $vk_css_tree_shaking_handles : add css tree shakinng array.
		 * @return array $vk_css_tree_shaking_handles
		 */
		public static function css_tree_shaking_handles( $vk_css_tree_shaking_handles ) {

			$vk_css_tree_shaking_handles = array_merge(
				$vk_css_tree_shaking_handles,
				array(
					'vk-header-top'
				)
			);

			return $vk_css_tree_shaking_handles;
		}

		/**
		 * Default Option.
		 */
		public static function default_option() {
			$args = array(
				'header_top_hidden'                  => false,
				'header_top_hidden_menu_and_contact' => false,
				'header_top_contact_icon'            => '<i class="far fa-envelope"></i>',
				'header_top_contact_txt'             => '',
				'header_top_contact_url'             => '',
				'header_top_tel_icon'                => '<i class="fas fa-mobile-alt"></i>',
				'header_top_tel_number'              => '',
				'header_top_background_color'        => '',
				'header_top_text_color'              => '',
				'header_top_border_bottom_color'     => '',
			);
			return $args;
		}

		/**
		 * Color Setting Enale
		 */
		public static function is_color_setting_enable() {
			$header_trance_option = get_option( 'lightning_header_trans_options' );

			$plugin_path = 'lightning-header-color-manager/lightning-header-color-manager.php';

			// ヘッダー透過が機能が有効な設定＆ページの場合はヘッダー上部機能は無効化する.

			if ( class_exists( 'Lightning_Header_Trans' ) && Lightning_Header_Trans::is_header_trans() ) {
				return false;
			}

			return true;

		}

		/**
		 * Customizer.
		 *
		 * @param \WP_Customize_Manager $wp_customize Customizer.
		 */
		public static function resister_customize( $wp_customize ) {

			global $vk_header_top_prefix;

			// Add Section.
			$wp_customize->add_section(
				'lightning_header_top',
				array(
					'title'    => $vk_header_top_prefix . __( 'Header Top Settings', 'lightning-g3-pro-unit' ),
					'priority' => 510,
				)
			);

			// header_top_hidden.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_hidden]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_hidden]',
				array(
					'label'    => __( 'Hide header top area', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_hidden]',
					'type'     => 'checkbox',
				)
			);

			// header_top_hidden_menu_and_contact.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_hidden_menu_and_contact]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_hidden_menu_and_contact]',
				array(
					'label'    => __( 'Text align center and hide menu and contact button', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_hidden_menu_and_contact]',
					'type'     => 'checkbox',
				)
			);

			/*************************************************
			 * Contact
			 */
			$wp_customize->add_setting(
				'header_top_contact_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'header_top_contact_title',
					array(
						'label'            => __( 'Contact Button', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_header_top',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
					)
				)
			);

			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_btn_hidden]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_btn_hidden]',
				array(
					'label'    => __( 'Hide header top contact button', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_btn_hidden]',
					'type'     => 'checkbox',
				)
			);

			// header_top_contact_txt.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_contact_txt]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'wp_kses_post', // sake for use i tags.
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_contact_txt]',
				array(
					'label'    => __( 'Contact button text', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_contact_txt]',
					'type'     => 'text',
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'lightning_header_top_options[header_top_contact_txt]',
				array(
					'selector'        => '.header-top-contact-btn',
					'render_callback' => '',
				)
			);

			// header_top_contact_url.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_contact_url]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw',
				)
			);
			$wp_customize->add_control(
				'ightning_header_top_options[header_top_contact_url]',
				array(
					'label'    => __( 'Contact button link url', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_contact_url]',
					'type'     => 'text',
				)
			);

			// header_top_contact_link_target.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_contact_link_target]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_contact_link_target]',
				array(
					'label'    => __( 'Open link target in new tab', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_contact_link_target]',
					'type'     => 'checkbox',
				)
			);

			// header_top_contact_icon.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_contact_icon]',
				array(
					'default'           => '<i class="far fa-envelope"></i>',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'wp_kses_post', // sake for use i tags.
				)
			);

			$icon_description = '';
			if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'ex_and_link' ) ) {
				$array            = array(
					'v4.7' => 'fa-envelope-o',
					'v5'   => 'far fa-envelope',
					'v6'   => 'fa-regular fa-envelope',
				);
				$icon_description = VkFontAwesomeVersions::ex_and_link( 'html', $array );
			}

			$wp_customize->add_control(
				new VK_Custom_Text_Control(
					$wp_customize,
					'lightning_header_top_options[header_top_contact_icon]',
					array(
						'label'       => __( 'Contact button icon', 'lightning-g3-pro-unit' ),
						'section'     => 'lightning_header_top',
						'settings'    => 'lightning_header_top_options[header_top_contact_icon]',
						'type'        => 'text',
						'description' => $icon_description,
					)
				)
			);

			/*************************************************
			 * Tel
			 */
			$wp_customize->add_setting(
				'header_top_tel',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'header_top_tel',
					array(
						'label'            => __( 'Contact Tel', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_header_top',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
					)
				)
			);

			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_tel_hidden]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_tel_hidden]',
				array(
					'label'    => __( 'Hide header top tel button', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_tel_hidden]',
					'type'     => 'checkbox',
				)
			);

			// Header Top Tel Number.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_tel_number]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_tel_number]',
				array(
					'label'    => __( 'Contact tel number', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_tel_number]',
					'type'     => 'text',
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'lightning_header_top_options[header_top_tel_number]',
				array(
					'selector'        => '.header-top-tel',
					'render_callback' => '',
				)
			);

			// Header Top Tel Icon.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_tel_icon]',
				array(
					'default'           => '<i class="fas fa-mobile-alt"></i>',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$icon_description = '';
			if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'ex_and_link' ) ) {
				$array            = array(
					'v4.7' => 'fa-phone-square',
					'v5'   => 'fas fa-phone-square-alt',
					'v6'   => 'fa-solid fa-square-phone',
				);
				$icon_description = VkFontAwesomeVersions::ex_and_link( 'html', $array );
			}

			$wp_customize->add_control(
				new VK_Custom_Text_Control(
					$wp_customize,
					'lightning_header_top_options[header_top_tel_icon]',
					array(
						'label'       => __( 'Contact tel icon', 'lightning-g3-pro-unit' ),
						'section'     => 'lightning_header_top',
						'settings'    => 'lightning_header_top_options[header_top_tel_icon]',
						'type'        => 'text',
						'description' => $icon_description,
					)
				)
			);

			/*************************************************
			 * Color
			 */
			$wp_customize->add_setting(
				'header_top_color',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'header_top_color',
					array(
						'label'            => __( 'Color', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_header_top',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
					)
				)
			);

			// Main Background Color.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_background_color]',
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
					'lightning_header_top_options[header_top_background_color]',
					array(
						'label'    => __( 'Background color', 'lightning-g3-pro-unit' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_background_color]',
					)
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'lightning_header_top_options[header_top_background_color]',
				array(
					'selector'        => '.header-top .container',
					'render_callback' => '',
				)
			);

			// Main Text Color.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_text_color]',
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
					'lightning_header_top_options[header_top_text_color]',
					array(
						'label'    => __( 'Text color', 'lightning-g3-pro-unit' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_text_color]',
					)
				)
			);

			// Main Text Color.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_border_bottom_color]',
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
					'lightning_header_top_options[header_top_border_bottom_color]',
					array(
						'label'    => __( 'Border bottom color', 'lightning-g3-pro-unit' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_border_bottom_color]',
					)
				)
			);
		}

		/**
		 * Header Top Prepend Item.
		 */
		public static function header_top_prepend_item() {

			$options = get_option( 'lightning_header_top_options' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			$header_top_style = '';

			// ヘッダートップ非表示処理.
			if ( ! empty( $options['header_top_hidden'] ) ) {
				return;
			}

			echo '<div class="header-top" id="header-top"' . $header_top_style . '>';
			echo '<div class="container">';

			$is_hidden_menu_and_contact = false;
			if ( ! empty( $options['header_top_hidden_menu_and_contact'] ) ) {
				$is_hidden_menu_and_contact = true;
			}

			$text_center = '';
			if ( $is_hidden_menu_and_contact ) {
				$text_center = ' text-center';
			}

			echo '<p class="header-top-description' . $text_center . '">' . apply_filters( 'header_top_description', get_bloginfo( 'description' ) ) . '</p>';

			if ( ! $is_hidden_menu_and_contact ) {
				if ( ! empty( $options['header_top_tel_number'] ) && empty( $options['header_top_tel_hidden'] ) ) {
					$tel_number = mb_convert_kana( esc_attr( $options['header_top_tel_number'] ), 'n' );

					$tel_icon = '';
					if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'get_icon_tag' ) ) {
						$tel_icon = apply_filters( 'header_top_tel_icon', VkFontAwesomeVersions::get_icon_tag( $options['header_top_tel_icon'] ) );
					}

					$contact_tel = '';

					if ( $tel_number ) {
						$contact_tel .= '<li class="header-top-tel">';
						if ( wp_is_mobile() ) {
							$contact_tel .= '<a class="header-top-tel-wrap" href="tel:' . $tel_number . '">' . $tel_icon . $tel_number . '</a>';
						} else {
							$contact_tel .= '<span class="header-top-tel-wrap">' . $tel_icon . $tel_number . '</span>';
						}
						$contact_tel .= '</li>';
					}
				} else {
					$contact_tel = '';
				}

				$args            = array(
					'theme_location' => 'header-top',
					'container'      => 'nav',
					'items_wrap'     => '<ul id="%1$s" class="%2$s nav">%3$s' . $contact_tel . '</ul>',
					'fallback_cb'    => '',
					'depth'          => 1,
					'echo'           => false,
				);
				$header_top_menu = wp_nav_menu( $args );
				if ( $header_top_menu ) {
					echo apply_filters( 'header-top-menu', $header_top_menu ); //phpcs:ignore
				} elseif ( $contact_tel || is_customize_preview() ) {
					echo '<nav><ul id="%1$s" class="%2$s nav">' . $contact_tel . '</ul></nav>';
				}

				if ( empty( $options['header_top_btn_hidden'] ) ) {
					echo self::header_top_contact_btn();
				}
			} // if ( ! is_hidden_menu_and_contact( $options ) ) {

			do_action( 'lightning_header_top_container_append' );

			echo '</div><!-- [ / .container ] -->';
			echo '</div><!-- [ / #header-top  ] -->';
		}

		/**
		 * Header Top Content Button
		 */
		public static function header_top_contact_btn() {

			$options = get_option( 'lightning_header_top_options' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			$contact_icon = '';
			if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'get_icon_tag' ) ) {
				$contact_icon = apply_filters( 'header_top_contact_icon', VkFontAwesomeVersions::get_icon_tag( $options['header_top_contact_icon'] ) );
			}

			$btn_txt = '';
			if ( ! empty( $options['header_top_contact_txt'] ) ) {
				$btn_txt = wp_kses_post( $options['header_top_contact_txt'] );
			}

			$link_url = '';
			if ( ! empty( $options['header_top_contact_url'] ) ) {
				$link_url = esc_url( $options['header_top_contact_url'] );
			}

			$link_target = '';
			if ( ! empty( $options['header_top_contact_link_target'] ) ) {
				$link_target = 'target="_blank"';
			}

			if ( ! empty( $btn_txt ) && $btn_txt && ! empty( $link_url ) && $link_url ) {
				$header_top_btn = '<a href="' . $link_url . '" class="btn btn-primary"' . $link_target . '>' . $contact_icon . $btn_txt . '</a>';

				$header_top_btn_html  = '<div class="header-top-contact-btn">';
				$header_top_btn_html .= apply_filters( 'header_top_btn', $header_top_btn );
				$header_top_btn_html .= '</div>';

				return apply_filters( 'lightning_header_top_btn_html', $header_top_btn_html );
			}
		}

		/**
		 * Header Top Menu.
		 */
		public static function header_top_add_menu() {
			register_nav_menus( array( 'header-top' => 'Header Top Navigation' ) );
		}

		/**
		 * Get Header Top Option
		 *
		 * @return array $options : header top options
		 */
		public static function get_header_top_options() {
			$options = get_option( 'lightning_header_top_options' );
			// 初期値をマージ.
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			// トップに色指定がない場合はヘッダーカラー設定の色をトップにも反映.
			if ( class_exists( 'VK_Header_Color' ) ) {
				$theme_options = get_option( 'lightning_theme_options' );
				// Topの背景色指定がない場合は通常の背景を適用.
				if ( empty( $options['header_top_background_color'] ) && ! empty( $theme_options['color_header_bg'] ) ) {
					$options['header_top_background_color'] = $theme_options['color_header_bg'];
				}
				if ( empty( $options['header_top_text_color'] ) ) {
					if ( class_exists( 'VK_Helpers' ) ) {
						$mode = VK_Helpers::color_mode_check( $options['header_top_background_color'] );
						if ( 'dark' === $mode['mode'] ) {
							$options['header_top_text_color'] = '#fff';
						}
					}
				}
			}
			return apply_filters( 'lightning_get_header_top_options', $options, 'lightning_get_header_top_options' );
		}

		/**
		 * Render Style
		 */
		public static function render_style() {

			$options = self::get_header_top_options();

			$dynamic_css = '';

			if ( true === self::is_color_setting_enable() ) {

				$text_color          = esc_html( $options['header_top_text_color'] );
				$bg_color            = esc_html( $options['header_top_background_color'] );
				$border_bottom_color = esc_html( $options['header_top_border_bottom_color'] );

				if ( $text_color || $bg_color || $border_bottom_color ) {

					$dynamic_css .= '.header-top{';
					if ( $text_color ) {
						$dynamic_css .= 'color:' . $text_color . ';';
					}
					if ( $bg_color ) {
						$dynamic_css .= 'background-color:' . $bg_color . ';';
					}
					if ( $border_bottom_color ) {
						$dynamic_css .= 'border-bottom: 1px solid ' . $border_bottom_color . ';';
					}
					$dynamic_css .= '}';
				}

				if ( $text_color ) {
					$dynamic_css .= '.header-top .nav li a{';
					$dynamic_css .= 'color:' . $text_color . ';';
					$dynamic_css .= '}';
				}

				if ( $dynamic_css ) {

					// delete before after space.
					$dynamic_css = trim( $dynamic_css );
					// convert tab and br to space.
					$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
					// Change multiple spaces to single space.
					$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

					$dynamic_css = '/* Header Top */' . $dynamic_css;

					wp_add_inline_style( 'vk-header-top', $dynamic_css );

				}
			}
		}

	}
	$lightning_header_top = new Lightning_Header_Top();

}
