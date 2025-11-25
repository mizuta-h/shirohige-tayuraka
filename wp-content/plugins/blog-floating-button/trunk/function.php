<?php

// 管理画面でスクリプト読み込み
function bfb_admin_scripts( $loader_src ) {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-tabs' );

	// 日付ピッカー
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'jquery-ui-css', plugins_url( 'css/jquery-ui.css', __FILE__ ) );

	// カラーピッカー
	wp_enqueue_style( 'wp-color-picker' );

	// フォント
	wp_enqueue_style( 'font-Montserrat', 'https://fonts.googleapis.com/css?family=Montserrat&display=swap' );

	// グラフ描画
	global $hook_suffix;
	if ( strpos( $hook_suffix, 'blog-floating-button' ) > -1 ) {
		wp_enqueue_script( 'chart-min-js', plugins_url( 'js/Chart.min.js', __FILE__ ) );
		wp_enqueue_script( 'chartjs-plugin-colorschemes-min-js', plugins_url( 'js/chartjs-plugin-colorschemes.min.js', __FILE__ ) );
	}

	// table
	wp_enqueue_script( 'jquery-dataTables-min-js', plugins_url( 'js/jquery.dataTables.min.js', __FILE__ ) );
	wp_enqueue_style( 'jquery-dataTables-min-css', plugins_url( 'css/jquery.dataTables.min.css', __FILE__ ) );

	// 管理画面CSS
	wp_enqueue_style( 'admin-bfb-style-css', plugins_url( 'css/admin_bfb_style.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'bfb_admin_scripts' );

// スクリプト読み込み
function bfb_enqueue_scripts() {

	wp_enqueue_script( 'jquery' );
	wp_register_script( 'bfb_js_cookie', plugins_url( 'js/jquery.cookie.js', __FILE__ ), array(), '', true );
	wp_enqueue_script( 'bfb_js_cookie' );
	wp_enqueue_style( 'bfb_fontawesome_stylesheet', 'https://use.fontawesome.com/releases/v5.12.1/css/all.css' );
	wp_enqueue_style( 'bfb_font_Montserrat', 'https://fonts.googleapis.com/css?family=Montserrat&display=swap' );
}
add_action( 'wp_enqueue_scripts', 'bfb_enqueue_scripts' );

// 投稿、固定ページ、カスタム投稿タイプ編集画面にBFB設定を追加
function bfb_add_fields() {

	$bfb = new BlogFloatingButton();

	if ( $bfb->is_activation ) {

		// カスタム投稿タイプ
		$custom_post_types = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'names'
		);
		foreach ( $custom_post_types as $post_type ) {
			add_meta_box(
				'blog_floating_button',
				'Blog Float Button設定(PRO版)',
				'bfb_fields',
				$post_type,
				'normal'
			);
		}

		// 投稿、固定ページ
		add_meta_box( 'blog_floating_button', 'Blog Float Button設定(PRO版)', 'bfb_fields', array( 'post', 'page' ), 'normal' );
	}
}
add_action( 'admin_menu', 'bfb_add_fields' );

function bfb_fields() {

	global $post;

	$bfb = new BlogFloatingButton();
	$bfb->option_page( $post->ID );
}

// 個別記事の設定保存
function bfb_save_fields( $post_id ) {

	// nonceを確認し値が正しくなければ何もしない
	$nonce = isset( $_POST['_wpnonce_bfb'] ) ? $_POST['_wpnonce_bfb'] : null;
	if ( ! wp_verify_nonce( $nonce, 'bfb_setting' ) ) {
		return $post_id;
	}

	$bfb = new BlogFloatingButton();

	// 共通
	$bfb_use_post = filter_input( INPUT_POST, 'bfb_use_post', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	if ( ! empty( $bfb_use_post ) ) {
		$is_validate = $bfb->check_validation( $bfb_use_post, array( 'w' ) );
		if ( $is_validate ) {
			update_post_meta( $post_id, 'bfb_use_post', $bfb_use_post );
		}
	}

	foreach ( $bfb->devices as $device ) {

		${'bfb_designType_' . $device} = filter_input( INPUT_POST, 'bfb_designType_' . $device, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		${'bfb_optId_' . $device}      = filter_input( INPUT_POST, 'bfb_optId_' . $device, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( ${'bfb_designType_' . $device} ) ) {
			$is_validate = $bfb->check_validation( ${'bfb_designType_' . $device}, array( 'w' ) );
			if ( $is_validate ) {
				update_post_meta( $post_id, 'bfb_designType_' . $device, ${'bfb_designType_' . $device} );
			} else {
				$bfb->validation_msg( 'bfb_designType' );
			}
		}
		if ( isset( ${'bfb_optId_' . $device} ) ) {
			$is_validate = $bfb->check_validation( ${'bfb_optId_' . $device}, array( 'w' ) );
			if ( $is_validate ) {
				update_post_meta( $post_id, 'bfb_optId_' . $device, ${'bfb_optId_' . $device} );
			} else {
				$bfb->validation_msg( 'bfb_optId' );
			}
		}

		foreach ( $bfb->designTypes as $designType ) {
			foreach ( $bfb->btnItems as $item => $validates ) {

				$key      = 'bfb_' . $designType . '_' . $item . '_' . $device;
				$postData = filter_input( INPUT_POST, $key );

				if ( isset( $postData ) ) {
					$is_validate = $bfb->check_validation( $postData, $validates );
					if ( $is_validate ) {
						update_post_meta( $post_id, 'bfb_' . $designType . '_' . $item . '_' . $device, $postData );
					} else {
						$bfb->validation_msg( $item );
					}
				}
			}
		}
	}
}
add_action( 'save_post', 'bfb_save_fields' );

// カテゴリーに追加
add_action( 'category_edit_form', 'bfb_add_category_fields' );
function bfb_add_category_fields( $tag ) {

	$bfb = new BlogFloatingButton();

	if ( $bfb->is_activation ) {

		global $post;

		$category_id = $tag->term_id;

		$bfb->option_page( $category_id );

	}
}
add_action( 'edited_term', 'bfb_save_category_fileds' );
function bfb_save_category_fileds( $category_id ) {

	$bfb = new BlogFloatingButton();

	if ( ! $_POST ) {
		return false; }

	// nonceを確認し値が正しくなければ何もしない
	$nonce = isset( $_POST['_wpnonce_bfb'] ) ? $_POST['_wpnonce_bfb'] : null;
	if ( ! wp_verify_nonce( $nonce, 'bfb_setting' ) ) {
		return $post_id;
	}

	// 個別設定の使用
	$bfb_use_category = filter_input( INPUT_POST, 'bfb_use_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	if ( ! empty( $bfb_use_category ) ) {
		$is_validate = $bfb->check_validation( $bfb_use_category, array( 'w' ) );
		if ( $is_validate ) {
			$cat_meta['bfb_use_category'] = $bfb_use_category;
			update_option( "cat_$category_id", $cat_meta );
		}
	}
	// 個別設定の優先度
	$bfb_categoryPriority = filter_input( INPUT_POST, 'bfb_use_category', FILTER_VALIDATE_INT );
	if ( ! empty( $bfb_categoryPriority ) ) {
		$is_validate = $bfb->check_validation( $bfb_categoryPriority, array( 'int' ) );
		if ( $is_validate ) {
			$cat_meta['bfb_categoryPriority'] = $bfb_categoryPriority;
			update_option( "cat_$category_id", $cat_meta );
		}
	}

	foreach ( $bfb->devices as $device ) {

		${'bfb_designType_' . $device} = filter_input( INPUT_POST, 'bfb_designType_' . $device, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		${'bfb_optId_' . $device}      = filter_input( INPUT_POST, 'bfb_optId_' . $device, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( ${'bfb_designType_' . $device} ) ) {
			$cat_meta[ 'bfb_designType_' . $device ] = ${'bfb_designType_' . $device};
			update_option( "cat_$category_id", $cat_meta );
		}
		if ( isset( ${'bfb_optId_' . $device} ) ) {
			$cat_meta[ 'bfb_optId_' . $device ] = ${'bfb_optId_' . $device};
			update_option( "cat_$category_id", $cat_meta );
		}

		foreach ( $bfb->designTypes as $designType ) {
			foreach ( $bfb->btnItems as $item => $validates ) {

				$key      = 'bfb_' . $designType . '_' . $item . '_' . $device;
				$postData = filter_input( INPUT_POST, $key );

				if ( isset( $postData ) ) {

					$is_validate = $bfb->check_validation( $postData, $validates );

					if ( ! $is_validate ) {
						$bfb->validation_msg( $item );
					} else {
						$cat_meta[ $key ] = $postData;
						update_option( "cat_$category_id", $cat_meta );
					}
				}
			}
		}
	}
}

// 有効化時にデータベース作成
function bfb_activate() {
	global $wpdb;
	$sql             = '';
	$charset_collate = '';

	// charset
	if ( ! empty( $wpdb->charset ) ) {
		$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} ";
	}
	// 照合順序
	if ( ! empty( $wpdb->collate ) ) {
		$charset_collate .= "COLLATE {$wpdb->collate}";
	}

	// アクセスログテーブル作成
	$bfb_access_db_version = '1.0.2';
	$access_installed_ver  = get_option( 'bfb_access_db_version' );

	if ( $access_installed_ver != $bfb_access_db_version ) {
		$sql = 'CREATE TABLE ' . $wpdb->prefix . "bfb_access_log (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
					post_id int(10) unsigned NOT NULL,
					post_url text NOT NULL,
					ip varchar(50) NOT NULL,
					ua text NOT NULL,
					device varchar(10) NOT NULL,
					referer text,
					optimize_id varchar(32) NOT NULL,
					optimize_type varchar(30) NOT NULL,
					date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
				) {$charset_collate};";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		update_option( 'bfb_access_db_version', $bfb_access_db_version );
	}

	// クリックログテーブル作成
	$bfb_click_db_version = '1.0.2';
	$click_installed_ver  = get_option( 'bfb_click_db_version' );

	if ( $click_installed_ver != $bfb_click_db_version ) {
		$sql = 'CREATE TABLE ' . $wpdb->prefix . "bfb_click_log (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
					post_id int(10) unsigned NOT NULL,
					post_url text NOT NULL,
					linked_url text NOT NULL,
					ip varchar(50) NOT NULL,
					ua text NOT NULL,
					device varchar(10) NOT NULL,
					memo text,
					optimize_id varchar(32) NOT NULL,
					optimize_type varchar(30) NOT NULL,
					date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
				) {$charset_collate};";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		update_option( 'bfb_click_db_version', $bfb_click_db_version );
	}

	// A/Bテストメタテーブル作成
	$bfb_optimize_db_version = '1.0.0';
	$optimize_installed_ver  = get_option( 'bfb_optimize_db_version' );

	if ( $optimize_installed_ver != $bfb_optimize_db_version ) {
		$sql = 'CREATE TABLE ' . $wpdb->prefix . "bfb_optimizemeta (
					id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					optimize_id varchar(255) NOT NULL,
					meta_key varchar(255) NOT NULL,
					meta_value longtext NOT NULL
				) {$charset_collate};";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		update_option( 'bfb_optimize_db_version', $bfb_optimize_db_version );
	}

	// プラグインが有効化されたときにクーロンジョブを登録する
	if ( ! wp_next_scheduled( 'bfb_cleanup_logs_event' ) ) { // クーロンジョブの重複登録を防ぐチェック
		wp_schedule_event( time(), 'daily', 'bfb_cleanup_logs_event' );
	}
}
register_activation_hook( __FILE__, 'bfb_activate' );
add_action( 'plugins_loaded', 'bfb_activate' );

