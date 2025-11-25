=== Lightning G3 Pro Unit ===
Contributors: kurudrive
Tags:
Requires at least: 6.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 0.29.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Description ==


== Changelog ==

0.29.7 ( 15.32.1 )
[ 不具合修正 ] bbPress プロフィール画面で発生する PHP Warning 修正

0.29.6 ( 15.30.4 )
[ 仕様変更 / 不具合修正 ] モバイル固定ナビにウィジェットなど配置している場合に高さを自動検出して余白を付与するように変更
[ 不具合修正 ] ヘッダー透過時の電話番号の色がキーカラーに追従するように修正

0.29.5 ( 15.30.0 )
[ 不具合修正 ] 外観 > カスタマイズ > アーカイブ設定 で、レイアウトを一旦「カード」以外に変更して保存しないと変更が反映されない不具合を修正
[ 不具合修正 ] カスタムフィールドに0を入力した時に再表示した時に消えてしまう不具合を修正（ Update CF Builder 0.2.4 ）

0.29.4 ( 15.29.4 )
[ 不具合修正 ] ヘッダー透過指定してヘッダーの高さを未指定の場合に、iPhone でスクロール時のページヘッダーの挙動を修正

0.29.3 ( 15.29.4 )
[ 不具合修正 ] ヘッダー透過時のページヘッダーの挙動を修正

0.29.2 ( 15.29.2 )
[ 不具合修正 ] 投稿リストの「テキスト1カラム」レイアウトでタクソノミーが表示されない不具合を修正

0.29.1 ( 15.29.2 )
[ 不具合修正 ] 記事詳細ページで発生する Fatal Error 修正

0.29.0 ( 15.29.2 )
[ 機能追加 ][ Lightning 詳細ページ設定 ] 新着バッジの文言と表示日数を 外観 > カスタマイズ > Lightning 詳細ページ設定 から指定できる機能を追加
[ 仕様変更 ] VK Component を composer 版を使用するように変更

0.28.0 ( 15.26.5 )
[ 仕様変更 ] ヘッダーでブロックテンプレートパーツを使用している場合はヘッダー透過機能が無効になるように変更
[ 仕様変更 ] 最低動作バージョンを WordPress 6.3 に変更
[ 不具合修正 / 仕様変更 ][ ブロックテンプレートパーツ機能 ] VK Block Patterns を有効にした事がないとブロックテンプレートパーツを制作できない不具合を修正（ 編集権限を create_vk_block_patterns から edit_theme_options に変更 ）
[ 不具合修正 ][ ヘッダー透過 ] 編集画面の Lightning デザイン設定 の 外観 > カスタマイズ のリンク先不良修正
[ 不具合修正 ][ ヘッダー透過機能 ] 色が未指定のときに発生するPHPエラー修正
[ 不具合修正 ] 新規 Lightning 環境で G3 モードなのに G3 Pro Unit が動作しないケースがあったため修正（ packagse.php 内の判定処理を lightning_is_g3() 使用に変更 ）
[ その他 ][ モバイル固定ナビ ] クリックイベントの例で書かれているコードをUA形式からGA4形式に修正
[ その他 ] Update Custom Field Builder 0.2.3

0.27.1 ( 15.26.3 )
[ 不具合修正 ] Lightning が G3 モードなのに G3 Pro Unit が動作しないケースがあったため修正

0.27.0 ( 15.26.2 )
[ 仕様変更 ] 登録されたライセンスキーが見えないように
[ 不具合修正 ] Lightning テンプレートパーツ でショートコードが効かない不具合を修正

0.26.8 ( 15.21.1 )
[ 不具合修正 ] カスタマイズ > Lightning アーカイブ設定 > 検索 で 表示タイプを「Lightning 標準」を選択しているのにカラム設定などが表示されてしまう不具合を修正

0.26.7 ( 15.21.1 )
[ 不具合修正 ] キャッチフレーズが空の時に Header Top Navigation の余白がない状態を修正

0.26.6 ( 15.21.1 )
[ その他 ] WordPress 6.5 で導入された新しい翻訳システムに対応

0.26.5 ( 15.20.1 )
[ 不具合修正 ] 通常ありえないが vk_page_header が配列じゃない場合に Fatal Error にならないように修正
[ その他 ] アクションフックリスト表示更新（ lightning_entry_footer_append ）

0.26.4 ( 15.16.0 )
[ 不具合修正 ] ブロックテンプレートパーツなど一部機能の有効・無効によるカスタマイズ画面の表示不具合修正

0.26.3 ( 15.15.1 )
[ その他 ] イレギュラーな環境で Fatal Error を引き起こす可能性があるコードを修正

