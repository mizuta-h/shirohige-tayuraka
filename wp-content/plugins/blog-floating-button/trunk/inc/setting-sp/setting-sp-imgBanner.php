<?php
	if( isset($this->optimize_step) ){
		$optimize_step = '_'.$this->optimize_step;
	}else{
		$optimize_step = '';
	}
?>

<div id="tabs-sp-imgBanner">

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
				$rt_sp = '';
				$rb_sp = '';
				$lb_sp = '';
				$lt_sp = '';
				switch( $this->{'bfb_imgBanner_bfbPos_sp'.$optimize_step} ){
					case "rt":
						$rt_sp = 'selected="selected"';
						break;
					case "rb":
						$rb_sp = 'selected="selected"';
						break;
					case "lb":
						$lb_sp = 'selected="selected"';
						break;
					case "lt":
						$lt_sp = 'selected="selected"';
						break;
				}
			?>
			<select name="bfb_imgBanner_bfbPos_sp">
				<option value="rt" <?php echo $rt_sp; ?>>画面上</option>
				<option value="rb" <?php echo $rb_sp; ?>>画面下</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>画像URL<span class="bfb_popup_help" data-message="フロートボタンとして表示するバナー画像のURLを設定します。">?</span></th>
		<td>
			<input name="bfb_imgBanner_bannerUrl_sp" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_imgBanner_bannerUrl_sp'.$optimize_step}); ?>" />
			<input type="button" name="bfb_select_banner"  tdata="bfb_imgBanner_bannerUrl_sp" class="cmb_upload_button button" value="画像を選択" />
			<input type="hidden" name="target" value="">
			<div id="bfb_imgBanner_bannerUrl_sp"></div>
			<small class="bfb_small">スマホの画像サイズは「640px × 120px」を推奨</small>
		</td>
	</tr>
	<tr>
		<th>リンク先URL<span class="bfb_popup_help" data-message="ボタンをクリックした時の飛び先URLを設定できます。">?</span></th>
		<td>
			<input name="bfb_imgBanner_linkUrl_sp" type="text" class="regular-text" value="<?php echo esc_url($this->{'bfb_imgBanner_linkUrl_sp'.$optimize_step}); ?>" />
		</td>
	</tr>
	<tr>
		<th>リンクの開き方<span class="bfb_popup_help" data-message="リンクの開き方を設定できます。ボタンをクリックした時に別タブで開きたい場合「別のタブ」を選択してください。">?</span></th>
		<td>
			<?php
				$linktarget_sp_blank = '';
				if( $this->{'bfb_imgBanner_linkTarget_sp'.$optimize_step} == "blank" ){
					$linktarget_sp_blank = 'selected="selected"';
				}
			?>
			<select name="bfb_imgBanner_linkTarget_sp">
				<option value="self">同じタブ</option>
				<option value="blank" <?php echo $linktarget_sp_blank; ?>>別のタブ</option>
			</select>
		</td>
	</tr>
	<?php if( $this->is_activation ): ?>
	<tr>
		<th>rel属性の付与(PRO版)<span class="bfb_popup_help" data-message='rel属性を付与します。リンクの開き方で「別タブ」を選択すると、rel="noopener"は自動付与されます。'>?</span></th>
		<td>
			<?php
				$imgBanner_linkRel_sp_nofollow = '';
				if( $this->{'bfb_imgBanner_linkRel_sp'.$optimize_step} == "nofollow" ){
					$imgBanner_linkRel_sp_nofollow = 'selected="selected"';
				}
			?>
			<select name="bfb_imgBanner_linkRel_sp">
				<option value="">付与しない</option>
				<option value="nofollow" <?php echo $imgBanner_linkRel_sp_nofollow; ?>>nofollow</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>クリック測定時のメモ(PRO版)<span class="bfb_popup_help" data-message='クリック測定時に保存するメモを入力します。ボタン名やテスト名などをメモを入力すると、あとでメモフィルタをかけて検索することができます。必要なければ、空欄のままでOK。'>?</span></th>
		<td>
			<input name="bfb_imgBanner_trackingMemo_sp" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_imgBanner_trackingMemo_sp'.$optimize_step}); ?>" />
			<small class="bfb_small"></small>
		</td>
	</tr>
	<?php endif; ?>
	</tbody>
</table>

</div>