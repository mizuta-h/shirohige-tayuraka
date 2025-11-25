<table class="form-table">
	<tbody>
<?php if( $pagenow == 'post.php' ): ?>
		<tr>
			<th>個別設定の使用<span class="bfb_popup_help" data-message="個別設定の使用方法を選択できます。">?</span><br><span style="font-size:0.8em;">プレビューは個別設定のものです。</span></th>
			<td>
				<?php
					$bfb_use_post_select_use = '';
					$bfb_use_post_select_none = '';
					$bfb_use_post_select_not_use = '';
					switch( $this->bfb_use_post ){
						case "true":
							$bfb_use_post_select_use = 'selected="selected"';
							break;
						case "none":
							$bfb_use_post_select_none = 'selected="selected"';
							break;
						default:
							$bfb_use_post_select_not_use = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_use_post">
					<option value="false" <?php echo $bfb_use_post_select_not_use; ?>>個別設定を使用しない</option>
					<option value="true" <?php echo $bfb_use_post_select_use; ?>>個別設定を優先</option>
					<option value="none" <?php echo $bfb_use_post_select_none; ?>>このページでは非表示</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation_optimize ): ?>
		<tr>
			<th>A/Bテスト(PC)<span class="bfb_popup_help" data-message="PC表示でA/Bテストを実施するテストIDを選択してください。">?</span></th>
			<td>
				<?php
					$optDatas = $this->opt->get_activeOptimizeId();
					$opt_html = '';
					if( !empty($optDatas) ){
						foreach( $optDatas as $optId => $optData ){
							if( $optData['device'] == 'sp' ) continue;
							$bfb_optId_checked = ($this->bfb_optId_pc==$optId)?'selected="selected"':'';
							$bfb_opt_status = '';							
							switch($optData['status']){
								case 1:
									$bfb_opt_status = '【稼働中】';
									break;
								case 2:
									$bfb_opt_status = '【完了】';
									break;
								default:
									$bfb_opt_status = '【停止】';
									break;
							}
							$opt_html .= '<option value="'.$optId.'" '.$bfb_optId_checked.'>'.$bfb_opt_status.$optData['optimize_name'].'('.$optId.')</option>';
						}
					}
				?>
				<select name="bfb_optId_pc">
					<option value="">実施しない</option>
					<?php echo $opt_html; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>A/Bテスト(スマホ)<span class="bfb_popup_help" data-message="PC表示でA/Bテストを実施するテストIDを選択してください。">?</span></th>
			<td>
				<?php
					$optDatas = $this->opt->get_activeOptimizeId();
					$opt_html = '';
					if( !empty($optDatas) ){
						foreach( $optDatas as $optId => $optData ){
							if( $optData['device'] == 'pc' ) continue;
							$bfb_optId_checked = ($this->bfb_optId_sp==$optId)?'selected="selected"':'';
							$bfb_opt_status = '';
							switch($optData['status']){
								case 1:
									$bfb_opt_status = '【稼働中】';
									break;
								case 2:
									$bfb_opt_status = '【完了】';
									break;
								default:
									$bfb_opt_status = '【停止】';
									break;
							}
							$opt_html .= '<option value="'.$optId.'" '.$bfb_optId_checked.'>'.$bfb_opt_status.$optData['optimize_name'].'('.$optId.')</option>';
						}
					}
				?>
				<select name="bfb_optId_sp">
					<option value="">実施しない</option>
					<?php echo $opt_html; ?>
				</select>
			</td>
		</tr>
		<?php endif; ?>
