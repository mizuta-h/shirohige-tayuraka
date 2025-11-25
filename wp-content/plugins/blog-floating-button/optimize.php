<?php

require_once 'tracking.php';

class Optimize extends BlogFloatingButton {

	const OPTMIZE_TABLE     = 'bfb_optimize';
	const OPTMIZEMETA_TABLE = 'bfb_optimizemeta';

	public $optimize_id     = '';
	public $optimize_step   = 'opt_list';
	public $optimize_status = '0';
	public $wpdb;
	public $optimize_table;
	public $optimizemeta_table;

	public $optimizeStep       = array(
		'opt_list'    => 'list',
		'opt_init'    => 'init',
		'opt_mainBtn' => 'mainBtn',
		'opt_subBtn'  => 'subBtn',
	);
	public $optimizeItems_list = array();
	public $optimizeItems_init = array(
		'optimize_name'     => array( 'require', 'max50' ),
		'mainBtnDesign'     => array( 'require', 'max50', 'w' ),
		'subBtnDesign'      => array( 'require', 'max50', 'w' ),
		'distribution_rate' => array( 'require', 'int' ),
		'device'            => array( 'require', 'w' ),
		// 'scheduled_finish_date'  => array('require','date'),
		'memo'              => array( 'max200' ),
		'status'            => array( 'require', 'num' ),
		'finished_date'     => array( 'datetime' ),
	);
	public $optimizeItems_mainBtn = array();
	public $optimizeItems_subBtn  = array();

	public function __construct() {

		global $wpdb;
		$this->wpdb = $wpdb;

		$this->optimize_table     = $wpdb->prefix . self::OPTMIZE_TABLE;
		$this->optimizemeta_table = $wpdb->prefix . self::OPTMIZEMETA_TABLE;

		$this->init_var();
		// $this->check_license_key();

		$this->report = new Tracking();
	}
	public function write_optimizemeta( $id, $key, $value ) {

		// DBエラーになるため、データが揃ってなければ更新しない
		if ( isset( $id ) && isset( $key ) && isset( $value ) ) {

			$data = array(
				'optimize_id' => $id,
				'meta_key'    => $key,
				'meta_value'  => $value,
			);

			$res = $this->wpdb->insert( $this->optimizemeta_table, $data, array( '%s', '%s', '%s' ) );
		}
	}
	public function update_optimizemeta( $id, $key, $value ) {

		// DBエラーになるため、データが揃ってなければ更新しない
		if ( isset( $id ) && isset( $key ) && isset( $value ) ) {

			$data      = array(
				'meta_value' => $value,
			);
			$condition = array(
				'optimize_id' => $id,
				'meta_key'    => $key,
			);

			$res = $this->wpdb->update( $this->optimizemeta_table, $data, $condition, array( '%s', '%s' ) );

			// テスト終了時に終了日時を記録
			if ( $key == 'status' && $value == '2' ) {
				$this->write_optimizemeta( $id, 'finished_date', date_i18n( 'Y-m-d H:i:s' ) );
			}
		}
	}
	// データ削除
	public function delete_data( $opt_id ) {

		$data = array(
			'optimize_id' => $opt_id,
		);

		$res = $this->wpdb->delete( $this->optimizemeta_table, $data );
	}
	public function read_optimize( $opt_id = null ) {

		$sql_opt_id = '';
		if ( isset( $opt_id ) ) {
			$sql_opt_id = ' WHERE `optimize_id` = \'' . $opt_id . '\'';
		}

		$datas = $this->wpdb->get_results( 'SELECT * FROM ' . $this->optimizemeta_table . $sql_opt_id );

		if ( empty( $datas ) ) {
			return false; }

		foreach ( $datas as $data ) {
			$res[ $data->optimize_id ][ $data->meta_key ] = $data->meta_value;
		}

		// 日付順に並び替え
		foreach ( $res as $key => $val ) {
			$sort[ $key ] = $val['created_date'];
		}
		array_multisort( $sort, SORT_DESC, $res );

		if ( isset( $res ) ) {

			foreach ( $res as $data ) {
				foreach ( $data as $key => $val ) {
					$this->{$key} = $val;
				}
			}

			return $res;

		} else {
			return false;
		}
	}
	// データコピー
	public function copy_optimizemeta( $opt_id ) {

		$oldDatas = $this->read_optimize( $opt_id )[ $opt_id ];

		$new_optimize_id           = $this->makeRandStr( 32 );
		$oldDatas['created_date']  = date_i18n( 'Y-m-d H:i:s' );   // 作成日時を現在日時
		$oldDatas['status']        = 0;    // ステータスを「停止」
		$oldDatas['finished_date'] = '';    // 終了日を削除

		foreach ( $oldDatas as $key => $val ) {
			if ( $key == 'optimize_name' ) {
				$val .= '_copy';
			}
			$this->write_optimizemeta( $new_optimize_id, $key, $val );
		}
	}

