<?php

	$todays_data = $this->report->get_target_data();
	$todays_data['date'] = date_i18n('Y-m-d');
	$today['access'] = $todays_data['access'][$todays_data['date']] ?? '0';
	$today['click'] = $todays_data['click'][$todays_data['date']] ?? '0';
	$today['click_rate'] = $this->report->calc_click_rate($today['access'],$today['click']);

	$one_day_ago_data = $this->report->get_target_data('-1 day');
	$one_day_ago_data['date'] = $this->report->get_target_date(date_i18n('Y-m-d'),'-1 day');
	$one_day_ago['access'] = $one_day_ago_data['access'][$one_day_ago_data['date']] ?? '0';
	$one_day_ago['click'] = $one_day_ago_data['click'][$one_day_ago_data['date']] ?? '0';
	$one_day_ago['click_rate'] = $this->report->calc_click_rate($one_day_ago['access'],$one_day_ago['click']);

	$two_days_ago_data = $this->report->get_target_data('-2 day');
	$two_days_ago_data['date'] = $this->report->get_target_date(date_i18n('Y-m-d'),'-2 day');
	$two_days_ago['access'] = $two_days_ago_data['access'][$two_days_ago_data['date']] ?? '0';
	$two_days_ago['click'] = $two_days_ago_data['click'][$two_days_ago_data['date']] ?? '0';
	$two_days_ago['click_rate'] = $this->report->calc_click_rate($two_days_ago['access'],$two_days_ago['click']);

	$search_span_data = $this->report->get_monthly_data();
	$search_span_data['date'] = date_i18n('Y-m');
	$search_span['access'] = $search_span_data['access'][$search_span_data['date']] ?? '0';
	$search_span['click'] = $search_span_data['click'][$search_span_data['date']] ?? '0';
	$search_span['click_rate'] = $this->report->calc_click_rate($search_span['access'],$search_span['click']);

	$last_month_data = $this->report->get_monthly_data('-1 month');
	$last_month_data['date'] = $this->report->get_target_date(date_i18n('Y-m'),'-1 month');
	$last_month['access'] = $last_month_data['access'][$last_month_data['date']] ?? '0';
	$last_month['click'] = $last_month_data['click'][$last_month_data['date']] ?? '0';
	$last_month['click_rate'] = $this->report->calc_click_rate($last_month['access'],$last_month['click']);

?>

<h2>ダッシュボード</h2>

<table class="table th_yellow scroll">
	<tr><th class="short_item"></th><th>本日</th><th>昨日</th><th>一昨日</th><th>今月</th><th>前月</th></tr>
	<tr><td>ユーザー数</td><td><?php echo $today['access']; ?></td><td><?php echo $one_day_ago['access']; ?></td><td><?php echo $two_days_ago['access']; ?></td><td><?php echo $search_span['access']; ?></td><td><?php echo $last_month['access']; ?></td></tr>
	<tr><td>クリック数</td><td><?php echo $today['click']; ?></td><td><?php echo $one_day_ago['click']; ?></td><td><?php echo $two_days_ago['click']; ?></td><td><?php echo $search_span['click']; ?></td><td><?php echo $last_month['click']; ?></td></tr>
	<tr><td>クリック率</td><td><?php echo $today['click_rate']; ?>%</td><td><?php echo $one_day_ago['click_rate']; ?>%</td><td><?php echo $two_days_ago['click_rate']; ?>%</td><td><?php echo $search_span['click_rate']; ?>%</td><td><?php echo $last_month['click_rate']; ?>%</td></tr>
</table>