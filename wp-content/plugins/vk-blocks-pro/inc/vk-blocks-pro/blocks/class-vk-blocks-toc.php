<?php
/**
 * VK_Blocks_TOC class
 *
 * @package vk-blocks
 */

if ( class_exists( 'VK_Blocks_TOC' ) ) {
	return;
}

/**
 * VK_Blocks_TOC
 */
class VK_Blocks_TOC {

	/**
	 * Class instance.
	 *
	 * @var VK_Blocks_TOC|null
	 */
	private static $instance = null;

	/**
	 * Initialize hooks
	 */
	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new static();
			self::$instance->register_hooks();
		}
		return self::$instance;
	}

	/**
	 * Register hooks
	 */
	protected function register_hooks() {
		add_action( 'admin_menu', array( $this, 'add_custom_fields' ) );
		if ( ! is_admin() ) {
			add_filter( 'render_block', array( $this, 'filter_toc_block' ), 10, 3 );
		}
	}

	/**
	 * Add custom fields to VK Blocks settings page
	 */
	public function add_custom_fields() {
		add_settings_section(
			'VK_Blocks_TOC',
			__( 'Table of Contents Settings', 'vk-blocks-pro' ),
			array( $this, 'section_description' ),
			'vk-blocks-options'
		);

		add_settings_field(
			'vk_blocks_toc_heading_levels',
			__( 'Heading Levels to Include', 'vk-blocks-pro' ),
			array( $this, 'render_heading_levels_field' ),
			'vk-blocks-options',
			'VK_Blocks_TOC'
		);
	}

	/**
	 * Section description
	 */
	public function section_description() {
		echo '<p>' . esc_html__( 'Configure which heading levels should be included in the table of contents.', 'vk-blocks-pro' ) . '</p>';
	}

	/**
	 * Render heading levels field
	 */
	public function render_heading_levels_field() {
		$options        = VK_Blocks_Options::get_options();
		$current_levels = $options['toc_heading_levels'];

		// 現在の最大レベルを取得.
		$max_level = empty( $current_levels ) ? 'h2' : end( $current_levels );

		echo '<select name="vk_blocks_options[toc_heading_levels]" class="regular-text">';
		foreach ( array( 'h2', 'h3', 'h4', 'h5', 'h6' ) as $level ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $level ),
				selected( $level, $max_level, false ),
				esc_html( strtoupper( $level ) )
			);
		}
		echo '</select>';

		echo '<p class="description">' .
			esc_html__( 'Headings from H2 up to the selected level will be included.', 'vk-blocks-pro' ) .
			'</p>';
	}

	/**
	 * TOCブロックの出力をグローバル設定で調整
	 *
	 * @param string   $block_content ブロックのHTML.
	 * @param array    $block         ブロック配列.
	 * @param WP_Block $_instance     インスタンス.
	 * @return string 調整後のHTML
	 */
	public function filter_toc_block( $block_content, $block, $_instance ) {
		// $_instance パラメータは WordPress の render_block フィルターの標準的な署名のため保持
		unset( $_instance );

		// 対象ブロック以外は早期リターン
		if ( empty( $block['blockName'] ) || 'vk-blocks/table-of-contents-new' !== $block['blockName'] ) {
			return $block_content;
		}

		$options = VK_Blocks_Options::get_options();
		// true のときはそのまま、false のときは枠線ブロックを除外
		$include_border_box = isset( $options['toc_include_border_box'] ) ? (bool) $options['toc_include_border_box'] : true;
		if ( $include_border_box ) {
			return $block_content;
		}

		global $post;
		if ( empty( $post ) || empty( $post->post_content ) ) {
			return $block_content;
		}

		$ids = $this->get_border_box_heading_ids_from_content( $post->post_content );
		if ( empty( $ids ) ) {
			return $block_content;
		}

		// 目次のli要素のうち、hrefが対象IDのものを削除
		foreach ( $ids as $id ) {
			$block_content = preg_replace(
				'/<li[^>]*class="[^"]*vk_tableOfContents_list_item[^"]*"[^>]*>.*?<a[^>]*href="#' . preg_quote( $id, '/' ) . '"[^>]*>.*?<\/a>.*?<\/li>/is',
				'',
				$block_content
			);
		}

		return $block_content;
	}


	/**
	 * コンテンツから枠線ブロックの見出しアンカーIDを取得
	 *
	 * @param string $content 投稿本文.
	 * @return array 枠線ブロックのアンカーID配列
	 */
	protected function get_border_box_heading_ids_from_content( $content ) {
		$blocks = parse_blocks( $content );
		$ids    = array();

		$walk = function ( $block ) use ( &$walk, &$ids ) {
			if ( isset( $block['blockName'] ) && 'vk-blocks/border-box' === $block['blockName'] ) {
				$heading_tag = isset( $block['attrs']['headingTag'] ) ? $block['attrs']['headingTag'] : 'h4';
				if ( 'p' !== $heading_tag ) {
					if ( ! empty( $block['attrs']['anchor'] ) ) {
						$ids[] = $block['attrs']['anchor'];
					}
				}
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as $inner ) {
					$walk( $inner );
				}
			}
		};

		foreach ( $blocks as $b ) {
			$walk( $b );
		}

		return array_unique( array_filter( $ids ) );
	}

	/**
	 * The_content内のh2〜h6を抽出する共通メソッド
	 *
	 * @param string $content 投稿本文.
	 * @return array 見出し情報の配列.
	 */
	public static function get_headings_from_content( $content ) {
		$blocks   = parse_blocks( $content );
		$headings = array();

		// グローバル設定の取得（未設定時は true 扱い）
		$options                 = VK_Blocks_Options::get_options();
		$include_border_box_flag = isset( $options['toc_include_border_box'] ) ? (bool) $options['toc_include_border_box'] : true;

		// 再帰的にブロックを探索する関数
		$extract_headings = function ( $block ) use ( &$extract_headings, &$headings, $include_border_box_flag ) {
			// core/heading、vk-blocks/heading、vk-blocks/border-boxブロックを処理
			$allowed_blocks = array( 'core/heading', 'vk-blocks/heading' );
			if ( $include_border_box_flag ) {
				$allowed_blocks[] = 'vk-blocks/border-box';
			}
			if ( in_array( $block['blockName'], $allowed_blocks, true ) ) {
				$level   = 2;
				$id      = '';
				$content = '';

				if ( 'vk-blocks/border-box' === $block['blockName'] ) {
					// 枠線ブロックの場合
					$heading_tag = isset( $block['attrs']['headingTag'] ) ? $block['attrs']['headingTag'] : 'h4';

					// pタグの場合は見出しとして扱わない
					if ( 'p' === $heading_tag ) {
						return;
					}

					$level = (int) str_replace( 'h', '', $heading_tag );

					// アンカーIDを取得（anchorがある場合のみ）
					$id = ( isset( $block['attrs']['anchor'] ) && ! empty( $block['attrs']['anchor'] ) )
						? $block['attrs']['anchor']
						: '';

					// 見出しコンテンツを取得
					if ( isset( $block['attrs']['heading'] ) ) {
						$content = wp_strip_all_tags( $block['attrs']['heading'] );
					}
				} else {
					// 通常の見出しブロックの場合
					$level = isset( $block['attrs']['level'] ) ? $block['attrs']['level'] : 2;

					// IDを取得（複数のソースをチェック）
					if ( isset( $block['attrs']['anchor'] ) ) {
						$id = $block['attrs']['anchor'];
					} elseif ( isset( $block['attrs']['id'] ) ) {
						$id = $block['attrs']['id'];
					} elseif ( preg_match( '/id="([^"]+)"/', $block['innerHTML'], $matches ) ) {
						$id = $matches[1];
					}

					// コンテンツを取得
					if ( preg_match( '/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $block['innerHTML'], $matches ) ) {
						$content = wp_strip_all_tags( $matches[1] );
					}
				}

				if ( ! empty( $content ) ) {
					$headings[] = array(
						$level,
						! empty( $id ) ? ' id="' . esc_attr( $id ) . '"' : '',
						$content,
					);
				}
			}

			// インナーブロックがある場合は再帰的に処理
			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as $inner_block ) {
					$extract_headings( $inner_block );
				}
			}
		};

		// 各ブロックを処理
		foreach ( $blocks as $block ) {
			$extract_headings( $block );
		}

		return $headings;
	}
}
