<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link http://wpdocs.osdn.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'LAA1668433-pzjw9o');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'LAA1668433');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'D5p8Ucui');

/** MySQL のホスト名 */
define('DB_HOST', 'mysql323.phy.lolipop.lan');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'o?4{1W"|i=uS&"2paPSkSwHgwkY-.#Oyo]g.wTs+n[,,p@#$Gk6Ogv$@Iw39&"[[');
define('SECURE_AUTH_KEY', '0`[4Z6$uCAz1vDh5.q*XD9]vkj<n"~nS/LEb5[KF&0zC&-]Bb5`O|L^;uD_^Ac1W');
define('LOGGED_IN_KEY', '>`~T*.F?96|s)4FmONA15&WK=yk2XU`N/)^G76O^_~{5-Tkhf]TqVzfeYWa&+9/^');
define('NONCE_KEY', '!Ee:/.A]P5"oqFNwZ#Ai4c0wrF[H+TrXbR?Ojo/f4kwa_68<MHA?P213zYf.J`YI');
define('AUTH_SALT', '-AaT?.UnqpbeN]5jMM6k8is8o^Z{q@HZ8/)6j~Q,%[p@!d6S~|0Q]ll*#Uf9VP1N');
define('SECURE_AUTH_SALT', 'E`P]jdI=z,1U$#(k$vq*tIy6|,.D2e@DA7FD_*.+oL{I&/qU$I:jNJ:R4:!HekKI');
define('LOGGED_IN_SALT', 'rgL?"&B:%X6~sdYVMk),m0udy$K}xb2;m#ckI4PUK/OFB<Q~_F)?5y9[raA!$Yj7');
define('NONCE_SALT', 'm,"xy>K6#}EhyyYxqY]e4g9MWbjq;D7om@3<<"eH%Z?YmqS^7C<EBfM:T@^<y<S+');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp20250612085942_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数については Codex をご覧ください。
 *
 * @link http://wpdocs.osdn.jp/WordPress%E3%81%A7%E3%81%AE%E3%83%87%E3%83%90%E3%83%83%E3%82%B0
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
