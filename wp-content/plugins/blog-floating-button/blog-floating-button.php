<?php
/*
Plugin Name: Blog Floating Button
Plugin URI: https://bfb-plugin.com/
Description: 「Blog Floating Button(BFB)」はブログ内にフロートボタンを簡単に実装できるプラグインです。
Version: 1.4.20
Author: Meril Inc.
Author URI: https://meril.co.jp/
License: GPL2
*/
?>
<?php

if ( strpos( $_SERVER['HTTP_HOST'], 'dev.' ) !== false ) {
	error_reporting( E_ALL );
}

#[AllowDynamicProperties] // PHP8.2対応
class BlogFloatingButton {

	const PLUGIN_PATH                            = __FILE__;// プラグインディレクトリパス
	public $metadate                             = array();
	private $get_license_key_status_url          = 'https://bfb-plugin.com/api/function/get_license_key_status.php';
	private $get_license_key_optimize_status_url = 'https://bfb-plugin.com/api/function/get_license_key_optimize_status.php';
	private $get_admin_ad_url                    = 'https://bfb-plugin.com/api/function/get_admin_ad.php';
	private $echo_bfb_optimize_url               = '/wp-json/bfb/api/echo_bfb_optimize';
	private $js_is_pro                           = 0;
	private $js_is_autohide                      = 0;

	public $is_activation          = false;
	public $is_activation_optimize = false;
	private $echo_bfb_num          = 0;

	public $abtest_message = '<p style="font-size:0.8em; margin-top:40px;">ABテスト稼働中は、こちらのプレビューは無効になります。該当のABテスト内でプレビューをご確認ください。<br>こちらで設定したプレビューを表示したい場合は、ABテストを「実施しない」に変更してください。</p>';

	public $devices     = array( 'pc', 'sp' );
	public $devicesName = array(
		'pc' => 'PC',
		'sp' => 'スマホ',
	);
	public $designTypes = array( 'textBtn', 'textTextBtn', 'textBtnTextBtn', 'imgBanner' );
	public $designNames = array(
		'textBtn'        => 'ボタン単体',
		'textTextBtn'    => '説明文+ボタン',
		'textBtnTextBtn' => 'ボタン+ボタン',
		'imgBanner'      => 'バナー画像',
	);
	public $btnItems    = array(
		'topText'             => array( 'max50' ), // トップテキスト
		'topTextColorPicker'  => array( 'colorCode' ), // トップテキスト色ピッカー
		'discText'            => array( 'max80' ),  // 説明文
		'discTextColorPicker' => array( 'colorCode' ),    // 説明文色ピッカー
		'btnType'             => array( 'w', 'max30' ),    // ボタン形状
		'btnText'             => array( 'max30' ),    // ボタンテキスト
		'btnTextColorPicker'  => array( 'colorCode' ), // ボタンテキスト色ピッカー
		'btnColor'            => array( 'w' ),   // ボタン色
		'btnColorPicker'      => array( 'colorCode' ), // ボタンピックカラー
		'btnColorLighten'     => array( 'num' ),  // グラデーション明度
		'linkUrl'             => array( 'url', 'max500' ), // リンク先URL
		'linkTarget'          => array( 'w' ), // リンクターゲット
		'linkRel'             => array( 'w' ),    // rel属性
		'btnText2'            => array( 'max30' ),   // ボタンテキスト2
		'btnTextColorPicker2' => array( 'colorCode' ),    // ボタンテキスト色ピッカー2
		'btnColor2'           => array( 'w' ),  // ボタン色2
		'btnColorPicker2'     => array( 'colorCode' ),    // ボタンピックカラー2
		'btnColorLighten2'    => array( 'num' ), // グラデーション明度2
		'linkUrl2'            => array( 'url', 'max500' ),    // リンク先URL
		'linkTarget2'         => array( 'w' ),    // リンクターゲット
		'linkRel2'            => array( 'w' ),   // rel属性
		'bfbBgColor'          => array( 'w' ), // 背景色
		'bfbBgColorPicker'    => array( 'colorCode' ),   // 背景ピックカラー
		'bfbPos'              => array( 'hw' ),    // 表示位置
		'bannerUrl'           => array( 'url' ), // 画像URL
		'trackingMemo'        => array( 'max200' ), // クリック測定時に使えるメモ
		'trackingMemo2'       => array( 'max200' ), // クリック測定時に使えるメモ2
	);
	// 共通設定(デバイス別の設定なし)
	public $commonItems = array(
		'bfb_exclude_toppage'            => array( 'w' ),    // トップページの表示方法
		'bfb_exclude_post_ids'           => array( 'hw' ),  // 除外投稿ID
		'bfb_license_key'                => array( 'license_key' ),  // ライセンスキー
		'bfb_license_key_optimize'       => array( 'license_key' ), // A/Bテスト専用ライセンスキー
		'bfb_mode'                       => array( 'w' ),   // 開発モード
		'bfb_autohide'                   => array( 'w' ),   // 自動非表示
		'bfb_cookie_hide_span'           => array( 'int' ), // Cookie非表示期間
		'bfb_clickAnalyze'               => array( 'w' ),   // クリック計測の有効
		'bfb_clickAnalyze_exclude_admin' => array( 'w' ),  // クリック計測の管理者除外
		'bfb_hidden_pages'               => array( 'w' ), // 非表示ページ
		'bfb_retention_period'           => array( 'w' ), // 解析レポートの保存期間
	);
	// BFBデザイン項目(デバイス別に設定)
	// bfb_designType.'_'.$device
	public $bfbDesignItems = array(
		'bfb_designType'         => array( 'w' ),
		'bfb_fontSize'           => array( 'num' ),
		'innerBfb_PaddingTop'    => array( 'num' ), // BFB全体の上下余白
		'innerBfb_PaddingLeft'   => array( 'num' ), // BFB全体の左右余白
		'topText_bottom'         => array( 'num' ), // マイクロコピー下の余白
		'bfbBtnPaddingTopBottom' => array( 'num' ),  // BFB内のボタンの上下余白
		'bfb_optId'              => array( 'w', 'max32' ), // ABテストID
		'bfb_showing_area'       => array( 'int' ), // [bfb_show]以降に表示する領域
	);
	// SCSSで使用する項目で初期値
	private $scssItems = array(
		'device'                    => 'pc',
		'designType'                => 'textBtn',
		'topTextColorPicker'        => '#fff', // トップテキスト色ピッカー
		'discTextColorPicker'       => '#fff',    // 説明文色ピッカー
		'btnTextColorPicker'        => '#fff', // ボタンテキスト色ピッカー
		'btnTextColorPicker2'       => '#fff',    // ボタンテキスト色ピッカー2
		'btnColor'                  => '#ea6103',
		'btnColor2'                 => '#009f07',
		'btnColorLighten'           => '0.1',
		'btnColorLighten2'          => '0.1',
		'bfbBgColor'                => '#000',
		'bfb_fontSize_pc'           => '14',
		'bfb_fontSize_sp'           => '14',
		'innerBfb_PaddingTop_pc'    => '10',
		'innerBfb_PaddingLeft_pc'   => '10',
		'topText_bottom_pc'         => '5',
		'bfbBtnPaddingTopBottom_pc' => '10',
		'innerBfb_PaddingTop_sp'    => '10',
		'innerBfb_PaddingLeft_sp'   => '10',
		'topText_bottom_sp'         => '5',
		'bfbBtnPaddingTopBottom_sp' => '10',
		'optimize_preview'          => 'false',
	);
	// マイクロコピーと説明文のみ許可するHTML
	// classなどは使えない
	public $allowed_html = array(
		'a'      => array(),
		'br'     => array(),
		'strong' => array(),
		'b'      => array(),
		'div'    => array(),
		'span'   => array(),
		's'      => array(),
		'em'     => array(),
	);

	public function __construct() {

		global $pagenow;

		// 管理者
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

		require_once __DIR__ . '/function.php';
		require_once __DIR__ . '/scssphp/scss.inc.php';
		require_once __DIR__ . '/optimize.php'; // 最適化テスト
		$this->opt = new Optimize();

		if ( is_admin() ) {
			$this->check_license_key();
			$this->check_license_key_optimize();
		} else {
			add_action( 'wp_footer', array( &$this, 'insertFooter' ), 999 );
		}

		$this->init_var(); // クラス変数を初期化

		// pro版にのみショートコードを登録する (v1.4.16)
		add_action( 'init', array( $this, 'bfb_register_shortcode_for_pro' ) );
	}
	// pro版にのみショートコードを登録する。タイミングの問題でアクティベーションチェックが必要。(v1.4.16)
	public function bfb_register_shortcode_for_pro() {
		if ( $this->check_license_key() ) {
			add_shortcode( 'bfb_show', 'bfb_show_position' );
			add_shortcode( 'bfb_hide', 'bfb_hide_position' );
		}
	}

