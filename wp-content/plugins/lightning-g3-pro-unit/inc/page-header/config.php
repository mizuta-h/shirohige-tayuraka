<?php
/*
  Load modules ( master config )
/*-------------------------------------------*/
if ( ! class_exists( 'Vk_Page_Header' ) ) {

	require_once 'package/class-vk-page-header.php';

	// 人力phpUnit読み込み
	// require_once 'test/test-page-header.php';

	require LTG3_PRO_DIRECTORY_PATH . '/inc/custom-field-builder/config.php';

	global $customize_setting_prefix;
	$customize_setting_prefix = 'Lightning ';

	global $customize_section_priority;
	$customize_section_priority = 530;

	global $vk_page_header_output_class;
	$vk_page_header_output_class = '.page-header';

	global $vk_page_header_inner_class;
	$vk_page_header_inner_class = '.page-header .page-header-inner';

	global $vk_page_header_default;
	$vk_page_header_default = array(
		'common' => array(
			'text_color'        => '#333',
			'element'           => 'post_type_name',
			'text_shadow_color' => '',
			'text_align'        => '',
			'height_min'        => 9,
			'cover_color'       => '#fff',
			'cover_opacity'     => 0.9,
			'image'             => LTG3_PRO_DIRECTORY_URL . '/inc/page-header/package/images/header-sample.jpg',
			'image_sp'          => '',
			'image_fixed'       => 'scroll',
			'image_type'        => 'post_thumbnail',
		),
		'post'   => array(
			'text_color'    => '#fff',
			'image'         => LTG3_PRO_DIRECTORY_URL . '/inc/page-header/package/images/header-sample.jpg',
			'element'       => 'post_title_and_meta',
			'cover_color'   => '#000',
			'cover_opacity' => 0.7,
		),
		'page'   => array(
			'element' => 'page_title',
		),
	);

	global $vk_page_header_enqueue_handle_style;
	$vk_page_header_enqueue_handle_style = 'lightning-design-style';

	global $vk_page_header_bg_color_hide;
	$vk_page_header_bg_color_hide = true;

}
