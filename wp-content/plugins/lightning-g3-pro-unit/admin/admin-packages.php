<?php
/**
 * Package List of Lightning G3 Pro Unit
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

/**
 * Admin Package Setting Form
 */
function ltg3pro_package_setting_form() {
	$license_key    = get_option( 'lightning-g3-pro-unit-license-key' );
	$options        = ltg3pro_get_options();
	$packages       = ltg3pro_package_list();
	$license_status = ltg3pro_get_license_status();

	// ライセンスキー入力欄の表示制御用フィルターフック.
	$license_key_display_setting = apply_filters( 'ltg3pro_license_key_display_setting', true );

	// ライセンスキー入力欄の実際の表示制御フラグ.
	// ※ ライセンスキーが未入力か、切れている場合は非表示にできないようにするため二段階にしている.
	$license_key_display = true;
	// ライセンスキー入力欄の表示制御フックが false の場合 ライセンスキーが未入力じゃない（入力されてる） && ライセンスキーが切れてない（有効）.
	if ( false === $license_key_display_setting && 'license_no_unregistered' !== $license_status && 'license_expired' !== $license_status ) {
		// 表示しない.
		$license_key_display = false;
	}
	?>

	<?php if ( true === $license_key_display ) : ?>
	<!--License Key Form -->
	<div id="license-key-form">
		<label for="lightning-g3-pro-unit-license-key">
			<strong><?php esc_html_e( 'License key: ', 'lightning-g3-pro-unit' ); ?></strong>
		</label>
		<input type="password" id="lightning-g3-pro-unit-license-key" name="lightning-g3-pro-unit-license-key" size="10" value="<?php echo esc_html( $license_key ); ?>">
		<p><?php esc_html_e( 'Once you enter the license key you will be able to do a one click update from the administration screen.', 'lightning-g3-pro-unit' ); ?></p>
	</div>
	<?php endif; ?>

	<style type="text/css">

	.vk_admin_page .wp-list-table tbody th,
	.vk_admin_page .wp-list-table tbody td{
		padding:1em;
	}

	.vk_admin_page input[type=password] {
		width: 50%;
		margin-bottom: 5px;
	}

	th .vk-admin-inline-radio input {
		height:40px;
		margin:0 10px;
	}
	.plugins .inactive th.check-column {
		border-left: 4px solid #ffffff;
		padding: 1em;
	}
	/*
	.plugins tbody th.check-column {
		padding-top:12px;
	}
	*/
	</style>
	<table class="wp-list-table widefat plugins" style="width:100%;">
		<thead>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb' style="white-space:nowrap;">
				<?php esc_html_e( 'OFF / ON', 'lightning-g3-pro-unit' ); ?>
			</th>
			<th scope='col' id='name' class='manage-column column-name'><?php esc_html_e( 'Function', 'lightning-g3-pro-unit' ); ?>
			<!-- </th>
			<th scope='col' id='description' class='manage-column column-description'><?php esc_html_e( 'Description', 'lightning-g3-pro-unit' ); ?> -->
			</th>
		</tr>
		</thead>

		<tbody id="the-list">
			<?php
			foreach ( $packages as $package ) {
				$active_class      = '';
				$checked_on_value  = '';
				$checked_off_value = '';
				if (
					isset( $options['package_enable'][ $package['name'] ] )
					&& 'on' === $options['package_enable'][ $package['name'] ]
				) {
					$active_class     = ' active';
					$checked_on_value = ' checked="checked"';
				} elseif (
					empty( $options['package_enable'][ $package['name'] ] ) &&
					isset( $package['default'] ) && 'on' === $package['default']
				) {
					$active_class     = ' active';
					$checked_on_value = ' checked="checked"';
				} else {
					$active_class      = ' inactive';
					$checked_off_value = ' checked="checked"';
				}
				?>
				<tr class="<?php echo esc_attr( $active_class ); ?>">
					<th scope="row" class="check-column">
						<div class="vk-admin-inline-radio">
							<div>
								<input
									type="radio"
									id="lightning_g3_pro_unit_options[package_enable][<?php echo esc_attr( $package['name'] ); ?>]"
									name="lightning_g3_pro_unit_options[package_enable][<?php echo esc_attr( $package['name'] ); ?>]"
									value="off"
									<?php echo $checked_off_value; ?>
								/>
								<label><?php esc_html_e( 'OFF', 'lightning-g3-pro-unit' ); ?></label>
							</div>
							<div>
								<input
									type="radio"
									id="lightning_g3_pro_unit_options[package_enable][<?php echo esc_attr( $package['name'] ); ?>]"
									name="lightning_g3_pro_unit_options[package_enable][<?php echo esc_attr( $package['name'] ); ?>]"
									value="on"
									<?php echo $checked_on_value; ?>
								/>
								<label><?php esc_html_e( 'ON', 'lightning-g3-pro-unit' ); ?></label>
							</div>
						</div>
					</th>
					<td>
						<div class='plugin-title'>
							<label for="lightning_g3_pro_unit_options[package_enable][<?php echo esc_attr( $package['name'] ); ?>]">
								<strong><?php echo esc_html( $package['title'] ); ?></strong>
							</label>
						</div>

					<!-- </td>
					<td class='column-description desc'> -->
						<div class='plugin-description'>
							<?php
							if ( is_array( $package['description'] ) ) {
								foreach ( $package['description'] as $desk ) {
									echo wp_kses_post( $desk );
								}
							} else {
									echo '<p>' . wp_kses_post( $package['description'] ) . '</p>';
							}
							?>
						</div><!-- [ /.plugin-description ] -->
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>

		<tfoot>
			<tr>
				<th scope="col"  class="manage-column column-cb">
					<?php esc_html_e( 'OFF / ON', 'lightning-g3-pro-unit' ); ?>
				</th>
				<th scope='col'  class='manage-column column-name'><?php esc_html_e( 'Function', 'lightning-g3-pro-unit' ); ?>
				<!-- </th>
				<th scope='col'  class='manage-column column-description'><?php esc_html_e( 'Description', 'lightning-g3-pro-unit' ); ?> -->
				</th>
			</tr>
		</tfoot>

	</table>
	<?php
	submit_button();
}