	// 追跡フッターメイン
	public function insertFooter( $device = null ) {

		// エラー処理を追加 v1.4.20
		set_error_handler(
			function ( $errno, $errstr, $errfile, $errline ) {
				// すべてのエラーを無視する
				return true;
			}
		);

		global $post;

		// 管理画面ではCookieは無視
		if ( ! is_admin() ) {
			// cookieが記録されていれば、非表示
			$cookie_bfb_closed = filter_input( INPUT_COOKIE, 'bfb_closed' );
			$cookie_bfb_closed = $cookie_bfb_closed !== null ? htmlspecialchars( $cookie_bfb_closed, ENT_QUOTES, 'UTF-8' ) : '';

			if ( $cookie_bfb_closed == 'true' ) {
				return false;
			}
		}

		// 条件に応じてデータ読み込み
		$this->read_metadata_condi();

		// 管理者のみ表示
		if ( $this->get_metadata( 'bfb_mode' ) != 'open' ) {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}
		}

		// トップのみ表示で、トップ以外なら非表示
		// 管理画面では非表示にならない
		if ( isset( $this->bfb_exclude_toppage ) && $this->bfb_exclude_toppage === 'show_top_only' && is_singular() ) {
			if ( ! is_home() && ! is_front_page() ) {
				return '';
			}
		}

		// トップページの表示/非表示
		if ( is_home() || is_front_page() ) {
			if ( isset( $this->bfb_exclude_toppage ) && $this->bfb_exclude_toppage === 'hide' ) {
				return '';
			}
		} else {

			// トップページ以外の表示非表示
			// 指定投稿IDは非表示
			if ( isset( $this->bfb_exclude_post_ids ) ) {
				$bfb_exclude_post_ids = explode( ',', $this->bfb_exclude_post_ids );
				if ( count( $bfb_exclude_post_ids ) > 0 && isset( $post->ID ) ) {
					if ( in_array( strval( $post->ID ), $bfb_exclude_post_ids, true ) ) {
						return '';
					}
				}
			}
		}

		if ( ! $this->is_mobile() && $device != 'sp' ) {
			$device = 'pc';
		} else {
			$device = 'sp';
		}

		// 管理画面以外では一回でPC、SPを両方出力
		if ( ! is_admin() ) {

			// 最適化テストでは直接出力しない
			// キャッシュ対策のためajaxで出力
			$this->init_optimize( 'pc' );  // 最適化データ読み込み
			if ( empty( $this->{'bfb_optId_pc'} ) ) {
				// PC出力
				if ( isset( $this->optDatas['status'] ) && $this->optDatas['status'] != 1 ) {
					// 最適化を停止していたら通常出力
					$this->init_bfb( 'pc' );  // ボタン設定読み込み
					$this->generate_btn_html( 'pc' );  // フロートボタンHTML生成
				} elseif ( empty( $this->optDatas ) ) {
					// A/Bテストをしていなければ通常表示
					$this->init_bfb( 'pc' );  // ボタン設定読み込み
					$this->generate_btn_html( 'pc' );  // フロートボタンHTML生成
				}
			} else {
				$this->init_bfb( 'pc' );  // ボタン設定読み込み
				$this->generate_btn_html( 'pc' );  // フロートボタンHTML生成
			}

			// 初期化
			$this->optDatas = null;
			$this->init_optimize( 'sp' );  // 最適化データ読み込み
			if ( empty( $this->{'bfb_optId_sp'} ) ) {
				// スマホ出力
				// 最適化を停止していたら通常出力
				if ( isset( $this->optDatas['status'] ) && $this->optDatas['status'] != 1 ) {
					// 最適化を停止していたら通常出力
					$this->init_bfb( 'sp' );  // ボタン設定読み込み
					$this->generate_btn_html( 'sp' );  // フロートボタンHTML生成
				} elseif ( empty( $this->optDatas ) ) {
					// A/Bテストをしていなければ通常表示
					$this->init_bfb( 'sp' );  // ボタン設定読み込み
					$this->generate_btn_html( 'sp' );  // フロートボタンHTML生成
				}
			} else {
					$this->init_bfb( 'sp' );  // ボタン設定読み込み
					$this->generate_btn_html( 'sp' );  // フロートボタンHTML生成
			}
		} else {
			// 管理画面のプレビュー
			// ボタン設定を読み込み
			$this->init_bfb( $device );  // ボタン設定読み込み
			$this->generate_btn_html( $device );  // フロートボタンHTML生成
		}

		// 管理画面はスクリプトは表示しない
		if ( is_admin() ) {
			return ''; }

		if ( empty( $this->bfb_cookie_hide_span ) ) {
			$bfb_cookie_hide_span = 7;
		} else {
			$bfb_cookie_hide_span = $this->bfb_cookie_hide_span;
		}

		// pro版の時出現ポイントの領域を取得する.無料版は値は使わないがダミーとして0を入れておく (v1.4.16)
		if ( $this->check_license_key() ) {
			if ( ! empty( $this->{'bfb_showing_area_' . $device} ) ) {
				$bfb_showing_area = $this->{'bfb_showing_area_' . $device};
			} else {
				$bfb_showing_area = 300;
			}
		} else {
			$bfb_showing_area = 0;
		}

		// jsからpro版かどうかの判定できるように変数を用意 (v1.4.16)
		if ( $this->check_license_key() ) {
			$js_is_pro = 1;
		} else {
			$js_is_pro = 0;
		}

		// jsから常に表示か下にスクロール時は非表示かを判定できるように変数を用意 (v1.4.16)
		if ( isset( $this->bfb_autohide ) && $this->bfb_autohide != 'off' ) {
			$js_is_autohide = 1;
		} else {
			$js_is_autohide = 0;
		}

		// jsの内容を大幅変更 (v1.4.17)
		echo $this->delete_br(
			'
<script type="text/javascript">
jQuery(function($){
	$(window).on("load", function() {
		var windowHeight = $(window).height();
		var scrollCnt = 0;
		var startPos = 0;
		var scrollTop = 0;
		var bfb_show_pos = 0;
		var bfb_hide_pos = 99999999;
		var bfb_showing_area = ' . $bfb_showing_area . ';
		var is_autohide = ' . $js_is_autohide . ';
		var is_pro = ' . $js_is_pro . ';
		var has_show_pos = 0;
		var has_hide_pos = 0;
		
		/* bfb_showがある場合、 表示領域が有効になる*/
		if ($("#bfb_show_position").length) {
            bfb_show_pos = $("#bfb_show_position").offset().top;
            has_show_pos = 1;
        }

		/* bfb_showがある場合、 非表示にするタイミングを決める。bfb_hideを優先。*/
		if (has_show_pos == 1){
			if ($("#bfb_hide_position").length) {
				bfb_hide_pos = $("#bfb_hide_position").offset().top;
			}else{
				bfb_hide_pos = bfb_show_pos + bfb_showing_area;
			}
		}

        /* 表示/非表示を管理する関数 */
        function toggleDisplay() {
            scrollTop = $(window).scrollTop();
            scrollCnt++;
            var $contentElements = $("[id^=\'bfb_content_\']");

            if (is_autohide == 0) { 
			/* 常に表示の場合 */
                if (is_pro == 0) {
                    /* proでない場合は常に表示 */
                    $contentElements.css({"cssText": "display: block;"}).removeClass("bfb_hide");
                } else {
                    /* proの場合 */
                    if (has_show_pos) {
                        if (bfb_show_pos < (scrollTop + windowHeight) && bfb_hide_pos > (scrollTop + windowHeight)) {
                            $contentElements.css({"cssText": "display: block;"}).removeClass("bfb_hide");
                        } else {
                            $contentElements.addClass("bfb_hide");
                        }
                    } else {
                        /* has_show_posがない場合は常に表示 */
                        $contentElements.css({"cssText": "display: block;"}).removeClass("bfb_hide");
                    }
                }
            } else { 
			 /* 下にスクロール時は非表示の場合 */
                if (is_pro == 0) {
					/* pro版でない場合 */
                    if (scrollTop < startPos) {
                        $contentElements.css({"cssText": "display: block;"}).removeClass("bfb_hide");
                    } else if (10 < scrollCnt) {
                        $contentElements.addClass("bfb_hide");
                        scrollCnt = 0;
                    }
                } else {
				 /* pro版の場合 */
					if (has_show_pos) {
                        if (bfb_show_pos < (scrollTop + windowHeight) && bfb_hide_pos > (scrollTop + windowHeight)) {
                            $contentElements.css({"cssText": "display: block;"}).removeClass("bfb_hide");
                        } else {
                            $contentElements.addClass("bfb_hide");
                        }
                    }else{
						if (scrollTop < startPos) {
							$contentElements.css({"cssText": "display: block;"}).removeClass("bfb_hide");
						} else if (10 < scrollCnt) {
							$contentElements.addClass("bfb_hide");
							scrollCnt = 0;
						}
					}
                }
            }

            startPos = scrollTop;
        }

        /* 読み込み時にもチェック */
        toggleDisplay();

        /* スクロールイベントでもチェック */
        $(window).on("scroll", toggleDisplay);
	});
});
</script>

'
		);

