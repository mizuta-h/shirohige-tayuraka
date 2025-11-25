<?php
	if( isset($this->optimize_step) ){
		$optimize_step = '_'.$this->optimize_step;
	}else{
		$optimize_step = '';
	}
?>

<div id="tabs-pc-textBtn">
	<?php if( $this->is_activation ): ?>
		<div class="bfb_load_preview_wrap"><a href="javascript:void(0)" class="bfb_load_preview">プレビュー更新</a></div>
	<?php endif; ?>
	<h3>ボタン単体の設定</h3>

	<?php if( $this->is_activation ): ?>
	<table class="form-table">
		<tbody>
		<tr>
			<th>マイクロコピー(PRO版)<span class="bfb_popup_help" data-message="フロートボタン上部に表示されるテキストです。">?</span></th>
			<td>
				<input name="bfb_textBtn_topText_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtn_topText_pc'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>マイクロコピー色(PRO版)<span class="bfb_popup_help" data-message="マイクロコピーの色を変更できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textBtn_topTextColorPicker_pc',
						$this->{'bfb_textBtn_topTextColorPicker_pc'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		</tbody>
	</table>

	<hr>
	<?php endif; ?>

	<table class="form-table">
		<tbody>
		<tr>
			<th>ボタンタイプ<span class="bfb_popup_help" data-message="ボタン枠の形状を選択できます。">?</span></th>
			<td>
				<?php
					$textBtn_btnType_pc_rounded_corners = '';
					$textBtn_btnType_pc_square = '';
					switch( $this->{'bfb_textBtn_btnType_pc'.$optimize_step} ){
						case "rounded_corners":
							$textBtn_btnType_pc_rounded_corners = 'selected="selected"';
							break;
						case "square":
							$textBtn_btnType_pc_square = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtn_btnType_pc">
					<option value="rounded_corners" <?php echo $textBtn_btnType_pc_rounded_corners; ?>>角丸ボタン</option>
					<option value="square" <?php echo $textBtn_btnType_pc_square; ?>>四角ボタン</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>ボタン色<span class="bfb_popup_help" data-message="ボタン色を変更できます。">?</span></th>
			<td>
				<?php
					$textBtn_btnColor_red_pc = '';
					$textBtn_btnColor_pink_pc = '';
					$textBtn_btnColor_yellow_pc = '';
					$textBtn_btnColor_orange_pc = '';
					$textBtn_btnColor_green_pc = '';
					$textBtn_btnColor_blue_pc = '';
					switch( $this->{'bfb_textBtn_btnColor_pc'.$optimize_step} ){
						case "red":
							$textBtn_btnColor_red_pc = 'selected="selected"';
							break;
						case "pink":
							$textBtn_btnColor_pink_pc = 'selected="selected"';
							break;
						case "yellow":
							$textBtn_btnColor_yellow_pc = 'selected="selected"';
							break;
						case "orange":
							$textBtn_btnColor_orange_pc = 'selected="selected"';
							break;
						case "green":
							$textBtn_btnColor_green_pc = 'selected="selected"';
							break;
						case "blue":
							$textBtn_btnColor_blue_pc = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtn_btnColor_pc">
					<option value="red" <?php echo $textBtn_btnColor_red_pc; ?>>赤</option>
					<option value="orange" <?php echo $textBtn_btnColor_orange_pc; ?>>オレンジ</option>
					<option value="green" <?php echo $textBtn_btnColor_green_pc; ?>>緑</option>
					<option value="blue" <?php echo $textBtn_btnColor_blue_pc; ?>>青</option>
					<option value="yellow" <?php echo $textBtn_btnColor_yellow_pc; ?>>黄</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textBtn_btnColorPicker_pc',
					$this->{'bfb_textBtn_btnColorPicker_pc'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		<tr>
			<th>グラデーション明度(PRO版)<span class="bfb_popup_help" data-message="ボタン色のグラデーション明度を設定できます。0から1に近くにつれ差が大きくなります。0が使えないため、0.00001のように入力するとグラデーションがない色に近づきます。デフォルト値は0.1です。">?</span></th>
			<td>
				<input name="bfb_textBtn_btnColorLighten_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->{'bfb_textBtn_btnColorLighten_pc'.$optimize_step}); ?>" />
				<small class="bfb_small">推奨値は0〜0.2 ※0→1に近づけると明度差が大きくなる</small>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>ボタンの文言<span class="bfb_popup_help" data-message="ボタン上の文言を設定できます。">?</span></th>
			<td>
				<input name="bfb_textBtn_btnText_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtn_btnText_pc'.$optimize_step}); ?>" />
				<small class="bfb_small">例) 無料登録はコチラ</small>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン文言の色(PRO版)<span class="bfb_popup_help" data-message="ボタン上の文言の色を設定できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textBtn_btnTextColorPicker_pc',
						$this->{'bfb_textBtn_btnTextColorPicker_pc'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>リンク先URL<span class="bfb_popup_help" data-message="ボタンをクリックした時の飛び先URLを設定できます。">?</span></th>
			<td>
				<input name="bfb_textBtn_linkUrl_pc" type="text" class="regular-text" value="<?php echo esc_url($this->{'bfb_textBtn_linkUrl_pc'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>リンクの開き方<span class="bfb_popup_help" data-message="リンクの開き方を設定できます。ボタンをクリックした時に別タブで開きたい場合「別のタブ」を選択してください。">?</span></th>
			<td>
				<?php
					$textBtn_linkTarget_pc_blank = '';
					if( $this->{'bfb_textBtn_linkTarget_pc'.$optimize_step} == "blank" ){
						$textBtn_linkTarget_pc_blank = 'selected="selected"';
					}
				?>
				<select name="bfb_textBtn_linkTarget_pc">
					<option value="self">同じタブ</option>
					<option value="blank" <?php echo $textBtn_linkTarget_pc_blank; ?>>別のタブ</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>rel属性の付与(PRO版)<span class="bfb_popup_help" data-message='rel属性を付与します。リンクの開き方で「別タブ」を選択すると、rel="noopener"は自動付与されます。'>?</span></th>
			<td>
				<?php
					$textBtn_linkRel_pc_nofollow = '';
					if( $this->{'bfb_textBtn_linkRel_pc'.$optimize_step} == "nofollow" ){
						$textBtn_linkRel_pc_nofollow = 'selected="selected"';
					}
				?>
				<select name="bfb_textBtn_linkRel_pc">
					<option value="">付与しない</option>
					<option value="nofollow" <?php echo $textBtn_linkRel_pc_nofollow; ?>>nofollow</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>クリック測定時のメモ(PRO版)<span class="bfb_popup_help" data-message='クリック測定時に保存するメモを入力します。ボタン名やテスト名などをメモを入力すると、あとでメモフィルタをかけて検索することができます。必要なければ、空欄のままでOK。'>?</span></th>
			<td>
				<input name="bfb_textBtn_trackingMemo_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtn_trackingMemo_pc'.$optimize_step}); ?>" />
				<small class="bfb_small"></small>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<?php if( $this->is_activation ): ?>

	<hr>

	<table class="form-table">
		<tbody>
		<tr>
			<th>背景色<span class="bfb_popup_help" data-message="BFB背景の帯の色を変更できます。">?</span></th>
			<td>
				<?php
					$textBtn_bfbBgColor_pc = '';
					$textBtn_bfbBgColor_red_pc = '';
					$textBtn_bfbBgColor_yellow_pc = '';
					$textBtn_bfbBgColor_orange_pc = '';
					$textBtn_bfbBgColor_green_pc = '';
					$textBtn_bfbBgColor_blue_pc = '';
					switch( $this->{'bfb_textBtn_bfbBgColor_pc'.$optimize_step} ){
						case "black":
							$textBtn_bfbBgColor_pc = 'selected="selected"';
							break;
						case "red":
							$textBtn_bfbBgColor_red_pc = 'selected="selected"';
							break;
						case "yellow":
							$textBtn_bfbBgColor_yellow_pc = 'selected="selected"';
							break;
						case "orange":
							$textBtn_bfbBgColor_orange_pc = 'selected="selected"';
							break;
						case "green":
							$textBtn_bfbBgColor_green_pc = 'selected="selected"';
							break;
						case "blue":
							$textBtn_bfbBgColor_blue_pc = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtn_bfbBgColor_pc">
					<option value="black" <?php echo $textBtn_bfbBgColor_pc; ?>>黒</option>
					<option value="red" <?php echo $textBtn_bfbBgColor_red_pc; ?>>赤</option>
					<option value="yellow" <?php echo $textBtn_bfbBgColor_yellow_pc; ?>>黄</option>
					<option value="orange" <?php echo $textBtn_bfbBgColor_orange_pc; ?>>オレンジ</option>
					<option value="green" <?php echo $textBtn_bfbBgColor_green_pc; ?>>緑</option>
					<option value="blue" <?php echo $textBtn_bfbBgColor_blue_pc; ?>>青</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>背景色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textBtn_bfbBgColorPicker_pc',
					$this->{'bfb_textBtn_bfbBgColorPicker_pc'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		</tbody>
	</table>

	<?php endif; ?>

</div>