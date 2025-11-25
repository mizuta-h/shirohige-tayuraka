<?php
/**
 * Font selector config
 *
 * @package Lightning G3 Pro Unit
 */

if ( ! class_exists( 'Vk_Font_Selector_Customize' ) ) {
	require_once 'package/class-vk-font-selector.php';
	global $vk_font_selector_prefix;
	$vk_font_selector_prefix = 'Lightning ';

	global $vk_font_selector_enqueue_handle_style;
	$vk_font_selector_enqueue_handle_style = 'lightning-design-style';

	global $vk_font_selector_editor_style;
	$vk_font_selector_editor_style = 'lightning-common-editor-gutenberg';

	global $vk_font_selector_priority;
	$vk_font_selector_priority = 502;

	/**
	 * Undocumented function
	 *
	 * @param array $target_array フォントを変更する対象の情報.
	 * @return array $target_array
	 */
	function lightning_font_target_change( $target_array ) {
		$target_array = array(
			'hlogo' => array(
				'label'    => __( 'Header Logo', 'lightning-g3-pro-unit' ),
				'selector' => '.site-header .site-header-logo',
			),
			'menu'  => array(
				'label'    => __( 'Global Menu', 'lightning-g3-pro-unit' ),
				'selector' => '.global-nav',
			),
			'title' => array(
				'label'    => __( 'Title', 'lightning-g3-pro-unit' ),
				'selector' => 'h1,h2,h3,h4,h5,h6,.page-header-title',
			),
			'text'  => array(
				'label'    => __( 'Text', 'lightning-g3-pro-unit' ),
				'selector' => 'body',
			),
		);

		return $target_array;
	}
	add_filter( 'vk_font_target_array', 'lightning_font_target_change' );

	/**
	 * 管理画面のフォントの設定
	 *
	 * @param array $editor_target_array 編集画面で変更するフォントの情報.
	 * @return array $editor_target_array 変更した配列を返す
	 */
	function lightning_font_editor_target_change( $editor_target_array ) {
		$editor_target_array = array(
			'title' => array(
				'label'    => __( 'Title', 'lightning-g3-pro-unit' ),
				'selector' => '.editor-styles-wrapper h1,
							.editor-styles-wrapper h2,
							.editor-styles-wrapper h3,
							.editor-styles-wrapper h4,
							.editor-styles-wrapper h5,
							.editor-styles-wrapper h6,
							.editor-styles-wrapper dt',
			),
			'text'  => array(
				'label'    => __( 'Text', 'lightning-g3-pro-unit' ),
				'selector' => '.edit-post-visual-editor .editor-styles-wrapper ',
			),
		);

		return $editor_target_array;
	}
	add_filter( 'vk_font_editor_target_array', 'lightning_font_editor_target_change' );
}
