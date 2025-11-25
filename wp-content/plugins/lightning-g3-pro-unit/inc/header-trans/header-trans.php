<?php
/**
 * Config File of Lightning Header Trans
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

if ( ! class_exists( 'Lightning_Header_Trans' ) && class_exists( 'VK_Header_Customize' ) ) {
	add_action(
		'after_setup_theme',
		function () {
			if ( class_exists( 'LTG_Block_Template_Parts' ) && LTG_Block_Template_Parts::is_replace( 'header' ) ) {
				return;
			} else {
				require_once 'class-lightning_header_trans.php';
				require_once 'admin-post-meta.php';
				Lightning_Header_Trans::init();
			}
		},
		12
	); // G3 Pro Unit の package での読み込みが 11 なのでそれより後に実行する
}
