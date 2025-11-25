<?php
/**
 * Config File of Lightning Header Top
 *
 * @package Lightning Pro
 */

if ( ! class_exists( 'Lightning_Header_Top' ) ) {

	global $vk_header_top_prefix;
	$vk_header_top_prefix = 'Lightning ';

	require_once dirname( __FILE__ ) . '/package/class-lightning-header-top.php';

	// packageマネージャー自体が init の 11 で読み込んでいるためか、ライブラリ内の読み込みだと反映されいため一時的に手動で追加
	// 要packageロジック見直し
	add_action( 'init', array( 'Lightning_Header_Top', 'header_top_add_menu' ), 12 );


}

if ( class_exists( 'Lightning_Header_Top' ) && ! class_exists( 'Lightning_Header_Top_Description' ) ) {
	require_once dirname( __FILE__ ) . '/package/class-lightning-header-top-description.php';
}
