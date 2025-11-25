<div id="bfb_wrap" class="optimize">

	<h1 class="bfb_h1">A/Bテストのパターン作成</h1>

	<div id="bfb_main" class="bfb_pro">

		<h2>サブボタン設定</h2>

		<div class="menuLink">

			<form action="" method="post" class="action_back">
				<?php wp_nonce_field( 'bfb_optimize_setting', '_wpnonce_bfb' ); ?>
				<input type="submit" value="戻る"  class="">
				<input type="hidden" name="page" value="blog-floating-button-optimize">
				<input type="hidden" name="optimize_id" value="<?php echo esc_attr($this->optimize_id); ?>">
				<input type="hidden" name="optimize_step" value="opt_subBtn">
				<input type="hidden" name="action" value="back">
			</form>

			<a href="?page=blog-floating-button-optimize" class="bfb_edit_finish">編集を終了</a>

		</div>

		<form id="bfb_form_opt" method="POST" action="?page=blog-floating-button-optimize" device="<?php echo esc_attr($this->device); ?>" btnDesign="<?php echo esc_attr($this->subBtnDesign); ?>">
			<?php wp_nonce_field( 'bfb_optimize_setting', '_wpnonce_bfb' ); ?>
			<div class="bfb_inner_wrap">
				<table class="form-table">
					<tr>
						<th>テストID<span class="bfb_popup_help" data-message="自動付与されるIDです。変更はできません。">?</span></th>
						<td><input type="text" class="regular-text" value="<?php echo $this->optimize_id; ?>" readonly><small class="bfb_small">自動付与されるため変更できません。</small></td>
					</tr>
				</table>

				<?php include( dirname(__FILE__) . '/../setting-'.$this->device.'/setting-'.$this->device.'-'.$this->subBtnDesign.'.php' ); ?>

			</div>

			<input type="submit" value="設定完了"  class="button button-primary bfb_saveBtn">
			<input type="hidden" name="page" value="blog-floating-button-optimize">
			<input type="hidden" name="optimize_id" value="<?php echo esc_attr($this->optimize_id); ?>">
			<input type="hidden" name="optimize_step" value="opt_subBtn">

		</form>
	</div>

	<div id="bfb_sub" class="bfb_pro">

		<?php include_once( dirname(__FILE__) . '/../live_preview.php' ); ?>

		<div class="preview_wrap bfb_sticky">
			<h2>プレビュー</h2>
			<div class="bfb_preview_pc">
				<div class="preview_area">
				</div>
			</div>
			<div class="bfb_preview_sp">
				<div class="preview_area">
				</div>
			</div>
		</div>

	</div>

</div><!--bfb_wrap-->