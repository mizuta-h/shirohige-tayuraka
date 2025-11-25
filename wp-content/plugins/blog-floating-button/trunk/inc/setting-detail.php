<h2>BFB詳細設定(PRO版)</h2>

<div class="bfb_box">

	<h3>PCボタン</h3>

	<table class="form-table">
		<tbody>
		<tr>
			<th>文字サイズ<span class="bfb_popup_help" data-message="PCでアクセスした時に表示されるBFB全体の文字サイズです。">?</span></th>
			<td>
				<?php
					if( !$this->bfb_fontSize_pc ){
						$bfb_fontSize_pc = 14;
					}else{
						$bfb_fontSize_pc = $this->bfb_fontSize_pc;
					}
				?>
				<input name="bfb_fontSize_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($bfb_fontSize_pc); ?>" />px
				<small class="bfb_small">13〜15pxがオススメです。</small>
			</td>
		</tr>
		<tr>
			<th>BFB全体の上下余白<span class="bfb_popup_help" data-message="BFBの上下余白を調整できます。">?</span></th>
			<td>
				<?php
					if( !$this->innerBfb_PaddingTop_pc ){
						$innerBfb_PaddingTop_pc = $this->scssItems['innerBfb_PaddingTop_pc'];
					}
				?>
				<input name="innerBfb_PaddingTop_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->innerBfb_PaddingTop_pc); ?>" />px
				<small class="bfb_small">デフォルトは10pxです。</small>
			</td>
		</tr>
		<tr>
			<th>ボタンの上下余白<span class="bfb_popup_help" data-message="BFB内のボタンの上下余白を調整できます。">?</span></th>
			<td>
				<?php
					if( !$this->bfbBtnPaddingTopBottom_pc ){
						$bfbBtnPaddingTopBottom_pc = $this->scssItems['bfbBtnPaddingTopBottom_pc'];
					}
				?>
				<input name="bfbBtnPaddingTopBottom_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->bfbBtnPaddingTopBottom_pc); ?>" />px
				<small class="bfb_small">デフォルトは10pxです。</small>
			</td>
		</tr>
		<tr>
			<th>マイクロコピー下の余白<span class="bfb_popup_help" data-message="マイクロコピー下の余白を調整できます。">?</span></th>
			<td>
				<?php
					if( !$this->topText_bottom_pc ){
						$topText_bottom_pc = $this->scssItems['topText_bottom_pc'];
					}
				?>
				<input name="topText_bottom_pc" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->topText_bottom_pc); ?>" />px
				<small class="bfb_small">デフォルトは5pxです。</small>
			</td>
		</tr>
		</tbody>
	</table>

	<h3>スマホボタン</h3>

	<table class="form-table">
		<tbody>
		<tr>
			<th>文字サイズ<span class="bfb_popup_help" data-message="スマホでアクセスした時に表示されるBFB全体の文字サイズです。">?</span></th>
			<td>
				<?php
					if( !$this->bfb_fontSize_sp ){
						$bfb_fontSize_sp = 14;
					}else{
						$bfb_fontSize_sp = $this->bfb_fontSize_sp;
					}
				?>
				<input name="bfb_fontSize_sp" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($bfb_fontSize_sp); ?>" />px
				<small class="bfb_small">13〜15pxがオススメです。</small>
			</td>
		</tr>
		<tr>
			<th>BFB全体の上下余白<span class="bfb_popup_help" data-message="BFBの上下余白を調整できます。">?</span></th>
			<td>
				<?php
					if( !$this->innerBfb_PaddingTop_sp ){
						$innerBfb_PaddingTop_sp = $this->scssItems['innerBfb_PaddingTop_sp'];
					}
				?>
				<input name="innerBfb_PaddingTop_sp" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->innerBfb_PaddingTop_sp); ?>" />px
				<small class="bfb_small">デフォルトは10pxです。</small>
			</td>
		</tr>
		<tr>
			<th>ボタンの上下余白<span class="bfb_popup_help" data-message="BFB内のボタンの上下余白を調整できます。">?</span></th>
			<td>
				<?php
					if( !$this->bfbBtnPaddingTopBottom_sp ){
						$bfbBtnPaddingTopBottom_sp = $this->scssItems['bfbBtnPaddingTopBottom_sp'];
					}
				?>
				<input name="bfbBtnPaddingTopBottom_sp" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->bfbBtnPaddingTopBottom_sp); ?>" />px
				<small class="bfb_small">デフォルトは10pxです。</small>
			</td>
		</tr>
		<tr>
			<th>マイクロコピー下の余白<span class="bfb_popup_help" data-message="マイクロコピー下の余白を調整できます。">?</span></th>
			<td>
				<?php
					if( !$this->topText_bottom_sp ){
						$topText_bottom_sp = $this->scssItems['topText_bottom_sp'];
					}
				?>
				<input name="topText_bottom_sp" type="text" class="regular-text bfb_shortInput" value="<?php echo esc_attr($this->topText_bottom_sp); ?>" />px
				<small class="bfb_small">デフォルトは5pxです。</small>
			</td>
		</tr>
		</tbody>
	</table>

</div><!--bfb_box-->