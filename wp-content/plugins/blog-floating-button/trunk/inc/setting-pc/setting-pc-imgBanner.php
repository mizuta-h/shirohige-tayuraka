<?php
	if( isset($this->optimize_step) ){
		$optimize_step = '_'.$this->optimize_step;
	}else{
		$optimize_step = '';
	}
?>

<div id="tabs-pc-imgBanner">

	<?php if( $this->is_activation ): ?>
		<div class="bfb_load_preview_wrap"><a href="javascript:void(0)" class="bfb_load_preview">プレビュー更新</a></div>
	<?php endif; ?>
	<h3>バナー画像の設定</h3>

	<table class="form-table">
		<tbody>
		<tr>
			<th>配置<span class="bfb_popup_help" data-message="フロートボタンを表示する位置を変更できます。">?</span></th>
			<td>
				<?php
					$rt_pc = '';
					$rb_pc = '';
					$lb_pc = '';
					$lt_pc = '';
					switch( $this->{'bfb_imgBanner_bfbPos_pc'.$optimize_step} ){
						case "rt":
							$rt_pc = 'selected="selected"';
							break;
						case "rb":
							$rb_pc = 'selected="selected"';
							break;
						case "lb":
							$lb_pc = 'selected="selected"';
							break;
						case "lt":
							$lt_pc = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_imgBanner_bfbPos_pc">
					<option value="rt" <?php echo $rt_pc; ?>>右上</option>
					<option value="rb" <?php echo $rb_pc; ?>>右下</option>
					<option value="lb" <?php echo $lb_pc; ?>>左下</option>
					<option value="lt" <?php echo $lt_pc; ?>>左上</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>画像URL<span class="bfb_popup_help" data-message="フロートボタンとして表示するバナー画像のURLを設定します。">?</span></th>
			<td>
				<input name="bfb_imgBanner_bannerUrl_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_imgBanner_bannerUrl_pc'.$optimize_step}); ?>" />
				<input type="button" name="bfb_select_banner"  tdata="bfb_imgBanner_bannerUrl_pc" class="cmb_upload_button button" value="画像を選択" />
				<input type="hidden" name="target" value="">
				<div id="bfb_imgBanner_bannerUrl_pc"></div>
				<small class="bfb_small">PCの画像サイズは「300px × 300px」以内を推奨</small>
			</td>
		</tr>
		<tr>
			<th>リンク先URL<span class="bfb_popup_help" data-message="ボタンをクリックした時の飛び先URLを設定できます。">?</span></th>
			<td>
				<input name="bfb_imgBanner_linkUrl_pc" type="text" class="regular-text" value="<?php echo esc_url($this->{'bfb_imgBanner_linkUrl_pc'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>リンクの開き方<span class="bfb_popup_help" data-message="リンクの開き方を設定できます。ボタンをクリックした時に別タブで開きたい場合「別のタブ」を選択してください。">?</span></th>
			<td>
				<?php
					$linktarget_pc_blank = '';
					if( $this->{'bfb_imgBanner_linkTarget_pc'.$optimize_step} == "blank" ){
						$linktarget_pc_blank = 'selected="selected"';
					}
				?>
				<select name="bfb_imgBanner_linkTarget_pc">
					<option value="self">同じタブ</option>
					<option value="blank" <?php echo $linktarget_pc_blank; ?>>別のタブ</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>rel属性の付与(PRO版)<span class="bfb_popup_help" data-message='rel属性を付与します。リンクの開き方で「別タブ」を選択すると、rel="noopener"は自動付与されます。'>?</span></th>
			<td>
				<?php
					$imgBanner_linkRel_pc_nofollow = '';
					if( $this->{'bfb_imgBanner_linkRel_pc'.$optimize_step} == "nofollow" ){
						$imgBanner_linkRel_pc_nofollow = 'selected="selected"';
					}
				?>
				<select name="bfb_imgBanner_linkRel_pc">
					<option value="">付与しない</option>
					<option value="nofollow" <?php echo $imgBanner_linkRel_pc_nofollow; ?>>nofollow</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>クリック測定時のメモ(PRO版)<span class="bfb_popup_help" data-message='クリック測定時に保存するメモを入力します。ボタン名やテスト名などをメモを入力すると、あとでメモフィルタをかけて検索することができます。必要なければ、空欄のままでOK。'>?</span></th>
			<td>
				<input name="bfb_imgBanner_trackingMemo_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_imgBanner_trackingMemo_pc'.$optimize_step}); ?>" />
				<small class="bfb_small"></small>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>

</div>