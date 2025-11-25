<?php

class Tracking {

	private $access_log_table = 'bfb_access_log';
	private $click_log_table  = 'bfb_click_log';
	public $pagination_limit  = 50;
	public $wpdb;

	public function __construct() {

		global $wpdb;
		$this->wpdb = $wpdb;

		$this->access_log_table = $wpdb->prefix . $this->access_log_table;
		$this->click_log_table  = $wpdb->prefix . $this->click_log_table;
	}

	public function write_accessLog( $data ) {

		$res = $this->wpdb->insert( $this->access_log_table, $data, array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );
	}
	public function write_clickLog( $data ) {

		$data['ip']     = $_SERVER['REMOTE_ADDR'];
		$data['device'] = $this->ch_device( $data['ua'] );

		$res = $this->wpdb->insert( $this->click_log_table, $data, array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );
	}

	public function get_user_data( $postData ) {

		$ip = $_SERVER['REMOTE_ADDR'];
		$ua = $_SERVER['HTTP_USER_AGENT'];

		$data = array(
			'post_id'       => $postData['post_id'],
			'post_url'      => $postData['post_url'],
			'ip'            => $ip,
			'ua'            => $ua,
			'device'        => $this->ch_device( $ua ),
			'referer'       => $postData['referer'],
			'optimize_id'   => $postData['optimize_id'],
			'optimize_type' => $postData['optimize_type'],
		);

		return $data;
	}
	private function ch_device( $ua ) {

		if ( ( strpos( $ua, 'Android' ) !== false ) && ( strpos( $ua, 'Mobile' ) !== false ) || ( strpos( $ua, 'iPhone' ) !== false ) || ( strpos( $ua, 'Windows Phone' ) !== false ) ) {
			// スマホ
			$device = 'SP';
		} elseif ( ( strpos( $ua, 'Android' ) !== false ) || ( strpos( $ua, 'iPad' ) !== false ) ) {
			// タブレット
			$device = 'Tab';
		} elseif ( ( strpos( $ua, 'DoCoMo' ) !== false ) || ( strpos( $ua, 'KDDI' ) !== false ) || ( strpos( $ua, 'SoftBank' ) !== false ) || ( strpos( $ua, 'Vodafone' ) !== false ) || ( strpos( $ua, 'J-PHONE' ) !== false ) ) {
			// ガラケー
			$device = 'Mobile';
		} else {
			// パソコン
			$device = 'PC';
		}

		return $device;
	}