		echo $this->delete_br(
			'

<script type="text/javascript">
jQuery(function($){

	$(document).on(\'click touchend\',\'[id^="bfb_content_"] .bfb_closed\',function(){
	    $(\'[id^="bfb_content_"]\').html("");
		$.cookie("bfb_closed", "true", { expires: ' . esc_html( $bfb_cookie_hide_span ) . ' });
	});

});
</script>

'
		);

		// 最適化テスト中
		if ( ! empty( $this->{'bfb_optId_pc'} ) || ! empty( $this->{'bfb_optId_sp'} ) ) {

			$ajax_page_type = '';
			if ( is_singular() ) {
				// 個別記事、固定ページ
				$ajax_page_type = '"page_type": "single",';
			}

			foreach ( $this->devices as $device ) {

				// 初期化
				$this->init_optimize( $device );

				if ( ! empty( $this->{'bfb_optId_' . $device} ) ) {
					// 最適化テストではキャッシュ対策のためajaxで出力

					// [bfb_show]があれば初期表示しない
					if ( ! $this->is_bfb_show() ) {
						$bfb_display_ajax = '$(\'[id^="bfb_content_"]\').css({"cssText": "display: block;"});';
					} else {
						$bfb_display_ajax = '';
					}

					$this->{'optimize_type_' . $device} = $this->optDatas['optimizeBtn']; // tracking_js.phpで使用

					echo $this->delete_br(
						'<script type="text/javascript">
						jQuery(function($){
							try{
						        jQuery.ajax({
						            url: "' . site_url( $this->echo_bfb_optimize_url ) . '",
						            type: "post",
						            data: {
						                "device": "' . $device . '",
						                "post_id": ' . $post->ID . ','
										. $ajax_page_type . '
						                "optimize_id": "' . $this->{'bfb_optId_' . $device} . '",
						                "optimizeBtn": "' . $this->{'optimize_type_' . $device} . '",
						            },
						        }).done(function(res){
		    						if(  "' . $device . '" == "sp" ){
										/*SPはPCを待機して表示*/
										var is_bfb_sp_show = setInterval(function(){
											if( jQuery(\'[id^="bfb_content_pc"]\').length ){
												jQuery("body").append(res);
						        				' . $bfb_display_ajax . '
												clearInterval(is_bfb_sp_show);
											}
										}, 1000);
									}else{
										jQuery("body").append(res);
						        		' . $bfb_display_ajax . '
									}
						        }).fail(function(res){
						        }).always(function(res){
						        });
						    }catch(e) {
						    }
						});
						</script>'
					);
				}
			}
		}

		if ( isset( $this->bfb_clickAnalyze ) && $this->bfb_clickAnalyze != 'off' ) {
			if ( $this->bfb_clickAnalyze_exclude_admin != 'on' && current_user_can( 'manage_options' ) ) {
				require_once __DIR__ . '/inc/tracking_js.php';
			} elseif ( ! current_user_can( 'manage_options' ) ) {
				require_once __DIR__ . '/inc/tracking_js.php';
			}
		}
		// エラー処理を追加 v1.4.20
		restore_error_handler();
	}

	// 変数の初期化
	protected function init_var() {

		foreach ( $this->devices as $device ) {
			foreach ( $this->designTypes as $designType ) {
				foreach ( $this->btnItems as $btnItem => $validates ) {
					$this->{'bfb' . '_' . $designType . '_' . $btnItem . '_' . $device} = '';
				}
			}
			foreach ( $this->bfbDesignItems as $bfbDesignItem => $validates ) {
				$this->{$bfbDesignItem . '_' . $device} = '';
			}
		}
	}

	// 条件に応じてデータ読み込み
	public function read_metadata_condi( $post_id = null, $post_type = null ) {

		global $post, $pagenow, $category;
		$is_read = false; // データ読み込みフラグ

		if ( isset( $post->ID ) ) {
			$post_id = $post->ID;
		}

		// 設定データの読み込み
		if ( is_singular() || $post_type == 'single' ) {
			if ( get_post_meta( $post_id, 'bfb_use_post', true ) == 'true' ) {
				// 個別記事の設定を優先
				$this->read_metadata( $post_id, 'single' );
			} elseif ( get_post_meta( $post_id, 'bfb_use_post', true ) == 'none' ) {
				// 個別記事で表示しない
				return false;
			} else {
				// 個別記事の「個別設定を優先」ではない
				$category_ids = $this->get_categoryId( $post_id );
				$i            = 0;
				if ( isset( $category_ids ) && count( $category_ids ) > 1 ) {
					// 複数カテゴリーに所属
					foreach ( $category_ids as $category_id ) {
						$categiry_meta[ $i ]                = get_option( "cat_$category_id", array() );
						$categiry_meta[ $i ]['category_id'] = $category_id;
						++$i;
					}
				} else {
					// 単一カテゴリーに所属
					$categiry_meta[ $i ] = isset( $category_ids ) ? get_option( "cat_$category_ids[0]" ) : null;

					// PHP8.1対応。＄categiry_metaがfalseの場合、配列に変換する
					if ( ! isset( $categiry_meta[ $i ] ) || ! is_array( $categiry_meta[ $i ] ) ) {
						$categiry_meta[ $i ] = array();
					}

					$categiry_meta[ $i ]['category_id'] = isset( $category_ids ) ? $category_ids[0] : null;
				}
				if ( isset( $categiry_meta ) ) {
					if ( $categiry_meta && count( $categiry_meta ) > 1 ) {
						$sortedCategories = $this->init_categoryPriority( $categiry_meta, 'bfb_categoryPriority' );
						if ( is_array( $sortedCategories[0] ) ) {
							// 複数カテゴリー選択
							foreach ( $sortedCategories as $key => $sortedCategory ) {
								// 個別設定が有効なカテゴリーがあれば
								if ( isset( $sortedCategory['bfb_use_category'] ) ) {
									if ( $sortedCategory['bfb_use_category'] === 'true' ) {
										$this->read_metadata( $sortedCategory['category_id'], 'category' );
										$is_read = true;
										break;
									} elseif ( $sortedCategory['bfb_use_category'] === 'none' ) {
										return false;
									}
								}
							}
						}
					} elseif ( isset( $categiry_meta[0]['bfb_use_category'] ) ) {
						if ( $categiry_meta[0]['bfb_use_category'] === 'true' ) {
							// 単一カテゴリー
							$this->read_metadata( $categiry_meta[0]['category_id'], 'category' );
							$is_read = true;
						} elseif ( $categiry_meta[0]['bfb_use_category'] === 'none' ) {
							return false;
						}
					}
				}

				// データ読み込まれてなければ、全体設定を読み込み
				if ( ! $is_read ) {
					$this->read_metadata();
				}
			}
		} elseif ( $pagenow == 'post.php' ) {
			// 投稿画面
			$this->read_metadata( $post_id, 'single' );
		} elseif ( $pagenow == 'term.php' ) {
			// カテゴリー設定画面
			$get_tag_ID = filter_input( INPUT_GET, 'tag_ID', FILTER_VALIDATE_INT );
			if ( $this->is_validate( $get_tag_ID, 'int' ) ) {
				$this->read_metadata( $get_tag_ID, 'category' );
			}
		} else {
			// トップページなどその他ページ
			$bfb_hidden_pages = $this->get_metadata( 'bfb_hidden_pages' );

			if ( is_array( $bfb_hidden_pages ) ) {
				if ( is_category() && in_array( 'category', $bfb_hidden_pages, true ) ) {
					return false;
				} elseif ( is_tag() && in_array( 'tag', $bfb_hidden_pages, true ) ) {
					return false;
				} elseif ( is_search() && in_array( 'search', $bfb_hidden_pages, true ) ) {
					return false;
				} elseif ( is_author() && in_array( 'author', $bfb_hidden_pages, true ) ) {
					return false;
				} elseif ( is_404() && in_array( '404', $bfb_hidden_pages, true ) ) {
					return false;
				}
			}

			$this->read_metadata();
		}
	}

