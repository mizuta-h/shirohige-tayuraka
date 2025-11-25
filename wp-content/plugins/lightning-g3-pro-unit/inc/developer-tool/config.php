<?php
/**
 * VK Developer Tool Configuration
 *
 * @package VK Developer Tool
 */

/**
 * Hook List of Lightning G3
 *
 * @param array $action_hook_list Action Hook List of Lightning G3.
 */
function lightning_g3_action_hook_list( $action_hook_list ) {
	$action_hook_list = array_merge(
		$action_hook_list,
		array(
			'lightning_header_top_container_append',
			// index.php
			'lightning_site_header_before',
			'lightning_site_header_after',
			'lightning_page_header_before',
			'lightning_page_header_after',
			'lightning_breadcrumb_before',
			'lightning_breadcrumb_after',
			'lightning_site_body_prepend',
			'lightning_main_section_prepend',
			'lightning_main_section_append',
			// 'lightning_sub_section_before',
			// 'lightning_sub_section_after',
			'lightning_sub_section_prepend',
			'lightning_sub_section_append',
			'lightning_site_body_append',
			'lightning_site_footer_before',
			'lightning_site_footer_after',
			'lightning_site_footer_content_prepend',
			'lightning_site_footer_content_append',
			// template-parts/entry.php
			'lightning_entry_body_before',
			'lightning_entry_body_prepend',
			'lightning_entry_body_apppend',
			'lightning_entry_body_after',
			'lightning_entry_footer_before',
			'lightning_entry_footer_append',
			'lightning_comment_before',
			'lightning_comment_after',
			// template-parts/main-archive.php
			'lightning_loop_before',
			'lightning_extend_loop',
			'lightning_loop_item_after',
			'lightning_loop_after',
			// template-parts/main-singular.php
			'lightning_extend_single',
			// template-parts/site-footer.php
			'lightning_copyright_before',
			// template-parts/site-header.php
			'lightning_site_header_prepend',
			'lightning_site_header_logo_after',
			'lightning_site_header_append',
			// ltg-g3-slider
			'lightning_top_slide_before',
			'lightning_top_slide_after',
		)
	);
	return $action_hook_list;
}
add_filter( 'vk_developer_tool_action_hook_list', 'lightning_g3_action_hook_list' );

if ( ! class_exists( 'VK_Developer_Tool' ) ) {
	require_once __DIR__ . '/package/class-vk-developer-tool.php';
}
