<?php


/*
  Load modules
-------------------------------------------*/
// ページ下部に固定表示するメニュー
if ( ! class_exists( 'Vk_Mobile_Fix_Nav' ) ) {

	global $vk_mobile_fix_nav_prefix;
	$vk_mobile_fix_nav_prefix = 'Lightning ';

	global $vk_mobile_fix_nav_priority;
	$vk_mobile_fix_nav_priority = 550;

	// Original VK Mobile Fix Nav was printed on wp_footer.
	// But it bring to problem on customize > widget screen that change to lgithning_site_footer_after
	function lightning_change_vk_mobile_fix_nav_hook_point( $vk_mobile_nav_html_hook_point ) {
		return 'lightning_site_footer_after';
	}

	add_filter( 'vk_mobile_fix_nav_html_hook_point', 'lightning_change_vk_mobile_fix_nav_hook_point' );

	require_once dirname( __FILE__ ) . '/package/class-vk-mobile-fix-nav.php';

}
