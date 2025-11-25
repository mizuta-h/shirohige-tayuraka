<?php
	if( isset($this->optimize_step) ){
		$optimize_step = '_'.$this->optimize_step;
	}else{
		$optimize_step = '';
	}
?>

<div id="tabs-pc-textBtnTextBtn">

	<?php if( $this->is_activation ): ?>
		<div class="bfb_load_preview_wrap"><a href="javascript:void(0)" class="bfb_load_preview">プレビュー更新</a></div>
	<?php endif; ?>
	<h3>ボタン+ボタンの設定</h3>

	<table class="form-table">
		<tbody>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>マイクロコピー(PRO版)<span class="bfb_popup_help" data-message="フロートボタン上部に表示されるテキストです。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_topText_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_topText_pc'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>マイクロコピー色(PRO版)<span class="bfb_popup_help" data-message="マイクロコピーの色を変更できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textBtnTextBtn_topTextColorPicker_pc',
						$this->{'bfb_textBtnTextBtn_topTextColorPicker_pc'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>ボタンタイプ<span class="bfb_popup_help" data-message="ボタン枠の形状を選択できます。">?</span></th>
			<td>
				<?php
					$btnType_pc_rounded_corners_1 = '';
					$btnType_pc_square_1 = '';
					switch( $this->{'bfb_textBtnTextBtn_btnType_pc'.$optimize_step} ){
						case "rounded_corners":
							$btnType_pc_rounded_corners_1 = 'selected="selected"';
							break;
						case "square":
							$btnType_pc_square_1 = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtnTextBtn_btnType_pc">
					<option value="rounded_corners" <?php echo $btnType_pc_rounded_corners_1; ?>>角丸ボタン</option>
					<option value="square" <?php echo $btnType_pc_square_1; ?>>四角ボタン</option>
				</select>
			</td>
		</tr>
		</tbody>
	</table>

	<hr>

	<h4>左ボタン</h4>

	<table class="form-table">
		<tbody>
		<tr>
			<th>ボタン色<span class="bfb_popup_help" data-message="ボタン色を変更できます。">?</span></th>
			<td>
				<?php
					$textBtnTextBtn_btnColor_red_pc = '';
					$textBtnTextBtn_btnColor_pink_pc = '';
					$textBtnTextBtn_btnColor_yellow_pc = '';
					$textBtnTextBtn_btnColor_orange_pc = '';
					$textBtnTextBtn_btnColor_green_pc = '';
					$textBtnTextBtn_btnColor_blue_pc = '';
					switch( $this->{'bfb_textBtnTextBtn_btnColor_pc'.$optimize_step} ){
						case "red":
							$textBtnTextBtn_btnColor_red_pc = 'selected="selected"';
							break;
						case "pink":
							$textBtnTextBtn_btnColor_pink_pc = 'selected="selected"';
							break;
						case "yellow":
							$textBtnTextBtn_btnColor_yellow_pc = 'selected="selected"';
							break;
						case "orange":
							$textBtnTextBtn_btnColor_orange_pc = 'selected="selected"';
							break;
						case "green":
							$textBtnTextBtn_btnColor_green_pc = 'selected="selected"';
							break;
						case "blue":
							$textBtnTextBtn_btnColor_blue_pc = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtnTextBtn_btnColor_pc">
					<option value="red" <?php echo $textBtnTextBtn_btnColor_red_pc; ?>>赤</option>
					<option value="orange" <?php echo $textBtnTextBtn_btnColor_orange_pc; ?>>オレンジ</option>
					<option value="green" <?php echo $textBtnTextBtn_btnColor_green_pc; ?>>緑</option>
					<option value="blue" <?php echo $textBtnTextBtn_btnColor_blue_pc; ?>>青</option>
					<option value="yellow" <?php echo $textBtnTextBtn_btnColor_yellow_pc; ?>>黄</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textBtnTextBtn_btnColorPicker_pc',
					$this->{'bfb_textBtnTextBtn_btnColorPicker_pc'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		<tr>
			<th>グラデーション明度(PRO版)<span class="bfb_popup_help" data-message="ボタン色のグラデーション明度を設定できます。0から1に近くにつれ差が大きくなります。0が使えないため、0.00001のように入力するとグラデーションがない色に近づきます。デフォルト値は0.1です。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_btnColorLighten_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_btnColorLighten_pc'.$optimize_step}); ?>" />
				<small class="bfb_small">推奨値は0〜0.2 ※0→1に近づけると明度差が大きくなる</small>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<th>ボタンの文言<span class="bfb_popup_help" data-message="ボタン上の文言を設定できます。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_btnText_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_btnText_pc'.$optimize_step}); ?>" />
				<small class="bfb_small">例) 無料登録はコチラ</small>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン文言の色(PRO版)<span class="bfb_popup_help" data-message="ボタン上の文言の色を設定できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textBtnTextBtn_btnTextColorPicker_pc',
						$this->{'bfb_textBtnTextBtn_btnTextColorPicker_pc'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>リンク先URL<span class="bfb_popup_help" data-message="ボタンをクリックした時の飛び先URLを設定できます。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_linkUrl_pc" type="text" class="regular-text" value="<?php echo esc_url($this->{'bfb_textBtnTextBtn_linkUrl_pc'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>リンクの開き方<span class="bfb_popup_help" data-message="リンクの開き方を設定できます。ボタンをクリックした時に別タブで開きたい場合「別のタブ」を選択してください。">?</span></th>
			<td>
				<?php
					$textBtnTextBtn_linkTarget_pc_blank = '';
					if( $this->{'bfb_textBtnTextBtn_linkTarget_pc'.$optimize_step} == "blank" ){
						$textBtnTextBtn_linkTarget_pc_blank = 'selected="selected"';
					}
				?>
				<select name="bfb_textBtnTextBtn_linkTarget_pc">
					<option value="self">同じタブ</option>
					<option value="blank" <?php echo $textBtnTextBtn_linkTarget_pc_blank; ?>>別のタブ</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>rel属性の付与(PRO版)<span class="bfb_popup_help" data-message='rel属性を付与します。リンクの開き方で「別タブ」を選択すると、rel="noopener"は自動付与されます。'>?</span></th>
			<td>
				<?php
					$textBtnTextBtn_linkRel_pc_nofollow = '';
					if( $this->{'bfb_textBtnTextBtn_linkRel_pc'.$optimize_step} == "nofollow" ){
						$textBtnTextBtn_linkRel_pc_nofollow = 'selected="selected"';
					}
				?>
				<select name="bfb_textBtnTextBtn_linkRel_pc">
					<option value="">付与しない</option>
					<option value="nofollow" <?php echo $textBtnTextBtn_linkRel_pc_nofollow; ?>>nofollow</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>クリック測定時のメモ(PRO版)<span class="bfb_popup_help" data-message='クリック測定時に保存するメモを入力します。ボタン名やテスト名などをメモを入力すると、あとでメモフィルタをかけて検索することができます。必要なければ、空欄のままでOK。'>?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_trackingMemo_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_trackingMemo_pc'.$optimize_step}); ?>" />
				<small class="bfb_small"></small>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<hr>

	<h4>右ボタン</h4>

	<table class="form-table">
		<tbody>
		<tr>
			<th>ボタン色<span class="bfb_popup_help" data-message="ボタン色を変更できます。">?</span></th>
			<td>
				<?php
					$textBtnTextBtn_btnColor2_red_pc = '';
					$textBtnTextBtn_btnColor2_pink_pc = '';
					$textBtnTextBtn_btnColor2_yellow_pc = '';
					$textBtnTextBtn_btnColor2_orange_pc = '';
					$textBtnTextBtn_btnColor2_green_pc = '';
					$textBtnTextBtn_btnColor2_blue_pc = '';
					switch( $this->{'bfb_textBtnTextBtn_btnColor2_pc'.$optimize_step} ){
						case "red":
							$textBtnTextBtn_btnColor2_red_pc = 'selected="selected"';
							break;
						case "pink":
							$textBtnTextBtn_btnColor2_pink_pc = 'selected="selected"';
							break;
						case "yellow":
							$textBtnTextBtn_btnColor2_yellow_pc = 'selected="selected"';
							break;
						case "orange":
							$textBtnTextBtn_btnColor2_orange_pc = 'selected="selected"';
							break;
						case "green":
							$textBtnTextBtn_btnColor2_green_pc = 'selected="selected"';
							break;
						case "blue":
							$textBtnTextBtn_btnColor2_blue_pc = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtnTextBtn_btnColor2_pc">
					<option value="red" <?php echo $textBtnTextBtn_btnColor2_red_pc; ?>>赤</option>
					<option value="orange" <?php echo $textBtnTextBtn_btnColor2_orange_pc; ?>>オレンジ</option>
					<option value="green" <?php echo $textBtnTextBtn_btnColor2_green_pc; ?>>緑</option>
					<option value="blue" <?php echo $textBtnTextBtn_btnColor2_blue_pc; ?>>青</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textBtnTextBtn_btnColorPicker2_pc',
					$this->{'bfb_textBtnTextBtn_btnColorPicker2_pc'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		<tr>
			<th>グラデーション明度(PRO版)<span class="bfb_popup_help" data-message="ボタン色のグラデーション明度を設定できます。0から1に近くにつれ差が大きくなります。0が使えないため、0.00001のように入力するとグラデーションがない色に近づきます。デフォルト値は0.1です。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_btnColorLighten2_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_btnColorLighten2_pc'.$optimize_step}); ?>" />
				<small class="bfb_small">推奨値は0〜0.2 ※0→1に近づけると明度差が大きくなる</small>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<th>ボタンの文言<span class="bfb_popup_help" data-message="ボタン上の文言を設定できます。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_btnText2_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_btnText2_pc'.$optimize_step}); ?>" />
				<small class="bfb_small">例) 無料登録はコチラ</small>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン文言の色(PRO版)<span class="bfb_popup_help" data-message="ボタン上の文言の色を設定できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textBtnTextBtn_btnTextColorPicker2_pc',
						$this->{'bfb_textBtnTextBtn_btnTextColorPicker2_pc'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>リンク先URL<span class="bfb_popup_help" data-message="ボタンをクリックした時の飛び先URLを設定できます。">?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_linkUrl2_pc" type="text" class="regular-text" value="<?php echo esc_url($this->{'bfb_textBtnTextBtn_linkUrl2_pc'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>リンクの開き方<span class="bfb_popup_help" data-message="リンクの開き方を設定できます。ボタンをクリックした時に別タブで開きたい場合「別のタブ」を選択してください。">?</span></th>
			<td>
				<?php
					$textBtnTextBtn_linkTarget2_pc_blank = '';
					if( $this->{'bfb_textBtnTextBtn_linkTarget2_pc'.$optimize_step} == "blank" ){
						$textBtnTextBtn_linkTarget2_pc_blank = 'selected="selected"';
					}
				?>
				<select name="bfb_textBtnTextBtn_linkTarget2_pc">
					<option value="self">同じタブ</option>
					<option value="blank" <?php echo esc_attr($textBtnTextBtn_linkTarget2_pc_blank); ?>>別のタブ</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>rel属性の付与(PRO版)<span class="bfb_popup_help" data-message='rel属性を付与します。リンクの開き方で「別タブ」を選択すると、rel="noopener"は自動付与されます。'>?</span></th>
			<td>
				<?php
					$textBtnTextBtn_linkRel2_pc_nofollow ='';
					if( $this->{'bfb_textBtnTextBtn_linkRel2_pc'.$optimize_step} == "nofollow" ){
						$textBtnTextBtn_linkRel2_pc_nofollow = 'selected="selected"';
					}
				?>
				<select name="bfb_textBtnTextBtn_linkRel2_pc">
					<option value="">付与しない</option>
					<option value="nofollow" <?php echo $textBtnTextBtn_linkRel2_pc_nofollow; ?>>nofollow</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>クリック測定時のメモ(PRO版)<span class="bfb_popup_help" data-message='クリック測定時に保存するメモを入力します。ボタン名やテスト名などをメモを入力すると、あとでメモフィルタをかけて検索することができます。必要なければ、空欄のままでOK。'>?</span></th>
			<td>
				<input name="bfb_textBtnTextBtn_trackingMemo2_pc" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textBtnTextBtn_trackingMemo2_pc'.$optimize_step}); ?>" />
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
					$textBtnTextBtn_bfbBgColor_pc = '';
					$textBtnTextBtn_bfbBgColor_red_pc = '';
					$textBtnTextBtn_bfbBgColor_yellow_pc = '';
					$textBtnTextBtn_bfbBgColor_orange_pc = '';
					$textBtnTextBtn_bfbBgColor_green_pc = '';
					$textBtnTextBtn_bfbBgColor_blue_pc = '';
					switch( $this->{'bfb_textBtnTextBtn_bfbBgColor_pc'.$optimize_step} ){
						case "black":
							$textBtnTextBtn_bfbBgColor_pc = 'selected="selected"';
							break;
						case "red":
							$textBtnTextBtn_bfbBgColor_red_pc = 'selected="selected"';
							break;
						case "yellow":
							$textBtnTextBtn_bfbBgColor_yellow_pc = 'selected="selected"';
							break;
						case "orange":
							$textBtnTextBtn_bfbBgColor_orange_pc = 'selected="selected"';
							break;
						case "green":
							$textBtnTextBtn_bfbBgColor_green_pc = 'selected="selected"';
							break;
						case "blue":
							$textBtnTextBtn_bfbBgColor_blue_pc = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textBtnTextBtn_bfbBgColor_pc">
					<option value="black" <?php echo $textBtnTextBtn_bfbBgColor_pc; ?>>黒</option>
					<option value="red" <?php echo $textBtnTextBtn_bfbBgColor_red_pc; ?>>赤</option>
					<option value="yellow" <?php echo $textBtnTextBtn_bfbBgColor_yellow_pc; ?>>黄</option>
					<option value="orange" <?php echo $textBtnTextBtn_bfbBgColor_orange_pc; ?>>オレンジ</option>
					<option value="green" <?php echo $textBtnTextBtn_bfbBgColor_green_pc; ?>>緑</option>
					<option value="blue" <?php echo $textBtnTextBtn_bfbBgColor_blue_pc; ?>>青</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>背景色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textBtnTextBtn_bfbBgColorPicker_pc',
					$this->{'bfb_textBtnTextBtn_bfbBgColorPicker_pc'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		</tbody>
	</table>

	<?php endif; ?>

</div>