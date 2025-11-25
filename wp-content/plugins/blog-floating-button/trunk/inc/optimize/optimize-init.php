<div id="bfb_wrap" class="optimize">

	<h1 class="bfb_h1">A/Bテストのパターン作成</h1>

	<h2>基本設定</h2>

	<div class="menuLink"><a href="?page=blog-floating-button-optimize" class="bfb_edit_finish">編集を終了</a></div>

	<form action="?page=blog-floating-button-optimize" method="post">
		<?php wp_nonce_field( 'bfb_optimize_setting', '_wpnonce_bfb' ); ?>

		<div class="bfb_inner_wrap">

			<table class="form-table">
				<tr>
					<th>テストID<span class="bfb_popup_help" data-message="自動付与されるIDです。変更はできません。">?</span></th>
					<td><input type="text" class="regular-text" value="<?php echo esc_attr($this->optimize_id); ?>" readonly><small class="bfb_small">自動付与されるため変更できません。</small></td>
				</tr>
				<tr>
					<th>テスト名<span class="bfb_popup_help" data-message="実際のテストでは使用しません。管理用に好きな名前をつけてください(日本語OK)。">?</span></th>
					<td><input type="text" class="regular-text" name="optimize_name" value="<?php echo esc_attr($this->optimize_name); ?>"><small class="bfb_small"></small></td>
				</tr>
				<tr>
					<th>ボタンデザイン(メイン)<span class="bfb_popup_help" data-message="テストのメインになるボタンデザインを選択してください。">?</span></th>
					<td>
						<?php
							foreach( $this->designTypes as $designType ): ?>
								<label for="<?php echo esc_attr($designType); ?>_main"><input type="radio" id="<?php echo esc_attr($designType); ?>_main" name="mainBtnDesign" class="" value="<?php echo esc_attr($designType); ?>" <?= ($this->mainBtnDesign == $designType) ? 'checked' : '' ?>><?php echo esc_attr($this->designNames[$designType]); ?></label>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<th>ボタンデザイン(サブ)<span class="bfb_popup_help" data-message="テストしたいボタンデザインを選択してください。">?</span></th>
					<td>
						<?php
							foreach( $this->designTypes as $designType ): ?>
							<label for="<?php echo esc_attr($designType); ?>_sub"><input type="radio" id="<?php echo esc_attr($designType); ?>_sub" name="subBtnDesign" class="" value="<?php echo esc_attr($designType); ?>" <?= ($this->subBtnDesign == $designType) ? 'checked' : '' ?>><?php echo esc_attr($this->designNames[$designType]); ?></label>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<th>メインへの振り分け率<span class="bfb_popup_help" data-message="メインボタンとサブボタンのアクセスの振り分け率を設定してください。50で均等に振り分けられます。0でサブボタンのみ、100でメインボタンのみが表示されます。">?</span></th>
					<td><input type="number" min="0" max="100" name="distribution_rate" value="<?php echo esc_attr($this->distribution_rate); ?>"> %<small class="bfb_small">50%で均等に振り分け</small></td>
				</tr>
				<tr>
					<th>デバイス<span class="bfb_popup_help" data-message="テストしたいデバイスを選択してください。">?</span></th>
					<td>
						<?php
							foreach( $this->devices as $bfb_device ): ?>
								<label for="<?php echo esc_attr($bfb_device); ?>"><input type="radio" id="<?php echo esc_attr($bfb_device); ?>" name="device" class="" value="<?php echo esc_attr($bfb_device); ?>" <?= ($this->device == $bfb_device)?'checked':''; ?>><?php echo esc_attr($this->devicesName[$bfb_device]); ?></label>
						<?php endforeach; ?>
					</td>
				</tr>
				<!--<tr>
					<th>終了予定日<span class="bfb_popup_help" data-message="終了予定日以降は配信されません。終了予定日前に途中終了することもできます。">?</span></th>
					<td>
						<input type="text" name="scheduled_finish_date" class="date-input regular-text" value="<?php echo esc_attr($this->scheduled_finish_date); ?>"></td>
				</tr>-->
				<tr>
					<th>メモ<span class="bfb_popup_help" data-message="実際のテストでは使用しません。テスト内容の詳細などメモを残せます。">?</span></th>
					<td><textarea name="memo" class="large-text code"><?php echo esc_attr($this->memo); ?></textarea></td>
				</tr>
			</table>

		</div><!--bfb_inner_wrap-->

		<input type="submit" value="保存して次へ"  class="button button-primary bfb_saveBtn">
		<input type="hidden" name="page" value="blog-floating-button-optimize">
		<input type="hidden" name="optimize_step" value="opt_init">
		<input type="hidden" name="optimize_id" value="<?php echo esc_attr($this->optimize_id); ?>">
		<input type="hidden" name="status" value="<?php echo esc_attr($this->optimize_status); ?>">

	</form>
</div><!--bfb_wrap-->