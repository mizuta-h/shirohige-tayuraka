<h2>プラグインの情報</h2>

<div class="bfb_box">

<?php  
	$eol = PHP_EOL;
	$li = '- ';
	$sep = '---'.$eol;
	$output = "";

	//サイト情報
	$output .= '# サイト情報'. $eol;
	$output .= $li . 'サイト名 ： ' . get_bloginfo('name').$eol;
	$output .= $li . 'サイトURL ： ' . site_url().$eol;
	$output .= $li . 'WordPressバージョン ： ' . get_bloginfo('version').$eol;
	$output .= $li . 'PHPバージョン ： ' . phpversion().$eol;
	if (isset($_SERVER['HTTP_USER_AGENT']))	$output .= $li . 'ブラウザ ： ' . $_SERVER['HTTP_USER_AGENT'].$eol;
	if (isset($_SERVER['SERVER_SOFTWARE']))	$output .= $li . 'サーバーソフト ： ' . $_SERVER['SERVER_SOFTWARE'].$eol;
	if (isset($_SERVER['SERVER_PROTOCOL']))	$output .= $li . 'サーバープロトコル ： ' . $_SERVER['SERVER_PROTOCOL'].$eol;
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))	$output .= $li . 'エンコーディング ： ' . $_SERVER['HTTP_ACCEPT_ENCODING'].$eol;
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))	$output .= $li . '言語 ： ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'].$eol;
	$output .= $li . 'ホームページを固定ページにしている場合のID ： ' . get_option('page_on_front').$eol;
	//利用中のプラグイン
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugins = get_plugins();
	if (!empty($plugins)) {
		$output .= $sep;
		$output .= '# 利用中のプラグイン'. $eol;
		foreach ($plugins as $path => $plugin) {
			if (is_plugin_active( $path )) {
				$output .= $li . $plugin['Name'];
				$output .= ' '.$plugin['Version'].$eol;
			}
		}
	}

	//共通設定(デバイス別の設定なし)
	$output .= $sep;
	$output .= '# 共通設定'. $eol;
	foreach( $this->commonItems as $commonItem => $validates ) {
		$metadata = $this->get_metadata($commonItem);
		
		if (is_array($metadata)) {
			// 配列の場合はカンマ区切りで文字列に変換
			$metadataStr = implode(', ', $metadata);
		} else {
			// 配列でない場合はそのまま使用
			$metadataStr = $metadata;
		}
		
		$output .= $li .  $commonItem . ' ： ' . $metadataStr . $eol;
	}
	
	// デザイン項目
	$output .= $sep;
	$output .= '# BFBデザイン設定'. $eol;
	// デバイスごとの設定
	foreach( $this->devices as $device ){
		// デザイン項目
		$output .= '## BFBデザイン設定(' . $device . ')'. $eol;
		foreach( $this->bfbDesignItems as $bfbDesignItems => $validates ){
			$output .= $li . $bfbDesignItems."_".$device . ' ： ' . $this->get_metadata($bfbDesignItems."_".$device). $eol;
		}
		// デザイン項目(詳細)
		foreach( $this->designTypes as $designType ){
			$output .= '### BFBデザイン詳細(' . $designType . '-' . $device . ')'. $eol;
			foreach( $this->btnItems as $item => $validates ){
				$output .= $li . 'bfb_'.$designType.'_'.$item.'_'.$device. ' ： ' . $this->get_metadata('bfb_'.$designType.'_'.$item.'_'.$device). $eol;
			}
		}
	}

	// A/Bテスト
	$opt_for_log = new Optimize();
	$opt_datas = $opt_for_log->read_optimize();
	if( is_array($opt_datas) ){
		foreach( $opt_datas as $opt_id => $datas ){
			$output .= $sep;
			$output .= '# A/Bテスト(' . $opt_id . ')'. $eol;
			foreach( $datas as $date_key => $data_item ){
				$output .= $li . $date_key. ' ： ' . $data_item. $eol;
			}
		}
	}

	// カテゴリーに個別に設定されたBFB設定がある場合、それらを出力
	// すべてのオプションを取得
	$all_options = wp_load_alloptions();
	// オプション名に特定の文字列を含むオプションを格納する配列
	$filtered_options = array();
	// オプション名に特定の文字列を含むオプションを検索
	$search_string = 'cat_'; // 検索する文字列
	foreach ($all_options as $option_name => $option_value) {
		if (strpos($option_name, $search_string) !== false) {
			$filtered_options[$option_name] = $option_value;
		}
	}
	$output .= $sep;
	$output .= '# 個別設定を優先しているカテゴリー一覧'. $eol;
	$this_bfb_use_category = null;
	if (!empty($filtered_options)){
		// 取得したオプションを出力
		foreach ($filtered_options as $option_name => $option_value) {
			// オプション名から設定値を取得し、BFBデザイン項目・A/BテストIDを出力
			$this_category_meta = get_option($option_name);		
			// 個別設定が優先になっているか確認
			if( isset($this_category_meta['bfb_use_category']) ){	
				$this_bfb_use_category = $this_category_meta['bfb_use_category'];
			}
			if('true' === $this_bfb_use_category){
				// カテゴリーID
				$this_cat_id = substr($option_name, 4);
				// カテゴリー名
				$this_cat_name = get_cat_name($this_cat_id);
				$output .= '## ' . $this_cat_name. $eol;
				$output .= $li . 'ID ： ' . $this_cat_id. $eol;
				foreach( $this->devices as $device ){
					if( isset($this_category_meta['bfb_designType_'.$device]) ){	
						$output .= $li . 'BFBデザイン項目(' . $device .  ') ： ' . $this_category_meta['bfb_designType_'.$device]. $eol;
					}else{
						$output .= $li . 'BFBデザイン項目(' . $device .  ') ： ' . ''. $eol;
					}
					if( isset($this_category_meta['bfb_optId_'.$device]) ){
						$output .= $li . 'A/BテストID(' . $device .  ') ： ' . $this_category_meta['bfb_optId_'.$device]. $eol;
					}else{
						$output .= $li . 'A/BテストID(' . $device .  ') ： ' . ''. $eol;
					}
				}
			}
		}
	}

	// 記事やページに個別に設定されたBFB設定がある場合、それらを出力
	$args = array(
		'post_type' => 'any',  // 取得する投稿タイプ
		'posts_per_page' => -1, // 全ての記事を取得
		'meta_query' => array(
			array(
				'key' => 'bfb_use_post',     // カスタムフィールドのキー
				'value' => 'true',       // カスタムフィールドの値
				'compare' => '=',                 // 値の比較演算子
			),
		),
	);
	$output .= $sep;
	$output .= '# 個別設定を優先している記事一覧'. $eol;
	$custom_query = new WP_Query($args);
	if ($custom_query->have_posts()) {
		while ($custom_query->have_posts()) {
			$custom_query->the_post();
			$output .= '## ' . get_the_title(). $eol;
			$output .= $li . 'ID ： ' . get_the_ID(). $eol;
			$output .= $li . 'ページタイプ ： ' . get_post_type(). $eol;
			foreach( $this->devices as $device ){
				// BFBデザイン項目
				$output .= $li . 'BFBデザイン項目(' . $device .  ') ： ' . get_post_meta(get_the_ID(), 'bfb_designType_'.$device,true). $eol;
				// ABテスト情報
				$output .= $li . 'A/BテストID(' . $device .  ') ： ' . get_post_meta(get_the_ID(), 'bfb_optId_'.$device,true). $eol;
			}
		}
		wp_reset_postdata();
	} 

	?>
<pre style="white-space: pre-wrap;"><?php echo $output; ?></pre>

</div><!--bfb_box-->
