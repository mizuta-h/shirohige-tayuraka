<h2>共通の設定</h2>

<div class="bfb_box">

	<table class="form-table">
		<tbody>
		<tr>
			<th>開発モード<span class="bfb_popup_help" data-message="「管理者のみを表示」を選択すると、フロートボタンは管理者としてログインしているユーザーにしか表示されません(初期設定時などに使用)。一般ユーザーにも表示したい場合「全体公開」を選択してください。">?</span></th>
			<td>
				<?php
					$bfb_mode_open = '';
					if( $this->bfb_mode == "open" ){
						$bfb_mode_open = 'selected="selected"';
					}
				?>
				<select name="bfb_mode">
					<option value="only_admin">管理者のみ表示</option>
					<option value="open" <?php echo esc_attr($bfb_mode_open); ?>>全体公開</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>PCのボタンデザイン<span class="bfb_popup_help" data-message="PCでアクセスした時に表示されるボタンデザインです。">?</span></th>
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
			<th>スマホのボタンデザイン<span class="bfb_popup_help" data-message="スマホでアクセスした時に表示されるボタンデザインです。">?</span></th>
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
		<tr>
			<th>トップページの表示<span class="bfb_popup_help" data-message="トップページの表示方法を選択できます。">?</span></th>
			<td>
				<?php
					$bfb_exclude_toppage_hide = '';
					$bfb_exclude_toppage_show_top_only = '';
					if( $this->bfb_exclude_toppage == "hide" ){
						$bfb_exclude_toppage_hide = 'selected="selected"';
					}elseif( $this->bfb_exclude_toppage == "show_top_only" ){
						$bfb_exclude_toppage_show_top_only = 'selected="selected"';
					}
				?>
				<select name="bfb_exclude_toppage">
					<option value="show">表示</option>
					<option value="show_top_only" <?php echo $bfb_exclude_toppage_show_top_only; ?>>トップページのみ表示</option>
					<option value="hide" <?php echo $bfb_exclude_toppage_hide; ?>>トップページのみ非表示</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>除外記事ID<span class="bfb_popup_help" data-message="設定した記事IDの記事ではフロートボタンが非表示になります。お問い合わせページなど、フロートボタンを表示したくない記事IDを設定してください。投稿、固定ページどちらもID指定で除外できます。">?</span></th>
			<td>
				<input name="bfb_exclude_post_ids" type="text" class="regular-text" value="<?php echo esc_attr($this->bfb_exclude_post_ids,'hw'); ?>" />
				<small class="bfb_small">複数指定する場合はカンマ区切りで入力 例)1821,828,1834</small>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>非表示ページ(PRO版)<span class="bfb_popup_help" data-message="チェックしたページはフロートボタンが非表示になります。ただし記事毎の個別設定をしている場合は個別設定が優先されます。">?</span></th>
			<?php
				$bfb_hidden_pages_category = '';
				$bfb_hidden_pages_tag = '';
				$bfb_hidden_pages_search = '';
				$bfb_hidden_pages_author = '';
				$bfb_hidden_pages_404 = '';
				if( isset($this->bfb_hidden_pages) && is_array($this->bfb_hidden_pages) ){
					$bfb_hidden_pages_category = in_array('category',$this->bfb_hidden_pages,true) ? 'checked': '' ;
					$bfb_hidden_pages_tag = in_array('tag',$this->bfb_hidden_pages,true) ? 'checked': '' ;
					$bfb_hidden_pages_search = in_array('search',$this->bfb_hidden_pages,true) ? 'checked': '' ;
					$bfb_hidden_pages_author = in_array('author',$this->bfb_hidden_pages,true) ? 'checked': '' ;
					$bfb_hidden_pages_404 = in_array('404',$this->bfb_hidden_pages,true) ? 'checked': '' ;
				}

			?>
			<td>
				<label for="category"><input type="checkbox" id="category" name="bfb_hidden_pages[]" value="category" <?php echo $bfb_hidden_pages_category; ?>>カテゴリーページ</label>
				<label for="tag"><input type="checkbox" id="tag" name="bfb_hidden_pages[]" value="tag" <?php echo $bfb_hidden_pages_tag; ?>>タグページ</label>
				<label for="search"><input type="checkbox" id="search" name="bfb_hidden_pages[]" value="search" <?php echo $bfb_hidden_pages_search; ?>>検索結果ページ</label>
				<label for="author"><input type="checkbox" id="author" name="bfb_hidden_pages[]" value="author" <?php echo $bfb_hidden_pages_author; ?>>投稿者アーカイブページ</label>
				<label for="404"><input type="checkbox" id="404" name="bfb_hidden_pages[]" value="404" <?php echo $bfb_hidden_pages_404; ?>>404ページ</label>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>自動非表示機能<span class="bfb_popup_help" data-message="ページをスクロールした時にフロートボタンを自動的に非表示にすることができます。「常に表示」を選択すると自動非表示はオフになります。">?</span></th>
			<td>
				<?php
					$bfb_autohide_off = '';
					if( $this->bfb_autohide == "off" ){
						$bfb_autohide_off = 'selected="selected"';
					}
				?>
				<select name="bfb_autohide">
					<option value="on">下スクロール時は非表示</option>
					<option value="off" <?php echo $bfb_autohide_off; ?>>常に表示</option>
				</select>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>非表示の期間(PRO版)<span class="bfb_popup_help" data-message="BFBの閉じるボタンをユーザーが押した場合にBFBを非表示にする期間を変更できます。初期値は7日間です。1以上の整数を半角で入力してください。">?</span></th>
			<td>
				<?php
					if( !$this->bfb_cookie_hide_span ){
						$bfb_cookie_hide_span = 7;
					}else{
						$bfb_cookie_hide_span = $this->bfb_cookie_hide_span;
					}
				?>
				<input name="bfb_cookie_hide_span" type="number" min="0" max="100" class="regular-text bfb_shortInput" value="<?php echo esc_attr($bfb_cookie_hide_span,'int'); ?>" />日間
				<small class="bfb_small">初期値は7日間</small>
			</td>
		</tr>
		<tr>
			<th>PCの出現ポイントの領域(PRO版)<span class="bfb_popup_help" data-message="[bfb_show]で出現したフロートボタンは、スクロールしても出現ポイントの領域で設定した領域は表示されます。1以上の半角数字で入力してください。">?</span></th>
			<td>
				<?php
					if( !$this->bfb_showing_area_pc ){
						$bfb_showing_area_pc = 300;
					}else{
						$bfb_showing_area_pc = $this->bfb_showing_area_pc;
					}
				?>
				<input name="bfb_showing_area_pc" type="number" min="1" max="99999999" class="regular-text bfb_shortInput" value="<?php echo esc_attr($bfb_showing_area_pc,'int'); ?>" />px
				<small class="bfb_small">初期値は300、[bfb_show]で表示、[bfb_hide]で非表示</small>
			</td>
		</tr>
		<tr>
			<th>スマホの出現ポイントの領域(PRO版)<span class="bfb_popup_help" data-message="[bfb_show]で出現したフロートボタンは、スクロールしても出現ポイントの領域で設定した領域は表示されます。1以上の半角数字で入力してください。">?</span></th>
			<td>
				<?php
					if( !$this->bfb_showing_area_sp ){
						$bfb_showing_area_sp = 300;
					}else{
						$bfb_showing_area_sp = $this->bfb_showing_area_sp;
					}
				?>
				<input name="bfb_showing_area_sp" type="number" min="1" max="99999999" class="regular-text bfb_shortInput" value="<?php echo esc_attr($bfb_showing_area_sp,'int'); ?>" />px
				<small class="bfb_small">初期値は300、[bfb_show]で表示、[bfb_hide]で非表示</small>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>クリック計測の有効化<span class="bfb_popup_help" data-message="クリック計測を有効にするかどうかを選択できます。">?</span></th>
			<td>
				<?php
					$bfb_clickAnalyze_off = '';
					if( $this->bfb_clickAnalyze == "off" ){
						$bfb_clickAnalyze_off = 'selected="selected"';
					}
				?>
				<select name="bfb_clickAnalyze">
					<option value="on">計測する</option>
					<option value="off" <?php echo $bfb_clickAnalyze_off; ?>>計測しない</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>クリック計測で管理者を除外<span class="bfb_popup_help" data-message="管理者としてログイン中のユーザーでクリック計測を有効にするかどうかを選択できます。正確にデータを取得するために、テスト時以外は除外することを推奨します。">?</span></th>
			<td>
				<?php
					$bfb_clickAnalyze_exclude_admin_off = '';
					if( $this->bfb_clickAnalyze_exclude_admin == "off" ){
						$bfb_clickAnalyze_exclude_admin_off = 'selected="selected"';
					}
				?>
				<select name="bfb_clickAnalyze_exclude_admin">
					<option value="on">管理者を除外する</option>
					<option value="off" <?php echo $bfb_clickAnalyze_exclude_admin_off; ?>>管理者を除外しない</option>
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
		<tr>
			<th>解析レポートの保存期間<span class="bfb_popup_help" data-message="解析レポートの保存期間です。期間を過ぎたデータから削除し容量を節約します。">?</span></th>
			<?php
				$bfb_retention_period_0 = '';
				$bfb_retention_period_30 = '';
				$bfb_retention_period_90 = '';
				$bfb_retention_period_180 = '';
				$bfb_retention_period_360 = '';
				switch( $this->bfb_retention_period ){
					case "period_0":
						$bfb_retention_period_0 = 'selected="selected"';
						break;
					case "period_30":
						$bfb_retention_period_30 = 'selected="selected"';
						break;
					case "period_90":
						$bfb_retention_period_90 = 'selected="selected"';
						break;
					case "period_180":
						$bfb_retention_period_180 = 'selected="selected"';
						break;
					case "period_360":
						$bfb_retention_period_360 = 'selected="selected"';
						break;
					default:
						$bfb_retention_period_0 = 'selected="selected"';
				}
			?>
			<td>
				<select name="bfb_retention_period">
					<option value="period_0" <?php echo $bfb_retention_period_0; ?>>ずっと</option>
					<option value="period_30" <?php echo $bfb_retention_period_30; ?>>30日</option>
					<option value="period_90" <?php echo $bfb_retention_period_90; ?>>90日</option>
					<option value="period_180" <?php echo $bfb_retention_period_180; ?>>180日</option>
					<option value="period_360" <?php echo $bfb_retention_period_360; ?>>360日</option>
				</select>
			</td>
		</tr>
		</tbody>
	</table>

	<hr>

	<table class="form-table">
		<tbody>
		<tr>
			<th>PRO版ライセンスキー<span class="bfb_popup_help" data-message="有料のPRO版ライセンスキーを入力すると、機能制限を解除できます。">?</span></th>
			<td>
					<input name="bfb_license_key" type="text" class="regular-text" value="<?php echo esc_attr($this->bfb_license_key); ?>" />
					<p>
					<?php
					 if( $this->is_activation ){
					 	echo '<div class="bfb_check bfb_true">ライセンスキーが有効です。</div>';
					 }else{
					 	echo '<div class="bfb_check bfb_false">ライセンスキーが無効です。<a href="https://bfb-plugin.com/pro" target="_blank">» PRO版はコチラ</a></div>';
					 }
					?>
					</p>
			</td>
		</tr>
		<?php if( $this->is_activation ): ?>
		<tr>
			<th>A/Bテスト専用ライセンスキー<span class="bfb_popup_help" data-message="有料のA/Bテスト専用ライセンスキーを入力すると、A/Bテスト機能が使えます。">?</span></th>
			<td>
					<input name="bfb_license_key_optimize" type="text" class="regular-text" value="<?php echo esc_attr($this->bfb_license_key_optimize); ?>" />
					<p>
					<?php
					 if( $this->is_activation_optimize ){
					 	echo '<div class="bfb_check bfb_true">ライセンスキーが有効です。</div>';
					 }else{
					 	echo '<div class="bfb_check bfb_false">ライセンスキーが無効です。<a href="https://bfb-plugin.com/abtest" target="_blank">» A/Bテスト機能はコチラ</a></div>';
					 }
					?>
					</p>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>

</div><!--bfb_box-->