	// プラグイン設定データの読み込み
	// $post_idはカテゴリーではカテゴリーID
	// $page_typeはsingle(個別記事)、category(カテゴリー)
	public function read_metadata( $post_id = null, $page_type = null ) {

		global $post, $pagenow;

		foreach ( $this->commonItems as $commonItem => $validates ) {
			$this->{$commonItem} = $this->get_metadata( $commonItem );
		}

		foreach ( $this->devices as $device ) {

			// BFB共通デザイン
			foreach ( $this->bfbDesignItems as $bfbDesignItems => $validates ) {
				$this->{$bfbDesignItems . '_' . $device} = $this->get_metadata( $bfbDesignItems . '_' . $device );
			}

			if ( $post_id && $page_type == 'single' ) {
				// 個別記事編集画面のみ
				$this->bfb_use_post                  = get_post_meta( $post_id, 'bfb_use_post', true );
				$this->{'bfb_designType_' . $device} = get_post_meta( $post_id, 'bfb_designType_' . $device, true );
				$this->{'bfb_optId_' . $device}      = get_post_meta( $post_id, 'bfb_optId_' . $device, true );

			} elseif ( $post_id && $page_type == 'category' ) {
				// カテゴリー編集
				$category_meta = get_option( "cat_$post_id" );
				if ( isset( $category_meta['bfb_use_category'] ) ) {
					$this->bfb_use_category = $category_meta['bfb_use_category'];
				} else {
					$this->bfb_use_category = '';
				}
				if ( isset( $category_meta['bfb_categoryPriority'] ) ) {
					$this->bfb_categoryPriority = $category_meta['bfb_categoryPriority'];
				} else {
					$this->bfb_categoryPriority = '';
				}
				if ( isset( $category_meta[ 'bfb_designType_' . $device ] ) ) {
					$this->{'bfb_designType_' . $device} = $category_meta[ 'bfb_designType_' . $device ];
				} else {
					$this->{'bfb_designType_' . $device} = '';
				}
				if ( isset( $category_meta[ 'bfb_optId_' . $device ] ) ) {
					$this->{'bfb_optId_' . $device} = $category_meta[ 'bfb_optId_' . $device ];
				} else {
					$this->{'bfb_optId_' . $device} = '';
				}
			} else {
				$this->{'bfb_designType_' . $device} = $this->get_metadata( 'bfb_designType_' . $device );
			}

			foreach ( $this->designTypes as $designType ) {
				foreach ( $this->btnItems as $item => $validates ) {

					if ( $post_id && $page_type == 'single' ) {
						$this->{'bfb_' . $designType . '_' . $item . '_' . $device} = get_post_meta( $post_id, 'bfb_' . $designType . '_' . $item . '_' . $device, true );
					} elseif ( $post_id && $page_type == 'category' ) {
						// カテゴリー編集
						if ( isset( $category_meta[ 'bfb_' . $designType . '_' . $item . '_' . $device ] ) ) {
							$this->{'bfb_' . $designType . '_' . $item . '_' . $device} = $category_meta[ 'bfb_' . $designType . '_' . $item . '_' . $device ];
						} else {
							$this->{'bfb_' . $designType . '_' . $item . '_' . $device} = '';
						}
					} else {
						$this->{'bfb_' . $designType . '_' . $item . '_' . $device} = $this->get_metadata( 'bfb_' . $designType . '_' . $item . '_' . $device );
					}
				}
			}
		}
	}
	// 最適化データ読み込み
	private function init_optimize( $device ) {
		// **optDatasを確実に初期化**
		$this->optDatas = array();

		// 最適化テスト中
		if ( ! empty( $this->{'bfb_optId_' . $device} ) ) {
			$this->optDatas = $this->opt->read_optimize( $this->{'bfb_optId_' . $device} )[ $this->{'bfb_optId_' . $device} ];

			$distribution_rate = $this->optDatas['distribution_rate'];

			// 振り分け率：70なら70%でメインボタン
			$opt_rand = mt_rand( 0, 100 );
			if ( $opt_rand < $distribution_rate ) {
				$this->optDatas['optimizeBtn']     = 'mainBtnDesign';
				$this->optDatas['suf_optimizeBtn'] = '_opt_mainBtn';
			} else {
				$this->optDatas['optimizeBtn']     = 'subBtnDesign';
				$this->optDatas['suf_optimizeBtn'] = '_opt_subBtn';
			}

			// ABCテスト稼働中以外ならABテストしない
			if ( $this->optDatas['status'] != 1 ) {
				$this->{'bfb_optId_' . $device} = '';
			}
		}
	}

	// 各ボタン設定の読み込み
	public function init_bfb( $device ) {

		$this->{'bfb_fontSize_' . $device} = $this->{'bfb_fontSize_' . $device}; //フォントサイズ
		$this->designType                  = $this->{'bfb_designType_' . $device}; //BFBタイプ
		$designType                        = $this->designType;

		// ボタン設定
		foreach ( $this->btnItems as $item => $validates ) {
			if ( isset( $this->{'bfb_' . $designType . '_' . $item . '_' . $device} ) ) {
				$this->{$item} = $this->{'bfb_' . $designType . '_' . $item . '_' . $device};
			} else {
				$this->{$item} = '';
			}
		}
		$this->topText = '<div class="bfb_topText">' . wp_kses( $this->topText, $this->allowed_html ) . '</div>';
	}

