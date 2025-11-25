<?php
/**
 * Plugin Name:     Lightning G3 Pro Unit
 * Plugin URI:      https://www.vektor-inc.co.jp/
 * Description:
 * Author:          Vektor,Inc.
 * Author URI:      https://www.vektor-inc.co.jp/
 * Text Domain:     lightning-g3-pro-unit
 * Domain Path:     /languags
 * Version:         0.29.7
 *
 * @package         LIGHTNING_G3_PRO_UNIT
 */

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
// Your code starts here.

// Get Plugin version.
$version = get_file_data( __FILE__, array( 'version' => 'Version' ) );
define( 'LIGHTNING_G3_PRO_UNIT_VERSION', $version['version'] );

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/vk-component/config.php';

/**
 * Initial set up
 */
add_action(
	'after_setup_theme',
	function () {

		define( 'LTG3_PRO_DIRECTORY_PATH', __DIR__ );
		define( 'LTG3_PRO_DIRECTORY_URL', plugins_url( '', __FILE__ ) );

		require LTG3_PRO_DIRECTORY_PATH . '/packages.php';
		require LTG3_PRO_DIRECTORY_PATH . '/admin/admin.php';

		load_plugin_textdomain( 'lightning-g3-pro-unit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( is_admin() ) {
			// PHPUnit用に global に入れたが現状 PHPUnit 上でどうも正常に受け取れていない .
			global $ltg3pro_update_checker;

			$ltg3pro_update_checker = PucFactory::buildUpdateChecker(
				'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-g3-pro-unit',
				__FILE__,
				'lightning-g3-pro-unit'
			);

			ltg3pro_check_update();
		}
	}
);

/**
 * Register update license key
 *
 * @param array $query_args : updatechacker array.
 * @return $query_args
 */
function ltg3pro_wsh_filter_update_checks( $query_args ) {

	$license = esc_html( get_option( 'lightning-g3-pro-unit-license-key' ) );

	if ( ! empty( $license ) ) {
		$query_args['lightning-g3-pro-unit-license-key'] = $license;
	}

	return $query_args;
}

/**
 * Update Check
 *
 * @return string
 */
function ltg3pro_check_update() {

	// Get Plugin version.

	$data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
	global $ltg3_pro_version;
	$ltg3_pro_version = $data['version'];

	// Cope with : WP HTTP Error: cURL error 60: SSL certificate problem: certificate has expired.
	add_filter( 'https_ssl_verify', '__return_false' );

	$license = esc_html( get_option( 'lightning-g3-pro-unit-license-key' ) );

	global $ltg3pro_update_checker;

	// ここでライセンスキーをoptionから受け取って投げ返している.
	// ltg3pro_wsh_filter_update_checks はコールバック関数で、ライセンスキーを含んだ配列を返す。
	$ltg3pro_update_checker->addQueryArgFilter( 'ltg3pro_wsh_filter_update_checks' );

	$state  = $ltg3pro_update_checker->getUpdateState();
	$update = $state->getUpdate();
	if (
			! empty( $update )
			&& version_compare( $update->version, $ltg3_pro_version, '>' )
			&& empty( $update->download_url )
		) {
			// ライセンス認証に失敗した場合.
			$status = 'license_expired';
	} else {
		// ライセンス切れ表示が最優先に処理.
		$status = 'license_other';
	}

	return $status;
}

/**
 * Update Status
 *
 * @args array : 主にテスト用の引数。ライセンス認証が現状 PHPUnit 上で正常に動作させられないので、テスト用に引数を受け取る。
 *
 * @return string
 */
