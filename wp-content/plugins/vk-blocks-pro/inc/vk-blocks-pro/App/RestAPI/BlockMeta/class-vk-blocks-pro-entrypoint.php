<?php
/**
 * VK Blocks REST API Init Actions
 *
 * @package vk_blocks
 */

/**
 * Vk_Blocks_Pro_EntryPoint
 */
class Vk_Blocks_Pro_EntryPoint {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'vk_blocks_pro_rest_api_init' ) );
	}

	/**
	 * Vk Blocks Rest Api Init
	 *
	 * @return void
	 */
	public function vk_blocks_pro_rest_api_init() {
		register_rest_route(
			'vk-blocks/v1',
			'/blog_card_data',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'post_blog_card_data' ),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				),
			)
		);

		register_rest_route(
			'vk-blocks/v1',
			'/toc_settings',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_toc_settings' ),
					'permission_callback' => function () {
						return true;
					},
				),
			)
		);
	}

	/**
	 * VK Blocks Rest Update Callback
	 *
	 * @param object $request — .
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function post_blog_card_data( $request ) {
		$json_params = $request->get_json_params();
		$data        = VK_Blocks_Blog_Card::vk_get_blog_card_data( $json_params['url'], $json_params['clearCache'] );
		return rest_ensure_response(
			array(
				'data'    => $data,
				'success' => true,
			)
		);
	}

	/**
	 * 目次設定を取得するAPI
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_toc_settings() {
		$options    = get_option( 'vk_blocks_options', array() );
		$toc_levels = isset( $options['toc_heading_levels'] ) ? $options['toc_heading_levels'] : array( 'h2', 'h3', 'h4', 'h5', 'h6' );
		$include_bb = isset( $options['toc_include_border_box'] ) ? (bool) $options['toc_include_border_box'] : true;

		$settings = array(
			'allowedHeadingLevels' => array_map(
				function ( $level ) {
					return intval( str_replace( 'h', '', $level ) );
				},
				$toc_levels
			),
			'globalHeadingLevels'  => $toc_levels,
			'includeBorderBox'     => $include_bb,
			'lastModified'         => time(),
		);

		return rest_ensure_response(
			array(
				'data'    => $settings,
				'success' => true,
			)
		);
	}
}