0.26.2 ( 15.15.1 )
[ 不具合修正 ][ ブロックテンプレートパーツ機能追加 ] 投稿タイプ「Lightning Block Template Parts」画面でパーツが一つも登録されていない場合に案内メッセージが表示されない不具合を修正

0.26.1 ( 15.15.1 )
[ 不具合修正 ][ ブロックテンプレートパーツ機能追加 ] PHPの Warning修正

0.26.0 ( 15.15.1 )
[ 機能追加 ][ ブロックテンプレートパーツ機能追加 ] 投稿タイプ「Lightning Block Template Parts」で作成したパーツをヘッダーとフッターに配置できる機能を追加
[ 不具合修正 ] Katawara から乗り換えた場合に xxl サイズのカラム指定が効いてしまい Lightning の xl のカラムサイズが効かない不具合を修正
[ 不具合修正 ] フォントで Noto Serif と Sawarabi Mincho を選択した場合、ウェブフォントを読み込むまでゴシックになってしまう不具合を修正
[ 不具合修正 ] パンくずリストの位置指定機能がデフォルトで 有効 になっていない不具合を修正
[ Other ] UpdateChecker 5.0 -> 5.1

0.25.1 ( 15.9.3 )
[ フック名変更 ] vk_license_key_display_setting -> ltg3pro_license_key_display_setting

0.25.0 ( 15.9.3 )
[ フィルターフック追加 ] ライセンスキーの入力画面の表示・非表示を切り替える vk_license_key_display_setting フックを追加

0.24.1 ( 15.8.2 )
[ 不具合修正 ][ 見出しデザイン ] Plain を指定すると 編集画面ではツールバーで align 指定しても text-align:left が上書きしてしまい、ツールバーの指定が負けてしまうので補正.
[ その他 ][ 見出しデザイン ] 関数名のタイポ修正

0.24.0 ( 15.6.0 )
[ 仕様変更 ] ページヘッダーのサブテキストのサブテキストで HTML の使用を許可
[ 不具合修正 ] 見出しデザインの 下線 左キーカラー で 元の見出しのに R 指定があると線の最後が跳ねてしまう不具合を修正
[ その他 ] Tree Shaking の仕様変更に伴う修正
[ その他 ] 管理画面の自動テスト追加

0.23.4 ( 15.1.4 )
[ 不具合修正 ] 環境によってアップデートチェック処理でエラーになる不具合を修正


0.23.3 ( 15.1.3 )
[ その他 ] アーカイブページのページヘッダーサブタイトルにクラス名を付与

0.23.2 ( 14.23.2 )
[ その他 ] 設定画面レイアウト不具合修正（VK Admin 2.6.0）

0.23.1 ( 14.23.1 )
[ その他 ] 設定画面レイアウト不具合修正

0.23.0 ( Lightning 14.23.1 )
[ その他 ] Update VK Admin 2.4.0

0.22.2 ( Lightning 14.22.10 )
[ その他 ] カスタムフィールドビルダーの jQuery-ui を除外するフックを追加

0.22.1 ( Lightning 14.22.5 )
[ 不具合修正 ] 0.22.0  でのアクションフック表示処理不具合修正

0.22.0 ( Lightning 14.22.5 )
[ フック追加 ][ ヘッダートップ ] アクションフック lightning_header_top_container_append 追加
[ その他 ] アクションフックポイント表示処理リファクタリング

0.21.0 ( Lightning 14.22.5 )
[ 機能追加 ][ ページヘッダー ] 表示要素を「投稿タイトル」や「投稿タイトルとメタ情報」にした時にアイキャッチ画像を表示しないモードを追加

0.20.0 ( Lightning 14.22.1 )
[ 仕様変更 ] ヘッダートップの電話アイコン、お問い合わせアイコン出力部分にフィルターフック（ header_top_tel_icon / header_top_contact_icon ）を追加

0.19.11 ( Lightning 14.22.1 )
[ 不具合修正 ] 0.19.6 から発生していた フォントセレクターでフォントウェイトがないフォントを選んだ時の読み込み不具合修正

0.19.10 ( Lightning 14.21.4 )
[ 不具合修正 ] 0.19.6 から発生していた VK Blocks Pro のインストール処理不具合修正

0.19.9 ( Lightning 14.21.4 )
[ 不具合修正 ] スクロール固定ナビのレイアウト不具合修正

0.19.8 ( Lightning 14.21.3 )
[ 不具合修正 ] スクロール固定ナビが中央揃えに指定しても右によってしまう不具合を修正

0.19.7 ( Lightning 14.21.1 )
[ 不具合修正 ] スクロール固定ナビが中央揃えに指定しても右によってしまう不具合を修正

0.19.6 ( Lightning 14.21.1 )
[ その他 ] ライセンスのメッセージ表示処理・メッセージ改善
[ 不具合修正 ][ Google Web font ] フォント未選択時に発生する不要な読み込み削除