	// 最適化設定画面
	public function optimize_page() {

		$res = $this->save_metadata( $this->optimize_id );

		if ( $res && $this->optimize_step != 'opt_init' ) {
			add_action( 'admin_notices', array( $this, 'updated_message' ) );
			do_action( 'admin_notices' );
		}

		wp_register_script(
			'bfb-admin-js',
			plugins_url( 'js/bfb_admin.js', __FILE__ ),
			array( 'jquery' ),
			false,
			true
		);
		wp_enqueue_script( 'bfb-admin-js' );

		include_once 'inc/optimize/optimize-main.php';
	}
	// 最適化設定画面
	public function optimize_report() {

		include_once 'inc/optimize/report/report-main.php';
	}
	public function save_metadata() {

		$validation_flg = true;

		// POSTデータなしか戻るボタンなら保存はしない
		if ( ! $_POST ) {
			return false; }

		// nonceを確認し値が正しくなければ何もしない
		if ( ! check_admin_referer( 'bfb_optimize_setting', '_wpnonce_bfb' ) ) {
			return false;
		}

		$filter_args = array(
			'action'        => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'optimize_id'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'optimize_step' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		);
		$postData    = filter_input_array( INPUT_POST, $filter_args );

		if ( $postData['action'] == 'back' ) {
			return false; }

		if ( empty( $postData['optimize_id'] ) ) {
			$this->optimize_id = $this->makeRandStr( 32 );
		} else {
			$this->optimize_id = $postData['optimize_id'];
		}

		// 最適化ステップ
		$this->optimize_step = $postData['optimize_step'];

		foreach ( $this->{'optimizeItems_' . $this->optimizeStep[ $this->optimize_step ]} as $item => $validates ) {

			$post_data = $_POST[ $item ] ?? '';

			$is_validate = $this->check_validation( $post_data, $validates );
			if ( ! $is_validate ) {
				$this->validation_msg( $item );
				$validation_flg = false;
			}
		}

		// バリデーションエラーがあれば、保存しない
		if ( ! $validation_flg ) {
			$this->error_message( '設定を保存できませんでした。' );
			return false;
		}

		// 保存しないデータは削除
		unset( $_POST['page'] );
		unset( $_POST['optimize_step'] );
		unset( $_POST['optimize_id'] );
		unset( $_POST['optimize_id'] );
		unset( $_POST['optimize_id'] );

		// 古いデータ取得
		$old_data = $this->read_optimize( $this->optimize_id );

		foreach ( $this->optimizeItems_init as $optItem => $validates ) {

			$postData = filter_input( INPUT_POST, $optItem );

			if ( isset( $postData ) && ! empty( $validates ) ) {

				$is_validate = $this->check_validation( $postData, $validates );

				if ( ! $is_validate ) {
					$this->validation_msg( $optItem );
				} elseif ( ! isset( $old_data[ $this->optimize_id ][ $optItem ] ) && ! isset( $old_data[ $this->optimize_id ][ $optItem . '_' . $this->optimize_step ] ) ) {
						// データがなければ保存
						$this->write_optimizemeta( $this->optimize_id, $optItem, $postData );
				} elseif ( $old_data[ $this->optimize_id ][ $optItem ] != $postData ) {
						$this->update_optimizemeta( $this->optimize_id, $optItem, $postData );
				}
			}
		}

		if ( $this->optimize_step == 'opt_mainBtn' || $this->optimize_step == 'opt_subBtn' ) {

			// ボタン設定の保存
			foreach ( $this->devices as $device ) {
				foreach ( $this->designTypes as $designType ) {
					foreach ( $this->btnItems as $item => $validates ) {

						$key      = 'bfb_' . $designType . '_' . $item . '_' . $device;
						$postData = filter_input( INPUT_POST, $key );

						if ( ! empty( $validates ) ) {

							$is_validate = $this->check_validation( $postData, $validates );

							if ( ! $is_validate ) {
								$this->validation_msg( $item );
							} elseif ( ! isset( $old_data[ $this->optimize_id ][ $key ] ) && ! isset( $old_data[ $this->optimize_id ][ $key . '_' . $this->optimize_step ] ) ) {
									// データがなければ新規保存
									$this->write_optimizemeta( $this->optimize_id, $key . '_' . $this->optimize_step, $postData );
							} else {
								// データがあれば更新
								$this->update_optimizemeta( $this->optimize_id, $key . '_' . $this->optimize_step, $postData );
							}
						}
					}
				}
			}
		}

		// 初期設定のみ実行
		if ( $this->optimize_step == 'opt_init' ) {
			if ( ! isset( $old_data[ $this->optimize_id ]['created_date'] ) ) {
				$this->write_optimizemeta( $this->optimize_id, 'created_date', date_i18n( 'Y-m-d H:i:s' ) );
			}
		}

		// 次のステップ
		$_POST['optimize_id'] = $this->optimize_id;
		$this->return_optStep( $this->optimize_step, 'next' );

		return true;
	}
	public function validation_msg( $item ) {

		$text_input      = 'で入力してください。';
		$text_donot_save = 'を保存できませんでした。';
		$text_require    = 'は必須です。';

		switch ( $item ) {
			case 'device':
				$this->error_message( 'デバイスの選択' . $text_require );
				break;
			case 'mainBtnDesign':
				$this->error_message( 'ボタンデザイン(メイン)の選択' . $text_require );
				break;
			case 'subBtnDesign':
				$this->error_message( 'ボタンデザイン(サブ)の選択' . $text_require );
				break;
			case 'finished_date':
				$this->error_message( 'テスト終了日' . $text_donot_save );
				break;
			case 'optimize_name':
				$this->error_message( 'テスト名は50文字以内' . $text_input );
				break;
			case 'distribution_rate':
				$this->error_message( '振り分け率は半角数字' . $text_input );
				break;
			case 'memo':
				$this->error_message( 'メモは200文字以内' . $text_input );
				break;
		}

		return false;
	}
	private function makeRandStr( $length ) {

		$str   = array_merge( range( 'a', 'z' ), range( '0', '9' ), range( 'A', 'Z' ) );
		$r_str = null;
		for ( $i = 0; $i < $length; $i++ ) {
			$r_str .= $str[ rand( 0, count( $str ) - 1 ) ];
		}
		return $r_str;
	}
	// 前のステップに戻す
	public function return_optStep( $optStep, $prev = 'prev' ) {
		if ( $prev == 'prev' ) {
			// 前のステップ
			if ( $optStep == 'opt_list' ) {
				$this->optimize_step = 'opt_list';
			} elseif ( $optStep == 'opt_init' ) {
				$this->optimize_step = 'opt_list';
			} elseif ( $optStep == 'opt_mainBtn' ) {
				$this->optimize_step = 'opt_init';
			} elseif ( $optStep == 'opt_subBtn' ) {
				$this->optimize_step = 'opt_mainBtn';
			}
		} else {
			// 次のステップ
			if ( $optStep == 'opt_list' ) {
				$this->optimize_step = 'opt_init';
			} elseif ( $optStep == 'opt_init' ) {
				$this->optimize_step = 'opt_mainBtn';
			} elseif ( $optStep == 'opt_mainBtn' ) {
				$this->optimize_step = 'opt_subBtn';
			} elseif ( $optStep == 'opt_subBtn' ) {
				$this->optimize_step = 'opt_list';
			}
		}
		return $this->optimize_step;
	}

