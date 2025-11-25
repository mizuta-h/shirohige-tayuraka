<?php

use VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions;

if ( ! class_exists( 'Lightning_Header_layout' ) ) {
	class Lightning_Header_layout {

		public static $version = LIGHTNING_G3_PRO_UNIT_VERSION;

		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'lightning_site_header_logo_after', array( $this, 'lightning_header_sub' ) );
			add_action( 'widgets_init', array( $this, 'register_widget' ) );
			add_filter( 'lightning_get_class_names', array( $this, 'change_layout_class' ) );
			add_action( 'wp_head', array( __CLASS__, 'render_style' ), 5 );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_css' ) );
			add_filter( 'vk_css_tree_shaking_handles', array( __CLASS__, 'css_tree_shaking_handles' ) );
			$options = get_option( 'lightning_theme_options' );
			if ( isset( $options['g_nav_scrolled_layout'] ) && 'no-fix' === $options['g_nav_scrolled_layout'] ) {
				remove_filter( 'lightning_localize_options', 'lightning_global_nav_fix', 10, 1 );
			}
		}

		/**
		 *
		 *
		 * @param  [type] $wp_customize [description]
		 * @return [type]               [description]
		 */
		public static function customize_register( $wp_customize ) {

			$priority = 1;

			$wp_customize->add_setting(
				'header_layout_subtitle',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'header_layout_subtitle',
					array(
						'label'            => __( 'Header Layout', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_header',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
						'priority'         => $priority,
						'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
					)
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'lightning_theme_options[header_layout]',
				array(
					'selector'        => '.site-header-container',
					'render_callback' => '',
				)
			);
			$wp_customize->add_setting(
				'lightning_theme_options[header_layout]',
				array(
					'default'           => 'nav-float',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'lightning_theme_options[header_layout]',
				array(
					'label'    => __( 'Header Layout', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_header',
					'settings' => 'lightning_theme_options[header_layout]',
					'type'     => 'select',
					'choices'  => array(
						'nav-float'                  => __( 'Nav Float', 'lightning-g3-pro-unit' ),
						'nav-float_and_vertical'     => __( 'Nav Float and Vertical ( Beta )', 'lightning-g3-pro-unit' ),
						'center'                     => __( 'Align Center', 'lightning-g3-pro-unit' ),
						'center_and_vertical'        => __( 'Align Center and Vertical ( Beta )', 'lightning-g3-pro-unit' ),
						'center_and_nav-penetration' => __( 'Center Logo & Nav Penetration', 'lightning-g3-pro-unit' ),
						'head-sub-contact_and_nav-penetration' => __( 'Header Sub Contact & Nav Penetration', 'lightning-g3-pro-unit' ),
						'head-sub-widget_and_nav-penetration' => __( 'Header Sub Widget & Nav Penetration', 'lightning-g3-pro-unit' ),
					),
					'priority'	=> $priority,
					'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
					// 'description' => __( '* If you save and reload after making changes, the number of the widget area setting panels will increase or decrease.', 'lightning-g3-pro-unit' ),
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'lightning_theme_options[g_nav_scrolled_layout]',
				array(
					'selector'        => '.global-nav',
					'render_callback' => '',
				)
			);
			$wp_customize->add_setting(
				'lightning_theme_options[g_nav_scrolled_layout]',
				array(
					'default'           => 'nav-center',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'lightning_theme_options[g_nav_scrolled_layout]',
				array(
					'label'       => __( 'Global Nav scrolled layout', 'lightning-g3-pro-unit' ),
					'section'     => 'lightning_header',
					'settings'    => 'lightning_theme_options[g_nav_scrolled_layout]',
					'type'        => 'select',
					'choices'     => array(
						'no-fix'                 => __( 'Not fixed', 'lightning-g3-pro-unit' ),
						/* メニュー1階層の各項目が自動的に少し広がって最小幅にならないので中央揃えは一旦コメントアウト */
						'nav-center'             => __( 'Fixed nav align center', 'lightning-g3-pro-unit' ),
						'nav-container'          => __( 'Fixed nav container width', 'lightning-g3-pro-unit' ),
						'logo-and-nav-container' => __( 'Fixed Logo & Nav Float', 'lightning-g3-pro-unit' ),
						'logo-and-nav-full'      => __( 'Fixed Logo & Nav Float Full width', 'lightning-g3-pro-unit' ),
					),
					'priority'    => $priority,
					'description' => '<ul><li>' . __( 'If you change to Not fixed or other layout that it will not be reflected unless it is saved and reloaded.', 'lightning-g3-pro-unit' ) . '</li><li>' . __( 'If you select Nav Float, adjust the number of navigations and the number of characters as needed so that the navigation does not cover the logo.', 'lightning-g3-pro-unit' ) . '</li></ul>',
					'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			);

			// head logo
			$wp_customize->add_setting(
				'lightning_theme_options[g_nav_scrolled_logo]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw',
				)
			);
			$description  = '<ul>';
			$description .= '<li>' . __( 'If not set, the header logo image will be reflected.', 'lightning-g3-pro-unit' ) . '</li>';
			$description .= '<li>' . __( 'Make it the same size as the logo image in the header logo image.', 'lightning-g3-pro-unit' ) . '</li>';
			$description .= '</ul>';
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'g_nav_scrolled_logo',
					array(
						'label'       => __( 'Scrolled logo', 'lightning-g3-pro-unit' ),
						'section'     => 'lightning_header',
						'settings'    => 'lightning_theme_options[g_nav_scrolled_logo]',
						'priority'    => $priority,
						'description' => $description,
						'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
					)
				)
			);

			$wp_customize->add_setting(
				'lightning_theme_options[header_logo_mobile_position]',
				array(
					'default'           => 'center',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				'lightning_theme_options[header_logo_mobile_position]',
				array(
					'label'       => __( 'Position of header logo on mobile', 'lightning-g3-pro-unit' ),
					'section'     => 'lightning_header',
					'settings'    => 'lightning_theme_options[header_logo_mobile_position]',
					'type'        => 'select',
					'choices'     => array(
						'center' => __( 'Center', 'lightning-g3-pro-unit' ),
						'left'   => __( 'Left', 'lightning-g3-pro-unit' ),
					),
					'priority'    => $priority,
					'description' => '',
					'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			);

			// カスタマイズ画面でExUnitのお問い合わせ情報への編集ショートカット
			$wp_customize->selective_refresh->add_partial(
				'vkExUnit_contact[tel_number]',
				array(
					'selector'        => '.site-header-sub .contact-txt-tel',
					'render_callback' => '',
				)
			);
		} // public static function customize_register( $wp_customize ){

		public static function change_layout_class( $class_names ) {

			// 初期レイアウトを一旦リセット
			if ( isset( $class_names['site-header'] ) ) {
				$class_names['site-header'] = array_diff( $class_names['site-header'], array( 'site-header--layout--nav-float' ) );
			}
			if ( isset( $class_names['global-nav'] ) ) {
				$class_names['global-nav'] = array_diff( $class_names['global-nav'], array( 'global-nav--layout--float-right' ) );
			}

			$options = get_option( 'lightning_theme_options' );

			if ( empty( $options['header_layout'] ) ) {
				$layout = 'nav-float';
			} else {
				$layout = $options['header_layout'];
			}

			if ( 'nav-float' === $layout ) {
				$class_names['site-header'][] = 'site-header--layout--nav-float';
				$class_names['global-nav'][]  = 'global-nav--layout--float-right';
			} elseif ( 'nav-float_and_vertical' === $layout ) {
				$class_names['site-header'][] = 'site-header--layout--nav-float';
				$class_names['global-nav'][]  = 'global-nav--layout--float-right global-nav--text-layout--vertical';
			} elseif ( 'center' === $layout ) {
				// ロゴはデフォルトがセンターなので指定しない と思いきや、幅が広い時はデフォルトが左なのでやはり指定必要
				$class_names['site-header'][] = 'site-header--layout--center';
				$class_names['global-nav'][]  = 'global-nav--layout--center';
			} elseif ( 'center_and_vertical' === $layout ) {
				// ロゴはデフォルトがセンターなので指定しない と思いきや、幅が広い時はデフォルトが左なのでやはり指定必要
				$class_names['site-header'][] = 'site-header--layout--center';
				$class_names['global-nav'][]  = 'global-nav--layout--center global-nav--text-layout--vertical';
			} elseif ( 'center_and_nav-penetration' === $layout ) {
				$class_names['site-header'][] = 'site-header--layout--center';
				$class_names['global-nav'][]  = 'global-nav--layout--penetration';
			} elseif ( 'head-sub-contact_and_nav-penetration' === $layout ) {
				$class_names['site-header'][] = 'site-header--layout--sub-active';
				$class_names['global-nav'][]  = 'global-nav--layout--penetration';
			} elseif ( 'head-sub-widget_and_nav-penetration' === $layout ) {
				$class_names['site-header'][] = 'site-header--layout--sub-active';
				$class_names['global-nav'][]  = 'global-nav--layout--penetration';
			}

			// Scrolled Layout
			if ( empty( $options['g_nav_scrolled_layout'] ) ) {
				$layout = 'nav-center';
			} else {
				$layout = $options['g_nav_scrolled_layout'];
			}

			$class_names['global-nav'][]            = 'global-nav--scrolled--' . $layout;
			$class_names['site-header-logo'][]      = 'site-header-logo--scrolled--' . $layout;
			$class_names['site-header-container'][] = 'site-header-container--scrolled--' . $layout;

			if ( ! empty( $options['header_logo_mobile_position'] ) ) {
				$class_names['site-header-logo'][]      = 'site-header-logo--mobile-position--' . $options['header_logo_mobile_position'];
				$class_names['site-header-container'][] = 'site-header-container--mobile-width--full';
			}

			return $class_names;
		}

		/*
		-------------------------------------------
		  Header top nav
		-------------------------------------------
		*/

		public static function lightning_header_sub() {

			$theme_options = get_option( 'lightning_theme_options' );
			if ( empty( $theme_options['header_layout'] ) ) {
				return;
			} elseif ( 'head-sub-widget_and_nav-penetration' === $theme_options['header_layout'] ) {

				if ( is_active_sidebar( 'header-right-widget-area' ) ) {
					echo '<div class="site-header-sub">';
					dynamic_sidebar( 'header-right-widget-area' );
					echo '</div>';
					return;
				}
			} elseif ( 'head-sub-contact_and_nav-penetration' === $theme_options['header_layout'] ) {
				$default             = array(
					'contact_txt'          => __( 'Please feel free to inquire.', 'lightning-g3-pro-unit' ),
					'tel_icon'             => 'fas fa-phone-square',
					'tel_number'           => '000-000-0000',
					'contact_time'         => __( 'Office hours 9:00 - 18:00 [ Weekdays except holidays ]', 'lightning-g3-pro-unit' ),
					'contact_link'         => home_url(),
					'contact_target_blank' => false,
					'button_text'          => __( 'Contact us', 'lightning-g3-pro-unit' ),
					'button_text_small'    => '',
					'short_text'           => __( 'Contact us', 'lightning-g3-pro-unit' ),
				);
				$veu_option          = get_option( 'vkExUnit_contact' );
				$veu_contact_options = wp_parse_args( $veu_option, $default );
				$link_target         = ! empty( $veu_option['contact_target_blank'] ) ? ' target="_blank"' : '';

				$cont  = '';
				$cont .= '<div class="site-header-sub">';
				$cont .= '<p class="contact-txt">';
				if ( ! empty( $veu_contact_options['contact_txt'] ) ) {
					$cont .= '<span class="contact-txt-catch">' . nl2br( esc_textarea( $veu_contact_options['contact_txt'] ) ) . '</span>';
				}

				// 電話番号アイコン.
				$tel_icon = '';
				if ( ! empty( $veu_contact_options['tel_icon'] ) ) {
					if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'get_icon_tag' ) ) {
						$tel_icon = VkFontAwesomeVersions::get_icon_tag( $veu_contact_options['tel_icon'], 'contact-txt-tel_icon' );
					}
				}

				if ( ! empty( $veu_contact_options['tel_number'] ) ) {
					$cont .= '<span class="contact-txt-tel">' . $tel_icon . $veu_contact_options['tel_number'] . '</span>';
				}
				if ( ! empty( $veu_contact_options['contact_time'] ) ) {
					$cont .= '<span class="contact-txt-time">' . nl2br( esc_textarea( $veu_contact_options['contact_time'] ) ) . '</span>';
				}
				$cont .= '</p>';
				if ( ( ! empty( $veu_contact_options['button_text'] ) ) && ( ! empty( $veu_contact_options['contact_link'] ) ) ) {
					$cont .= '<div class="contact-btn">';

					// Envelope Icon.
					$icon = 'far fa-envelope';
					if ( method_exists( 'VektorInc\VK_Font_Awesome_Versions\VkFontAwesomeVersions', 'class_switch' ) ) {
						$tel_icon = VkFontAwesomeVersions::class_switch( 'fa fa-envelope-o', 'far fa-envelope', 'fa-regular fa-envelope' );
					}

					$cont .= '<a href="' . esc_url( $veu_contact_options['contact_link'] ) . '" class="btn btn-primary"' . $link_target . '><i class="' . $icon . '"></i>' . wp_kses_post( $veu_contact_options['button_text'] ) . '</a>';
					$cont .= '</div>';

					$cont .= '</div>';

					$cont = apply_filters( 'lightning_header_contact_custom', $cont );

					echo $cont;
				}
			}

		} // public static function lightning_header_sub() {

		public static function register_widget() {
			$description  = '<ul>';
			$description .= '<li>この領域は ヘッダーレイアウトで「ヘッダーサブウィジェット有効」の場合のみ反映されます。</li>';
			$description .= '<li>この領域にはテキストウィジェット、カスタムHTMLウィジェット、固定ページ本文ウィジェット、バナーウィジェットなどを配置する事を想定しています。</li>';
			$description .= '<li>内容に応じてCSSによるデザイン指定が別途が必要です。<br>
			モバイル時に表示にする場合は、「外観 > カスタマイズ」画面の「追加CSS」パネルなどに <br>@media (max-width: 991px){<br>.site-header-sub { display:block; }<br>}</br>を記載してください。<br>※CSSの書き方はサポート対象外です。</li>';
			$description .= '</ul>';
			register_sidebar(
				array(
					'name'          => __( 'Header Right Area', 'lightning-g3-pro-unit' ),
					'id'            => 'header-right-widget-area',
					'before_widget' => '<aside class="widget %2$s" id="%1$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title site-header-sub-title">',
					'after_title'   => '</h4>',
					'description'   => $description,
				)
			);
		}

		public static function style_url() {
			$path    = wp_normalize_path( dirname( __FILE__ ) );
			$css_url = str_replace( wp_normalize_path( ABSPATH ), site_url() . '/', $path ) . '/package/css/header-layout.css';
			return $css_url;
		}

		public static function load_css() {
			if ( apply_filters( 'vk_header_layout_print_css', true ) ) {
				$css_url = self::style_url();
				wp_enqueue_style( 'vk-header-layout', $css_url, array(), self::$version );
			}
		}

		public static function css_tree_shaking_handles( $vk_css_tree_shaking_handles ) {
			$vk_css_tree_shaking_handles = array_merge(
				$vk_css_tree_shaking_handles,
				array(
					'vk-header-layout',
				)
			);

			return $vk_css_tree_shaking_handles;
		}

		/**
		 * Render Style
		 */
		public static function render_style() {

			$options = get_option( 'lightning_theme_options' );
			if ( ! empty( $options['head_logo'] ) || ! empty( $options['g_nav_scrolled_logo'] ) ) {
				if ( ! empty( $options['g_nav_scrolled_logo'] ) ) {
					$img_url = esc_url( $options['g_nav_scrolled_logo'] );
				} else {
					$img_url = esc_url( $options['head_logo'] );
				}
				$dynamic_css  = '/* Header Layout */';
				$dynamic_css .= ':root {--vk-header-logo-url:url(' . $img_url . ');}';

				if ( $dynamic_css ) {

					// delete before after space.
					$dynamic_css = trim( $dynamic_css );
					// convert tab and br to space.
					$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
					// Change multiple spaces to single space.
					$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

					wp_add_inline_style( 'vk-header-layout', $dynamic_css );

				}
			}

		}

	} // class Lightning_Header_layout {

	// フックさせるために変数に入れているので外さない。
	$lightning_header_contact = new Lightning_Header_layout();
}