0.19.5 ( Lightning 14.21.1 )
[ 不具合修正 ][ Google Web font ] Sawarabi などフォントウェイトが１種類のみのフォントを選択した際に Warning が出る不具合を修正

0.19.4 ( Lightning 14.21.1 )
[ 不具合修正 ][ Google Web font ] Google Web Font の組み合わせによってフォントが正常に反映されない不具合を修正

0.19.1 ( Lightning 14.21.0 )
[ 不具合修正 ][ Google Web font ] 編集画面で Web Font が反映されない不具合を修正
[ 不具合修正 ][ Google Web font ] 太字と Edge での日本語表示不具合修正

0.19.0 ( Lightning 14.20.1 )
[ フック追加 ][ キャンペーンテキスト ] 本体テキストと本体背景色のフィルターフック追加
[ その他 ][ キャンペーンテキスト ] アイコンを HTML でも記述できるように変更
[ 仕様変更 ] Font Awesome ベクトルライブラリアップデート対応

0.18.1 ( Lightning 14.19.1 )
[ 不具合修正 ][ フック名表示 ] アクションフック名間違い修正（ lightning_site_body_apepend -> lightning_site_body_append ）

0.18.0 ( Lightning 14.18.2 )
[ フック追加 ][ ヘッダートップ ] ボタン部分HTML改変用のフィルターフック追加

0.17.1 ( Lightning 14.17.0 )
[ その他 ] Lightning Pro ユーザー向けのメッセージ修正

0.17.0 ( Lightning 14.17.0 )
[ 機能追加 ] パンくずの表示位置をフッター上部以外にヘッダー下、フッターウィジェット上にも指定できる機能を追加
[ 機能追加 ] パンくずの位置指定でアクションフックのプライオリティも指定できるように設定追加

0.16.0 ( Lightning 14.17.0 )
[ 機能追加 ] パンくずの表示位置をフッターに変更する機能を追加
[ 不具合修正 ][ 見出しデザイン ] Fix selector before after

0.15.1 ( Lightning 14.14.0 )
[ Bug fix ] Fix media post order modified

0.15.0 ( Lightning 14.14.0 )
[ Other ] Cope with WordPress 5.9

0.14.0 ( Lightning 14.13.0 )
[ Add Function ] 404 Page customize

0.13.2 ( Lightning 14.12.0 )
[ Bug fix ][ Page Header ] Fix PHP notice error

0.13.1 ( Lightning 14.12.0 )
[ Bug fix ][ Headding design ] Fix Footer upper widget area design not refrected properly.

0.13.0 ( Lightning 14.12.0 )
[ Add function ][ Page Header ] Add Page Header Sub Text.
[ Add function ][ Footer ] Add footer nav position.

0.12.2 ( Lightning 14.11.10 )
[ Bug fix ][ Archive Page Setting ] Remove extra input items

0.12.1 ( Lightning 14.11.7 )
[ Bug fix ][ Header top ] Fix php notice on 404

0.12.0 ( Lightning 14.11.4 )
[ Add function ][ Header top ] Add custom description

0.11.1 ( Lightning 14.11.3 )
[ Other ] Cope with old ssl

0.11.0 ( Lightning 14.11.3 )
[ Specification Change ][ Header Top ] Header Top Menu disply change to only 1 depth

0.10.1 ( Lightning 14.11.2 )
[ Bug fix ][ Media Posts ] Fix archive post count on wooCommerce

0.10.0 ( Lightning 14.11.0 )
[ Add function ][ Header Setting ] Add mobile header logo position
[ Specification Change ][ Lightning Design  ] Move the text color setting to Color Panel from Lightning Design Setting

0.9.5 ( Lightning 14.7.0 )
[ Bug fix ][ Page Header ] Fix PHP notice on 404 page

0.9.4 ( Lightning 14.7.0 )
[ Bug fix ] Fix WordPress 5.8 Widget Screen

0.9.3 ( Lightning 14.6.1 )
[ Bug fix ] Fix php error

0.9.2 ( Lightning 14.6.0 )
[ Bug fix ][ Font selector ] Fix php error

0.9.1 ( Lightning 14.5.12 )
[ Bug fix ][ Header Trans ] Fix can't trans setting from 0.9.0

0.9.0 ( Lightning 14.5.12 )
[ Add function ][ Header trans ] Add header trans mode

0.8.1 ( Lightning 14.5.12 )
[ Other ][ Design Preset ] Cope with reset bg texture

0.8.0 ( Lightning 14.5.12 )
[ Add Function ][ Hidden Function ] Can be hide mobile btn and mobile fix nav

