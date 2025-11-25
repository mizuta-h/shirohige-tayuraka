<?php
/**
 * Footer Customize Config
 *
 * @package Lightning Pro
 */

if ( ! class_exists( 'VK_Footer_Customize' ) ) {

	global $vk_footer_customize_prefix;
	$vk_footer_customize_prefix = 'Lightning ';

	// add_inline_style の対象ハンドル
	global $vk_footer_customize_hook_style;
	$vk_footer_customize_hook_style = 'lightning-design-style';

	global $vk_footer_widgrt_selector;
	$vk_footer_widgrt_selector = '.site-footer .site-footer-content';

	global $vk_footer_option_name;
	$vk_footer_option_name = 'lightning_widget_setting';
	
	global $vk_footer_customize_priority;
	$vk_footer_customize_priority = 540;

	require_once dirname( __FILE__ ) . '/package/class-vk-footer-customize.php';
}