<?php endif; ?>
<?php if( $pagenow == 'term.php' ): ?>
		<tr>
			<th>個別設定の使用<span class="bfb_popup_help" data-message="個別設定の使用方法を選択できます。">?</span><br><span style="font-size:0.8em;">プレビューは個別設定のものです。</span></th>
			<td>
			<td>
				<?php
					$bfb_use_category_select_use = '';
					$bfb_use_category_select_none = '';
					$bfb_use_category_select_not_use = '';
					switch( $this->bfb_use_category ){
						case "true":
							$bfb_use_category_select_use = 'selected="selected"';
							break;
						case "none":
							$bfb_use_category_select_none = 'selected="selected"';
							break;
						default:
							$bfb_use_category_select_not_use = 'selected="selected"';
							break;
					}
				?>
				<select name="bfb_use_category">
					<option value="false" <?php echo $bfb_use_category_select_not_use; ?>>個別設定を使用しない</option>
					<option value="true" <?php echo $bfb_use_category_select_use; ?>>個別設定を優先</option>
					<option value="none" <?php echo $bfb_use_category_select_none; ?>>このカテゴリーでは非表示</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>カテゴリー同士の優先度<span class="bfb_popup_help" data-message="1つ記事で複数のカテゴリーを選択している場合、優先度が高いカテゴリーのBFB設定が優先されます。数字が大きいほど、優先度が高くなります。例えば、優先度10と11のカテゴリーでは優先度11のカテゴリーの設定が優先されます。">?</span></th>
			<td>
				<input name="bfb_categoryPriority" type="number" min="0" max="99999" class="regular-text bfb_shortInput" value="<?php echo esc_attr( $this->bfb_categoryPriority ); ?>" />
				<small class="bfb_small">数値が大きいほど設定が優先されます。</small>
			</td>
		</tr>
		<?php if( $this->is_activation_optimize ): ?>
		<tr>
			<th>A/Bテスト(PC)<span class="bfb_popup_help" data-message="PC表示でA/Bテストを実施するテストIDを選択してください。">?</span></th>
			<td>
				<?php
					$optDatas = $this->opt->get_activeOptimizeId();
					$opt_html = '';
					if( !empty($optDatas) ){
						foreach( $optDatas as $optId => $optData ){
							if( $optData['device'] == 'sp' ) continue;
							$bfb_optId_checked = ($this->bfb_optId_pc==$optId)?'selected="selected"':'';
							$bfb_opt_status = '';							
							switch($optData['status']){
								case 1:
									$bfb_opt_status = '【稼働中】';
									break;
								case 2:
									$bfb_opt_status = '【完了】';
									break;
								default:
									$bfb_opt_status = '【停止】';
									break;
							}
							$opt_html .= '<option value="'.$optId.'" '.$bfb_optId_checked.'>'.$bfb_opt_status.$optData['optimize_name'].'('.$optId.')</option>';
						}
					}
				?>
				<select name="bfb_optId_pc">
					<option value="">実施しない</option>
					<?php echo $opt_html; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>A/Bテスト(スマホ)<span class="bfb_popup_help" data-message="PC表示でA/Bテストを実施するテストIDを選択してください。">?</span></th>
			<td>
				<?php
					$optDatas = $this->opt->get_activeOptimizeId();
					$opt_html = '';
					if( !empty($optDatas) ){
						foreach( $optDatas as $optId => $optData ){
							if( $optData['device'] == 'pc' ) continue;
							$bfb_optId_checked = ($this->bfb_optId_sp==$optId)?'selected="selected"':'';
							$bfb_opt_status = '';
							switch($optData['status']){
								case 1:
									$bfb_opt_status = '【稼働中】';
									break;
								case 2:
									$bfb_opt_status = '【完了】';
									break;
								default:
									$bfb_opt_status = '【停止】';
									break;
							}
							$opt_html .= '<option value="'.$optId.'" '.$bfb_optId_checked.'>'.$bfb_opt_status.$optData['optimize_name'].'('.$optId.')</option>';
						}
					}
				?>
				<select name="bfb_optId_sp">
					<option value="">実施しない</option>
					<?php echo $opt_html; ?>
				</select>
			</td>
		</tr>
		<?php endif; ?>
<?php endif; ?>
		<tr>
		<th>PCボタン<span class="bfb_popup_help" data-message="PCでアクセスした時に表示されるボタンデザインです。">?</span></th>
		<td>
			<?php
				$bfb_designType_pc_textBtn = '';
				$bfb_designType_pc_textTextBtn = '';
				$bfb_designType_pc_textBtnTextBtn = '';
				$bfb_designType_pc_imgBanner = '';
				$bfb_designType_pc_none = '';
				switch( $this->bfb_designType_pc ){
					case "textBtn":
						$bfb_designType_pc_textBtn = 'selected="selected"';
						break;
					case "textTextBtn":
						$bfb_designType_pc_textTextBtn = 'selected="selected"';
						break;
					case "textBtnTextBtn":
						$bfb_designType_pc_textBtnTextBtn = 'selected="selected"';
						break;
					case "imgBanner":
						$bfb_designType_pc_imgBanner = 'selected="selected"';
						break;
					case "none":
						$bfb_designType_pc_none = 'selected="selected"';
						break;
				}
			?>
			<select name="bfb_designType_pc">
				<option value="textBtn" <?php echo $bfb_designType_pc_textBtn; ?>>ボタンのみ</option>
				<option value="textTextBtn" <?php echo $bfb_designType_pc_textTextBtn; ?>>説明文+ボタン</option>
				<option value="textBtnTextBtn" <?php echo $bfb_designType_pc_textBtnTextBtn; ?>>ボタン+ボタン</option>
				<option value="imgBanner" <?php echo $bfb_designType_pc_imgBanner; ?>>バナー画像</option>
				<option value="none" <?php echo $bfb_designType_pc_none; ?>>表示しない</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>スマホボタン<span class="bfb_popup_help" data-message="スマホでアクセスした時に表示されるボタンデザインです。">?</span></th>
		<td>
			<?php
				$bfb_designType_sp_textBtn = '';
				$bfb_designType_sp_textTextBtn = '';
				$bfb_designType_sp_textBtnTextBtn = '';
				$bfb_designType_sp_imgBanner = '';
				$bfb_designType_sp_none = '';
				switch( $this->bfb_designType_sp ){
					case "textBtn":
						$bfb_designType_sp_textBtn = 'selected="selected"';
						break;
					case "textTextBtn":
						$bfb_designType_sp_textTextBtn = 'selected="selected"';
						break;
					case "textBtnTextBtn":
						$bfb_designType_sp_textBtnTextBtn = 'selected="selected"';
						break;
					case "imgBanner":
						$bfb_designType_sp_imgBanner = 'selected="selected"';
						break;
					case "none":
						$bfb_designType_sp_none = 'selected="selected"';
						break;
				}
			?>
			<select name="bfb_designType_sp">
				<option value="textBtn" <?php echo $bfb_designType_sp_textBtn; ?>>ボタンのみ</option>
				<option value="textTextBtn" <?php echo $bfb_designType_sp_textTextBtn; ?>>説明文+ボタン</option>
				<option value="textBtnTextBtn" <?php echo $bfb_designType_sp_textBtnTextBtn; ?>>ボタン+ボタン</option>
				<option value="imgBanner" <?php echo $bfb_designType_sp_imgBanner; ?>>バナー画像</option>
				<option value="none" <?php echo $bfb_designType_sp_none; ?>>表示しない</option>
			</select>
		</td>
	</tr>
	</tbody>
</table>