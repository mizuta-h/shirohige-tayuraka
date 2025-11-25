<?php

$get_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if( $get_page == "blog-floating-button-optimize" ){
	$is_optimize_page = true;
}else{
	$is_optimize_page = false;
}

$datas = $this->read_optimize();

if( is_array($datas) ){

	$html = '';
	foreach( $datas as $opt_id => $data ){

		if(isset($data['status'])){
			switch($data['status']){
				case 0:
					$status = '<span class="status_stop">停止</span>';
					break;
				case 1:
					$status = '<span class="status_start">稼働中</span>';
					break;
				case 2:
					$status = '完了';
					break;
				default:
					$status = '<span class="status_stop">停止</span>';
					break;
			}
			
		}

		$optimize_name = isset($data['optimize_name']) ? $data['optimize_name'] : '';
		$device = isset($data['device']) ? $data['device'] : '';
		$mainBtnDesign = isset($data['mainBtnDesign']) ? $data['mainBtnDesign'] : '';
		$subBtnDesign = isset($data['subBtnDesign']) ? $data['subBtnDesign'] : '';
		$distribution_rate = isset($data['distribution_rate']) ? $data['distribution_rate'] : '';
		$scheduled_finish_date = isset($data['scheduled_finish_date']) ? $data['scheduled_finish_date'] : '';
		$created_date = isset($data['created_date']) ? $data['created_date'] : '';
		$finished_date = isset($data['finished_date']) ? $data['finished_date'] : '';
		$memo = isset($data['memo']) ? $data['memo'] : '';

		$report_linkTarget = $is_optimize_page?'target="_blank"':'';

		$html .= '<tr>';
		$html .= '<td>'.$status.'</td>';
		$html .= '<td><a href="?page=blog-floating-button-optimize-report&optimize_id='.$opt_id.'" '.$report_linkTarget.'>'.$optimize_name.'</a></td>';
		$html .= '<td>'.$device.'</td>';
		$html .= '<td>'.$this->designNames[$mainBtnDesign].'</td>';
		$html .= '<td>'.$this->designNames[$subBtnDesign].'</td>';
		$html .= '<td>'.$distribution_rate.'%</td>';
		//$html .= '<td>'.$scheduled_finish_date.'</td>';
		$html .= '<td>'.$created_date.'</td>';
		$html .= '<td>'.$finished_date.'</td>';
		$html .= '<td>'.$memo.'</td>';

		if( $is_optimize_page ){

			$html .= '<td>';
			//0:停止、1:稼働中、2:完了
			if( $data['status'] == 0 && $data['status'] != 2 ){
				$html .= '<a href="?page=blog-floating-button-optimize&optimize_id='.$opt_id.'&action=opt_start" class="alert" action="opt_start"><b>開始</b></a> | ';
			}elseif( $data['status'] == 1 ){
				$html .= '<a href="?page=blog-floating-button-optimize&optimize_id='.$opt_id.'&action=opt_stop" class="alert" action="opt_stop">停止</a> | ';
			}
			if( $data['status'] == 0 || $data['status'] == 1 ){
				$html .= '<a href="?page=blog-floating-button-optimize&optimize_id='.$opt_id.'&action=opt_finish" class="alert" action="opt_finish">完了</a> | ';
			}
			if( $data['status'] == 0 && $data['status'] != 2 ){
				$html .= '<a href="?page=blog-floating-button-optimize&optimize_id='.$opt_id.'&optimize_step=opt_init&action=edit">編集</a> | ';
			}
			$html .= '<a href="?page=blog-floating-button-optimize&optimize_id='.$opt_id.'&action=copy">コピー</a> | ';
			if( $data['status'] == 0 ){
				$html .= '<a href="?page=blog-floating-button-optimize&optimize_id='.$opt_id.'&action=delete" class="alert" action="delete">削除</a>';
			}
			$html .= '</td>';
		}
		$html .= '</tr>';
	}

	?>

	<table class="table th_yellow" style="margin-top: 50px;">
		<thead>

			<tr>
				<th>ステータス</th><th>テスト名</th><th>対象デバイス</th><th>メインボタン</th><th>サブボタン</th><th>メインへの振り分け率</th><th>作成日時</th><th>終了日時</th><th>メモ</th>
				<?php if( $is_optimize_page ): ?>
					<th>アクション</th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php echo $html; ?>
		</tbody>
	</table>

<?php } ?>