	// 最適化テスト実施中か
	public function is_optimize() {

		$url             = ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$opt_datas       = $this->read_optimize();
		$opt_activeDatas = array();

		foreach ( $opt_datas as $opt_id => $opt_data ) {
			if ( $opt_data['status'] == 1 ) {
				// パターンがURLに一致するか
				if ( preg_match( '/' . $opt_data['url_regexPattern'] . '/', $url ) ) {
					$opt_activeDatas[ $opt_id ] = $opt_data;
					break;
				}
			}
		}

		if ( ! empty( $opt_activeDatas ) ) {
			return $opt_activeDatas;
		} else {
			return false;
		}
	}
	// 最適化テスト実施中か
	public function get_activeOptimizeId( $active = true ) {

		$opt_datas       = $this->read_optimize();
		$opt_activeDatas = array();

		if ( empty( $opt_datas ) ) {
			return false; }

		foreach ( $opt_datas as $opt_id => $opt_data ) {
			if ( $active ) {
				if ( $opt_data['status'] == 1 ) {
					$opt_activeDatas[ $opt_id ] = $opt_data;
				}
			} else {
				$opt_activeDatas[ $opt_id ] = $opt_data;
			}
		}

		if ( ! empty( $opt_activeDatas ) ) {
			return $opt_activeDatas;
		} else {
			return false;
		}
	}
	// 合計数を取得
	public function get_num_sum( $datas ) {

		$sum = 0;

		if ( empty( $datas ) || ! is_array( $datas ) ) {
			return 0;
		}

		foreach ( $datas as $key => $val ) {
			$sum += $val;
		}
		return $sum;
	}
	// 表作成時のデータ取得
	public function get_tableData( $start_date = '', $end_date = '', $optimize_id = '' ) {

		$res = array();

		$opt_datas = $this->read_optimize( $optimize_id )[ $optimize_id ];

		if ( ! empty( $opt_datas['created_date'] ) ) {
			$start_date = explode( ' ', $opt_datas['created_date'] )[0];
		} else {
			$start_date = '2020-01-01';
		}

		if ( ! empty( $opt_datas['finished_date'] ) ) {
			$end_date = explode( ' ', $opt_datas['finished_date'] )[0];
		} else {
			$end_date = date_i18n( 'Y-m-d' );
		}

		// レポート表示期間
		$res['search_span'] = $this->report->get_first_last_date( $start_date, $end_date, 'daily' );

		// メインデータ
		$mainData               = array(
			'start_date'    => $start_date,
			'end_date'      => $end_date,
			'post_url'      => '',
			'device'        => '',
			'optimize_id'   => $optimize_id,
			'optimize_type' => 'mainBtnDesign',
		);
		$res['access_mainData'] = $this->report->get_report_data( 'access', 'daily', $mainData );
		$res['click_mainData']  = $this->report->get_report_data( 'click', 'daily', $mainData );
		$res['mainDatas']       = array(
			'access' => $res['access_mainData'],
			'click'  => $res['click_mainData'],
		);
		$res['main_access']     = $this->get_num_sum( $res['access_mainData'] );
		$res['main_click']      = $this->get_num_sum( $res['click_mainData'] );

		if ( $res['main_access'] > 0 ) {
			$res['main_clickRate'] = round( $res['main_click'] / $res['main_access'] * 100, 1 );
		} else {
			$res['main_clickRate'] = 0;
		}

		// サブデータ
		$subData               = array(
			'start_date'    => $start_date,
			'end_date'      => $end_date,
			'post_url'      => '',
			'device'        => '',
			'optimize_id'   => $optimize_id,
			'optimize_type' => 'subBtnDesign',
		);
		$res['access_subData'] = $this->report->get_report_data( 'access', 'daily', $subData );
		$res['click_subData']  = $this->report->get_report_data( 'click', 'daily', $subData );
		$res['subDatas']       = array(
			'access' => $res['access_subData'],
			'click'  => $res['click_subData'],
		);
		$res['sub_access']     = $this->get_num_sum( $res['access_subData'] );
		$res['sub_click']      = $this->get_num_sum( $res['click_subData'] );

		if ( $res['sub_access'] > 0 ) {
			$res['sub_clickRate'] = round( $res['sub_click'] / $res['sub_access'] * 100, 1 );
		} else {
			$res['sub_clickRate'] = 0;
		}

		// グラフデータ
		$res['graphData'] = $this->report->graphDate_clickRate( $res['search_span'], $res['mainDatas'], $res['subDatas'] );

		$res['optimize_name'] = $opt_datas['optimize_name'];
		$res['created_date']  = $opt_datas['created_date'];

		$res['finished_date'] = $opt_datas['finished_date'] ?? '';
		$res['device']        = $opt_datas['device'] ?? '';

		return $res;
	}
}
