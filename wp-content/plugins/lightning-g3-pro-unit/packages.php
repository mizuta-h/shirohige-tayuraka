<?php
/**
 * Lightning G3 Pro Unit Packages
 *
 * @package Lightning G3 Pro Unit
 */

/**
 * Default Options
 */
function ltg3pro_get_options() {
	$options  = get_option( 'lightning_g3_pro_unit_options' );
	$packages = ltg3pro_package_list();
	$default  = array();

	foreach ( $packages as $package ) {
		$default['package_enable'][ $package['name'] ] = $package['default'];
	}

	return wp_parse_args( $options, $default );
}

/**
 * Require Package
 */
function ltg3pro_require_packages() {

	// VK Page Header ＆他のプラグインで使用しているため必須.
	include dirname( __FILE__ ) . '/inc/custom-field-builder/config.php';
	// 他のものに影響するので必須.
	include dirname( __FILE__ ) . '/inc/header-customize/class-header-customize.php';

	$options          = ltg3pro_get_options();
	$packages         = ltg3pro_package_list();
	$current_template = get_template();

	if ( function_exists( 'lightning_is_g3' ) && lightning_is_g3() ) {

		foreach ( $packages as $package ) {
			if (
				! empty( $options['package_enable'][ $package['name'] ] ) &&
				'on' === $options['package_enable'][ $package['name'] ]
			) {
				require LTG3_PRO_DIRECTORY_PATH . '/inc/' . $package['include'];

			} elseif (
				empty( $options['package_enable'][ $package['name'] ] ) &&
				isset( $package['default'] ) && 'on' === $package['default']
			) {
				require LTG3_PRO_DIRECTORY_PATH . '/inc/' . $package['include'];
			}
		}

	}
}
// init だとヘッダーウィジェットが生成できない 他にも弊害が出ると思われる
add_action( 'after_setup_theme', 'ltg3pro_require_packages', 11 ); // 11にしている理由を確認したら記載する事

/**
 * Package List
 */
function ltg3pro_package_list() {
	$packages = array();

	// base-controller
	$packages = array(
		array(
			'name'        => 'design',
			'title'       => __( 'Design Customize', 'lightning-g3-pro-unit' ),
			'description' => __( 'デザインに関するカスタマイズ項目が追加されます。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'customize/design.php',
		),
		array(
			'name'        => 'vk-font-selector',
			'title'       => __( 'Font Selector', 'lightning-g3-pro-unit' ),
			'description' => __( '見出しや本文のフォントを変更できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'vk-font-selector/config.php',
		),
		array(
			'name'        => 'header-top',
			'title'       => __( 'Header Top', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダーの上部にサイトディスクリプションやメニュー、お問い合わせボタンを追加できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'header-top/config.php',
		),
		array(
			'name'        => 'header-layout',
			'title'       => __( 'Header Layout', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダーのレイアウト変更できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'header-layout/class-header-layout.php',
		),
		array(
			'name'        => 'header-color',
			'title'       => __( 'Header Color', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダーの色をカスタマイザから指定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'header-color/config.php',
		),
		array(
			'name'        => 'header-trans',
			'title'       => __( 'Header Transparent', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダーの透過指定ができるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'header-trans/header-trans.php',
		),
		array(
			'name'        => 'vk-campaign-text',
			'title'       => __( 'Campain Text', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダーの上や下にキャンペーンテキストを表示する事ができるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'vk-campaign-text/config.php',
		),
		array(
			'name'        => 'vk-page-header',
			'title'       => __( 'Page Header', 'lightning-g3-pro-unit' ),
			'description' => __( 'ページヘッダーの表示要素、画像や文字位置、高さなど投稿タイプ毎に設定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'page-header/config.php',
		),
		array(
			'name'        => 'hide-controller',
			'title'       => __( 'Hide Controller', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダー、ページヘッダー、パンくずリスト、フッターなどをページ個別で非表示に設定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'hide-controller/class-hide-controller.php',
		),
		array(
			'name'        => 'base-controller',
			'title'       => __( 'Base Controller', 'lightning-g3-pro-unit' ),
			'description' => __( 'Control add Base Section or not.', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'base-controller/base-controller.php',
		),
		array(
			'name'        => 'headding-design',
			'title'       => __( 'Heading Design', 'lightning-g3-pro-unit' ),
			'description' => __( '共通の見出しデザインが変更できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'headding-design/config.php',
		),
		array(
			'name'        => 'media-posts-bs4',
			'title'       => __( 'Media Posts BS4', 'lightning-g3-pro-unit' ),
			'description' => __( 'アーカイブページで投稿タイプ毎に投稿一覧の表示レイアウト、要素、件数などを設定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'media-posts-bs4/config.php',
		),
		array(
			'name'        => 'single-page-setting',
			'title'       => __( '詳細ページ設定', 'lightning-g3-pro-unit' ),
			'description' => __( '詳細ページでの表示要素などを設定できます。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'single-page-setting/class-single-page-setting.php',
		),
		array(
			'name'        => 'vk-footer-customize',
			'title'       => __( 'Footer Customize', 'lightning-g3-pro-unit' ),
			'description' => __( 'フッターの色や背景画像、ウィジェットエリア数が設定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'vk-footer-customize/config.php',
		),
		array(
			'name'        => 'copyright-customizer',
			'title'       => __( 'Copyright Customizer', 'lightning-g3-pro-unit' ),
			'description' => __( 'フッターのコピーライトの内容を設定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'copyright-customizer/config.php',
		),
		array(
			'name'        => 'vk-mobile-fix-nav',
			'title'       => __( 'Mobile Fix Navigation', 'lightning-g3-pro-unit' ),
			'description' => __( '画面が狭い場合に画面下部に固定のナビゲーションを設定できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'vk-mobile-fix-nav/config.php',
		),
		array(
			'name'        => 'main-404-cusomize',
			'title'       => __( '404 Page Customize', 'lightning-g3-pro-unit' ),
			'description' => __( '404ページに表示する内容を管理画面から編集できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'main-404-customize/class-main-404-customize.php',
		),
		array(
			'name'        => 'developer-tool',
			'title'       => __( 'Developer Tool', 'lightning-g3-pro-unit' ),
			'description' => __( 'アクションフックの場を確認できるなど開発用の機能を追加します。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'developer-tool/config.php',
		),
		array(
			'name'        => 'breadcrumb-controller',
			'title'       => __( 'パンくずリストの表示位置設定', 'lightning-g3-pro-unit' ),
			'description' => __( 'パンくずリストの表示位置を 外観 > カスタマイズ > レイアウト設定 > パンくずリスト から変更できるようになります。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'breadcrumb-controller/class-ltg-breadcrumb-controller.php',
		),
		array(
			'name'        => 'block-template-parts',
			'title'       => __( 'ブロックテンプレートパーツ', 'lightning-g3-pro-unit' ),
			'description' => __( 'ヘッダー及びフッターがブロックエディタで構築できるようになります。投稿タイプ Lightning Block Template Parts でヘッダーやフッターを作成し、外観 > カスタマイズ > Lightning ヘッダー設定 / Lightning フッター設定 より適用するテンプレートパーツを選択してください。', 'lightning-g3-pro-unit' ),
			'default'     => 'on',
			'include'     => 'block-template-parts/class-ltg-block-template-parts.php',
		),
	);

	return $packages;
}
