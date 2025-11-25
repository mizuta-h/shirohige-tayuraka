<?php

class Lightning_Header_Trans {
	static function init() {
		add_action( 'customize_register', array( __CLASS__, 'resister_customize' ), 15, 1 );

		// 透過機能自体を有効にするかどうか（透過するかどうかではなくブロックテンプレートパーツの時に動作しなくするため）
		$active_trans_fanction = true;
		if ( class_exists( 'LTG_Block_Template_Parts' ) && LTG_Block_Template_Parts::is_replace( 'site_header' ) ) {
			// ブロックテンプレートの場合は処理しない
			$active_trans_fanction = false;
		}

		if ( $active_trans_fanction ) {
			add_action( 'wp_head', array( __CLASS__, 'render_style' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_js' ) );
			add_filter( 'lightning_get_class_names', array( __CLASS__, 'class_filter' ), 10, 2 );
			add_filter( 'lightning_localize_options', array( __CLASS__, 'remove_header_offset' ), 10, 1 );
		}
	}

	public static function remove_header_offset( $options ) {
		if ( self::is_header_trans() ) {
			$options['add_header_offset_margin'] = false;
		}
		return $options;
	}

	public static function load_js() {
		if ( filter_input( INPUT_GET, 'legacy-widget-preview', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) ) {
			return;
		}
		$o = self::get_options();
		// 高さ指定がない場合のみ JS を読み込んで制御する
		if ( empty( $o['header_height_pc'] ) ) {
			if ( self::is_header_trans() ) {
				wp_register_script( 'lightning-header-trans-js', LTG3_PRO_DIRECTORY_URL . '/inc/header-trans/_js/header-trans.js', array(), LIGHTNING_G3_PRO_UNIT_VERSION, true );
				wp_enqueue_script( 'lightning-header-trans-js' );
			}
		}
	}

	/**
	 * convert html color code to rgb string
	 *
	 * @param   string $color   html color code (ex, '#abcde1'
	 * @return  string $color   rgb string (ex, '123, 42, 1'
	 */
	static function to_rgb( $color ) {

		// $color が # を含んでいる場合は # を削除
		if ( substr( $color, 0, 1 ) == '#' ) {
			$color = substr( $color, 1 );
		}

		$buf = array();
		// $color が 3 文字の場合は 6 文字に変換
		if ( strlen( $color ) == 3 ) {
			$color = substr( $color, 0, 1 ) . substr( $color, 0, 1 )
				. substr( $color, 1, 1 ) . substr( $color, 1, 1 )
				. substr( $color, 2, 1 ) . substr( $color, 2, 1 );
		}

		// 2文字ずつに分割して格納
		for ( $i = 0; $i < 3; $i++ ) {
			$buf[ $i ] = substr( $color, $i * 2, 2 );
		}

		// 16進数を10進数に変換して返す
		return implode( ',', array( hexdec( $buf[0] ), hexdec( $buf[1] ), hexdec( $buf[2] ) ) );
	}

	/**
	 * ヘッダー画像を書き換える
	 */
	static function rewrite_header_image( $url ) {
		$o = self::get_options();
		return $o['header_image'];
	}

	/**
	 * ヘッダーテキストカラー
	 */
	static function render_style_text_color() {
		$vk_helpers = new VK_Helpers();
		$options    = self::get_options();

		$bg_mode = $vk_helpers->color_mode_check( $options['background_color'] );

		$text_color = null;
		if ( ! empty( $options['text_color'] ) ) {
			// 文字色指定がある場合はその色を反映
			$text_color = esc_html( $options['text_color'] );
		} elseif ( 'dark' === $bg_mode['mode'] ) {
			// 背景色が濃い場合文字色は白
			$text_color = '#fff';
		} else {
			// 背景色が明るい場合文字色は黒
			$text_color = '#333';
		}
		return $text_color;
	}

	/**
	 * ヘッダーサブ電話番号カラー
	 */
	static function render_style_contact_tel_color() {
		$vk_helpers = new VK_Helpers();
		$options    = self::get_options();
		$text_color = self::render_style_text_color();
		
		if ( isset( $options['header_top_background_opacity'] ) ) {
			$opacity = $options['header_top_background_opacity'];
		} else {
			$opacity = 0;
		}
		
		// 透明度が低くない場合は 指定の文字色にする
		if ( $opacity < 0.75 ) {
			// カスタマイザーで明示的に文字色が指定されている場合はその色に追従
			if ( ! empty( $options['text_color'] ) ) {
				$contact_color = $text_color;
			} else {
				// $text_colorが#fffの時は$contact_colorも追従
				if ( '#fff' === $text_color ) {
					$contact_color = $text_color;
				} else {
					// それ以外はnullにしてlightning-g3テーマのデフォルトCSSに任せる
					$contact_color = null;
				}
			}
		} else {
			$bg_mode       = $vk_helpers->color_mode_check( $options['background_color'] );
			$contact_color = null;
			// 背景が濃色の時
			if ( 'dark' === $bg_mode['mode'] ) {
				// 透過度が低い場合（背景色が濃い場合）
				// if ( 0.1 < $options['background_opacity'] ) {
					$contact_color = '#fff';
				// }
			}
		}
		return $contact_color;
	}

	/**
	 * ヘッダー透過時のスタイルを出力
	 */
	static function render_style() {
		$options = self::get_options();

		// ↓ 非推奨 : このアクションフックは削除予定
		do_action( 'lightning_header_trans_render', $options );

		if ( ! apply_filters( 'lightning_header_trans_enable_default_render', true ) ) {
			return;
		}
		if ( ! self::is_header_trans() ) {
			return;
		}

		if ( $options['header_image'] ) {
			add_filter( 'lightning_head_logo_image_url', array( __CLASS__, 'rewrite_header_image' ) );
		}

		$vk_helpers = new VK_Helpers();

		$text_color      = self::render_style_text_color();
		$contact_color   = self::render_style_contact_tel_color();

		$head_rgba       = 'rgba(' . self::to_rgb( $options['background_color'] ) . ',' . $options['background_opacity'] . ')';
		$border_rgba     = 'rgba(' . self::to_rgb( $text_color ) . ',0.1)';
		$header_top_rgba = 'rgba(' . self::to_rgb( $options['background_color'] ) . ',' . $options['header_top_background_opacity'] . ')';
		$bg_mode         = $vk_helpers->color_mode_check( $options['background_color'] );
		$theme_options   = lightning_get_theme_options();

		$dynamic_css = '<style>';

		// ↓ 非推奨 : このアクションフックは削除予定
		do_action( 'lightning_header_trans_pre_render_style', $options );

		// 注 : このテキストカラーは保存されているオプション値ではなく、出力するテキストカラー
		// よって、背景色が濃い場合など、テキストカラー指定がなくても出力されるケースは存在する。

		if ( $text_color ) {
			$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true,
			body:not(.header_scrolled) .site-header--trans--true .header-top-description{
				--vk-color-text-body:' . $text_color . ';
				--vk-color-text-light:' . $text_color . ';
			}
			body:not(.header_scrolled) .site-header--trans--true .global-nav-list > li > .acc-btn {
				border-color:' . $text_color . ';
			}
			body:not(.header_scrolled) .site-header--trans--true .global-nav-list > li > a{
				color:' . $text_color . ';
			}
			';
			// Touch device
			$mode = VK_Helpers::color_mode_check( $text_color );
			if ( $mode['mode'] === 'bright' ) {
				$icon_url       = get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-acc-icon-open-white.svg';
				$icon_url_close = get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-close-white.svg';
			} else {
				$icon_url       = get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-acc-icon-open-black.svg';
				$icon_url_close = get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-close-black.svg';
			}
			$dynamic_css     .= '@media (min-width: 768px) {';
				$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .global-nav > li:before { border-bottom:1px solid ' . $border_rgba . '; }';

				$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .global-nav-list > li > .acc-btn {
					background-image:url(' . $icon_url . ');
				}';
				$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .gMenu > li > .acc-btn.acc-btn-close {
					background-image:url(' . $icon_url_close . ');
				}';
			$dynamic_css     .= '}';
		}

		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .global-nav-list > li{
			--vk-color-border-hr: ' . $border_rgba . ';
		}';

		// 背景透過
		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true{
			background-color:' . $head_rgba . ';
			box-shadow:none;
			border-bottom:none;
		}';

		// 背景透過グラデーション
		if ( ! empty( $options['trans_mode'] ) ) {
			if ( 'gradation_pc' === $options['trans_mode'] ) {
				$dynamic_css .= '@media ( min-width : 992px) {';
			}
			if ( 'none' !== $options['trans_mode'] ) {
				$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true{
					background:linear-gradient( ' . $head_rgba . ' 70%, rgba(0,0,0,0));
					}';
			}
			if ( 'gradation_pc' === $options['trans_mode'] ) {
				$dynamic_css .= '}';
			}
		}

		// ヘッダーサブお問い合わせ電話番号
		if ( $contact_color ) {
			$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .contact-txt-tel{
		color:' . $contact_color . ';}';
		}

		// グローバルナビ（非スクロール）
		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .global-nav,';
		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .global-nav > li{
			background:none;border:none;
		}';
		$dynamic_css .= "body:not(.header_scrolled) .site-header--trans--true .global-nav .global-nav li{border-color:{$border_rgba};}";

		// ヘッダートップ
		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .header-top { background-color:' . $header_top_rgba . ';border-bottom:none}';

		// ヘッダーコンタクトお問い合わせボタン
		$btn_color_bg       = '';
		$btn_color_bg_hover = '';
		if ( ! empty( $theme_options['color_key'] ) ) {
			$btn_color_bg       = 'rgba(' . self::to_rgb( $theme_options['color_key'] ) . ',0.8)';
			$btn_color_bg_hover = $vk_helpers->color_auto_modifi( $theme_options['color_key'], 1.1 );
		}
		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .btn-primary {
			background-color:' . $btn_color_bg . ';
		}';
		$dynamic_css .= 'body:not(.header_scrolled) .site-header--trans--true .btn-primary:hover {
			background-color:' . $btn_color_bg_hover . ';
		}';

		/*
		ヘッダー高さの指定があり CSSで制御 JSを使わない場合
		/*-------------------------------------------*/
		if ( ! empty( $options['header_height_pc'] ) ) {

			// キャンペーンテキストの高さを設定
			$cp_options      = get_option( 'vk_campaign_text' );
			$campaign_height = 0;
			if ( isset( $cp_options['display'] ) ) {
				if (
					'show_in_front_page' === $cp_options['display'] && is_front_page() ||
					'show_in_full_page' === $cp_options['display']
					) {
						$campaign_height = 30;
				}
			}

			// ヘッダー高さ変数定義 : PC
			$dynamic_css  .= '@media ( min-width:992px ){:root{';
			$header_height = intval( $options['header_height_pc'] ) + $campaign_height;
			$dynamic_css  .= '--vk-header-height: ' . $header_height . 'px;';
			$dynamic_css  .= '}}';

			// ヘッダー高さ変数定義 : モバイル
			$dynamic_css .= '@media ( max-width:991px ){:root{';
			if ( ! empty( $options['header_height_mobile'] ) ) {
				$header_height = intval( $options['header_height_mobile'] ) + $campaign_height;
			} else {
				// モバイルの高さ指定がなかったらとりあえず適当に64で指定
				$header_height = 64 + $campaign_height;
			}
			$dynamic_css .= '--vk-header-height: ' . $header_height . 'px;';
			$dynamic_css .= '}
				.site-header {
					height: var(--vk-header-height);
				}
			}';

			if ( class_exists( 'VK_Page_Header' ) ) {
				$page_header_options = VK_Page_Header::get_options_post_type();
				if ( ! empty( $page_header_options['height_min'] ) ) {
					$normal_page_header_height = $page_header_options['height_min'];
					$dynamic_css              .= '
					.site-header {
						position:absolute;
					}
					.page-header {
						height: calc( ' . $normal_page_header_height . 'rem + ' . esc_attr( $options['header_height_pc'] ) . 'px );
					}
					.page-header-inner {
						margin-top: var(--vk-header-height);
					}
					.ltg-slide-text-set,
					.ltg-slide-button-next,
					.ltg-slide-button-prev {
						top: calc(50% + var(--vk-header-height) / 2 );
					}';
				}
			}

			// ltg-slide-text-set
			// ltg-slide-button-next
			// ltg-slide-button-prev

		} else {
			/*
			JSで高さ制御関連の処理をする場合
			/*-------------------------------------------*/
			if ( is_front_page() ) {
				// トップページ以外は最初からabsoluteにすると高さ検出する時点でガクンとなるので
				// jsで高さ検出した後でabsoluteをつける処理をしている
				$dynamic_css .= '.site-header--trans--true{position:absolute;}';
			}

			// 透過にした場合にjsを読み込んだ後で位置調整のスクリプトが走るため、あとからスライド上のテキストがガクっと落ちる。そのため、最初は文字をcssで透明にして、後からjsでcssの透過を解除している
			$dynamic_css .= '.ltg-slide .ltg-slide-text-set,
			.ltg-slide .ltg-slide-button-next,
			.ltg-slide .ltg-slide-button-prev,
			.page-header-inner { opacity:0;transition: opacity 1s; }
			.page-header {
				opacity:0;
			}
			';
		}

		$dynamic_css .= '</style>';

				// delete before after space
				$dynamic_css = trim( $dynamic_css );
				// convert tab and br to space
				$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
				// Change multiple spaces to single space
				$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );
				// wp_add_inline_style( 'lightning-design-style', $dynamic_css );

