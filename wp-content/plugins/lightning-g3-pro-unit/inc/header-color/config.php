<?php

if ( class_exists( 'VK_Header_Customize' ) ) {

	// add_inline_style の対象ハンドル
	global $vk_header_customize_style_handle;
	$vk_header_customize_style_handle = 'lightning-design-style';

	global $vk_header_widgrt_selector;
	$vk_header_widgrt_selector = '.site-header .site-header-content';

	global $vk_header_customize_priority;
	$vk_header_customize_priority = 540;

	require_once dirname( __FILE__ ) . '/package/class-vk-header-color.php';

	// 人力phpUnit読み込み
	// require_once 'test/test-header-color.php';
}
