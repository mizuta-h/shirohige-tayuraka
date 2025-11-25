<?php
/**
 * VK Heqading Design
 *
 * @package VK Heqading Design
 */

if ( ! class_exists( 'VK_Headding_Design' ) ) {

	/**
	 * VK Heqading Design
	 */
	class VK_Headding_Design {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'customize_register' ) );
			add_action( 'wp_head', array( __CLASS__, 'print_heading_front_css' ), 4 );
			add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'print_heading_editor_css' ), 12 );
		}

		/**
		 * いろいろな見出しのデザインの情報を取得する関数
		 *
		 * @param string $color_key Color Key.
		 */
		public static function get_heading_style_array( $color_key = '#c00' ) {
			// ※ margin:unset; にすると編集画面で左ベタ付きになるので注意
			$reset                        = '
				color:var(--vk-color-text-body);
				background-color:unset;
				position: relative;
				border:none;
				padding:unset;
				margin-left: auto;
				margin-right: auto;
				border-radius:unset;
				outline: unset;
				outline-offset: unset;
				box-shadow: unset;
				content:none;
				overflow: unset;
			';
			$brackets_before_after_common = '
				content:"";
				position: absolute;
				top: 0;
				width: 12px;
				height: 100%;
				display: inline-block;
				margin-left:0;
			';
			$styles                       = array(
				'plain'                           => array(
					'label'  => __( 'Plain', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . 'text-align:left;',
					'before' => $reset,
					'after'  => $reset,
				),
				'plain_center'                    => array(
					'label'  => __( 'Plain ( Align center )', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . 'text-align:center;',
					'before' => $reset,
					'after'  => $reset,
				),

				'speech_balloon_fill'             => array(
					'label'  => __( 'Speech balloon fill', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:#fff;',
					'normal' => $reset . '
						background-color:var(--vk-color-primary);
						position: relative;
						padding: 0.6em 0.8em 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						color:#fff;
						border-radius:4px;
						text-align:left;
						',
					'before' => $reset . '
						content: "";
						position: absolute;
						top: auto;
						left: 40px;
						bottom: -20px;
						width: auto;
						margin-left: -10px;
						border: 10px solid transparent;
						border-top: 10px solid var(--vk-color-primary);
						z-index: 2;
						height: auto;
						background-color: transparent !important;
						',
					'after'  => $reset,
				),
				'background_fill'                 => array(
					'label'  => __( 'Background fill', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:#fff;',
					'normal' => $reset . '
						background-color:var(--vk-color-primary);
						padding: 0.6em 0.7em 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						color:#fff;
						border-radius:4px;
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'background_fill_stitch'          => array(
					'label'  => __( 'Background fill stitch', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:#fff;',
					'normal' => $reset . '
						background-color:var(--vk-color-primary);
						padding: 0.6em 0.7em 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						color:#fff;
						border-radius:4px;
						outline: dashed 1px #fff;
						outline-offset: -4px;
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'background_fill_lightgray'       => array(
					'label'  => __( 'Background fill lightgray', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						color: #333;
						background-color: #efefef;
						padding: 0.6em 0.7em 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-radius: 4px;
						',
					'before' => $reset,
					'after'  => $reset,
				),

				'topborder_background_fill_none'  => array(
					'label'  => __( 'Top border keycolor background fill none', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.5em 0 0.45em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-top: 2px solid  var(--vk-color-primary);
						border-bottom: 1px solid var(--vk-color-border-hr);
						',
					'before' => $reset,
					'after'  => $reset,
				),

				'topborder_background_fill_black' => array(
					'label'  => __( 'Top border keycolor background fill black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:#fff;',
					'normal' => $reset . '
						background-color: #191919;
						padding: 0.5em 0.7em 0.45em;
						margin-bottom:var(--vk-margin-headding-bottom);
						color: #fff;
						border-top: 2px solid  var(--vk-color-primary);
						border-bottom: 1px solid var(--vk-color-border-hr);
						',
					'before' => $reset,
					'after'  => $reset,
				),

				'double'                          => array(
					'label'  => __( 'Double', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-top: double 3px var(--vk-color-primary);
						border-bottom: double 3px var(--vk-color-primary);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'double_black'                    => array(
					'label'  => __( 'Double black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-top: double 3px var(--vk-color-border-hr);
						border-bottom: double 3px var(--vk-color-border-hr);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'double_bottomborder'             => array(
					'label'  => __( 'Double bottom border', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-bottom: double 3px var(--vk-color-primary);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'double_bottomborder_black'       => array(
					'label'  => __( 'Double bottom border black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-bottom: double 3px var(--vk-color-border-hr);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'solid'                           => array(
					'label'  => __( 'Solid', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-top: solid 1px var(--vk-color-primary);
						border-bottom: solid 1px var(--vk-color-primary);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'solid_black'                     => array(
					'label'  => __( 'Solid black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-top: solid 1px var(--vk-color-border-hr);
						border-bottom: solid 1px var(--vk-color-border-hr);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'solid_bottomborder'              => array(
					'label'  => __( 'Solid bottom border', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-bottom: solid 1px var(--vk-color-primary);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'solid_bottomborder_black'        => array(
					'label'  => __( 'Solid bottom border black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-bottom: solid 1px var(--vk-color-border-hr);
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'solid_bottomborder_leftkeycolor' => array(
					'label'  => __( 'Solid bottom border left keycolor', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-bottom: 1px solid var(--vk-color-border-hr);
						background-color:transparent;
						text-align:left;
						border-radius:0;
						',
					'before' => $reset,
					'after'  => $reset . '
						content: "";
						line-height: 0;
						display: block;
						overflow: hidden;
						position: absolute;
						left:0;
						bottom: -1px;
						width: 30%;
						border-bottom: 1px solid var(--vk-color-primary);
						margin-left: 0;
						height:inherit;
					',
				),
				'dotted_bottomborder_black'       => array(
					'label'  => __( 'Dotted bottom border black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-bottom: 1px dotted var(--vk-color-text-body);
						background-color:transparent;
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'both_ends'                       => array(
					'label'  => __( 'Both ends', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						border:none;
						display: flex;
						align-items: center;
						text-align: center;
						margin-bottom:var(--vk-margin-headding-bottom);
						padding:0;
						',
					'before' => '
						content: "";
						flex-grow: 1;
						border-bottom: 1px solid var(--vk-color-text-body);
						margin-right: 1em;
						top: unset;
						position: unset;
						width: unset;
						border-top: none;
						',
					'after'  => '
						content: "";
						flex-grow: 1;
						border-bottom: 1px solid var(--vk-color-text-body);
						margin-left: 1em;
						bottom: unset;
						position: unset;
						width: unset;
						border-top: none;
					',
				),
				'leftborder'                      => array(
					'label'  => __( 'Left border', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.6em 0.7em 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-left:solid 2px var(--vk-color-primary);
						background-color: rgba(0,0,0,0.1);
						text-align:left;
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'leftborder_nobackground'         => array(
					'label'  => __( 'Left border nobackground', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						border:none;
						padding: 0.6em 0.7em 0.55em;
						margin-bottom:var(--vk-margin-headding-bottom);
						border-left:solid 2px var(--vk-color-primary);
						background-color:transparent;
						text-align:left;
						',
					'before' => $reset,
					'after'  => $reset,
				),
				'diagonal_stripe_bottomborder'    => array(
					'label'  => __( 'Diagonal stripe bottom border', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0 0 0.7em;
						margin-bottom:var(--vk-margin-headding-bottom);
						',
					'before' => $reset,
					'after'  => $reset . '
						content:"";
						position: absolute;
						top:unset;
						left: 0;
						bottom: 0;
						width: 100%;
						height: 7px;
						background: linear-gradient(
							-45deg,
							rgba(255,255,255,0.1) 25%, var(--vk-color-primary) 25%,
							var(--vk-color-primary) 50%, rgba(255,255,255,0.1) 50%,
							rgba(255,255,255,0.1) 75%, var(--vk-color-primary) 75%,
							var(--vk-color-primary)
						);
						background-size: 5px 5px;
					',
				),
				'brackets'                        => array(
					'label'  => __( 'Brackets', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.7em;
						margin-bottom:var(--vk-margin-headding-bottom);
						text-align: center;
						',
					'before' => $brackets_before_after_common . '
						border-top: solid 1px var(--vk-color-primary);
						border-bottom: solid 1px var(--vk-color-primary);
						border-left: solid 1px var(--vk-color-primary);
						left: 0;
						',
					'after'  => $brackets_before_after_common . '
						border-top: solid 1px var(--vk-color-primary);
						border-bottom: solid 1px var(--vk-color-primary);
						border-right: solid 1px var(--vk-color-primary);
						right: 0;
						left: auto;
					',
				),
				'brackets_black'                  => array(
					'label'  => __( 'Brackets black', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0.7em;
						margin-bottom:var(--vk-margin-headding-bottom);
						text-align: center;
						',
					'before' => $brackets_before_after_common . '
						border-top: solid 1px var(--vk-color-text-body);
						border-bottom: solid 1px var(--vk-color-text-body);
						border-left: solid 1px var(--vk-color-text-body);
						margin-left:0;
						left: 0;
						',
					'after'  => $brackets_before_after_common . '
						border-top: solid 1px var(--vk-color-text-body);
						border-bottom: solid 1px var(--vk-color-text-body);
						border-right: solid 1px var(--vk-color-text-body);
						right: 0;
						left: auto;
					',
				),
				'small_bottomborder'              => array(
					'label'  => __( 'Small bottom border', 'lightning-g3-pro-unit' ),
					'inner'  => 'color:var(--vk-color-text-body);',
					'normal' => $reset . '
						padding: 0;
						text-align: center;
						background-color:transparent;
						margin-bottom: 3em;
						',
					'before' => $reset,
					'after'  => $reset . '
						content: "";
						display: inline-block;
						position: absolute;
						left: 50%;
						margin-left: -19px;
						bottom: -24px;
						top: unset;
						width: 38px;
						border-top: solid 2px var(--vk-color-primary);
					',
				),
			);
			return apply_filters( 'vk_headding_style_array', $styles );
		}

		/**
		 * Customize Register
		 *
		 * @param object $wp_customize WP_CCustomize.
		 */
		public static function customize_register( $wp_customize ) {

			global $headding_default_options;
			global $headding_selector_array;
			global $headding_customize_section;

			// カスタマイザーに表示されるタイトルなど.
			$wp_customize->add_setting(
				'vk_headding_design',
				array(
					'default' => false,
				)
			);
			$wp_customize->add_control(
				new VK_Custom_Html_Control(
					$wp_customize,
					'vk_headding_design',
					array(
						'label'            => __( 'Headding Design', 'lightning-g3-pro-unit' ),
						'section'          => $headding_customize_section,
						'type'             => 'text',
						'custom_title_sub' => '',
						'custom_html'      => __( '※ 配置する場所の背景色などの都合で適切に見えないものがあります。', 'lightning-g3-pro-unit' ),
						'priority'         => 710,

					)
				)
			);

			$choices = array(
				'none' => __( 'No setting', 'lightning-g3-pro-unit' ),
			);

			$styles = self::get_heading_style_array();
			foreach ( $styles as $key => $value ) {
				$choices[ $key ] = $value['label'];
			}

			$selectors = $headding_selector_array;
			foreach ( $selectors as $key => $value ) {
				$wp_customize->add_setting(
					'vk_headding_desigin[' . $key . '][style]',
					array(
						'default'           => $headding_default_options[ $key ]['style'],
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'sanitize_text_field',
					)
				);
				$wp_customize->add_control(
					'vk_headding_desigin[' . $key . '][style]',
					array(
						'label'    => $value['label'],
						'section'  => $headding_customize_section,
						'settings' => 'vk_headding_desigin[' . $key . '][style]',
						'type'     => 'select',
						'choices'  => $choices,
						'priority' => 710,
					)
				);
			}
		}

		/**
		 * Print Headding CSS
		 *
		 * @param array $selectors Selectors of Headding.
		 */
		public static function headding_css( $selectors ) {

			global $headding_default_options;
			global $headding_theme_options;

			$options = get_option( 'vk_headding_desigin' );
			$default = $headding_default_options;
			$options = wp_parse_args( $options, $default );
			if ( ! is_array( $options ) ) {
				return;
			}

			$dynamic_css = '';

			// キーカラーの色情報を取得.
			if ( ! empty( $headding_theme_options['color_key'] ) ) {
				$color_key = esc_html( $headding_theme_options['color_key'] );
			} else {
				$color_key = '#337ab7';
			}

			// 見出しデザインの配列データを取得.
			$styles = self::get_heading_style_array( $color_key );

			// 見出しデザインを何にしたいか 保存された値をループ.
			foreach ( $options as $option_key => $option_value ) {
				/*
				$option_key : 対象の見出し （ h2など ）
				$option_value['style'] : デザインの種類の識別名
				*/

				if ( ! empty( $option_value['style'] ) && 'none' !== $option_value['style'] ) {

					$selected_design = $option_value['style'];

					/*
					指定した標準セレクタ / innerセレクタ / ::before / ::after の順でループしながら CSSを構成する
					 */
					$selector_types = array( 'normal', 'inner', 'before', 'after' );
					foreach ( $selector_types as $selecter_key => $selecter_value ) {
						$count = 0;
						if ( isset( $selectors[ $option_key ]['selector'] ) && is_array( $selectors[ $option_key ]['selector'] ) ) {
							foreach ( $selectors[ $option_key ]['selector'] as $key => $value ) {
								// 最初のセレクタじゃない場合は セレクタの前に , を追加.
								if ( $count ) {
									$dynamic_css .= ',';
								}

								// 出力するCSSのセレクタ部分を文字列で生成.
								if ( 'normal' === $selecter_value ) {
									$dynamic_css .= $value;
								} elseif ( 'inner' === $selecter_value ) {
									$dynamic_css .= $value . ' a';
								} elseif ( 'before' === $selecter_value ) {
									$dynamic_css .= $value . '::before';
								} elseif ( 'after' === $selecter_value ) {
									$dynamic_css .= $value . '::after';
								}

								// 最初かどうかの識別用変数に 1 を追加.
								$count ++;
							}
							$dynamic_css .= ' { ' . $styles[ $selected_design ][ $selecter_value ] . '}';
						}
					}
				}
			}

			// delete before after space.
			$dynamic_css = trim( $dynamic_css );
			// convert tab and br to space.
			$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
			// Change multiple spaces to single space.
			$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

			return $dynamic_css;

		}

		/**
		 * Print Heading Front CSS
		 */
		public static function print_heading_front_css() {
			global $headding_selector_array;
			global $headding_front_hook_style;
			$dynamic_css = self::headding_css( $headding_selector_array );
			if ( ! empty( $dynamic_css ) ) {
				$dynamic_css = '/* Pro Title Design */ ' . $dynamic_css;
				wp_add_inline_style( $headding_front_hook_style, $dynamic_css );
			}
		}

		/**
		 * Print Heading Editor CSS
		 */
		public static function print_heading_editor_css() {
			global $headding_editor_hook_style;
			$headding_selector_array = array(
				'h2' => array(
					'label'    => __( 'H2', 'lightning-g3-pro-unit' ),
					'selector' => array(
						'.editor-styles-wrapper .block-editor-block-list__layout h2',
					),
				),
				'h3' => array(
					'label'    => __( 'H3', 'lightning-g3-pro-unit' ),
					'selector' => array(
						'.editor-styles-wrapper .block-editor-block-list__layout h3',
					),
				),
				'h4' => array(
					'label'    => __( 'H4', 'lightning-g3-pro-unit' ),
					'selector' => array(
						'.editor-styles-wrapper .block-editor-block-list__layout h4',
					),
				),
				'h5' => array(
					'label'    => __( 'H5', 'lightning-g3-pro-unit' ),
					'selector' => array(
						'.editor-styles-wrapper .block-editor-block-list__layout h5',
					),
				),
				'h6' => array(
					'label'    => __( 'H6', 'lightning-g3-pro-unit' ),
					'selector' => array(
						'.editor-styles-wrapper .block-editor-block-list__layout h6',
					),
				),
			);

			$dynamic_css = self::headding_css( $headding_selector_array );
			// Plain を指定すると 編集画面ではツールバーで align 指定しても text-align:left が上書きしてしまい、ツールバーの指定が負けてしまうので補正.
			$dynamic_css .= '.editor-styles-wrapper .block-editor-block-list__layout .has-text-align-center { text-align:center; }';
			$dynamic_css .= '.editor-styles-wrapper .block-editor-block-list__layout .has-text-align-right { text-align:right; }';
			if ( ! empty( $dynamic_css ) ) {
				$dynamic_css = '/* Pro Title Design */ ' . $dynamic_css;
				wp_add_inline_style( $headding_editor_hook_style, $dynamic_css );
			}
		}
	}
	new VK_Headding_Design();
}