	// フロートボタンHTML生成
	public function generate_btn_html( $device, $preview_datas = null, $meta = null ) {

		// $imagePath                 = plugins_url( 'images/', __FILE__ );  // 画像パス
		$bfbDatas['device']        = $device;
		$bfbDatas['optimize_id']   = ''; // A/Bテスト時のみ使用
		$bfbDatas['optimize_type'] = ''; // A/Bテスト時のみ使用

		// A/Bテスト専用ライセンスキーを無効にしても、ABテストが動いてしまうことを防ぐため、A/Bテスト専用ライセンスキーを無効であればテストIDを空にする
		if ( is_admin() && ! $this->is_activation_optimize ) {
			$this->{'bfb_optId_' . $device} = '';
		}

		// 管理画面でpro版の場合はライブプレビューをONにするフラグ
		$admin_pro_flg = false;
		if ( is_admin() && $this->is_activation ) {
			$admin_pro_flg = true;
		}

		if ( ( ! $preview_datas && empty( $this->{'bfb_optId_' . $device} ) ) || $admin_pro_flg ) {
			// ライブプレビュー以外かつ最適化テスト以外
			// 本番、設定画面の初期表示
			$bfbDatas['designType'] = $this->designType;

			// フロートボタン内容
			foreach ( $this->btnItems as $item => $validates ) {
				$bfbDatas[ $item ] = $this->{$item};
			}
			// 余白等のボタンデザイン
			foreach ( $this->bfbDesignItems as $bfbDesignItem => $validates ) {
				$bfbDatas[ $bfbDesignItem . '_' . $device ] = $this->{$bfbDesignItem . '_' . $device};
			}

			// 設定画面のプレビュー
			if ( is_admin() ) {
				$bfbDatas['optimize_preview'] = 'true'; // scssの共通部分を出力
			}
		} elseif ( ! empty( $this->{'bfb_optId_' . $device} ) && $this->{'bfb_optId_' . $device} != 'false' ) {
			// 最適化テスト実施中
			// ajaxで読み込み
			// 管理画面でない(管理画面でABテストが行われていても、通常のボタンを表示する。ABテストボタンはABテストボタンページで確認してもらう)

			$this->init_optimize( $device );  // 最適化データ読み込み

			// 最適化ボタン種類は取得(アクセスログと
			// ボタンタイプ(mainBtnDesignとsubBtnDesign)を合わせるため)

			if ( ! empty( $meta['optimizeBtn'] ) && ! empty( $meta['suf_optimizeBtn'] ) ) {
				// 本番の最適化
				$optimizeBtn     = $meta['optimizeBtn'];
				$suf_optimizeBtn = $meta['suf_optimizeBtn'];
			} else {
				// 最適化が選択されているプレビュー
				$optimizeBtn     = $this->optDatas['optimizeBtn'];
				$suf_optimizeBtn = $this->optDatas['suf_optimizeBtn'];
			}

			// フロートボタン内容
			foreach ( $this->btnItems as $item => $validates ) {
				if ( isset( $this->optDatas[ 'bfb_' . $this->optDatas[ $optimizeBtn ] . '_' . $item . '_' . $device . $suf_optimizeBtn ] ) ) {
					$bfbDatas[ $item ] = $this->optDatas[ 'bfb_' . $this->optDatas[ $optimizeBtn ] . '_' . $item . '_' . $device . $suf_optimizeBtn ];
				} else {
					$bfbDatas[ $item ] = '';
				}
			}

			// 余白等のボタンデザイン
			foreach ( $this->bfbDesignItems as $bfbDesignItem => $validates ) {
				$bfbDatas[ $bfbDesignItem . '_' . $device ] = $this->{$bfbDesignItem . '_' . $device};
			}

			$bfbDatas['topText']       = '<div class="bfb_topText">' . wp_kses( $bfbDatas['topText'], $this->allowed_html ) . '</div>';
			$bfbDatas['designType']    = $this->optDatas[ $optimizeBtn ];
			$bfbDatas['optimize_id']   = $this->{'bfb_optId_' . $device};
			$bfbDatas['optimize_type'] = $optimizeBtn;

			// tracing_js.phpのクリック計測(アクセスログ)で使用
			$this->optimize_id                  = $bfbDatas['optimize_id'];
			$this->{'optimize_type_' . $device} = $bfbDatas['optimize_type'];

			// キャッシュによりABテストできないためAjaxで読み込み
			$bfbDatas['ajax_echo'] = true;

		} else {
			// 最適化テストが行われていない
			// ライブプレビュー時
			if ( isset( $preview_datas['designType'] ) ) {
				$bfbDatas['designType'] = $preview_datas['designType'];
			} else {
				return false;
			}
			// ライブプレビュー判定
			if ( isset( $preview_datas['live_preview'] ) ) {
				$bfbDatas['live_preview'] = $preview_datas['live_preview'];
			}

			foreach ( $this->btnItems as $item => $validates ) {
				if ( isset( $preview_datas[ $item ] ) ) {
					$bfbDatas[ $item ] = $preview_datas[ $item ];
				} else {
					$bfbDatas[ $item ] = '';
				}
			}
			foreach ( $this->devices as $device ) {
				foreach ( $this->bfbDesignItems as $bfbDesignItem => $validates ) {
					if ( isset( $preview_datas[ $bfbDesignItem . '_' . $device ] ) ) {
						$bfbDatas[ $bfbDesignItem . '_' . $device ] = $preview_datas[ $bfbDesignItem . '_' . $device ];
					} else {
						$bfbDatas[ $bfbDesignItem . '_' . $device ] = '';
					}
				}
			}

			$bfbDatas['topText'] = '<div class="bfb_topText">' . wp_kses( $bfbDatas['topText'], $this->allowed_html ) . '</div>';

			$bfbDatas['optimize_preview'] = 'true'; // scssの共通部分を出力

		}

		$bfbDatas['noopener']  = '';
		$bfbDatas['noopener2'] = '';
		if ( $bfbDatas['linkTarget'] == 'blank' ) {
			$bfbDatas['noopener'] = 'noopener';
		}
		if ( $bfbDatas['linkTarget2'] == 'blank' ) {
			$bfbDatas['noopener2'] = 'noopener';
		}

		// svgファイルを外部読み込みにすると、読み込み時間が異常に増えるのでコードを直接埋め込む
		$svg_path = '<svg class="bfb_icon" version="1.1" aria-hidden="true" focusable="false" id="circle-arrow" class="circle-arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px" role="img" viewBox="0 0 496 496"><path fill="currentColor" d="M256,8C393,8,504,119,504,256S393,504,256,504,8,393,8,256,119,8,256,8ZM369.9,239,234.4,103.5a23.9,23.9,0,0,0-33.9,0l-17,17a23.9,23.9,0,0,0,0,33.9L285.1,256,183.5,357.6a23.9,23.9,0,0,0,0,33.9l17,17a23.9,23.9,0,0,0,33.9,0L369.9,273A24,24,0,0,0,369.9,239Z" /></svg>';

		// ボタンHTML生成
		if ( $bfbDatas['designType'] == 'textBtn' ) {
			$btn_html = $bfbDatas['topText'] . '<a href="' . esc_url( $bfbDatas['linkUrl'] ) . '" class="bfb_btn bfb_' . esc_attr( $bfbDatas['btnColor'] ) . '" target="_' . esc_attr( $bfbDatas['linkTarget'] ) . '" rel="' . esc_attr( $bfbDatas['noopener'] ) . ' ' . esc_attr( $bfbDatas['linkRel'] ) . '" bfb-memo="' . esc_attr( $bfbDatas['trackingMemo'] ) . '" bfb-memo="' . esc_attr( $bfbDatas['trackingMemo'] ) . '" bfb-optimize-id="' . esc_attr( $bfbDatas['optimize_id'] ) . '" bfb-optimize-type="' . esc_attr( $bfbDatas['optimize_type'] ) . '">' . wp_kses( $bfbDatas['btnText'], $this->allowed_html ) . $svg_path . '</a>';
		} elseif ( $bfbDatas['designType'] == 'textTextBtn' ) {
			$btn_html = $bfbDatas['topText'] . '<div class="bfb_parts_2"><div class="bfb_discText">' . wp_kses( $bfbDatas['discText'], $this->allowed_html ) . '</div><a href="' . esc_url( $bfbDatas['linkUrl'] ) . '" class="bfb_btn bfb_' . esc_attr( $bfbDatas['btnColor'] ) . '" target="_' . esc_attr( $bfbDatas['linkTarget'] ) . '" rel="' . esc_attr( $bfbDatas['noopener'] ) . ' ' . esc_attr( $bfbDatas['linkRel'] ) . '" bfb-memo="' . esc_attr( $bfbDatas['trackingMemo'] ) . '" bfb-optimize-id="' . esc_attr( $bfbDatas['optimize_id'] ) . '" bfb-optimize-type="' . esc_attr( $bfbDatas['optimize_type'] ) . '">' . wp_kses( $bfbDatas['btnText'], $this->allowed_html ) . $svg_path . '</a></div>';
		} elseif ( $bfbDatas['designType'] == 'textBtnTextBtn' ) {
			$btn_html = $bfbDatas['topText'] . '<div class="bfb_parts_2"><a href="' . esc_url( $bfbDatas['linkUrl'] ) . '" class="bfb_btn bfb_' . esc_attr( $bfbDatas['btnColor'] ) . '"  target="_' . esc_attr( $bfbDatas['linkTarget'] ) . '" rel="' . esc_attr( $bfbDatas['noopener'] ) . ' ' . esc_attr( $bfbDatas['linkRel'] ) . '" bfb-memo="' . esc_attr( $bfbDatas['trackingMemo'] ) . '" bfb-optimize-id="' . esc_attr( $bfbDatas['optimize_id'] ) . '" bfb-optimize-type="' . esc_attr( $bfbDatas['optimize_type'] ) . '">' . wp_kses( $bfbDatas['btnText'], $this->allowed_html ) . $svg_path . '</a><a href="' . esc_url( $bfbDatas['linkUrl2'] ) . '" class="bfb_btn2 bfb_' . esc_attr( $bfbDatas['btnColor2'] ) . '" target="_' . esc_attr( $bfbDatas['linkTarget2'] ) . '" rel="' . esc_attr( $bfbDatas['noopener2'] ) . ' ' . esc_attr( $bfbDatas['linkRel2'] ) . '" bfb-memo="' . esc_attr( $bfbDatas['trackingMemo2'] ) . '" bfb-optimize-id="' . esc_attr( $bfbDatas['optimize_id'] ) . '" bfb-optimize-type="' . esc_attr( $bfbDatas['optimize_type'] ) . '">' . wp_kses( $bfbDatas['btnText2'], $this->allowed_html ) . $svg_path . '</a></div>';
		} elseif ( $bfbDatas['designType'] == 'imgBanner' ) {
			$btn_html = '<a href="' . esc_url( $bfbDatas['linkUrl'] ) . '" target="_' . esc_attr( $bfbDatas['linkTarget'] ) . '" rel="' . esc_attr( $bfbDatas['noopener'] ) . ' ' . esc_attr( $bfbDatas['linkRel'] ) . '" bfb-memo="' . esc_attr( $bfbDatas['trackingMemo'] ) . '" bfb-optimize-id="' . esc_attr( $bfbDatas['optimize_id'] ) . '" bfb-optimize-type="' . esc_attr( $bfbDatas['optimize_type'] ) . '"><img src="' . esc_url( $bfbDatas['bannerUrl'] ) . '" alt=""></a>';
		}

		// PCで最適化テストしていたら
		// SPは非表示→PCをajaxで出力
		// PCで共通部分のCSSを出力
		// CSS共通部分がないとSP表示が崩れる
		$bfb_hide = '';
		if ( ! empty( $this->{'bfb_optId_pc'} ) && $this->{'bfb_optId_pc'} != 'false' ) {
			if ( ! is_admin() ) {
				$bfb_hide = 'style="display: none !important;"';
			}
		} else {
			$bfb_hide = '';
		}

		// 投稿、固定ページのみ動作
		// 表示制御コード[bfb_show]があれば非表示
		if ( $this->is_bfb_show() ) {
			$bfb_hide = 'style="display: none !important;"';
		}

		// フロートボタン全体のHTML生成
		$echo_html = '';
		if ( $bfbDatas['designType'] != 'none' ) {
			if ( $bfbDatas['designType'] == 'textBtn' ) {
				$echo_html = '<div id="bfb_content_' . esc_attr( $bfbDatas['device'] ) . '" class="bfb_' . esc_attr( $bfbDatas['designType'] ) . ' bfb_' . esc_attr( $bfbDatas['btnType'] ) . ' bfb_view_' . esc_attr( $bfbDatas['device'] ) . '" ' . $bfb_hide . '><div class="inner_bfb"><div class="bfb_closed"><img src="' . plugin_dir_url( __FILE__ ) . 'images/closed.png" alt=""></div>' . $btn_html . '</div></div>';
			} elseif ( $bfbDatas['designType'] == 'textTextBtn' || $bfbDatas['designType'] == 'textBtnTextBtn' ) {
				$echo_html = '<div id="bfb_content_' . esc_attr( $bfbDatas['device'] ) . '" class="bfb_' . esc_attr( $bfbDatas['designType'] ) . ' bfb_' . esc_attr( $bfbDatas['btnType'] ) . ' bfb_view_' . esc_attr( $bfbDatas['device'] ) . '" ' . $bfb_hide . '><div class="inner_bfb"><div class="bfb_closed"><img src="' . plugin_dir_url( __FILE__ ) . 'images/closed.png" alt=""></div>' . $btn_html . '</div></div>';
			} elseif ( $bfbDatas['designType'] == 'imgBanner' ) {
				$echo_html = '<div id="bfb_content_' . esc_attr( $bfbDatas['device'] ) . '" class="bfb_' . esc_attr( $bfbDatas['designType'] ) . ' bfb_' . esc_attr( $bfbDatas['bfbPos'] ) . ' bfb_view_' . esc_attr( $bfbDatas['device'] ) . '" ' . $bfb_hide . '><div class="bfb_closed"><img src="' . plugin_dir_url( __FILE__ ) . 'images/closed.png" alt=""></div>' . $btn_html . '</div>';
			}
		}

		$bfb_basic_scss = $this->init_scss( $bfbDatas, __DIR__ . '/css/bfb_basic.scss' );
		$bfb_btn_scss   = $this->init_scss( $bfbDatas, __DIR__ . '/css/bfb_btn.scss' );

		$echo_html .= '<style type="text/css">';
		$echo_html .= $this->delete_br( $this->compile_scss( $bfb_basic_scss ) );
		$echo_html .= $this->delete_br( $this->compile_scss( $bfb_btn_scss ) );
		$echo_html .= '</style>';

		// 管理画面の場合
		// 最適化テスト中であればプレビューを停止。それ以外は表示
		if ( is_admin() ) {
			$current_screen = get_current_screen();
			// ABテスト結果画面ではABテスト実行中でもメッセージは出さずプレビューを出力する
			if ( $current_screen && $current_screen->id === 'blog-floating-button_page_blog-floating-button-optimize-report' ) {
				echo $echo_html;
			} elseif ( $admin_pro_flg && empty( $this->{'bfb_optId_' . $device} ) ) {
				echo $echo_html;
			} else {
				echo wp_kses_post( $this->abtest_message );
			}
		} elseif ( ( isset( $bfbDatas['live_preview'] ) && $bfbDatas['live_preview'] ) || ( isset( $bfbDatas['ajax_echo'] ) && $bfbDatas['ajax_echo'] ) ) {
				return $echo_html;
		} elseif ( empty( $this->{'bfb_optId_' . $bfbDatas['device']} ) ) {
				// ABテストでは出力しない
				// キャッシュによりABできないため
				// Ajaxで取得→出力
				echo $echo_html;
		}
	}

