<?php
/**
 * Header Customize
 *
 * @package Lightning G3 Pro
 */
if ( ! class_exists( 'VK_Header_Customize' ) ) {

	/**
	 * Header Customize Class
	 */
	class VK_Header_Customize {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'resister_customize_color' ) );
		}

		public static function resister_customize_color( $wp_customize ) {

			$wp_customize->add_section(
				'lightning_header',
				array(
					'title'    => 'Lightning ' . __( 'Header Settings', 'lightning-g3-pro-unit' ),
					'priority' => 511,
				)
			);
		}

		/**
		 * ブロックテンプレートを使うかどうかの判定
		 *
		 * @return bool true : 使用する false : 使用しない
		 */
		public static function is_block_template_parts_header( $control ) {

			// デフォルトは false（使用しない）
			$is_fse = false;
			if ( $control->manager->get_setting( 'lightning_theme_options[block_template_header]' ) ) {
				if ( empty( $control->manager->get_setting( 'lightning_theme_options[block_template_header]' )->value() ) ) {
					$is_fse = false;
				} else {
					$is_fse = true;
				}
			}
			return $is_fse;
		}

		/**
		 * ブロックテンプレートを使うかどうかの判定（逆）
		 * active_callback で渡すために is_block_template_parts_header() を反転したものを関数化
		 *
		 * ブロックテンプレートを使う場合は false を返す事で該当のカスタマイズ項目を表示しない
		 *
		 * @return bool true:非表示 false:表示
		 */
		public static function is_default_header( $control ) {
			// 使用する場合は非表示にするので反転させる
			return ! self::is_block_template_parts_header( $control );
		}
	}
	$vk_header_customize = new VK_Header_Customize();
}