function ltg3pro_get_license_status( $args = array() ) {

	$current_theme = get_template();

	$status = '';

	// そもそもテーマが Lightning でない場合.
	if ( 'lightning' !== $current_theme ) {
		$status = 'other_theme';

		// ライセンスキーが未入力の場合.
	} elseif ( empty( get_option( 'lightning-g3-pro-unit-license-key', false ) ) ) {
		$status = 'license_no_unregistered';

		// ライセンスキーが入力されている場合.
	} else {

		// ライセンス状態が PHPUnit 上で認識できていないので、
		// テストの際にステータスを引数で受け取る.
		if ( ! empty( $args['test']['ltg3pro_check_update'] ) ) {
			$status = $args['test']['ltg3pro_check_update'];
		} else {
			$status = ltg3pro_check_update();
		}

		// ライセンスキーが有効じゃない場合.
		if ( 'license_expired' === $status ) {
			$status = 'license_expired';
			// ライセンスキーが有効な場合.
		} else {
			// G3 じゃない場合
			if ( function_exists( 'lightning_is_g3' ) && ! lightning_is_g3() ) {
				$status = 'lightning_not_g3';
			} else {
				$status = 'license_valid';
			}
		}
	}

	return $status;
}

/**
 * License notice
 *
 * @return void
 */
function ltg3pro_license_notice() {

	$status = ltg3pro_get_license_status();

	$current_theme = get_template();

	$notice = '';

	if ( 'other_theme' === $status ) {

		// そもそもテーマが Lightning でない場合.
		$notice .= '<p>';
		$notice .= __( 'The Lightning G3 Pro Unit only works Lightning(free) theme. Plese install and activate Lightning(free) theme.', 'lightning-g3-pro-unit' );
		$notice .= '</p>';

	} elseif ( 'lightning_not_g3' === $status ) {

		// テーマは Lightning だが世代が G3 じゃない場合.
		$notice .= '<p>';
		$notice .= __( 'The Lightning G3 Pro Unit only works in G3 mode. To change to G3 mode, change to Generation 3 from Appearance > Customize > Lightning Feature Settings.', 'lightning-g3-pro-unit' );
		$notice .= '</p>';

	} elseif ( 'license_no_unregistered' === $status || 'license_expired' === $status ) {

		if ( 'license_expired' === $status ) {
			// 期限が切れている場合.
			$notice .= __( 'Your Lightning G3 Pro Unit license key is expired.', 'lightning-g3-pro-unit' );
		}
		if ( 'license_no_unregistered' === $status ) {
			// ライセンスキーが未入力の場合.
			$notice .= '<p>' . __( 'License Key has no registerd.', 'lightning-g3-pro-unit' ) . '</p>';
		}

		$register_url = admin_url( '/' ) . 'options-general.php?page=lightning_g3_pro_unit_options';
		$purchase_url = 'https://vws.vektor-inc.co.jp/product/lightning-g3-pro-pack?ref=g3pro-notice';
		$update_url   = admin_url( '/' ) . 'update-core.php?force-check=1';

		$notice .= '<p>';
		$notice .= __( 'Please register a valid license key.', 'lightning-g3-pro-unit' );
		$notice .= ' [ <a href="' . $register_url . '">' . __( 'Register license key', 'lightning-g3-pro-unit' ) . '</a> | <a href="' . $purchase_url . '" target="_blank">' . __( 'Purchase a license', 'lightning-g3-pro-unit' ) . '</a> ]';
		$notice .= '</p>';

		$notice .= '<p>';
		/* translators: %s: 再読み込みURL */
		$notice .= __( 'If this display does not disappear even after entering a valid license key, re-acquire the update.', 'lightning-g3-pro-unit' );
		$notice .= '[ <a href="' . $update_url . '">' . __( 'Re-acquisition of updates', 'lightning-g3-pro-unit' ) . '</a> ]';
		$notice .= '</p>';

	}

	if ( $notice ) {
		echo '<div class="error">';
		echo '<h4>Lightning G3 Pro Unit</h4>';
		echo wp_kses_post( $notice );
		echo '</div>';
	}
}
add_action( 'admin_notices', 'ltg3pro_license_notice' );

/**
 * Add a link to this plugin's settings page
 *
 * @param array $links : existing links.
 */
function ltg3pro_set_plugin_meta( $links ) {
	$settings_link = '<a href="options-general.php?page=lightning_g3_pro_unit_options">' . __( 'Setting', 'lightning-g3-pro-unit' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ltg3pro_set_plugin_meta', 10, 1 );

/**
 * Plugin activation
 */
if ( is_admin() ) {
	require_once __DIR__ . '/inc/tgm-plugin-activation/config.php';
}