	private function is_bfb_show() {
		if ( is_singular() ) {
			// 表示制御コード[bfb_show]があれば非表示
			$post_content = get_the_content();
			if ( strpos( $post_content, '[bfb_show]' ) > -1 ) {
				return true;
			}
		}
		return false;
	}

	public function getUserMeta( $attr ) {
		return wp_get_current_user()->$attr;
	}

	public function add_menu_page() {

		$opt = new Optimize();

		add_menu_page( 'Blog Floating Button', 'Blog Floating Button', 'manage_options', 'blog-floating-button', array( $this, 'option_page' ), '' );
		add_submenu_page( 'blog-floating-button', '設定', '設定', 'manage_options', 'blog-floating-button', array( $this, 'option_page' ) );
		add_submenu_page( 'blog-floating-button', '解析レポート', '解析レポート', 'manage_options', 'blog-floating-button-report', array( $this, 'report_page' ) );

		if ( $this->is_activation_optimize ) {
			add_submenu_page( 'blog-floating-button', 'A/Bテスト', 'A/Bテスト', 'manage_options', 'blog-floating-button-optimize', array( $opt, 'optimize_page' ) );
			add_submenu_page( 'blog-floating-button', 'A/Bテスト結果', 'A/Bテスト結果', 'manage_options', 'blog-floating-button-optimize-report', array( $opt, 'optimize_report' ) );
		}
	}

	public function option_page( $post_id = null ) {

		global $pagenow, $hook_suffix;

		// メディアップローダー
		wp_register_script(
			'MediaUpLoader',
			plugins_url( 'js/bfb_admin.js', __FILE__ ),
			array( 'jquery' ),
			false,
			true
		);
		wp_enqueue_media();
		wp_enqueue_script( 'MediaUpLoader' );

		// BFB設定画面のみ保存
		// 個別投稿、カテゴリー画面は別関数で保存
		// bfb_save_fields(),bfb_save_category_fileds()
		if ( strpos( $hook_suffix, 'blog-floating-button' ) > -1 ) {
			// メタデータ保存
			$res = $this->save_metadata();

			if ( $res ) {
				add_action( 'admin_notices', array( $this, 'updated_message' ) );
				do_action( 'admin_notices' );
			}
		}

		if ( $pagenow == 'term.php' ) {
			// カテゴリー設定画面
			$this->read_metadata( $post_id, 'category' );
		} elseif ( $pagenow == 'post.php' ) {
			$this->read_metadata( $post_id, 'single' );
		} else {
			$this->read_metadata();
		}

		include_once 'inc/setting_main.php';
	}

	// クリック計測のレポート画面
	public function report_page() {

		include_once 'tracking.php';
		$this->report = new Tracking();

		include_once 'inc/report/report-main.php';
	}

