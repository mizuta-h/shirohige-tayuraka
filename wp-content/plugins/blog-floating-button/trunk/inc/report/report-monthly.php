<h2>月別レポート</h2>

<?php
	$search_span_data = $this->report->get_monthly_data();
	$search_span = $this->report->get_first_last_date($start_date,$end_date,'monthly');
	$data = array( 'start_date' => $start_date, 'end_date' => $end_date, 'post_url' => $post_url, 'device' => $device );
	$access_datas = $this->report->get_report_data('access','monthly',$data);
	$click_datas = $this->report->get_report_data('click','monthly',$data);
	$graphData = $this->report->graphDate($search_span,$access_datas,$click_datas,'monthly');
?>

<?php include( dirname(__FILE__) . '/report-graph.php' ); ?>

<table class="table th_yellow">
<tr><th class="short_item">月</th><th>ユーザー数</th><th>クリック数</th><th>クリック率</th></tr>

<?php 

$access_num_sum = 0;
$click_num_sum = 0;

foreach( $search_span as $date ){
	$today = $date->format('Y-m');
	$access_num = $access_datas[$today] ?? 0;
	$click_num = $click_datas[$today] ?? 0;
	
	if( !empty($access_num) ){
		$click_rate = round(($click_num/$access_num)*100,1);
	}else{
		$click_rate = 0;
	}
	echo "<tr><td>$today</td><td>$access_num</td><td>$click_num</td><td>$click_rate%</td></tr>";
	$access_num_sum += $access_num;
	$click_num_sum += $click_num;
}
if( !empty($access_num_sum) ){
	$click_rate_sum = round(($click_num_sum/$access_num_sum)*100,1);
}else{
	$click_rate_sum = 0;
}
echo "<tr class='bold red'><td>合計</td><td>$access_num_sum</td><td>$click_num_sum</td><td>$click_rate_sum%</td></tr>";
?>

</table>