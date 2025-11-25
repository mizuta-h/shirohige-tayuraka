<?php

$this->is_activation = $this->check_license_key();
$this->is_activation_optimize = $this->check_license_key_optimize();

if( $pagenow == 'post.php' && !$this->is_activation ){
	return false;
}

if( $pagenow == 'post.php' ){
	$bfb_wrap_style = "style='overflow:hidden;'";
}else{
	$bfb_wrap_style = '';
}
if( $pagenow == 'admin.php' ){
	$bfb_sticky = "bfb_sticky";
}

if( $this->is_activation ){ $is_pro = 'bfb_pro'; }

?>

<div id="bfb_wrap" <?php echo esc_attr($bfb_wrap_style); ?>>

<?php if( $pagenow == 'admin.php' ): ?>

	<?php if( $this->is_activation ): ?>
		<h1 class="bfb_h1">Blog Floating Button(PRO版)の設定</h1>
	<?php else: ?>
		<h1 class="bfb_h1">Blog Floating Buttonの設定</h1>
	<?php endif; ?>
<?php elseif( $pagenow == 'term.php' && $this->is_activation ): ?>
	<h2>Blog Floating Buttonの設定(PRO版)</h2>
<?php endif; ?>

<div id="bfb_main" <?php if( isset( $is_pro ) ) echo 'class="' . esc_attr($is_pro) . '"'; ?>>

<?php if( $this->bfb_mode != "open" && $pagenow == 'admin.php' ): ?>
	<div class="bfb_box2 bfb_yellow"><b class="bfb_fnt-red">現在は管理者のみに表示されています。一般ユーザーに公開する場合は「開発モード」で「全体公開」を選択してください。</b></div>
<?php endif; ?>

<?php if( $pagenow == 'admin.php' ): ?>
	<form id="bfb_form" method="POST" action="?<?php echo esc_attr($_SERVER['QUERY_STRING']); ?>">
<?php endif; ?>
<?php wp_nonce_field( 'bfb_setting', '_wpnonce_bfb' ); ?>
<div id="tabs">

  <ul>
<?php if( $pagenow == 'admin.php' ): ?>
    <li><a href="#tabs-common">共通の設定</a></li>
<?php endif; ?>   
<?php if( $pagenow == 'post.php' || $pagenow == 'term.php' ): ?>
    <li><a href="#tabs-single">個別設定</a></li>
<?php endif; ?>
	<li><a href="#tabs-pc">PC設定</a></li>
    <li><a href="#tabs-sp">スマホ設定</a></li>
<?php if( $this->is_activation && $pagenow == 'admin.php' ): ?>
    <li><a href="#tabs-detail">ボタン詳細設定(PRO版)</a></li>
<?php endif; ?>
<?php if( !$this->is_activation && false ): ?>
    <li><a href="#tabs-premium">PRO版の案内</a></li>
<?php endif; ?>
<?php if( $pagenow == 'admin.php' ): ?>
    <li><a href="#tabs-help">ヘルプ</a></li>
<?php endif; ?>
<?php if( $pagenow == 'admin.php' ): ?>
    <li><a href="#tabs-info">プラグイン情報</a></li>
<?php endif; ?>
  </ul>

<?php if( $pagenow == 'admin.php' ): ?>

  <div id="tabs-common">

	<?php include_once( dirname(__FILE__) . '/setting-common.php' ); ?>

  </div><!--tabs-common-->

<?php endif; ?>

<?php if( $pagenow == 'post.php' || $pagenow == 'term.php' ): ?>

  <div id="tabs-single">

	<?php include_once( dirname(__FILE__) . '/setting-single.php' ); ?>

  </div>

<?php endif; ?>

  <div id="tabs-pc">

	  	<div id="inner-tabs-pc">

			<?php include_once( dirname(__FILE__) . '/setting-pc.php' ); ?>
	  	
	  	</div><!--inner-tabs-pc-->

  </div><!--tabs-pc-->

  <div id="tabs-sp">

	  	<div id="inner-tabs-sp">

	  		<?php include_once( dirname(__FILE__) . '/setting-sp.php' ); ?>

	  	</div><!--inner-tabs-sp-->
	
  </div><!--tabs-sp-->

<?php if( $pagenow == 'admin.php' && $this->is_activation ): ?>

  <div id="tabs-detail">

	  	<div id="inner-tabs-detail">

	  		<?php include_once( dirname(__FILE__) . '/setting-detail.php' ); ?>

	  	</div><!--inner-tabs-detail-->
	
  </div><!--tabs-detail-->

<?php endif; ?>

<?php if( !$this->is_activation && false ): ?>

  <div id="tabs-premium">

	<?php include_once( dirname(__FILE__) . '/setting-premium.php' ); ?>

  </div><!--tabs-premium-->

<?php endif; ?>

<?php if( $pagenow == 'admin.php' ): ?>

  <div id="tabs-help">

	  	<div id="inner-tabs-help">

	  		<?php include_once( dirname(__FILE__) . '/setting-help.php' ); ?>

	  	</div><!--inner-tabs-help-->
	
  </div><!--tabs-help-->

<?php endif; ?>

<?php if( $pagenow == 'admin.php' ): ?>

<div id="tabs-info">

		<div id="inner-tabs-info">

			<?php include_once( dirname(__FILE__) . '/setting-info.php' ); ?>

		</div><!--inner-tabs-info-->
  
</div><!--tabs-info-->

<?php endif; ?>

</div><!--tabs-->

<?php if( $pagenow == 'admin.php' ): ?>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="設定を保存">
	</p>

<?php endif; ?>

<?php if( $pagenow == 'admin.php' ): ?>
	</form>
<?php endif; ?>

</div><!--.bfb_main-->

<?php if( $this->is_activation ):

	include_once( dirname(__FILE__) . '/live_preview.php' );

?>
	<div id="bfb_sub" class="bfb_pro">

		<div class="preview_wrap <?php if( isset( $bfb_sticky ) ) echo esc_attr($bfb_sticky); ?>">

			<div class="bfb_preview_pc">

				<?php if( $pagenow == 'admin.php' || $pagenow == 'term.php' || $pagenow == 'post.php' ): ?>
					<h2>PCのプレビュー</h2>
				<?php endif; ?>

				<div class="preview_area">
					<iframe srcdoc='<?php echo $this->insertFooter(); ?>'></iframe>
				</div>

			</div>

			<div class="bfb_preview_sp">

				<?php if( $pagenow == 'admin.php' || $pagenow == 'term.php' || $pagenow == 'post.php'): ?>
					<h2>スマホのプレビュー</h2>
				<?php endif; ?>

				<div class="preview_area">
					<iframe srcdoc='<?php echo $this->insertFooter('sp'); ?>'></iframe>
				</div>

			</div>

		</div>

	</div><!--.bfb_sub-->

<?php else: ?>

	<div id="bfb_sub" class="bfb_free">
		<?php echo $this->get_ad_html(); ?>
	</div><!--.bfb_sub-->

<?php endif; ?>

</div><!--wrap-->