	// 設定保存
	public function save_metadata() {

		if ( ! $_POST ) {
			return false; }

		// nonceを確認し値が正しくなければ何もしない
		if ( ! check_admin_referer( 'bfb_setting', '_wpnonce_bfb' ) ) {
			return false;
		}

		// ボタン設定の保存
		foreach ( $this->devices as $device ) {
			foreach ( $this->designTypes as $designType ) {
				foreach ( $this->btnItems as $item => $validates ) {

					$key      = 'bfb_' . $designType . '_' . $item . '_' . $device;
					$postData = filter_input( INPUT_POST, $key );

					if ( ( isset( $postData ) || $postData == '' ) && ! empty( $validates ) ) {

						$is_validate = $this->check_validation( $postData, $validates );

						if ( ! $is_validate ) {
							$this->validation_msg( $item );
						} else {
							update_option( $key, $postData );
						}
					}
				}
			}
		}

		// 共通設定の保存
		foreach ( $this->commonItems as $item => $validates ) {

			if ( $item == 'bfb_hidden_pages' ) {
				// データが配列の場合
				$postData = filter_input( INPUT_POST, $item, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			} else {
				$postData = filter_input( INPUT_POST, $item );
			}

			if ( isset( $postData ) || $postData == '' ) {
				if ( ! empty( $validates ) ) {
					$is_validate = $this->check_validation( $postData, $validates );
					if ( ! $is_validate ) {
						$this->validation_msg( $item );
					} else {
						update_option( $item, $postData );
					}
				}
			}
		}

		// ボタン余白等の保存
		foreach ( $this->devices as $device ) {
			foreach ( $this->bfbDesignItems as $item => $validates ) {

				$key      = $item . '_' . $device;
				$postData = filter_input( INPUT_POST, $key );

				if ( isset( $postData ) ) {
					if ( ! empty( $validates ) ) {
						$is_validate = $this->check_validation( $postData, $validates );
						if ( ! $is_validate ) {
							$this->validation_msg( $item );
						} else {
							update_option( $key, $postData );
						}
					}
				}
			}
		}
		return true;
	}

	public function validation_msg( $item ) {

		if ( strpos( $item, 'ColorPicker' ) > 0 ) {
			$item = 'ColorPicker';
		}

		$text_input      = 'で入力してください。';
		$text_donot_save = 'を保存できませんでした。';

		switch ( $item ) {
			case 'topText':
				$this->error_message( 'トップテキストは50文字以内' . $text_input );
				break;
			case 'ColorPicker':
				$this->error_message( 'カラーピッカーは「#」+「00AABB(6文字)」' . $text_input );
				break;
			case 'discText':
				$this->error_message( '説明文は80文字以内' . $text_input );
				break;
			case 'btnType':
				$this->error_message( 'ボタンタイプ' . $text_donot_save );
				break;
			case 'btnText':
				$this->error_message( 'ボタンの文言は30文字以内' . $text_input );
				break;
			case 'btnColor':
				$this->error_message( 'ボタンの色' . $text_donot_save );
				break;
			case 'btnColorLighten':
				$this->error_message( 'グラデーションの明度は半角数字' . $text_input );
				break;
			case 'linkUrl':
				$this->error_message( 'リンク先URLは500文字以内のURL' . $text_input );
				break;
			case 'linkTarget':
				$this->error_message( 'リンクの開き方' . $text_donot_save );
				break;
			case 'linkRel':
				$this->error_message( 'rel属性' . $text_donot_save );
				break;
			case 'btnText2':
				$this->error_message( 'ボタンの文言は30文字以内' . $text_input );
				break;
			case 'btnColor2':
				$this->error_message( 'ボタンの色' . $text_donot_save );
				break;
			case 'btnColorLighten2':
				$this->error_message( 'グラデーションの明度は半角数字' . $text_input );
				break;
			case 'linkUrl2':
				$this->error_message( 'リンク先URLは500文字以内のURL' . $text_input );
				break;
			case 'linkTarget2':
				$this->error_message( 'リンクの開き方' . $text_donot_save );
				break;
			case 'linkRel2':
				$this->error_message( 'rel属性' . $text_donot_save );
				break;
			case 'bfbBgColor':
				$this->error_message( '背景色' . $text_donot_save );
				break;
			case 'bfbPos':
				$this->error_message( '配置' . $text_donot_save );
				break;
			case 'bannerUrl':
				$this->error_message( '画像URL' . $text_donot_save );
				break;
			case 'trackingMemo':
				$this->error_message( 'クリック測定時のメモは200文字以内' . $text_input );
				break;
			case 'trackingMemo2':
				$this->error_message( 'クリック測定時のメモは200文字以内' . $text_input );
				break;
			case 'bfb_exclude_toppage':
				$this->error_message( 'トップページの表示方法' . $text_donot_save );
				break;
			case 'bfb_exclude_post_ids':
				$this->error_message( '除外記事IDは半角数字と半角カンマ「,」' . $text_input );
				break;
			case 'bfb_license_key':
				$this->error_message( 'ライセンスキー' . $text_donot_save );
				break;
			case 'bfb_license_key_optimize':
				$this->error_message( 'A/Bテスト専用ライセンスキー' . $text_donot_save );
				break;
			case 'bfb_mode':
				$this->error_message( '開発モード' . $text_donot_save );
				break;
			case 'bfb_autohide':
				$this->error_message( '自動非表示' . $text_donot_save );
				break;
			case 'bfb_cookie_hide_span':
				$this->error_message( '非表示の期間は半角数字' . $text_input );
				break;
			case 'bfb_clickAnalyze':
				$this->error_message( 'クリック計測の有効化' . $text_donot_save );
				break;
			case 'bfb_clickAnalyze_exclude_admin':
				$this->error_message( 'クリック計測の管理者除外' . $text_donot_save );
				break;
			case 'bfb_hidden_pages':
				$this->error_message( '非表示ページ' . $text_donot_save );
				break;
			case 'bfb_retention_period':
				$this->error_message( '解析レポートの保存期間' . $text_donot_save );
				break;
			case 'bfb_designType':
				$this->error_message( 'ボタンデザイン' . $text_donot_save );
				break;
			case 'bfb_fontSize':
				$this->error_message( 'フォントサイズは半角数字' . $text_input );
				break;
			case 'bfbBtnPaddingTopBottom':
				$this->error_message( 'BFB全体の上下余白は半角数字' . $text_input );
				break;
			case 'innerBfb_PaddingTop':
				$this->error_message( 'ボタンの上下余白は半角数字' . $text_input );
				break;
			case 'innerBfb_PaddingLeft':
				$this->error_message( 'ボタンの左右余白は半角数字' . $text_input );
				break;
			case 'topText_bottom':
				$this->error_message( 'マイクロコピー下の余白は半角数字' . $text_input );
				break;
			case 'bfb_optId':
				$this->error_message( 'A/Bテストの設定' . $text_input );
				break;
			case 'bfb_categoryPriority':
				$this->error_message( 'カテゴリー同士の優先度は半角数字' . $text_input );
				break;
			case 'bfb_showing_area':
				$this->error_message( '出現ポイントの領域は半角数字' . $text_input );
				break;
		}

		return false;
	}

	public function get_metadata( $key ) {
		return get_option( $key );
	}

	private function is_mobile() {
		$useragents = array(
			'iPhone',
			'iPod',
			'Android.*Mobile',
			'Windows.*Phone',
			'dream',
			'CUPCAKE',
			'blackberry*',
			'webOS',
			'incognito',
			'webmate',
		);
		$pattern    = '/' . implode( '|', $useragents ) . '/i';
		return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
	}

	// 更新メッセージ
	public function updated_message() {
		echo '<div id="setting-error-settings_updated" class="notice bfb_notice notice-success settings-error is-dismissible"> <p><strong>設定を保存しました。</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button></div>';
	}
	// エラーメッセージ
	public function error_message( $msg ) {
		echo '<div id="setting-error-settings_updated" class="error settings-error notice bfb_notice is-dismissible"><p><strong>' . $msg . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button></div>';
	}
	// PRO版ライセンスキーのチェック
	public function check_license_key() {

		$license_key = get_option( 'bfb_license_key' );

		// キャッシュされた情報を取得
		$cache = get_transient( 'bfb_license_key_check_cache' );

		// 現在の時間を取得
		$current_time = time();
		$thirty_days  = 30 * 24 * 60 * 60; // 30日(秒単位)

		// キャッシュがあり、ライセンスキーがキャッシュと同じで、キャッシュ期間が有効な場合
		if ( $cache && $cache['license_key'] === $license_key && ( $current_time - $cache['time_checked'] ) < $thirty_days ) {
			// キャッシュされた結果を返す
			$this->is_activation = $cache['is_activation'];
			return $cache['is_activation'];
		}

		// キャッシュがないもしくは期限切れもしくはキーが変更されていた場合、キャッシュを削除する
		delete_transient( 'bfb_license_key_check_cache' );

		// ライセンスキーを無効をはじく
		if ( isset( $license_key ) && ! $this->is_validate( $license_key, 'license_key' ) ) {
			$this->is_activation = false;
			return false;
		}

		// 新しいライセンスキーをサーバーに問い合わせ
		$data = array( 'body' => array( 'license_key' => $license_key ) );
		$res  = wp_remote_post( $this->get_license_key_status_url, $data );
		$body = wp_remote_retrieve_body( $res );

		$is_activation = ( $body == 'true' );
		if ( $is_activation ) {
			$this->is_activation = true;
		}

		// 新しい情報でキャッシュを更新（1週間有効）
		set_transient(
			'bfb_license_key_check_cache',
			array(
				'license_key'   => $license_key,
				'is_activation' => $is_activation,
				'time_checked'  => $current_time,
			),
			$thirty_days
		);

		return $is_activation;
	}

	// A/Bテスト専用ライセンスキーのチェック
	public function check_license_key_optimize() {

		$license_key = get_option( 'bfb_license_key_optimize' );

		// キャッシュされた情報を取得
		$cache = get_transient( 'bfb_license_key_optimize_cache' );

		// 現在の時間を取得
		$current_time = time();
		$thirty_days  = 30 * 24 * 60 * 60; // 30日(秒単位)

		// キャッシュがあり、ライセンスキーがキャッシュと同じで、キャッシュ期間が有効な場合
		if ( $cache && $cache['license_key'] === $license_key && ( $current_time - $cache['time_checked'] ) < $thirty_days ) {
			// キャッシュされた結果を返す
			$this->is_activation_optimize = $cache['is_activation'];
			return $cache['is_activation'];
		}

		// キャッシュがないもしくは期限切れもしくはキーが変更されていた場合、キャッシュを削除する
		delete_transient( 'bfb_license_key_optimize_cache' );

		if ( isset( $license_key ) && ! $this->is_validate( $license_key, 'license_key' ) ) {
			$this->is_activation_optimize = false;
			return false;
		}

		// 現在のURL
		$url = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$data = array(
			'body' => array(
				'license_key' => $license_key,
				'url'         => $url,
			),
		);
		$res  = wp_remote_post( $this->get_license_key_optimize_status_url, $data );
		$body = wp_remote_retrieve_body( $res );

		$is_activation = ( $body == 'true' );
		if ( $is_activation ) {
			$this->is_activation_optimize = true;
		}

		// 新しい情報でキャッシュを更新（1週間有効）
		set_transient(
			'bfb_license_key_optimize_cache',
			array(
				'license_key'   => $license_key,
				'is_activation' => $is_activation,
				'time_checked'  => $current_time,
			),
			$thirty_days
		);

		return $is_activation;
	}

	public function get_ad_html() {

		$data = array();
		$res  = wp_remote_post( $this->get_admin_ad_url, $data );
		$body = wp_remote_retrieve_body( $res );

		return $body;
	}

	public function genelate_color_picker( $name, $value ) {

		?>

			<input type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" >
		<?php
		wp_enqueue_script( 'wp-color-picker' );
			$data = '(function($){
		      var options = {
		          defaultColor: false,
		          change: function(event,ui){ bfb_preview(); },
		          clear: function() {},
		          hide: true,
		          palettes: true
		      };
		      $("input:text[name=' . esc_attr( $name ) . ']").wpColorPicker(options);
		  })( jQuery );';
			wp_add_inline_script( 'wp-color-picker', $data, 'after' );
	}
	private function hex2rgb( $hex ) {

		if ( substr( $hex, 0, 1 ) == '#' ) {
			$hex = substr( $hex, 1 );
		}
		if ( strlen( $hex ) == 3 ) {
			$hex = substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) . substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) . substr( $hex, 2, 1 ) . substr( $hex, 2, 1 );
		}

		return array_map( 'hexdec', array( substr( $hex, 0, 2 ), substr( $hex, 2, 2 ), substr( $hex, 4, 2 ) ) );
	}
	private function rgba_css( $rgb, $alpha = 0.85 ) {
		return 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $alpha . ')';
	}

	// SCSS
	private function init_scss( $bfbDatas, $src ) {

		switch ( $bfbDatas['btnColor'] ) {
			case 'red':
				$bfbDatas['btnColor'] = '#d00a0a';
				break;
			case 'orange':
				$bfbDatas['btnColor'] = '#ea6103';
				break;
			case 'blue':
				$bfbDatas['btnColor'] = '#0061a7';
				break;
			case 'green':
				$bfbDatas['btnColor'] = '#009f07';
				break;
			case 'yellow':
				$bfbDatas['btnColor'] = '#d6c200';
				break;
			default:
				$bfbDatas['btnColor'] = '#000';
				break;
		}
		if ( $bfbDatas['btnColorPicker'] ) {
			$bfbDatas['btnColor'] = $bfbDatas['btnColorPicker']; }

		switch ( $bfbDatas['btnColor2'] ) {
			case 'red':
				$bfbDatas['btnColor2'] = '#d00a0a';
				break;
			case 'orange':
				$bfbDatas['btnColor2'] = '#ea6103';
				break;
			case 'blue':
				$bfbDatas['btnColor2'] = '#0061a7';
				break;
			case 'green':
				$bfbDatas['btnColor2'] = '#009f07';
				break;
			case 'yellow':
				$bfbDatas['btnColor2'] = '#d6c200';
				break;
			default:
				$bfbDatas['btnColor2'] = '#000';
				break;
		}
		if ( $bfbDatas['btnColorPicker2'] ) {
			$bfbDatas['btnColor2'] = $bfbDatas['btnColorPicker2']; }

		switch ( $bfbDatas['bfbBgColor'] ) {
			case 'red':
				$bfbDatas['bfbBgColor'] = '#BF0000';
				break;
			case 'orange':
				$bfbDatas['bfbBgColor'] = '#EAA500';
				break;
			case 'blue':
				$bfbDatas['bfbBgColor'] = '#0061a7';
				break;
			case 'green':
				$bfbDatas['bfbBgColor'] = '#009f07';
				break;
			case 'yellow':
				$bfbDatas['bfbBgColor'] = '#BFB600';
				break;
			default:
				$bfbDatas['bfbBgColor'] = '#000';
				break;
		}
		if ( $bfbDatas['bfbBgColorPicker'] ) {
			$bfbDatas['bfbBgColor'] = $bfbDatas['bfbBgColorPicker']; }

		$scss = $this->get_scss( $src );

		foreach ( $this->scssItems as $item => $defaultVal ) {

			if ( ! empty( $bfbDatas[ $item ] ) && $this->is_validate( $bfbDatas[ $item ], 'css' ) ) {
				$scss = str_replace( '{{' . $item . '}}', '$' . $item . ':' . $bfbDatas[ $item ] . ';', $scss );
			} else {
				$scss = str_replace( '{{' . $item . '}}', '$' . $item . ':' . $defaultVal . ';', $scss );
			}
		}
		return $this->compile_scss( $scss );
	}
	private function get_scss( $url ) {
		ob_start();
		require $url;
		return ob_get_clean();
	}


	private function compile_scss( $data ) {
		$scss = new scssc();
		return $scss->compile( $data );
	}
	private function delete_br( $text ) {
		$text = str_replace( array( "\r\n", "\r", "\n", "\t" ), ' ', $text );
		return preg_replace( '/\s(?=\s)/', '', $text );
	}
	// カテゴリー
	private function get_categoryId( $post_ID ) {
		foreach ( ( get_the_category( $post_ID ) ) as $obj ) {
			$cat_id = $obj->term_id;
			if ( isset( $cat_id ) && $cat_id != 0 ) {
				$cat_ids[] = $cat_id;
			}
		}
		if ( isset( $cat_ids ) ) {
			return $cat_ids; }
	}
	// カテゴリー優先順位で降順に並び替え
	private function init_categoryPriority( $categories, $targetKey ) {

		foreach ( $categories as $key => $value ) {
			if ( array_key_exists( $targetKey, $value ) ) {
				if ( isset( $value[ $targetKey ] ) ) {
					$sort_keys[] = $value[ $targetKey ];
				}
			} elseif ( isset( $value[ $targetKey ] ) ) {
					$sort_keys[] = $value[ $targetKey ];
			}
		}
		if ( isset( $sort_keys ) ) {
			if ( $sort_keys && count( $sort_keys ) > 1 ) {
				array_multisort( $sort_keys, SORT_DESC, $categories );
			}
		}
		return $categories;
	}
	// バリデーション
	public function is_validate( $data, $validation = false ) {

		if ( ! $validation ) {
			return true; }
		if ( $validation != 'require' && $data == '' ) {
			return true; }

		switch ( $validation ) {
			case 'css':
				$pattern = '/^[a-zA-Z0-9#\.]*$/';
				break;
			case 'colorCode':
				$pattern = '/^#([A-Fa-f0-9]{3}){1,2}$/';
				break;
			case 'int':
				$pattern = '/^[0-9]*$/';
				break;
			case 'num':
				$pattern = '/^[0-9\-\.]*$/';
				break;
			case 'w':
				$pattern = '/^\w*$/';
				break;
			case 'hw':
				$pattern = '/^[\w\-\.,]*$/';
				break;
			case 'require':
				$pattern = '/^.+$/';
				break;
			case 'date':
				$pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';
				break;
			case 'datetime':
				$pattern = '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/';
				break;
			case 'url':
				$pattern = '/^[\w\/:%#\$&%\?\(\)~\.=\+\-]+$/';
				break;
			case 'domain':
				$pattern = '/^(?!\-)[\-0-9A-Za-z]{1,63}(?<!\-)(?:\.(?!\-)[\-0-9A-Za-z]{1,63}(?<!\-))*$/';
				break;
			case 'license_key':
				$pattern = '/^[0-9a-zA-Z]{12}-[0-9a-zA-Z]{12}$/';
				break;
			case 'opt_id':
				$pattern = '/^[0-9a-zA-Z]{32}$/';
				break;
			default:
				$pattern = '/^.*$/';
				break;
		}

		if ( strpos( $validation, 'max' ) !== false ) {
			if ( mb_strlen( $data ) > intval( ltrim( $validation, 'max' ) ) ) {
				return false;
			}
		}

		// チェックボックス
		if ( is_array( $data ) ) {
			foreach ( $data as $val ) {
				if ( ! preg_match( $pattern, $val ) ) {
					return false;
				}
			}
		} elseif ( ! preg_match( $pattern, $data ) ) {
				return false;
		}

		return true;
	}
	public function check_validation( $data, $validates ) {

		$errors = array();

		foreach ( $validates as $validate ) {
			$is_validate = $this->is_validate( $data, $validate );
			if ( ! $is_validate ) {
				return false;
			}
		}

		return true;
	}

	public function bfb_get_data( $key, $method = 'get' ) {

		if ( $method == 'get' ) {
			$postData = filter_input( INPUT_GET, $key );
			if ( ! empty( $postData ) ) {
				return $postData;
			}
		} else {
			$postData = filter_input( INPUT_POST, $key );
			if ( ! empty( $postData ) ) {
				return $postData;
			}
		}

		return '';
	}
	public function date_picker_script() {
		echo "<script type=\"text/javascript\">
		jQuery(document).ready(function ($) {
			$('.date-input').datepicker ({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				yearRange: '-2:+0' 
			});
			$('.date-input').attr('placeholder', '日付を選択してください');

		});
		</script>";
	}
}

$bfb_content = new BlogFloatingButton();