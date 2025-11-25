<?php
/**
 * Header Customize
 *
 * @package Lightning G3 Pro
 */
if ( ! class_exists( 'VK_Header_Color' ) ) {

	/**
	 * Footer Customize Class
	 */
	class VK_Header_Color {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'resister_customize_color' ) );
			add_action( 'wp_head', array( __CLASS__, 'enqueue_style' ), 5 );
		}

		public static function resister_customize_color( $wp_customize ) {

			$wp_customize->add_setting(
				'color_header_subtitle',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'color_header_subtitle',
					array(
						'label'            => __( 'Header color', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_header',
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => '',
						'priority'         => 1,
						'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
					)
				)
			);

			// Header Bg
			$wp_customize->add_setting(
				'lightning_theme_options[color_header_bg]',
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
					'color_header_bg',
					array(
						'label'    => __( 'Header background color', 'lightning-g3-pro-unit' ),
						'section'  => 'lightning_header',
						'settings' => 'lightning_theme_options[color_header_bg]',
						'priority' => 1,
						'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
					)
				)
			);

			// color
			// $wp_customize->add_setting(
			// 'lightning_theme_options[color_header_text]',
			// array(
			// 'default'           => '',
			// 'type'              => 'option',
			// 'capability'        => 'edit_theme_options',
			// 'sanitize_callback' => 'sanitize_hex_color',
			// )
			// );
			// $wp_customize->add_control(
			// new WP_Customize_Color_Control(
			// $wp_customize,
			// 'color_header_text',
			// array(
			// 'label'    => __( 'Header text color', 'lightning-g3-pro-unit' ),
			// 'section'  => 'lightning_header',
			// 'settings' => 'lightning_theme_options[color_header_text]',
			// 'priority' => 1,
			// )
			// )
			// );

			// color
			$wp_customize->add_setting(
				'lightning_theme_options[color_global_nav_bg]',
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
					'color_global_nav_bg',
					array(
						'label'           => __( 'Global Nav background color', 'lightning-g3-pro-unit' ),
						'section'         => 'lightning_header',
						'settings'        => 'lightning_theme_options[color_global_nav_bg]',
						'priority'        => 1,
						'active_callback' => array( 'VK_Header_Color', 'is_global_nav_penetration_form' ),
					)
				)
			);

			// color
			$wp_customize->add_setting(
				'lightning_theme_options[global_nav_border_top]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_boolean' ),
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'global_nav_border_top',
					array(
						'label'           => __( 'Add Global Nav border top', 'lightning-g3-pro-unit' ),
						'section'         => 'lightning_header',
						'settings'        => 'lightning_theme_options[global_nav_border_top]',
						'priority'        => 1,
						'type'            => 'checkbox',
						'description'     => '<p class="admin-customize-descpription">' . __( 'border color is key color.', 'lightning-g3-pro-unit' ) . '</p>',
						'active_callback' => array( 'VK_Header_Color', 'is_global_nav_penetration_form' ),
					)
				)
			);
		}

		public static function is_global_nav_penetration( $layout ) {
			if ( $layout == 'center_and_nav-penetration' ||
				$layout == 'head-sub-contact_and_nav-penetration' ||
				$layout == 'head-sub-widget_and_nav-penetration'
				) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * カスタマイズ画面でナビゲーションの背景色の設定項目を表示するかしないか？
		 *
		 * @return bool 表示する場合が true
		 */
		public static function is_global_nav_penetration_form( $control ) {
			if ( $control->manager->get_setting( 'lightning_theme_options[header_layout]' ) ) {
				$layout = $control->manager->get_setting( 'lightning_theme_options[header_layout]' )->value();
			} else {
				$layout = '';
			}

			// ナビが貫通の場合のみ && ブロックテンプレートを使わない場合 にナビ背景指定を表示する
			if ( self::is_global_nav_penetration( $layout ) &&  ! VK_Header_Customize::is_block_template_parts_header( $control ) ){
				return true;
			} else {
				return false;
			}

		}

		/*
		色の出力
		/*-------------------------------------------*/
		public static function enqueue_style() {
			if ( function_exists( 'lightning_get_theme_options' ) ){
				$options     = lightning_get_theme_options();
			} else {
				$options     = get_option( 'lightning_theme_options' );
			}
			$dynamic_css = '';
			$color_header_bg = '';
			$color_global_nav_bg = '';
			if ( ! empty( $options['color_header_bg'] ) ) {
				$color_header_bg = esc_attr( $options['color_header_bg'] );
				$dynamic_css    .= '
					.site-header {
						background-color:' . $color_header_bg . ' ;
					}';

				if ( class_exists( 'VK_Helpers' ) ) {
					$mode = VK_Helpers::color_mode_check( $color_header_bg );
					if ( $mode['mode'] === 'dark' ) {
						$dynamic_css .= '
						.site-header {
							--vk-color-text-body: rgba( 255,255,255,0.95 );
						}
						.site-header-sub .contact-txt-tel {
							color:rgba( 255,255,255,0.95 );
						}';

						// ヘッダー透過じゃない場合のみボタンの色をゴーストに変更
						if (
							! class_exists( 'Lightning_Header_Trans' ) ||
							( class_exists( 'Lightning_Header_Trans' ) && ! Lightning_Header_Trans::is_header_trans() )
						){
							$dynamic_css .= '
							.site-header-sub .btn {
								background:none;
								border:1px solid rgba( 255,255,255,0.7 );
							}
							.site-header-sub .btn:hover {
								background:var(--vk-color-primary);
								border-color:rgba( 255,255,255,0.5 );
							}
							';
						}


						// Mobile Nav Button
						$dynamic_css .= '
						.vk-mobile-nav-menu-btn {
							border-color:rgba(255,255,255,0.7);
							background-color:rgba(0,0,0,0.2);
							background-image: url(' . get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-btn-white.svg);
						}
						.global-nav .acc-btn{
							background-image: url(' . get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-acc-icon-open-white.svg);
						}
						.global-nav .acc-btn.acc-btn-close {
							background-image: url(' . get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-close-white.svg);
						}
						.vk-menu-acc .acc-btn{
							border: 1px solid #fff;
						}';

						// Dark Color ///////////////////
						$dynamic_css .= '
							.headerTop {
								border-bottom:1px solid rgba(255,255,255,0.2);
							}';

						$dynamic_css .= '
							.global-nav {
								--vk-color-border-hr: rgba(255, 255, 255, 0.2);
							}
							.header_scrolled .global-nav>li{
								border-left:1px solid rgba(255,255,255,0.2);
							}';
					}
				}
			}

			$layout = '';
			if ( ! empty( $options['header_layout'] ) ) {
				$layout = $options['header_layout'];
			}

			/**
			 * グローバルナビの背景色
			 */
			if ( ! empty( $options['color_global_nav_bg'] ) && self::is_global_nav_penetration( $layout ) ) {
				$color_global_nav_bg = esc_attr( $options['color_global_nav_bg'] );
				/**
				 * 名目はグローバルナビだが、スクロール時は構造上 .site-header に色を付けざるをえない
				 */
				$dynamic_css .= '.global-nav,
				.header_scrolled .site-header{
					background-color:' . $color_global_nav_bg . ';
				}
				';
				if ( class_exists( 'VK_Helpers' ) ) {
					$mode = VK_Helpers::color_mode_check( $color_global_nav_bg );
					if ( $mode['mode'] === 'dark' ) {
						$dynamic_css .= '
						.global-nav {
							--vk-color-border-hr: rgba(255, 255, 255, 0.2);
						}
						.global-nav-list.vk-menu-acc > li > .acc-btn-open {
							background-image: url(' . get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-acc-icon-open-white.svg);
						}
						.global-nav-list.vk-menu-acc > li > .acc-btn-close {
							background-image: url(' . get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-close-white.svg);
						}
						.global-nav-list>li>a{
							color:#fff;
						}';
					} else {
						$dynamic_css .= '.global-nav-list>li>a{
							color:#333;
						}';
					}
				}
			}

			/**
			 * スクロール時の固定部分背景色
			 */
            if ( $color_global_nav_bg ) {
				$dynamic_css .= '
				.header_scrolled .site-header {
					background-color:' . $color_global_nav_bg . ';
				}';
            } elseif ( $color_header_bg ) {
				$dynamic_css .= '
				.header_scrolled .site-header {
					background-color:' . $color_header_bg . ';
				}';
			}

			/**
			 * グロナビボーダートップ
			 */
			if ( ! empty( $options['global_nav_border_top'] ) && self::is_global_nav_penetration( $layout ) ) {
				$dynamic_css .= '
				.global-nav,
				.header_scrolled .site-header {
					border-top:2px solid var(--vk-color-primary);
				}
				.header_scrolled .global-nav{
					border-top:none;
				}';
			}

			if ( $dynamic_css ) {
				// delete before after space
				$dynamic_css = trim( $dynamic_css );
				// convert tab and br to space
				$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
				// Change multiple spaces to single space
				$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

				global $vk_header_customize_style_handle;
				wp_add_inline_style( $vk_header_customize_style_handle, $dynamic_css );
				// echo '<style id="lightning-color-custom-for-plugins" type="text/css">' . $dynamic_css . '</style>';

			}
		}
	}
	$vk_header_customize = new VK_Header_Color();
}
