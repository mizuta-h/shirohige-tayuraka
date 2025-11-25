<div id="bfb_wrap" class="optimize">

	<h1 class="bfb_h1">A/Bテスト</h1>

	<form action="?page=blog-floating-button-optimize" method="post">
		<?php wp_nonce_field( 'bfb_optimize_setting', '_wpnonce_bfb' ); ?>
		<input type="submit" value="新しいテストを開始"  class="button button-primary">
		<input type="hidden" name="page" value="blog-floating-button-optimize">
		<input type="hidden" name="optimize_step" value="opt_list">
	</form>

	<?php include_once( dirname(__FILE__) . '/optimize-table.php' ); ?>

</div><!--bfb_wrap-->