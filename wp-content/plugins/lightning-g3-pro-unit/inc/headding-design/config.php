<?php
/**
 * VK Heading Config
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

/**
 * Hedding Default Options
 */
function lightning_headding_default_option() {
	$default_option = array(
		'h2'                => array(
			'style' => 'none',
		),
		'sub-section-title' => array(
			'style' => 'none',
		),
		'site-footer-title' => array(
			'style' => 'none',
		),
		'h3'                => array(
			'style' => 'none',
		),
		'h4'                => array(
			'style' => 'none',
		),
		'h5'                => array(
			'style' => 'none',
		),
		'h6'                => array(
			'style' => 'none',
		),
	);
	return $default_option;
}

/**
 * Hedding Selecters
 */
function lightning_get_headding_selector_array() {
	// セレクタは ::before や ::after を自動生成するために配列で格納している.
	// 素のhタグは スライダーなど他の要素で使っている部分のセレクタ指定を強くして、この見出しデザインの指定を上書きするようにする.
	// 本文欄はブロックからデザイン指定が来るので main-section h3 みたいに書くと上書きが効かない.
	$selectors = array(
		'h2'                => array(
			'label'    => __( 'H2 and main section title', 'lightning-g3-pro-unit' ),
			'selector' => array(
				/* .entry-bodyクラスをつけないとLanding Page for Page Builderテンプレートで効かなくなる */
				'h2',
				'.main-section .cart_totals h2', // wooCommerce Cart Page.
				'h2.main-section-title',
			),
		),
		'sub-section-title' => array(
			'label'    => __( 'Sub section title', 'lightning-g3-pro-unit' ),
			'selector' => array(
				'.sub-section .sub-section-title',
				'.site-body-bottom .sub-section-title',
			),
		),
		'site-footer-title' => array(
			'label'    => __( 'Footer section title', 'lightning-g3-pro-unit' ),
			'selector' => array(
				'.site-footer .site-footer-title',
			),
		),
		'h3'                => array(
			'label'    => __( 'H3', 'lightning-g3-pro-unit' ),
			'selector' => array(
				'h3',
			),
		),
		'h4'                => array(
			'label'    => __( 'H4', 'lightning-g3-pro-unit' ),
			'selector' => array(
				'h4',
				'.veu_sitemap h4',
			),
		),
		'h5'                => array(
			'label'    => __( 'H5', 'lightning-g3-pro-unit' ),
			'selector' => array(
				'h5',
			),
		),
		'h6'                => array(
			'label'    => __( 'H6', 'lightning-g3-pro-unit' ),
			'selector' => array(
				'h6',
			),
		),
	);
	return apply_filters( 'vk_headding_selector_array', $selectors );
}

if ( ! class_exists( 'VK_Headding_Design' ) ) {
	global $headding_default_options;
	$headding_default_options = lightning_headding_default_option();

	global $headding_selector_array;
	$headding_selector_array = lightning_get_headding_selector_array();

	global $headding_customize_section;
	$headding_customize_section = 'lightning_design';

	global $headding_theme_options;
	$headding_theme_options = get_option( 'lightning_theme_options' );

	global $headding_front_hook_style;
	$headding_front_hook_style = 'lightning-design-style';

	global $headding_editor_hook_style;
	$headding_editor_hook_style = 'lightning-common-editor-gutenberg';

	require_once dirname( __FILE__ ) . '/package/class-vk-headding-design.php';

}
