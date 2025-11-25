<?php
/**
 * Lightning Header Top Description
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

/**
 * Header Top Description
 */
class Lightning_Header_Top_Description {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'header_top_description', array( __CLASS__, 'header_top_description' ) );
		add_action( 'save_post', array( __CLASS__, 'custom_field_save' ) );
		add_action( 'admin_menu', array( __CLASS__, 'custom_field_set' ) );
	}

	/**
	 * カスタムフィールドの入力エリア
	 */
	public static function custom_field_form() {
		global $post;
		wp_nonce_field( wp_create_nonce( __FILE__ ), 'noncename__header_top_description' );
		$post_meta = get_post_meta( $post->ID, 'header_top_description', true );
		$field     = '<label><input type="text" name="header_top_description" value="' . $post_meta . '"></label>';
		$field    .= '<p>' . __( 'If you enter here, the input will be reflected in the catchphrase at the top of the page.', 'lightning-g3-pro-unit' ) . '</p>';

		echo $field;
	}

	/**
	 * カスタムフィールドを保存
	 *
	 * @param int $post_id : 投稿ID.
	 * @return void|number
	 */
	public static function custom_field_save( $post_id ) {

		$post_meta = get_post_meta( $post_id, 'header_top_description', true );

		if ( empty( $_POST['noncename__header_top_description'] ) ) {
			return $post_id;
		}

		$noncename__value = sanitize_text_field( wp_unslash( $_POST['noncename__header_top_description'] ) );

		if ( ! wp_verify_nonce( $noncename__value, wp_create_nonce( __FILE__ ) ) ) {
			return $post_id;
		}

		if ( ! empty( $_POST['header_top_description'] ) ) {
			// 値がある場合.
			if ( ! empty( $post_meta ) ) {
				// 値が登録されている場合.
				update_post_meta( $post_id, 'header_top_description', sanitize_text_field( wp_unslash( $_POST['header_top_description'] ) ) );
			} else {
				// 新規登録時.
				add_post_meta( $post_id, 'header_top_description', sanitize_text_field( wp_unslash( $_POST['header_top_description'] ) ) );
			}
		} else {
			// チェックされていない場合.
			delete_post_meta( $post_id, 'header_top_description' );
		}
	}

	/**
	 * カスタムフィールドを設置
	 */
	public static function custom_field_set() {
		$field_title = __( 'Catchphrase for Header Top', 'lightning-g3-pro-unit' );
		$post_types  = get_post_types(
			array(
				'public' => true,
			)
		);
		foreach ( $post_types as $post_type ) {
			add_meta_box( 'header_top_description', $field_title, array( __CLASS__, 'custom_field_form' ), $post_type, 'normal', 'high' );
		}
	}

	/**
	 * Header Top Description を書き換え
	 *
	 * @param string $description : description text.
	 * @return string $description : header top description text
	 */
	public static function header_top_description( $description ) {
		if ( is_singular() ) {
			global $post;
			$post_meta = get_post_meta( $post->ID, 'header_top_description', true );
			if ( ! empty( $post_meta ) ) {
				$description = $post_meta;
			}
		}
		return $description;
	}
}

new Lightning_Header_Top_Description();
