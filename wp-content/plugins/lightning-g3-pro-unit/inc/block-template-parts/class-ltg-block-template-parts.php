<?php
/**
 * Block Template Parts
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

class LTG_Block_Template_Parts {

	/**
	 * Constructor
	 */
	public function __construct() {

		// 外観 > カスタマイズ > Lightning ヘッダー設定 / フッター設定 でのブロックテンプレートパーツ指定処理
		add_action( 'customize_register', array( __CLASS__, 'resister_customize' ) );
		// ブロックテンプレートパーツ作成用のカスタム投稿タイプを登録
		add_action( 'init', array( __CLASS__, 'register_post_type_ltg_template_parts' ), 7 );

		// ヘッダーとフッターは処理が近いのでループで処理
		$positions = array(
			'header',
			'footer',
		);
		foreach ( $positions as $position ) {
			// ヘッダーとフッターの非表示制御用のフィルターフックで処理を実行
			add_filter( 'lightning_is_site_' . $position, array( __CLASS__, 'template_parts_filter' ), 10, 2 );
		}

		// 特殊なインストール環境によっては lightning_get_theme_options の読み込みより先に実行されることがある
		if ( function_exists( 'lightning_get_theme_options' ) ) {
			// ほぼほぼ関係ないが、lightning_get_theme_options の中のフックを使われる事があるので一応 lightning_get_theme_options() から取得
			$options = lightning_get_theme_options();
		} else {
			$options = get_option( 'lightning_theme_options' );
		}

		// ブロックテンプレートヘッダーを使用する場合
		if ( ! empty( $options['block_template_header'] ) ) {
			// 標準モバイルナビを使用しない場合
			if ( empty( $options['block_template_header_use_default_mobile_nav'] ) ) {
				// 標準のモバイルナビを削除
				remove_action( 'lightning_site_footer_after', array( 'Vk_Mobile_Nav', 'menu_set_html' ) );
			} else {
				// 標準モバイルナビを使用する場合
				// ブロックナビゲーションをCSSでモバイル時に非表示にする
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'disable_block_nav_on_mobile' ), 11 );
			}
		}
		// ブロックテンプレートパーツを利用した場合の識別用クラスをbodyに追加
		add_filter( 'body_class', array( __CLASS__, 'add_body_class' ) );
		// ブロックテンプレートパーツ用のCSSを読み込み
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_block_template_parts_style' ) );
		// ブロックテンプレートパーツのブロックエディター用のCSSを読み込み
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'add_block_template_parts_style' ), 11 );
		// テンプレートパーツ編集画面での案内表示
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	/**
	 * Add Body Class
	 * ブロックテンプレートパーツを使用している場合、bodyにクラスを追加
	 *
	 * @param array $class Body Class.
	 */
	public static function add_body_class( $class ) {
		$positions = array(
			'header',
			'footer',
		);
		foreach ( $positions as $position ) {
			if ( self::is_replace( 'site_' . $position ) ) {
				$class[] = 'block-template-parts-' . $position;
			}
		}

		$options = lightning_get_theme_options();
		if ( ! empty( $options['block_template_header_sticky'] ) ) {
			$class[] = 'block-template-parts-header--sticky';
		}
		return $class;
	}

	/**
	 * Add Block Template Parts Style
	 * ロックテンプレートパーツ用のCSSを読み込み
	 */
	public static function add_block_template_parts_style() {
		if ( self::is_replace( 'site_header' ) ) {
			$path    = wp_normalize_path( __DIR__ );
			$css_url = str_replace( wp_normalize_path( ABSPATH ), site_url() . '/', $path ) . '/css/style.css';
			wp_enqueue_style( 'lightning-block-template-parts-style', $css_url, array(), LIGHTNING_G3_PRO_UNIT_VERSION );
		}
		// カスタマイズ画面でのみショートカットアイコンの位置を補正するCSSを追加
		if ( is_customize_preview() ) {
			$css  = '.customize-partial-edit-shortcut.customize-partial-edit-shortcut-lightning_theme_options-block_template_header button{ 
				left:2em;top:1em; }';
			$css .= '.customize-partial-edit-shortcut.customize-partial-edit-shortcut-lightning_theme_options-block_template_footer button{ 
				left:2em;top:0.5em; }';
			wp_add_inline_style( 'lightning-common-style', $css );
		}
	}

	/**
	 * Register Post Type LTG Template Parts
	 * ブロックテンプレートパーツ作成用のカスタム投稿タイプを登録
	 */
	public static function register_post_type_ltg_template_parts() {
		register_post_type(
			'ltg-template-parts',
			array(
				'label'        => _x( 'Lightning Block Template Parts', 'Post Type Menu', 'lightning-g3-pro-unit' ),
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => true,
				'capabilities' => array(
					'edit_posts' => 'edit_theme_options',
				),
				'map_meta_cap' => true,
				'has_archive'  => false,
				'menu_icon'    => 'dashicons-screenoptions',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor' ),
			)
		);
	}

	/**
	 * テンプレートパーツ編集画面での案内表示
	 */
	public static function admin_notices() {
		global $pagenow;
		// 表示中のURLに ?post_type=ltg-template-parts が含まれている場合
		if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'ltg-template-parts' === $_GET['post_type'] ) {
			$description  = '<div class="notice notice-info is-dismissible">';
			$description .= '<p>' . __( 'ここでは、テーマ Lightning のヘッダーとフッターに使用するテンプレートパーツをブロックエディタで登録・編集できます。', 'lightning-g3-pro-unit' ) . '</p>';
			$description .= '<p>' . __( 'カスタマイズの画面でプルダウンに表示される際にわかりやすい名前をつけて登録してください。', 'lightning-g3-pro-unit' ) . '</p>';
			$description .= '<p>' . sprintf( __( 'ヘッダー及びフッター用のブロックテンプレートパーツを登録後は <a href="%s" target="_blank">外観 > カスタマイズ</a> 画面の Lightning ヘッダー設定 及び Lightning フッター設定 から適用させてください。', 'lightning-g3-pro-unit' ), admin_url() . '/customize.php', admin_url() . '/customize.php' ) . '</p>';
			$description .= '<p>' . __( 'ヘッダー及びフッター用のブロックパターンは必要に応じて以下からご利用ください。', 'lightning-g3-pro-unit' ) . '</p>';
			$description .= '<p>[ <a href="https://patterns.vektor-inc.co.jp/pattern-category/header/" target="_blank">' . __( 'ヘッダー用パターン', 'lightning-g3-pro-unit' ) . '</a> | <a href="https://patterns.vektor-inc.co.jp/pattern-category/footer/" target="_blank">' . __( 'フッター用パターン', 'lightning-g3-pro-unit' ) . '</a> ]</p>';
			$description .= '</div>';
			echo wp_kses_post( $description );
		}
	}

	/**
	 * Register Customize
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer.
	 */
	public static function resister_customize( $wp_customize ) {

		$positions = array(
			'header',
			'footer',
		);

		$priority = 0;

		// 登録されているブロックテンプレートパーツを取得
		$args = array(
			'post_type'      => 'ltg-template-parts',
			'posts_per_page' => -1, // 全件取得
			'post_status'    => array( 'publish', 'private' ),
		);

		$template_parts                = new WP_Query( $args );
		$template_parts_select_options = array(
			'' => __( '使用しない（Lightning 標準）', 'lightning-g3-pro-unit' ),
		);
		// 投稿が存在する場合、ループして内容を表示
		if ( $template_parts->have_posts() ) {
			while ( $template_parts->have_posts() ) {
				$template_parts->the_post();
				$template_parts_select_options[ get_the_ID() ] = get_the_title();
			}
		}

		wp_reset_postdata();

		foreach ( $positions as $position ) {
			// Add setting
			$wp_customize->add_setting(
				'ltg_block_template_parts_title_' . $position,
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			// プロックテンプレートパーツへのリンク
			$link_block_template_parts = '<a href="' . admin_url( '/' ) . 'edit.php?post_type=ltg-template-parts' . '" target="_blank">Lightning Block Template Parts</a>';
			$description               = '<p>' . sprintf( __( 'ブロックテンプレートパーツは %s から登録してください。', 'lightning-g3-pro-unit' ), $link_block_template_parts ) . '</p>';

			// ブロックパターンへのリンク
			$link_block_pattern = '<a href="https://patterns.vektor-inc.co.jp/pattern-category/' . $position . '/" target="_blank">' . __( 'ブロックパターン', 'lightning-g3-pro-unit' ) . '</a>';
			$description       .= '<p>' . sprintf( __( '簡単につくれるように %s も用意していますのでご活用ください。', 'lightning-g3-pro-unit' ), $link_block_pattern ) . '</p>';

			$description .= '<p>' . __( 'ブロックテンプレートパーツを新規登録・編集した場合、この画面を再読み込みしないとプルダウンには反映されません。', 'lightning-g3-pro-unit' ) . '</p>';
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'ltg_block_template_parts_title_' . $position,
					array(
						'label'            => __( 'ブロックテンプレートパーツ', 'lightning-g3-pro-unit' ),
						'section'          => 'lightning_' . $position,
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => $description,
						'priority'         => $priority,
					)
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'lightning_theme_options[block_template_' . $position . ']',
				array(
					'selector'        => '.block-site-' . $position,
					'render_callback' => '',
				)
			);

			// Select Template Parts.
			$wp_customize->add_setting(
				'lightning_theme_options[block_template_' . $position . ']',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			$wp_customize->add_control(
				'lightning_theme_options[block_template_' . $position . ']',
				array(
					'label'    => __( 'Block Template Parts', 'lightning-g3-pro-unit' ),
					'section'  => 'lightning_' . $position,
					'settings' => 'lightning_theme_options[block_template_' . $position . ']',
					'type'     => 'select',
					'choices'  => $template_parts_select_options,
					'priority' => $priority,
				)
			);
		}

		// ヘッダーでブロックテンプレートパーツを使用するかどうかを判定
		// アクティブコールバック（有効・無効での設定項目の出し分け）で使用
		function is_block_template_parts_header( $control ) {
			$is_fse = false;
			if ( empty( $control->manager->get_setting( 'lightning_theme_options[block_template_header]' )->value() ) ) {
				$is_fse = false;
			} else {
				$is_fse = true;
			}
			return ! $is_fse;
		}

		// Lightning 標準モバイルナビを使用した場合の注意文
		$wp_customize->add_setting(
			'ltg_block_template_parts_active_alert',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new VK_Custom_Html_Control(
				$wp_customize,
				'ltg_block_template_parts_active_alert',
				array(
					'label'            => '',
					'section'          => 'lightning_header',
					'type'             => 'text',
					'custom_title_sub' => '',
					'custom_html'      => '<div class="alert alert-info">' . __( 'ブロックテンプレートパーツを使用する場合は、ヘッダー上部機能やキャンペーンテキスト機能は反映されなくなります。表示したい要素は必要に応じてヘッダーに指定したブロックテンプレートパーツに直接記載してください。', 'lightning-g3-pro-unit' ) . '</div>',
					'priority'         => $priority,
					'active_callback'  => array( 'VK_Header_Customize', 'is_block_template_parts_header' ),
				)
			)
		);

		// Header Template Parts.
		$wp_customize->add_setting(
			'lightning_theme_options[block_template_header_sticky]',
			array(
				'default'           => false,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => array( 'VK_Helpers', 'sanitize_boolean' ),
			)
		);

		$wp_customize->add_control(
			'lightning_theme_options[block_template_header_sticky]',
			array(
				'label'           => __( 'ヘッダーを固定する', 'lightning-g3-pro-unit' ),
				'section'         => 'lightning_header',
				'settings'        => 'lightning_theme_options[block_template_header_sticky]',
				'type'            => 'checkbox',
				'priority'        => $priority,
				'active_callback' => array( 'VK_Header_Customize', 'is_block_template_parts_header' ),
			)
		);

		// Header Template Parts.
		$wp_customize->add_setting(
			'lightning_theme_options[block_template_header_use_default_mobile_nav]',
			array(
				'default'           => false,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => array( 'VK_Helpers', 'sanitize_boolean' ),
			)
		);

		$description  = '<ul style="font-style:normal;" class="mt-2">';
		$description .= '<li>' . __( 'この設定は保存して再読み込みしないと反映されません。', 'lightning-g3-pro-unit' ) . '</li>';
		$description .= '</ul>';
		$wp_customize->add_control(
			'lightning_theme_options[block_template_header_use_default_mobile_nav]',
			array(
				'label'           => __( 'モバイル端末では Lightning 標準のモバイルナビゲーションを有効にする', 'lightning-g3-pro-unit' ),
				'section'         => 'lightning_header',
				'settings'        => 'lightning_theme_options[block_template_header_use_default_mobile_nav]',
				'type'            => 'checkbox',
				'priority'        => $priority,
				'active_callback' => array( 'VK_Header_Customize', 'is_block_template_parts_header' ),
				'description'     => $description,
			)
		);

		// Lightning 標準モバイルナビを使用するかどうかを判定
		function is_mobile_nav_active_alert( $control ) {
			if ( $control->manager->get_setting( 'lightning_theme_options[block_template_header_use_default_mobile_nav]' )->value() ) {
				return true;
			}
			return false;
		}

		// Lightning 標準モバイルナビを使用した場合の注意文
		$wp_customize->add_setting(
			'ltg_default_mobile_nav_active_alert',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new VK_Custom_Html_Control(
				$wp_customize,
				'ltg_default_mobile_nav_active_alert',
				array(
					'label'            => '',
					'section'          => 'lightning_header',
					'type'             => 'text',
					'custom_title_sub' => '',
					'custom_html'      => '<div class="alert alert-info">' . __( 'ヘッダー内のナビゲーションブロックをモバイル時に非表示にするには、該当のナビゲーションブロックの 表示 > オーバーレイメニュー をオフにしてください。', 'lightning-g3-pro-unit' ) . '</div>',
					'priority'         => $priority,
					'active_callback'  => 'is_mobile_nav_active_alert',
				)
			)
		);
	}

	/**
	 * Lightning 標準モバイルナビを使用した場合、
	 * モバイル時にブロックナビゲーションを非表示にする
	 */
	public static function disable_block_nav_on_mobile() {
		$dynamic_css = '
				@media (max-width: 991.9px) {
					.wp-block-navigation.wp-block-navigation-is-layout-flex {
						display: none;
					}
				}';
		wp_add_inline_style( 'lightning-common-style', $dynamic_css );
	}

	/**
	 * テンプレートパーツを表示
	 */
	public static function echo_template_parts( $position ) {

		// 表示するテンプレートパーツ情報を取得（ フィルターで呼び出すパーツを改変できるようにするため lightning_get_theme_options を使用 ）
		$options = lightning_get_theme_options( 'lightning_theme_options' );

		$parts_post = get_post( $options[ 'block_template_' . $position ] );

		// $parts_post->post_content をブロックの配列に変換
		$blocks = parse_blocks( $parts_post->post_content );

		$template_part = '';

		// 各ブロックをレンダリング
		foreach ( $blocks as $block ) {
			$template_part .= render_block( $block );
		}

		// ショートコードを処理
		$template_part = do_shortcode( $template_part );

		// テンプレートパーツを表示
		if ( 'header' === $position || 'footer' === $position ) {
			$class         = 'block-site-' . $position;
			$template_part = '<' . $position . ' id="block-site-' . $position . '" class="' . $class . '">' . $template_part . '</' . $position . '>';
		}
		echo $template_part;
	}

	/**
	 * ブロックパーツへの変更を実施するかどうかを判定
	 *
	 * @param string $hook_name 対象フック名
	 */
	public static function is_replace( $hook_name ) {

		$lightning_theme_options = lightning_get_theme_options( 'lightning_theme_options' );

		$is_replace = false;

		$position = str_replace( 'site_', 'block_template_', $hook_name ); // site_ を block_template_ に置換

		// 対象のエリアにテンプレートパーツが設定されている場合
		if ( ! empty( $lightning_theme_options[ $position ] ) ) {
			if ( is_singular() ) {
				global $post;
				if ( empty( $post->_lightning_design_setting[ 'hide_' . $hook_name ] ) ) {
					// テンプレートパーツを表示
					$is_replace = true;
				}
			} else {

				// テンプレートパーツを表示
				$is_replace = true;
			}
		}
		return $is_replace;
	}

	/**
	 * 書き換え対象場所での処理
	 *
	 * @param boolean $is_default_display デフォルトパーツ（PHP版）の表示状態
	 * @param string  $hook_name フック名
	 */
	public static function template_parts_filter( $is_default_display, $hook_name ) {

		$position = str_replace( 'site_', '', $hook_name ); // site_ を fes_ に置換

		if ( self::is_replace( $hook_name ) ) {
			// ブロックパーツを表示
			self::echo_template_parts( $position );
			// デフォルトのパーツ（クラシック）を非表示にする
			$is_default_display = false;
		}
		return $is_default_display;
	}
}

$ltg_block_template_parts = new LTG_Block_Template_Parts();