	public function get_first_last_date( $ym = null, $ym2 = null, $span = null ) {

		if ( ! $ym ) {
			$ym = date( 'Y-m' ); }
		if ( ! $ym2 ) {
			$ym2 = date( 'Y-m-d', strtotime( 'last day of ' . $ym ) ); }

		$start = new DateTime( $ym . '-01 00:00:00' );

		if ( $span == 'daily' ) {
			$end           = new DateTime( $ym2 . ' 23:59:59' );
			$date_interval = new DateInterval( 'P1D' ); // 1日間隔

		} elseif ( $span == 'monthly' ) {
			$end           = new DateTime( $ym2 . ' 23:59:59' );
			$date_interval = new DateInterval( 'P1M' ); // 1月間隔
		}

		$date_period = new DatePeriod( $start, $date_interval, $end );

		return $date_period;
	}
	public function get_tracking_data( $table, $data, $condi ) {

		if ( $table == 'access' ) {
			$table_name = $this->access_log_table;
		} elseif ( $table == 'click' ) {
			$table_name = $this->click_log_table;
		}

		if ( ! empty( $data['start_date'] ) && ! empty( $data['end_date'] ) ) {
			$search_date = " WHERE date BETWEEN '" . $data['start_date'] . " 00:00:00' AND '" . $data['end_date'] . " 23:59:59'";
		} else {
			$search_date = " WHERE date BETWEEN '2000-01-01 00:00:00' AND '" . date_i18n( 'Y-m-d' ) . " 23:59:59'";
		}

		if ( ! empty( $data['memo'] ) ) {
			$sql_memo = " AND memo LIKE '%" . $data['memo'] . "%'";
		} else {
			$sql_memo = '';
		}
		if ( ! empty( $data['post_url'] ) ) {
			$sql_post_url = " AND post_url LIKE '%" . $data['post_url'] . "%'";
		} else {
			$sql_post_url = '';
		}
		if ( ! empty( $data['device'] ) ) {
			$sql_device = " AND device LIKE '%" . $data['device'] . "%'";
		} else {
			$sql_device = '';
		}
		// 表示件数
		if ( ! empty( $condi['limit'] ) ) {
			$sql_limit = ' LIMIT ' . $condi['limit'];
		} else {
			// $sql_limit = ' LIMIT '.$this->pagination_limit;
			$sql_limit = '';
		}
		// オフセット
		if ( ! empty( $condi['paged'] ) ) {
			if ( $condi['paged'] > 1 ) {
				$sql_paged = ' OFFSET ' . ( $condi['limit'] * ( $condi['paged'] - 1 ) );
			} else {
				$sql_paged = '';
			}
		} else {
			$sql_paged = '';
		}
		// テストID
		if ( ! empty( $condi['optimize_id'] ) ) {
			$sql_optimize_id = " AND optimize_id = '" . $condi['optimize_id'] . "'";
		} else {
			$sql_optimize_id = '';
		}

		$datas = $this->wpdb->get_results( 'SELECT * FROM ' . $table_name . $search_date . " $sql_memo $sql_post_url $sql_device $sql_optimize_id ORDER BY date DESC " . $sql_limit . $sql_paged );

		$res_count      = $this->wpdb->get_results( 'SELECT * FROM ' . $table_name . $search_date . " $sql_memo $sql_post_url $sql_device $sql_optimize_id" );  // ページ送りせず全件取得
		$datas['count'] = $this->wpdb->num_rows;    // 件数表示

		return $datas;
	}
	public function get_report_data( $table, $span, $data, $unique = true ) {

		$distinct = '*';  // 重複データも含め取得(PV)

		if ( $table == 'access' ) {
			$table_name = $this->access_log_table;
			if ( $unique ) {
				$distinct = 'DISTINCT CONCAT(ip,ua)';  // 重複データを削除(ユニークユーザー)
			}
		} elseif ( $table == 'click' ) {
			$table_name = $this->click_log_table;
		}

		if ( ! empty( $data['start_date'] ) && ! empty( $data['end_date'] ) ) {
			$search_date = " date BETWEEN '" . $data['start_date'] . " 00:00:00' AND '" . $data['end_date'] . " 23:59:59'";
		} else {
			$search_date = '';
		}
		if ( ! empty( $data['post_url'] ) ) {
			$sql_post_url = " AND post_url LIKE '%" . $data['post_url'] . "%'";
		} else {
			$sql_post_url = '';
		}
		if ( ! empty( $data['device'] ) ) {
			$sql_device = " AND device LIKE '%" . $data['device'] . "%'";
		} else {
			$sql_device = '';
		}

		// テストID
		if ( ! empty( $data['optimize_id'] ) ) {
			$sql_optimize_id = " AND optimize_id = '" . $data['optimize_id'] . "'";
		} else {
			$sql_optimize_id = '';
		}
		if ( ! empty( $data['optimize_type'] ) ) {
			$sql_optimize_type = " AND optimize_type = '" . $data['optimize_type'] . "'";
		} else {
			$sql_optimize_type = '';
		}

		if ( ! empty( $search_date ) || ! empty( $sql_post_url ) || ! empty( $sql_device ) || ! empty( $sql_optimize_id ) ) {
			$sql_str = ' WHERE' . $search_date . $sql_post_url . $sql_device . $sql_optimize_id . $sql_optimize_type;
			$sql_str = str_replace( 'WHERE AND', 'WHERE', $sql_str );
			$sql_str = rtrim( $sql_str, ' AND' );
		} else {
			$sql_str = '';
		}

		if ( $span == 'daily' ) {

			$datas = $this->wpdb->get_results(
				"
				SELECT DATE_FORMAT(date, '%Y-%m-%d') as 'date',
					COUNT(" . $distinct . ') as count 
				FROM ' . $table_name
				. $sql_str . "
				GROUP BY DATE_FORMAT(date, '%Y%m%d') ORDER BY date DESC
			"
			);

		} elseif ( $span == 'monthly' ) {

			$datas = $this->wpdb->get_results(
				"
				SELECT DATE_FORMAT(date, '%Y-%m') as 'date',
					COUNT(" . $distinct . ') as count
				FROM ' . $table_name
				. $sql_str . "
				GROUP BY DATE_FORMAT(date, '%Y%m') ORDER BY date DESC
			"
			);

		}

		// 日付をキーに変更
		return array_column( $datas, 'count', 'date' );
	}
	// 日付取得
	public function get_target_date( $date, $span ) {

		if ( ! empty( $span ) ) {
			if ( strpos( $span, 'month' ) === false ) {
				return date( 'Y-m-d', strtotime( $span . $date ) );
			} else {
				return date( 'Y-m', strtotime( $span . $date ) );
			}
		} else {
			return date( 'Y-m-d', strtotime( $date ) );
		}
	}
	// 指定日のデータ取得
	public function get_target_data( $span = null ) {

		$search_day  = $this->get_target_date( date_i18n( 'Y-m-d' ), $span );
		$search_data = array(
			'start_date' => $search_day,
			'end_date'   => $search_day,
		);

		$res['access'] = $this->get_report_data( 'access', 'daily', $search_data );
		$res['click']  = $this->get_report_data( 'click', 'daily', $search_data );

		return $res;
	}
	// 月別データ取得
	// 指定月データを取得したい場合は#$date='2020-03'などを入力。
	// その場合の$spanは無視される
	public function get_monthly_data( $span = null, $date = null ) {

		if ( ! empty( $date ) ) {
			// 日付指定があれば
			$start_date = date( 'Y-m-01', strtotime( $date ) );
			$end_date   = date( 'Y-m-t', strtotime( $start_date ) );  // 月末取得
		} else {
			// 日付指定がない
			$start_date = $this->get_target_date( date_i18n( 'Y-m-01' ), $span );
			$end_date   = date( 'Y-m-t', strtotime( $start_date ) );  // 月末取得
		}

		$search_data = array(
			'start_date' => $start_date,
			'end_date'   => $end_date,
		);

		$res['access'] = $this->get_report_data( 'access', 'monthly', $search_data );
		$res['click']  = $this->get_report_data( 'click', 'monthly', $search_data );

		return $res;
	}
	// クリック率を計算
	public function calc_click_rate( $access_num_sum, $click_num_sum ) {

		if ( ! empty( $access_num_sum ) ) {
			return round( ( $click_num_sum / $access_num_sum ) * 100, 1 );
		} else {
			return 0;
		}
	}
	// グラフ描画データ作成
	public function graphDate( $date_datas, $access_datas, $click_datas, $span = 'daily' ) {

		$max_access     = 0;
		$max_click      = 0;
		$max_click_rate = 0;

		foreach ( $date_datas as $date ) {

			if ( $span == 'monthly' ) {
				$today = $date->format( 'Y-m' );
			} else {
				$today = $date->format( 'Y-m-d' );
			}

			$graphData['date'][] = $today;

			$graphData['access'][ $today ] = '';
			if ( isset( $access_datas[ $today ] ) ) {
				$graphData['access'][ $today ] = $access_datas[ $today ];
				if ( $max_access < $access_datas[ $today ] ) {
					$max_access = $access_datas[ $today ];
				}
			}

			$graphData['click'][ $today ] = '';
			if ( isset( $click_datas[ $today ] ) ) {
				$graphData['click'][ $today ] = $click_datas[ $today ];
				if ( $max_click < $click_datas[ $today ] ) {
					$max_click = $click_datas[ $today ];
				}
			}

			if ( isset( $access_datas[ $today ] ) && isset( $click_datas[ $today ] ) ) {
				$graphData['click_rate'][ $today ] = $this->calc_click_rate( $access_datas[ $today ], $click_datas[ $today ] );
				if ( $max_click_rate < $graphData['click_rate'][ $today ] ) {
					$max_click_rate = $graphData['click_rate'][ $today ];
				}
			}
		}

		$js_data['max_access']     = $max_access;
		$js_data['max_click']      = $max_click;
		$js_data['max_click_rate'] = $max_click_rate;

		$js_data['date']       = '';
		$js_data['access']     = '';
		$js_data['click']      = '';
		$js_data['click_rate'] = '';

		if ( isset( $graphData ) && is_array( $graphData ) ) {

			foreach ( $graphData['date'] as $date ) {

				if ( isset( $date ) ) {
					$js_data['date'] .= "'" . $date . "',";
				}
				if ( isset( $graphData['access'][ $date ] ) ) {
					$js_data['access'] .= "'" . $graphData['access'][ $date ] . "',";
				} else {
					$js_data['access'] .= ',';
				}
				if ( isset( $graphData['click'][ $date ] ) ) {
					$js_data['click'] .= "'" . $graphData['click'][ $date ] . "',";
				} else {
					$js_data['click'] .= ',';
				}
				if ( isset( $graphData['click_rate'][ $date ] ) ) {
					$js_data['click_rate'] .= "'" . $graphData['click_rate'][ $date ] . "',";
				} else {
					$js_data['click_rate'] .= ',';
				}
			}
		}

		return $js_data;
	}
	// ABテストのグラフ
	public function graphDate_clickRate( $date_datas, $mainDatas, $subDatas ) {

		$js_data['date'] = '';
		$js_data['main'] = '';
		$js_data['sub']  = '';

		foreach ( $date_datas as $date ) {
			$today               = $date->format( 'Y-m-d' );
			$graphData['date'][] = $today;
		}

		foreach ( $graphData['date'] as $date ) {

			if ( ! empty( $mainDatas['access'][ $date ] ) && ! empty( $mainDatas['click'][ $date ] ) ) {
				$mainCTR = $this->calc_click_rate( $mainDatas['access'][ $date ], $mainDatas['click'][ $date ] );
			} else {
				$mainCTR = 0;
			}

			if ( ! empty( $subDatas['access'][ $date ] ) && ! empty( $subDatas['click'][ $date ] ) ) {
				$subCTR = $this->calc_click_rate( $subDatas['access'][ $date ], $subDatas['click'][ $date ] );
			} else {
				$subCTR = 0;
			}

			if ( isset( $date ) ) {
				$js_data['date'] .= "'" . $date . "',";
			}
			if ( isset( $mainCTR ) ) {
				$js_data['main'] .= "'" . $mainCTR . "',";
			} else {
				$js_data['main'] .= ',';
			}
			if ( isset( $subCTR ) ) {
				$js_data['sub'] .= "'" . $subCTR . "',";
			} else {
				$js_data['sub'] .= ',';
			}
		}

		return $js_data;
	}

	// 1の位で切り上げ
	public function ceil_1( $no ) {
		return ceil( ( $no / 10 ) ) * 10;
	}
	// ページネーション
	public function pagination( $data, $condi ) {

		if ( $condi['limit'] > 0 ) {
			$limit     = $condi['limit'];
			$paged_num = ceil( $data['count'] / $condi['limit'] );
		} else {
			$limit     = $pagination_limit;
			$paged_num = ceil( $data['count'] / $this->pagination_limit );
		}

		if ( $paged_num < 2 ) {
			$res['count_text'] = '全' . $data['count'] . '件を表示中';
			return $res;
		}

		if ( $condi['paged'] > 1 ) {
			$paged = $condi['paged'] - 1;
		} else {
			$paged = 0;
		}

		$res['count']     = $data['count']; // 全件数
		$res['min_count'] = $condi['limit'] * $paged + 1;
		$res['max_count'] = $condi['limit'] * $paged + $condi['limit'];
		if ( $res['max_count'] > $data['count'] ) {
			$res['max_count'] = $data['count']; }

		$url = ltrim( mb_strstr( $_SERVER['REQUEST_URI'], '?' ), '?' ); // パラメータ部分を取得
		parse_str( $url, $param );

		unset( $param['paged'] );  // パラメータのpagedは削除
		$pagination_param = http_build_query( $param );

		// 現在のページの前後のページネーション表示
		if ( $paged_num > 10 ) {
			if ( ( $paged - 5 ) <= 0 ) {
				$min_pagination = 1;
				$max_pagination = 10;
			} else {
				$min_pagination = ( $paged - 5 ) + 1;
				$max_pagination = 5 + $paged;
			}
			if ( ( $paged + 5 ) >= $paged_num ) {
				$min_pagination = $paged_num - 10;
				$max_pagination = $paged_num;
			}
		} else {
			$min_pagination = 1;
			$max_pagination = $paged_num;
		}

		$res['html'] = '';
		for ( $i = $min_pagination; $max_pagination >= $i; $i++ ) {

			if ( $max_pagination > $paged_num ) {
				break; }

			$active = '';
			if ( ! $condi['paged'] && $i == 1 ) {
				$active = 'class="active"';
			} elseif ( $condi['paged'] == $i ) {
				$active = 'class="active"';
			}

			$res['html'] .= '<a href="?' . $pagination_param . '&paged=' . $i . '" ' . $active . '>' . $i . '</a>';

		}
		if ( $min_pagination > 1 ) {
			$res['html'] = '<a href="?' . $pagination_param . '&paged=1">最初へ</a> ' . $res['html'];
		}
		if ( $max_pagination < $paged_num ) {
			$res['html'] = $res['html'] . ' <a href="?' . $pagination_param . '&paged=' . $paged_num . '">最後へ</a>';
		}
		$res['html'] = '<div class="bfb_pagination">' . $res['html'] . '</div>';

		$res['count_text'] = '全' . $res['count'] . '件中' . ( $paged > 0 ? ( $paged + 1 ) : 1 ) . 'ページ目';

		return $res;
	}
}
