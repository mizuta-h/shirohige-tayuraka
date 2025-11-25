<?php
/**
 * VK Campaign Text Config
 *
 * @package Lightning Pro
 */

if ( ! class_exists( 'VK_Campaign_Text' ) ) {

	// キャンペーンテキストを挿入する位置.
	// * 後で読み込むと読み込み順の都合上反映されない.
	global $vk_campaign_text_hook_point;
	$vk_campaign_text_hook_point = array();

	// キャンペーンテキストの CSS を読み込む位置.
	// * 後で読み込むと読み込み順の都合上反映されない.
	global $vk_campaign_text_hook_style;
	$vk_campaign_text_hook_style = 'lightning-design-style';

	// 表示位置の配列.
	global $vk_campaign_text_display_position_array;
	$vk_campaign_text_display_position_array = array(
		'header_prepend'          => array(
			'hookpoint' => array( 'lightning_site_header_prepend' ),
			'label'     => __( 'Header Before', 'lightning-g3-pro-unit' ),
		),
		'header_append'           => array(
			'hookpoint' => array( 'lightning_site_header_append' ),
			'label'     => __( 'Header After', 'lightning-g3-pro-unit' ),
		),
	);

	require_once dirname( __FILE__ ) . '/package/class-vk-campaign-text.php';

	add_filter( 'vk_campaign_text_print_css', 'lightning_campaign_text_print_css_custom' );
	function lightning_campaign_text_print_css_custom(){
		return true;
	}

}

// なるべくLightnigの名前になるように class_exists の外でOK.
global $vk_campaign_text_prefix;
$vk_campaign_text_prefix = 'Lightning ';
