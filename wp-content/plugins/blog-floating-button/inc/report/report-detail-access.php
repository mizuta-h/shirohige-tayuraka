<?php
	$data = array();
	$data = array( 'start_date' => $start_date, 'end_date' => $end_date, 'post_url' => $post_url, 'device' => $device );
	$condi = array( 'limit' => intval($this->bfb_get_data('limit','get')), 'paged' => intval($this->bfb_get_data('paged','get')) );
	$datas = $this->report->get_tracking_data('access',$data,$condi);

	$pagination = $this->report->pagination($datas,$condi); //ページネーション処理

?>

<h2>アクセス詳細レポート</h2>

<?php
if( isset($pagination['html']) ){
	echo $pagination['html'];
}
?>

<div class="bfb_pagination_count_text"><?php echo $pagination['count_text']; ?></div>

<table class="table th_yellow scroll">
<tr><th class="w50">id</th><th>記事URL</th><th class="w100">IPアドレス</th><th>リファラ</th><th>ユーザーエージェント</th><th class="w50">デバイス</th><th class="w100">日時</th></tr>

<?php 
foreach( $datas as $data ){
	if( !isset($data->id) ){ continue; }
	echo "<tr><td>$data->id</td><td><a href=\"$data->post_url\" target=\"_blank\">$data->post_url</a></td><td>$data->ip</td><td><a href=\"$data->referer\" target=\"_blank\">$data->referer</a></td><td>$data->ua</td><td>$data->device</td><td>$data->date</td></tr>";
}
?>

</table>

<?php
	
if( isset($pagination['html']) ){
	echo $pagination['html'];
}

?>