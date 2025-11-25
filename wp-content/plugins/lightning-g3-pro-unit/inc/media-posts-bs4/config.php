<?php
/**
 * VK Media Posts BS4 Config
 *
 * @package VK Media Posts BS4
 */

if ( ! class_exists( 'VK_MEDIA_POSTS_BS4' ) ) {

	define( 'VK_MEDIA_POSTS_BS4_URL', LTG3_PRO_DIRECTORY_URL . '/inc/media-posts-bs4/package/' );
	define( 'VK_MEDIA_POSTS_BS4_DIR', dirname( __FILE__ ) );
	define( 'VK_MEDIA_POSTS_BS4_VERSION', '1.1' );

	global $system_name;
	$system_name = 'Lightning';

	global $vk_media_post_prefix;
	$vk_media_post_prefix = 'LTG ';

	global $customize_section_name;
	$customize_section_name = 'Lightning ';

	global $is_extend_loop_name;
	$is_extend_loop_name = 'lightning_is_extend_loop';

	global $do_extend_loop_name;
	$do_extend_loop_name = 'lightning_extend_loop';

	global $vk_mpbs4_archive_layout_class;
	$vk_mpbs4_archive_layout_class = '.main-section';

	require_once dirname( __FILE__ ) . '/package/class-vk-media-posts-bs4.php';

	/**
	 * Column size setting
	 *
	 * @param array $sizes size of using on media post bs4.
	 */
	function lightning_media_posts_bs4_sizes( $sizes ) {
		unset( $sizes['xxl'] );
		return $sizes;
	}
	add_filter( 'vk_media_post_bs4_size', 'lightning_media_posts_bs4_sizes' );

	/**
	 * Katawara から乗り換えると xxl の設定が残ってしまうので削除する
	 */
	function lightning_delete_xxl() {
		global $pagenow;
		// ダッシュボードのトップページでのみ実行
		if ( $pagenow == 'index.php' ) {
			$vk_post_type_archive = get_option( 'vk_post_type_archive' );
			if ( $vk_post_type_archive && is_array( $vk_post_type_archive ) ) {
				$updated = false;
				foreach ( $vk_post_type_archive as $key => &$values ) {
					if ( isset( $values['col_xxl'] ) ) {
						unset( $values['col_xxl'] ); // col_xxl 項目を削除
						$updated = true;
					}
				}
				if ( $updated ) {
					update_option( 'vk_post_type_archive', $vk_post_type_archive );
				}
			}
		}
	}
	add_action( 'admin_init', 'lightning_delete_xxl' );

	/**
	 * Default Options
	 *
	 * @param array $default_options default options of using on media post bs4.
	 */
	function lightning_media_posts_bs4_default_options( $default_options ) {
		$default_options['layout'] = 'default';
		unset( $default_options['col_xxl'] );
		return $default_options;
	}
	add_filter( 'vk_media_posts_bs4_default_options', 'lightning_media_posts_bs4_default_options' );

	/**
	 * Default Options of Widget
	 *
	 * @param array $default_options default options of using on media post bs4 widget.
	 */
	function lightning_media_posts_bs4_widget_default_options( $default_options ) {
		unset( $default_options['col_xxl'] );
		return $default_options;
	}
	add_filter( 'vk_media_posts_bs4_widget_default_options', 'lightning_media_posts_bs4_widget_default_options' );


}