// DBにアクセスログ、クリックログを残す
function bfb_write_log() {

	if ( empty( $_POST['data'] ) ) {
		return false; }

	$tr            = new Tracking();
	$bfb_db_action = filter_input( INPUT_POST, 'bfb_db_action' );

	if ( $bfb_db_action == 'bfb_tracking_access' ) {

		$arg      = array(
			'data' => array(
				'post_id'       => FILTER_VALIDATE_INT,
				'post_url'      => FILTER_VALIDATE_URL,
				'referer'       => FILTER_VALIDATE_URL,
				'optimize_id'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
				'optimize_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
				'flags'         => FILTER_REQUIRE_ARRAY,
			),
		);
		$postData = filter_input_array( INPUT_POST, $arg );

		$data = $tr->get_user_data( $postData['data'] );
		if ( isset( $data ) ) {
			$tr->write_accessLog( $data );
		}
	} elseif ( $bfb_db_action == 'bfb_tracking_click' ) {

		$arg      = array(
			'data' => array(
				'post_id'       => FILTER_VALIDATE_INT,
				'post_url'      => FILTER_VALIDATE_URL,
				'linked_url'    => FILTER_VALIDATE_URL,
				'ua'            => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
				'memo'          => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
				'optimize_id'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
				'optimize_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
				'flags'         => FILTER_REQUIRE_ARRAY,
			),
		);
		$postData = filter_input_array( INPUT_POST, $arg );

		$tr->write_clickLog( $postData['data'] );

	}
}
function add_bfb_endpoint() {
	register_rest_route(
		'bfb/api',
		'/bfb_write_log',
		array(
			'methods'             => 'POST',
			'callback'            => 'bfb_write_log',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'add_bfb_endpoint' );

// ライブプレビュー
function bfb_api_livePreview() {

	$bfb = new BlogFloatingButton();

	$postData = filter_input( INPUT_POST, 'btnData', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

	if ( ! $postData ) {
		return false; }

	foreach ( $postData as $key => $postData ) {

		if ( $key == 'designType' ) {

			if ( strpos( $postData, 'textBtnTextBtn' ) > -1 ) {
				$previewDatas['designType'] = 'textBtnTextBtn';
			} elseif ( strpos( $postData, 'textTextBtn' ) > -1 ) {
				$previewDatas['designType'] = 'textTextBtn';
			} elseif ( strpos( $postData, 'textBtn' ) > -1 ) {
				$previewDatas['designType'] = 'textBtn';
			} elseif ( strpos( $postData, 'imgBanner' ) > -1 ) {
				$previewDatas['designType'] = 'imgBanner';
			}
		}

		foreach ( $bfb->btnItems as $item => $validates ) {
			if ( strpos( $key, '_' . $item . '_' ) > -1 && $key != 'designType' ) {
				$previewDatas[ $item ] = $postData;
				break;
			}
		}
	}

	// 詳細設定の読み込み
	foreach ( $bfb->devices as $device ) {
		foreach ( $bfb->bfbDesignItems as $bfbDesignItem => $validates ) {
			if ( isset( $postData[ $bfbDesignItem . '_' . $device ] ) ) {
				$previewDatas[ $bfbDesignItem . '_' . $device ] = $postData[ $bfbDesignItem . '_' . $device ];
			} else {
				$previewDatas[ $bfbDesignItem . '_' . $device ] = '';
			}
		}
	}

	// ABテストのプレビュー時のテストID
	if ( isset( $postData['optimize_id'] ) ) {
		$previewDatas['optimize_id'] = $postData['optimize_id'];
	}

	$previewDatas['live_preview'] = true;
	if ( $previewDatas ) {
		return $bfb->generate_btn_html( $_POST['device'], $previewDatas );
	}
}
function bfb_livePreview_endpoint() {
	register_rest_route(
		'bfb/api',
		'/live_preview',
		array(
			'methods'             => 'POST',
			'callback'            => 'bfb_api_livePreview',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'bfb_livePreview_endpoint' );

// 最適化テスト時のフロートボタン出力
// 通常出力だとキャッシュにより切り替えられない
function bfb_api_echo_bfb_optimize() {

	$bfb = new BlogFloatingButton();

	$arg      = array(
		'device'      => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		'post_id'     => FILTER_VALIDATE_INT,
		'page_type'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
		'optimizeBtn' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
	);
	$postData = filter_input_array( INPUT_POST, $arg );

	if ( isset( $postData['device'] ) && $postData['device'] == 'pc' ) {
		$device = 'pc';
	} elseif ( isset( $postData['device'] ) && $postData['device'] == 'sp' ) {
		$device = 'sp';
	}

	if ( isset( $postData['post_id'] ) && preg_match( '/^[0-9]+$/', $postData['post_id'] ) ) {
		$post_id = $postData['post_id'];
	} else {
		$post_id = '';
	}
	if ( isset( $postData['page_type'] ) && preg_match( '/^\w+$/', $postData['page_type'] ) ) {
		$page_type = $postData['page_type'];
	} else {
		$page_type = '';
	}
	if ( isset( $postData['optimizeBtn'] ) && preg_match( '/^\w+$/', $postData['optimizeBtn'] ) ) {
		$optimizeBtn = $postData['optimizeBtn'];
	} else {
		$optimizeBtn = '';
	}

	// アクセスログとフロートボタンを合わせる
	// アクセスログは早く呼ばれるためPOSTでデータ保持
	if ( $optimizeBtn == 'mainBtnDesign' ) {
		$meta = array(
			'optimizeBtn'     => 'mainBtnDesign',
			'suf_optimizeBtn' => '_opt_mainBtn',
		);
	} else {
		$meta = array(
			'optimizeBtn'     => 'subBtnDesign',
			'suf_optimizeBtn' => '_opt_subBtn',
		);
	}

	$bfb->read_metadata_condi( $post_id, $page_type );
	$bfb->init_bfb( $device );
	return $bfb->generate_btn_html( $device, null, $meta );
}
function bfb_echo_bfb_optimize_endpoint() {
	register_rest_route(
		'bfb/api',
		'/echo_bfb_optimize',
		array(
			'methods'             => 'POST',
			'callback'            => 'bfb_api_echo_bfb_optimize',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'bfb_echo_bfb_optimize_endpoint' );

function bfb_show_position() {
	return '<div id="bfb_show_position"></div>';
}
// ショートコード、bfb_showとbfb_hideはpro版のみ動作するように変更 v1.4.16
// add_shortcode('bfb_show', 'bfb_show_position');

function bfb_hide_position() {
	return '<div id="bfb_hide_position"></div>';
}
// ショートコード、bfb_showとbfb_hideはpro版のみ動作するように変更 v1.4.16
// add_shortcode('bfb_hide', 'bfb_hide_position');

// 設定された保存期間を超えた、解析レポートのログを削除する
function bfb_cleanup_analysis_logs() {

	// 削除する日数を取得
	$threshold_date = get_option( 'bfb_retention_period', 'period_0' );
	switch ( $threshold_date ) {
		case 'period_0':
			$threshold_date = '0';
			break;
		case 'period_30':
			$threshold_date = '30';
			break;
		case 'period_90':
			$threshold_date = '90';
			break;
		case 'period_180':
			$threshold_date = '180';
			break;
		case 'period_360':
			$threshold_date = '360';
			break;
		default:
			$threshold_date = '0';
	}

	// デバッグ用。基本コメントアウト(バックグラウンド処理はvar_dumpなどで出力して確認ができないため、任意の値をDBに登録することで確認している)
	// $bfb_debug = $threshold_date;
	// update_option( 'bfb_cleanup_analysis_logs_debug', $bfb_debug );

	// 0の場合は何もしない
	if ( $threshold_date == '0' ) {
		return;
	} else {
		// 取得した数値をUnixタイムスタンプに変換
		$threshold_date = date( 'Y-m-d', strtotime( '-' . $threshold_date . ' days' ) );
		// ログを削除する処理を実装
		global $wpdb;

		// アクセスログを削除
		$table_name = $wpdb->prefix . 'bfb_access_log';
		$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE date < %s", $threshold_date ) );

		// クリックログを削除
		$table_name = $wpdb->prefix . 'bfb_click_log';
		$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE date < %s", $threshold_date ) );
	}
}
add_action( 'bfb_cleanup_logs_event', 'bfb_cleanup_analysis_logs' );

// プラグインが無効化されたときにクーロンジョブを削除する
function bfb_deactivation_cleanup_analysis_logs_cron() {
		wp_clear_scheduled_hook( 'bfb_cleanup_logs_event' );
}
register_deactivation_hook( __FILE__, 'bfb_deactivation_cleanup_analysis_logs_cron' );
