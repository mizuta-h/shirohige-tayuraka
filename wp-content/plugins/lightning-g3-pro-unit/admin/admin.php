<?php
/**
 * Options
 *
 * @package Lightning G3 Pro Unit
 */
use VektorInc\VK_Admin\VkAdmin;
VkAdmin::init();

function ltg3pro_setting() {
	require_once dirname( __FILE__ ) . '/admin-packages.php';
	?>
	<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ;?>">
		<?php wp_nonce_field( 'ltg3pro-nonce-key', 'ltg3pro-setting-page' ); ?>
		<?php
		ltg3pro_package_setting_form();
		?>
	</form>
	<?php
}

$admin_pages = array( 'settings_page_lightning_g3_pro_unit_options' );
VkAdmin::admin_scripts( $admin_pages );

function ltg3pro_setting_menu() {
	$custom_page = add_options_page(
		__( 'Lightning G3 <br>Pro Unit Setting', 'lightning-g3-pro-unit' ),       // Name of page
		_x( 'Lightning G3 <br>Pro Unit Setting', 'label in admin menu', 'lightning-g3-pro-unit' ),                // Label in menu
		'edit_theme_options',               // Capability required　このメニューページを閲覧・使用するために最低限必要なユーザーレベルまたはユーザーの種類と権限。
		'lightning_g3_pro_unit_options',               // ユニークなこのサブメニューページの識別子
		'ltg3pro_setting_page'         // メニューページのコンテンツを出力する関数
	);
	if ( ! $custom_page ) {
		return;
	}
}
add_action( 'admin_menu', 'ltg3pro_setting_menu' );

/*-------------------------------------------*/
/*	Setting Page
/*-------------------------------------------*/
function ltg3pro_setting_page() {
    global $ltg3pro_prefix;
	$get_page_title = $ltg3pro_prefix . ' ' . __( 'Lightning G3 <br>Pro Unit', 'lightning-g3-pro-unit' );

	$get_logo_html = '';
	// $get_logo_html = '<img src="'.plugin_dir_url( __FILE__ ).'/images/lightning-g3-pro-unit-logo_ol.svg'.'" alt="VK Blocks" />';
	// $get_logo_html = apply_filters( 'ltg3pro_logo_html', $get_logo_html );

	$get_menu_html  = '';
	$get_menu_html .= apply_filters( 'ltg3pro_pro_menu', '' );

	VkAdmin::admin_page_frame( $get_page_title, 'ltg3pro_setting', $get_logo_html, $get_menu_html );
}

/*-------------------------------------------*/
/*	save option
/*-------------------------------------------*/
function ltg3pro_setting_option_save() {

	// 他のAjaxリクエストが403エラーになるので、LTG3PRO設定ページ以外では実行しない。
	if(strpos($_SERVER["REQUEST_URI"],'lightning_g3_pro_unit_options') === false ){
		return;
	}

	if ( ! empty( $_POST ) && check_admin_referer( 'ltg3pro-nonce-key', 'ltg3pro-setting-page' ) ) {

		if ( isset( $_POST['lightning_g3_pro_unit_options'] ) ) {
			update_option( 'lightning_g3_pro_unit_options', $_POST['lightning_g3_pro_unit_options'] );
		}

		if ( isset( $_POST['lightning-g3-pro-unit-license-key'] )  ) {
			update_option( 'lightning-g3-pro-unit-license-key', $_POST['lightning-g3-pro-unit-license-key'] );
		}

	}
	// wp_safe_redirect( menu_page_url( 'lightning_g3_pro_unit_options', false ) );
}
add_action( 'admin_init', 'ltg3pro_setting_option_save', 10, 2 );


