<?php

$report_type = $this->bfb_get_data('report_type','get');
$start_date = $this->bfb_get_data('start_date','get');
$end_date = $this->bfb_get_data('end_date','get');
$memo = $this->bfb_get_data('memo','get');
$post_url = $this->bfb_get_data('post_url','get');
$referer = $this->bfb_get_data('referer','get');
$device = $this->bfb_get_data('device','get');

if( !$report_type ){
	$report_type = 'daily_report';
}
if( !$start_date ){ $start_date = date_i18n("Y-m-01"); }
if( !$end_date ){ $end_date = date_i18n("Y-m-d"); }

?>

<div id="bfb_wrap">

<h1 class="bfb_h1">Blog Floating Buttonの解析レポート</h1>

<?php 
if( !$report_type || $report_type == 'daily_report' ){
	include_once( dirname(__FILE__) . '/report-dashboard.php' );
}
?>

<?php include_once( dirname(__FILE__) . '/report-search.php' ); ?>

<?php if( $report_type == 'daily_report' ): ?>

	<?php include_once( dirname(__FILE__) . '/report-daily.php' ); ?>

<?php elseif( $report_type == 'monthly_report' ): ?>

	<?php include_once( dirname(__FILE__) . '/report-monthly.php' ); ?>

<?php elseif( $report_type == 'access_detail_report' ): ?>

	<?php include_once( dirname(__FILE__) . '/report-detail-access.php' ); ?>

<?php elseif( $report_type == 'click_detail_report' ): ?>

	<?php include_once( dirname(__FILE__) . '/report-detail-click.php' ); ?>

<?php endif; ?>

</div><!--bfb_wrap-->