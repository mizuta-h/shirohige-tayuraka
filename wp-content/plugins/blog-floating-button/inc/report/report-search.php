<form action="" method="get" class="search_box">
	<table>
		<tr>
			<th>レポート種類</th>
			<td>
				<label for="daily_report"><input type="radio" id="daily_report" name="report_type" class="" value="daily_report" <?= $report_type=='daily_report' ? 'checked' : '' ?>>日別</label><label for="monthly_report"><input type="radio" id="monthly_report" name="report_type" class="" value="monthly_report" <?= $report_type=='monthly_report' ? 'checked' : '' ?>>月別</label><label for="access_detail_report"><input type="radio" id="access_detail_report" name="report_type" class="" value="access_detail_report" <?= $report_type=='access_detail_report' ? 'checked' : '' ?>>アクセス詳細</label><label for="click_detail_report"><input type="radio" id="click_detail_report" name="report_type" class="" value="click_detail_report" <?= $report_type=='click_detail_report' ? 'checked' : '' ?>>クリック詳細</label>
			</td>
		</tr>
		<tr>
			<th>期間指定</th>
			<td>
				<input type="text" name="start_date" class="date-input" value="<?php echo esc_attr($start_date); ?>"> 〜 <input type="text" name="end_date" class="date-input" value="<?php echo esc_attr($end_date); ?>">
			</td>
		</tr>
		<tr class="post_url">
			<th>記事URL</th>
			<td>
				<input type="text" class="longItem" name="post_url" value="<?php echo esc_attr($post_url); ?>">
			</td>
		</tr>
		<tr>
			<th>デバイス</th>
			<td>
				<label for="all"><input type="radio" id="all" name="device" class="" value="" <?= $device=='' ? 'checked' : '' ?>>全て</label>
				<label for="PC"><input type="radio" id="PC" name="device" class="" value="PC" <?= $device=='PC' ? 'checked' : '' ?>>PC</label><label for="SP"><input type="radio" id="SP" name="device" class="" value="SP" <?= $device=='SP' ? 'checked' : '' ?>>スマホ</label><label for="Tab"><input type="radio" id="Tab" name="device" class="" value="Tab" <?= $device=='Tab' ? 'checked' : '' ?>>タブレット</label><label for="Mobile"><input type="radio" id="Mobile" name="device" class="" value="Mobile" <?= $report_type=='Mobile' ? 'checked' : '' ?>>モバイル</label>
			</td>
		</tr>
		<tr class="bfb_memo" style="display: none;">
			<th>メモ</th>
			<td>
				<input type="text" class="longItem" name="memo" value="<?php echo esc_attr($memo); ?>">
			</td>
		</tr>
		<tr class="bfb_limit" style="display: none;">
			<th>表示件数<span class="bfb_popup_help" data-message="表示件数を変更することができます。">?</span></th>
			<td>
				<select name="limit">
					<option value="50" <?php echo esc_attr($this->bfb_get_data('limit','get')=="50"?'selected':''); ?>>50件</option>
					<option value="100" <?php echo esc_attr($this->bfb_get_data('limit','get')=="100"?'selected':''); ?>>100件</option>
					<option value="200" <?php echo esc_attr($this->bfb_get_data('limit','get')=="200"?'selected':''); ?>>200件</option>
					<option value="500" <?php echo esc_attr($this->bfb_get_data('limit','get')=="500"?'selected':''); ?>>500件</option>
					<option value="1000" <?php echo esc_attr($this->bfb_get_data('limit','get')=="1000"?'selected':''); ?>>1000件</option>
				</select>
			</td>
		</tr>
		<tr><td colspan="1"><input type="submit" value="レポートを表示する"  class="button button-primary"></td></tr>
	</table>
	<input type="hidden" name="page" value="blog-floating-button-report">
</form>

<script type="text/javascript">
jQuery(document).ready(function ($) {

	var bfb_report_type_obj = $('[name=report_type]');
	var bfb_report_type = $("[name=report_type]:checked").val();

	if( bfb_report_type == 'click_detail_report' ){
		$("tr.bfb_memo").show();
	}

	if( bfb_report_type == '' || bfb_report_type == 'daily_report' || bfb_report_type == 'monthly_report' ){
		bfb_default_report();
	}else if( bfb_report_type == 'access_detail_report' ){
		bfb_access_report();
	}else if( bfb_report_type == 'click_detail_report' ){
		bfb_click_report();
	}

	bfb_report_type_obj.click(function() {
	    if( $(this).val() == 'click_detail_report' ){
	    	bfb_click_report(); 	
	    }else if( $(this).val() == 'access_detail_report' ){
	    	bfb_access_report();
	    }else{
			bfb_default_report();
	    }
	});

	//日別、月別
	function bfb_default_report(){
		$("tr.bfb_memo").hide();
		$("tr.bfb_limit").hide();
		$("[name=memo]").val("");
	}
	function bfb_access_report(){
    	$("tr.bfb_memo").hide();
    	$("[name=memo]").val("");
    	$("tr.bfb_limit").show();
	}
	function bfb_click_report(){
    	$("tr.bfb_memo").show();
    	$("tr.bfb_limit").show();
	}
});
</script>

<?php $this->date_picker_script(); ?>