<?php
/**
 * VK_Blocks_Fixed_Display class
 *
 * @package vk-blocks
 */

if ( class_exists( 'VK_Blocks_Fixed_Display' ) ) {
	return;
}

/**
 * VK_Blocks_Fixed_Display
 */
class VK_Blocks_Fixed_Display {

	/**
	 * Initialize
	 */
	public static function init() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
		}
		return $instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'localize_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_script' ) );
		add_action( 'wp_ajax_vk_blocks_fixed_display_get_session', array( $this, 'get_session_data' ) );
		add_action( 'wp_ajax_nopriv_vk_blocks_fixed_display_get_session', array( $this, 'get_session_data' ) );
		add_filter( 'render_block', array( $this, 'filter_all_blocks' ), 10, 2 );
	}

	/**
	 * JavaScriptにnonceとURLを渡す
	 */
	public function localize_script() {
		wp_localize_script(
			'vk-blocks/fixed-display-script',
			'vkBlocksFixedDisplayAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'vk_blocks_fixed_display_nonce' ),
			)
		);
	}

	/**
	 * セッションストレージの状態を取得
	 */
	public function get_session_data() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'vk_blocks_fixed_display_nonce' ) ) {
			wp_die( 'Security check failed' );
		}

		$block_ids = isset( $_POST['block_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['block_ids'] ) ) : array();

		if ( ! empty( $block_ids ) ) {
			$user_id    = get_current_user_id();
			$option_key = 'vk_blocks_hidden_block_ids_' . ( $user_id ? $user_id : 'guest' );
			$json_func  = function_exists( 'wp_json_encode' ) ? 'wp_json_encode' : 'json_encode';
			update_option( $option_key, $json_func( $block_ids ) );
		}

		wp_send_json_success();
	}

	/**
	 * すべてのブロックをフィルタリング
	 *
	 * @param string $block_content ブロックコンテンツ.
	 * @param array  $block         ブロック情報.
	 * @return string
	 */
	public function filter_all_blocks( $block_content, $block ) {
		if ( isset( $block['blockName'] ) && strpos( $block['blockName'], 'fixed-display' ) !== false ) {
			return $this->filter_fixed_display_block( $block_content, $block );
		}
		return $block_content;
	}

	/**
	 * ブロックのレンダリングをフィルタリング
	 *
	 * @param string $block_content ブロックコンテンツ.
	 * @param array  $block         ブロック情報.
	 * @return string
	 */
	private function filter_fixed_display_block( $block_content, $block ) {
		if ( ! isset( $block['attrs']['blockId'] ) || ! ( isset( $block['attrs']['dontShowAgain'] ) ? $block['attrs']['dontShowAgain'] : false ) ) {
			return $block_content;
		}

		$block_id              = $block['attrs']['blockId'];
		$user_id               = get_current_user_id();
		$option_key            = 'vk_blocks_hidden_block_ids_' . ( $user_id ? $user_id : 'guest' );
		$hidden_block_ids_json = get_option( $option_key, '[]' );

		$json_func        = function_exists( 'wp_json_decode' ) ? 'wp_json_decode' : 'json_decode';
		$hidden_block_ids = $json_func( $hidden_block_ids_json, true );

		if ( is_array( $hidden_block_ids ) && in_array( $block_id, $hidden_block_ids, true ) ) {
			return '';
		}

		return $block_content;
	}
}