0.7.5 ( Lightning 14.5.10 )
[ Bug fix ][ Header Trans ] If low opacity that contact tel menu change to #fff
[ Other ][ Design Preset ] Design tuning

0.7.4 ( Lightning 14.5.10 )
[ Bug fix ][ Design Preset ] Fix design preset php notice

0.7.3 ( Lightning 14.5.10 )
[ Bug fix ][ Header Nav Vertical ] Fix design bug on firefox and safari

0.7.2 ( Lightning 14.5.10 )
[ Bug fix ] fix php notice on customize preview

0.7.1 ( Lightning 14.5.10 )
[ Design specification change ] change header menu vertical padding

0.7.0 ( Lightning 14.5.9 )
[ Add Function ] Design Preset system
[ Add Function ][ Header Trans ] Cope with all page

0.6.4 ( Lightning 14.5.5 )
[ Bug fix ][ Page Header ] Fix page header php error

0.6.3 ( Lightning 14.5.5 )
[ Bug fix ][ Page Header ] Fix page header cover opacity bug

0.6.2 ( Lightning 14.5.5 )
[ Bug fix ][ Page Header ] Fix page header cover opacity bug

0.6.1 ( Lightning 14.5.5 )
[ Bug fix ][ Page Header ] Fix page header cover opacity bug

0.6.0 ( Lightning 14.5.2 )
[ Add Function ][ Single Page Setting ] Add Hidden Element setting

0.5.1 ( Lightning 14.5.0 )
[ Bug fix ][ Page Header ] Fix Back fround image logic.

0.5.0 ( Lightning 14.5.0 )
[ Add Function ][ Page Header ] Add Page Header Background-image fix setting.
[ Add Function ][ Campain Text ] Add Background stripe pattern.

0.4.1 ( Lightning 14.5.0 )
[ Bug fix ] Add Header Layout CSS Version

0.4.0 ( Lightning 14.5.0 )
[ Add Function ][ Header Layout ] Cope with vertical menu
[ Cope with WP 5.8 ][ Mobile Fix Nav ] change html hook point

0.3.5 ( Lightning 14.3.1 )
[ Bug fix ][ Headding Design ] Fix Block style defeat by Headding design

0.3.4 ( Lightning 14.3.1 )
[ Specification Change ] Update VK Admin Library.

0.3.3 ( Lightning 14.3.1 )
[ Specification Change ] Update VK Admin Library.

0.3.2 ( Lightning 14.3.1 )
[ Specification Change ] Update VK Admin Library.

0.3.1 ( Lightning 14.3.1 )
[ Bug fix ][ Search result ] Fix search result ( with no post type specified ) layout
[ Bug fix ][ Archive ] Fix default customize layput
[ Bug fix ][ Header Trans ] Fix some colors bug

0.3.0 ( Lightning 14.3.0 )
[ Specification Change ][ Search result ] If the search condition includes the "post type" as not only the keyword search, change top the settings specified in the "post type" will be applied.

0.2.9 ( Lightning 14.1.9 )
[ Bug fix ][ Header Top ] Fix customize error

0.2.8 ( Lightning 14.1.9 )
[ Bug fix ][ Header Top ] Fix customize error

0.2.7 ( Lightning 14.1.6 )
[ Bug fix ][ Page Header ] Fix php error in case of filter search no post type selected

0.2.6 ( Lightning 14.1.6 )
[ Bug fix ][ Page Header ] Fix Page Header Image Bug

0.2.5 ( Lightning 14.1.0 )
[ Bug fix ][ Header Color / Layout ] fix header setting function

0.2.4
[ Bug fix ][ Header scrolled layout ] fix header fix system

0.2.3
[ Bug fix ][ Header scrolled layout ] fix header fix system

0.2.2
[ Bug fix ][ Header scrolled layout ] fix logo appears on mobile screen

0.2.1
[ Spacification change ][ Header scrolled layout ] can be click logo

0.2.0
[ Add Function ] Add Global Nav scrolled layout

0.1.5
[ Inprovement ][ Header Top ] In case of not set the header top color that inherit header color.
[ Bug fix ] Fix Header trans text align auto adjustment.

0.1.4
[ Bug fix ] Fix scrolled g-nav bg color

0.1.3
[ Bug fix ] Fix Header sub widget area not work

0.1.2
[ Bug fix ] Update checker

0.1.1
[ Bug fix ] Developer tool not work
[ Bug fix ] Delete tite under case of display post title on page header
[ Bug fix ] Add VK Blocks Pro installer
[ Bug fix ] Delete page title under case of page header hidden
[ Bug fix ] Fix Developer mode ( without ExUnit )

0.1.0
[ Add Function ] Header Nav color setting
[ Add Function ] Develop mode
[ Other ] speedtuning

= 0.0.1 =
First release

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.