		echo $dynamic_css;
	}

	/*
		Customize
	/*-------------------------------------------*/

	static function resister_customize( $wp_customize ) {
		$default = self::option_default();

		// Add setting
		$wp_customize->add_setting(
			'ltg_trans_setting',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new VK_Custom_Html_Control(
				$wp_customize,
				'ltg_trans_setting',
				array(
					'label'            => __( 'Header Transmission', 'lightning-g3-pro-unit' ),
					'section'          => 'lightning_header',
					'type'             => 'text',
					'custom_title_sub' => '',
					'custom_html'      => '',
					// 'priority'         => 700,
					'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[enable]',
			array(
				'default'    => $default['enable'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			'lightning_header_trans_options[enable]',
			array(
				'label'           => __( 'Enable header transmission', 'lightning-g3-pro-unit' ),
				'section'         => 'lightning_header',
				'settings'        => 'lightning_header_trans_options[enable]',
				'type'            => 'select',
				'choices'         => array(
					'normal' => __( 'Not transparent', 'lightning-g3-pro-unit' ),
					'front'  => __( 'Front only', 'lightning-g3-pro-unit' ),
					'all'    => __( 'All Pages', 'lightning-g3-pro-unit' ),
				),
				'priority'        => 300,
				'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[background_color]',
			array(
				'default'    => $default['background_color'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'lightning_header_trans_options[background_color]',
				array(
					'label'           => __( 'Transmission mode Header Background Color', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_header',
					'settings'        => 'lightning_header_trans_options[background_color]',
					'priority'        => 300,
					'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[text_color]',
			array(
				'default'    => null,
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'lightning_header_trans_options[text_color]',
				array(
					'label'           => __( 'Transmission mode Header Text Color', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_header',
					'settings'        => 'lightning_header_trans_options[text_color]',
					'priority'        => 300,
					'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[background_opacity]',
			array(
				'default'    => $default['background_opacity'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'lightning_header_trans_options[background_opacity]',
				array(
					'label'           => __( 'Transmission mode Header Opacity', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_header',
					'settings'        => 'lightning_header_trans_options[background_opacity]',
					'type'            => 'range',
					'priority'        => 300,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
					'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[header_top_background_opacity]',
			array(
				'default'    => $default['header_top_background_opacity'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'lightning_header_trans_options[header_top_background_opacity]',
				array(
					'label'           => __( 'Transmission mode Header Top Opacity', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_header',
					'settings'        => 'lightning_header_trans_options[header_top_background_opacity]',
					'type'            => 'range',
					'priority'        => 300,
					'input_attrs'     => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
					'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[trans_mode]',
			array(
				'default'    => 'none',
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);
		$wp_customize->add_control(
			'lightning_header_trans_options[trans_mode]',
			array(
				'label'           => __( 'Gradation Mode', 'lightning-g3-pro-unit' ),
				'section'         => 'lightning_header',
				'settings'        => 'lightning_header_trans_options[trans_mode]',
				'type'            => 'select',
				'choices'         => array(
					'none'          => __( 'No gradation', 'lightning-g3-pro-unit' ),
					'gradation_pc'  => __( 'Active only wide screen', 'lightning-g3-pro-unit' ),
					'gradation_all' => __( 'Active all screen width', 'lightning-g3-pro-unit' ),
				),
				'priority'        => 300,
				'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[header_image]',
			array(
				'default'    => $default['header_image'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		// global $vk_header_top_prefix;

		// $wp_customize->add_section(
		// 'lightning_header',
		// array(
		// 'title'    => $vk_header_top_prefix . __( 'Header settings', 'lightning-g3-pro-unit' ),
		// 'priority' => 511,
		// )
		// );

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'lightning_header_trans_options[header_image]',
				array(
					'label'           => __( 'Transmission mode Header Logo Image', 'lightning-g3-pro-unit' ),
					'section'         => 'lightning_header',
					'settings'        => 'lightning_header_trans_options[header_image]',
					'description'     => __( 'Recommended image size : 280*60px', 'lightning-g3-pro-unit' ),
					'priority'        => 300,
					'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'lightning_header_trans_options[header_image]',
			array(
				'selector'        => '.site-header-logo.site-header-logo-trans-true',
				'render_callback' => '',
			)
		);

		// Add setting
		$wp_customize->add_setting(
			'ltg_trans_header_height_setting',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new VK_Custom_Html_Control(
				$wp_customize,
				'ltg_trans_header_height_setting',
				array(
					'label'            => '',
					'section'          => 'lightning_header',
					'type'             => 'text',
					'custom_title_sub' => __( 'Header Height', 'lightning-g3-pro-unit' ),
					'custom_html'      => __( '※ ヘッダーの高さ自体を変更するための指定ではありません。通常JavaScriptで自動的にヘッダーの高さを検出しますが、ここで予めヘッダーの高さが指定する事により、そのJavaScriptのファイルの読み込み及びプログラムの実行の必要がなくなるので少しだけ反応が早くなるという機能です。', 'lightning-g3-pro-unit' ),
					'priority'         => 300,
					'active_callback'  => array( 'VK_Header_Customize', 'is_default_header' ),
				)
			)
		);
		$device = array(
			'pc'     => 'PC',
			'mobile' => 'Mobile',
		);
		foreach ( $device as $key => $value ) {
			$wp_customize->add_setting(
				'lightning_header_trans_options[header_height_' . $key . ']',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'esc_attr',
				)
			);

			$wp_customize->add_control(
				new VK_Custom_Text_Control(
					$wp_customize,
					'lightning_header_trans_options[header_height_' . $key . ']',
					array(
						'label'           => __( 'Header height', 'lightning-g3-pro-unit' ) . ' [ ' . $value . ' ]',
						'section'         => 'lightning_header',
						'settings'        => 'lightning_header_trans_options[header_height_' . $key . ']',
						'type'            => 'number',
						'description'     => '',
						'input_after'     => 'px',
						'priority'        => 300,
						'active_callback' => array( 'VK_Header_Customize', 'is_default_header' ),
					)
				)
			);
		}
	}

	static function option_default() {
		return array(
			'enable'                        => false,
			'background_color'              => '#ffffff',
			'background_opacity'            => 0.3,
			'header_top_background_opacity' => 0,
			'text_color'                    => null, // 未指定の場合は背景色によって自動指定されるのでここでは指定しない
			'header_image'                  => '',
		);
	}

	/**
	 * この機能に対するオプションの取得。未設定時はデフォルト値が返る
	 * get_option( 'lightning_header_trans_options',$default_array ) だけで処理すると、
	 * カスタマイザで一つの項目だけ変更して保存されると他の項目はデフォルト値で保存されず公開画面で Undefined になる。
	 * ちなみにカスタマイズ画面ではカスタマイザで指定したデフォルト値が入った状態で表示されるので Undefined にならずに気づきにくいので注意
	 * これを回避するために wp_parse_args() でデフォルト値の配列と結合してから返す
	 */
	static function get_options() {
		$options = get_option( 'lightning_header_trans_options' );
		$default = self::option_default();
		$o       = wp_parse_args( $options, $default );
		// 値が空で保存されている場合は wp_parse_args ではデフォルト値が反映されないのでここで再設定
		if ( empty( $o['background_color'] ) ){
			$o['background_color'] = $default['background_color'];
		}
		return $o;
	}

	static function is_header_trans() {
		$return = false;
		$o      = self::get_options();

		// 透過未設定 || 透過しない設定の場合 false
		if ( empty( $o['enable'] ) || ( isset( $o['enable'] ) && 'normal' === $o['enable'] ) ) {
			$return = false;

		} elseif ( isset( $o['enable'] ) && true === $o['enable'] && is_front_page() ) {
			// 初期のトップページのみ透過対応だった場合
			$return = true;

		} elseif ( ! empty( $o['enable'] ) && 'front' === $o['enable'] ) {
			if ( is_front_page() ) {
				$return = true;
			} else {
				$return = false;
			}
		} elseif ( ! empty( $o['enable'] ) && 'all' === $o['enable'] ) {
			$return = true;
		}

		if ( ! is_front_page() && is_singular() ) {
			global $post;
			$meta = get_post_meta( $post->ID, '_lightning_design_setting', true );
			if ( ! empty( $meta['header_trans'] ) ) {
				if ( 'true' === $meta['header_trans'] ) {
					$return = true;
				} elseif ( 'normal' === $meta['header_trans'] ) {
					$return = false;
				}
			}
		}
		return apply_filters( 'lightning_is_header_trans', $return );
	}

	/**
	 * lightning_get_class_namesに対するフィルターフック
	 * 透過の時に識別用classを追加する
	 * header_logo (.site-header-logo) に対する透過クラス名追加
	 */
	static function class_filter( $class_names ) {

		// print '<pre style="text-align:left">';print_r($class_names);print '</pre>';
		if ( isset( $class_names['site-header'] ) && self::is_header_trans() ) {
			$class_names['site-header'][] = 'site-header--trans--true';
		}

		if ( isset( $class_names['site-header-logo'] ) && self::is_header_trans() ) {
			$class_names['site-header-logo'][] = 'site-header-logo--trans--true';
		}

		return $class_names;
	}
}
