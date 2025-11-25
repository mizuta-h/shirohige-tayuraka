<?php
	if( isset($this->optimize_step) ){
		$optimize_step = '_'.$this->optimize_step;
	}else{
		$optimize_step = '';
	}
?>

<div id="tabs-sp-textTextBtn">

<?php if( $this->is_activation ): ?>
	<div class="bfb_load_preview_wrap"><a href="javascript:void(0)" class="bfb_load_preview">プレビュー更新</a></div>
<?php endif; ?>
	<h3>説明文+ボタンの設定</h3>

	<table class="form-table">
		<tbody>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>マイクロコピー(PRO版)<span class="bfb_popup_help" data-message="フロートボタン上部に表示されるテキストです。">?</span></th>
			<td>
				<input name="bfb_textTextBtn_topText_sp" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textTextBtn_topText_sp'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>マイクロコピー色(PRO版)<span class="bfb_popup_help" data-message="マイクロコピーの色を変更できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textTextBtn_topTextColorPicker_sp',
						$this->{'bfb_textTextBtn_topTextColorPicker_sp'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>説明文<span class="bfb_popup_help" data-message="ボタン横の説明文を設定できます。">?</span></th>
			<td>
				<input name="bfb_textTextBtn_discText_sp" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textTextBtn_discText_sp'.$optimize_step}); ?>" />
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>説明文の色(PRO版)<span class="bfb_popup_help" data-message="説明文の色を設定できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textTextBtn_discTextColorPicker_sp',
						$this->{'bfb_textTextBtn_discTextColorPicker_sp'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>

		</tbody>
	</table>

	<hr>

	<table class="form-table">
		<tbody>
		<tr>
			<th>ボタンタイプ<span class="bfb_popup_help" data-message="ボタン枠の形状を選択できます。">?</span></th>
			<td>
				<?php
					$textTextBtn_btnType_sp_rounded_corners_1 = '';
					$textTextBtn_btnType_sp_square_1 = '';
					switch( $this->{'bfb_textTextBtn_btnType_sp'.$optimize_step} ){
						case "rounded_corners":
							$textTextBtn_btnType_sp_rounded_corners_1 = 'selected="selected"';
							break;
						case "square":
							$textTextBtn_btnType_sp_square_1 = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textTextBtn_btnType_sp">
					<option value="rounded_corners" <?php echo $textTextBtn_btnType_sp_rounded_corners_1; ?>>角丸ボタン</option>
					<option value="square" <?php echo $textTextBtn_btnType_sp_square_1; ?>>四角ボタン</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>ボタン色<span class="bfb_popup_help" data-message="ボタン色を変更できます。">?</span></th>
			<td>
				<?php
					$textTextBtn_btnColor_red_sp = '';
					$textTextBtn_btnColor_pink_sp = '';
					$textTextBtn_btnColor_yellow_sp = '';
					$textTextBtn_btnColor_orange_sp = '';
					$textTextBtn_btnColor_green_sp = '';
					$textTextBtn_btnColor_blue_sp = '';
					switch( $this->{'bfb_textTextBtn_btnColor_sp'.$optimize_step} ){
						case "red":
							$textTextBtn_btnColor_red_sp = 'selected="selected"';
							break;
						case "pink":
							$textTextBtn_btnColor_pink_sp = 'selected="selected"';
							break;
						case "yellow":
							$textTextBtn_btnColor_yellow_sp = 'selected="selected"';
							break;
						case "orange":
							$textTextBtn_btnColor_orange_sp = 'selected="selected"';
							break;
						case "green":
							$textTextBtn_btnColor_green_sp = 'selected="selected"';
							break;
						case "blue":
							$textTextBtn_btnColor_blue_sp = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textTextBtn_btnColor_sp">
					<option value="red" <?php echo $textTextBtn_btnColor_red_sp; ?>>赤</option>
					<option value="orange" <?php echo $textTextBtn_btnColor_orange_sp; ?>>オレンジ</option>
					<option value="green" <?php echo $textTextBtn_btnColor_green_sp; ?>>緑</option>
					<option value="blue" <?php echo $textTextBtn_btnColor_blue_sp; ?>>青</option>
					<option value="yellow" <?php echo $textTextBtn_btnColor_yellow_sp; ?>>黄</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textTextBtn_btnColorPicker_sp',
					$this->{'bfb_textTextBtn_btnColorPicker_sp'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		<tr>
			<th>グラデーション明度(PRO版)<span class="bfb_popup_help" data-message="ボタン色のグラデーション明度を設定できます。0から1に近くにつれ差が大きくなります。0が使えないため、0.00001のように入力するとグラデーションがない色に近づきます。デフォルト値は0.1です。">?</span></th>
			<td>
				<input name="bfb_textTextBtn_btnColorLighten_sp" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->{'bfb_textTextBtn_btnColorLighten_sp'.$optimize_step}); ?>" />
				<small class="bfb_small">推奨値は0〜0.2 ※0→1に近づけると明度差が大きくなる</small>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>ボタンの文言<span class="bfb_popup_help" data-message="ボタン上の文言を設定できます。">?</span></th>
			<td>
				<input name="bfb_textTextBtn_btnText_sp" type="text" class="regular-text" value="<?php echo esc_attr( $this->{'bfb_textTextBtn_btnText_sp'.$optimize_step} ); ?>" />
				<small class="bfb_small">例) 無料登録はコチラ</small>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>ボタン文言の色(PRO版)<span class="bfb_popup_help" data-message="ボタン上の文言の色を設定できます。">?</span></th>
			<td>
				<?php
					$this->genelate_color_picker(
						'bfb_textTextBtn_btnTextColorPicker_sp',
						$this->{'bfb_textTextBtn_btnTextColorPicker_sp'.$optimize_step}
					);
			  	?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>リンク先URL<span class="bfb_popup_help" data-message="ボタンをクリックした時の飛び先URLを設定できます。">?</span></th>
			<td>
				<input name="bfb_textTextBtn_linkUrl_sp" type="text" class="regular-text" value="<?php echo esc_url($this->{'bfb_textTextBtn_linkUrl_sp'.$optimize_step}); ?>" />
			</td>
		</tr>
		<tr>
			<th>リンクの開き方<span class="bfb_popup_help" data-message="リンクの開き方を設定できます。ボタンをクリックした時に別タブで開きたい場合「別のタブ」を選択してください。">?</span></th>
			<td>
				<?php
					$textTextBtn_linkTarget_sp_blank = '';
					if( $this->{'bfb_textTextBtn_linkTarget_sp'.$optimize_step} == "blank" ){
						$textTextBtn_linkTarget_sp_blank = 'selected="selected"';
					}
				?>
				<select name="bfb_textTextBtn_linkTarget_sp">
					<option value="self">同じタブ</option>
					<option value="blank" <?php echo $textTextBtn_linkTarget_sp_blank; ?>>別のタブ</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>rel属性の付与(PRO版)<span class="bfb_popup_help" data-message='rel属性を付与します。リンクの開き方で「別タブ」を選択すると、rel="noopener"は自動付与されます。'>?</span></th>
			<td>
				<?php
					$textTextBtn_linkRel_sp_nofollow = '';
					if( $this->{'bfb_textTextBtn_linkRel_sp'.$optimize_step} == "nofollow" ){
						$textTextBtn_linkRel_sp_nofollow = 'selected="selected"';
					}
				?>
				<select name="bfb_textTextBtn_linkRel_sp">
					<option value="">付与しない</option>
					<option value="nofollow" <?php echo $textTextBtn_linkRel_sp_nofollow; ?>>nofollow</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>クリック測定時のメモ(PRO版)<span class="bfb_popup_help" data-message='クリック測定時に保存するメモを入力します。ボタン名やテスト名などをメモを入力すると、あとでメモフィルタをかけて検索することができます。必要なければ、空欄のままでOK。'>?</span></th>
			<td>
				<input name="bfb_textTextBtn_trackingMemo_sp" type="text" class="regular-text" value="<?php echo esc_attr($this->{'bfb_textTextBtn_trackingMemo_sp'.$optimize_step}); ?>" />
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
					$textTextBtn_bfbBgColor_sp = '';
					$textTextBtn_bfbBgColor_red_sp = '';
					$textTextBtn_bfbBgColor_yellow_sp = '';
					$textTextBtn_bfbBgColor_orange_sp = '';
					$textTextBtn_bfbBgColor_green_sp = '';
					$textTextBtn_bfbBgColor_blue_sp = '';
					switch( $this->{'bfb_textTextBtn_bfbBgColor_sp'.$optimize_step} ){
						case "black":
							$textTextBtn_bfbBgColor_sp = 'selected="selected"';
							break;
						case "red":
							$textTextBtn_bfbBgColor_red_sp = 'selected="selected"';
							break;
						case "yellow":
							$textTextBtn_bfbBgColor_yellow_sp = 'selected="selected"';
							break;
						case "orange":
							$textTextBtn_bfbBgColor_orange_sp = 'selected="selected"';
							break;
						case "green":
							$textTextBtn_bfbBgColor_green_sp = 'selected="selected"';
							break;
						case "blue":
							$textTextBtn_bfbBgColor_blue_sp = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_textTextBtn_bfbBgColor_sp">
					<option value="black" <?php echo $textTextBtn_bfbBgColor_sp; ?>>黒</option>
					<option value="red" <?php echo $textTextBtn_bfbBgColor_red_sp; ?>>赤</option>
					<option value="yellow" <?php echo $textTextBtn_bfbBgColor_yellow_sp; ?>>黄</option>
					<option value="orange" <?php echo $textTextBtn_bfbBgColor_orange_sp; ?>>オレンジ</option>
					<option value="green" <?php echo $textTextBtn_bfbBgColor_green_sp; ?>>緑</option>
					<option value="blue" <?php echo $textTextBtn_bfbBgColor_blue_sp; ?>>青</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>背景色(PRO版)<span class="bfb_popup_help" data-message="カラーピッカーで自由に色を選択できます。">?</span></th>
			<td>
			<?php
				$this->genelate_color_picker(
					'bfb_textTextBtn_bfbBgColorPicker_sp',
					$this->{'bfb_textTextBtn_bfbBgColorPicker_sp'.$optimize_step}
				);
		  	?>
			</td>
		</tr>
		</tbody>
	</table>

	<?php endif; ?>

</div>