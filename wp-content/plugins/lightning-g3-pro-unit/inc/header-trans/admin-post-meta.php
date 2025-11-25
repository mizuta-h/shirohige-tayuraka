<?php
/*
	入力フィールドの生成
/*-------------------------------------------*/
add_action( 'lightning_design_setting_meta_fields', 'lightning_design_setting_header_trans_meta_fields' );
function lightning_design_setting_header_trans_meta_fields() {

	// ブロックテンプレートパーツの場合は透過設定を表示しない
	if ( class_exists( 'LTG_Block_Template_Parts' ) && LTG_Block_Template_Parts::is_replace( 'site_header' ) ) {
		return false;
	}

	global $post;

	$form = '<h4>' . __( 'Header transmission', 'lightning-g3-pro-unit' ) . '</h4>';

	// トップページの設定を取得
	$page_on_front = intval( get_option( 'page_on_front' ) ); // フロントに指定する固定ページ
	$show_on_front = get_option( 'show_on_front' ); // or posts

	// カスタマイズ画面へのリンク
	$custumize_link = '<a href="' . admin_url() . 'customize.php' . '" target="_blank">' . __( 'Appearance > Customize', 'lightning-g3-pro-unit' ) . '</a>';

	if ( 'page' === $show_on_front && $post->ID === $page_on_front ) {
		$form .= '<p>' . sprintf( __( 'Please set from %s > Lightning Header Setting.', 'lightning-g3-pro-unit' ), $custumize_link ) . '</p>';
	} else {
		$saved_post_meta = get_post_meta( $post->ID, '_lightning_design_setting', true );
		$id              = '_lightning_design_setting[header_trans]';

		$form   .= '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '">';
		$options = array(
			'default' => __( 'Use common settings', 'lightning-g3-pro-unit' ),
			'true'    => __( 'Enable transparency', 'lightning-g3-pro-unit' ),
			'normal'  => __( 'Disable transparency', 'lightning-g3-pro-unit' ),
		);
		foreach ( $options as $key => $value ) {
			$selected = '';
			if ( isset( $saved_post_meta['header_trans'] ) ) {
				if ( $key === $saved_post_meta['header_trans'] ) {
					$selected = ' selected';
				}
			}
			$form .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $value ) . '</option>';
		}
		$form .= '</select>';

		$form .= '<p>' . sprintf( __( '"Common setting" can set from %s > Lightning Header Setting.', 'lightning-g3-pro-unit' ), $custumize_link ) . '</p>';
	}

	echo $form;
}
