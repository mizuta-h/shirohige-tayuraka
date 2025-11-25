<?php
class LTG3_Preset_Manager {

	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'run_filter_on_preview_page' ) );
		add_action( 'update_option_lightning_design_preset', array( $this, 'refrect_preset_data' ) );
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	/**
	 * レイアウトやフォントなどのプリセット
	 */
	public static function get_base_presets() {
		$theme_slug   = get_option( 'stylesheet' );
		$fonts        = array(
			'hlogo' => 'Noto+Sans+JP:700',
			'menu'  => 'Noto+Sans+JP:700',
			'title' => 'Noto+Sans+JP:700',
			'text'  => 'Noto+Sans+JP:400',
		);
		$base_presets = array(
			'business_01' => array(
				'label'   => __( 'STD Business 01', 'lightning-g3-pro-unit' ),
				'options' => array(
					'lightning_theme_options'        => array(
						'header_layout'         => 'nav-float',
						'g_nav_scrolled_layout' => 'logo-and-nav-container',
					),
					'lightning_header_top_options'   => array(
						'header_top_hidden'     => false,
						'header_top_tel_hidden' => false,
						'header_top_btn_hidden' => false,
						'header_top_hidden_menu_and_contact' => false,
					),
					'vk_campaign_text'               => array(
						'display_position' => 'header_append',
					),
					'lightning_header_trans_options' => array(
						'trans_mode' => 'none',
					),
					'vk_font_selector'               => $fonts,
				),
			),
			'business_02' => array(
				'label'   => __( 'STD Business 02', 'lightning-g3-pro-unit' ),
				'options' => array(
					'lightning_theme_options'        => array(
						'header_layout'         => 'head-sub-contact_and_nav-penetration',
						'g_nav_scrolled_layout' => 'logo-and-nav-container',
					),
					'lightning_header_top_options'   => array(
						'header_top_hidden'     => false,
						'header_top_tel_hidden' => true,
						'header_top_btn_hidden' => true,
						'header_top_hidden_menu_and_contact' => false,
					),
					'vk_campaign_text'               => array(
						'display_position' => 'header_append',
					),
					'lightning_header_trans_options' => array(
						'trans_mode' => 'none',
					),
					'vk_font_selector'               => $fonts,
				),
			),
			'blog_01'     => array(
				'label'   => __( 'STD Blog 01', 'lightning-g3-pro-unit' ),
				'options' => array(
					'lightning_theme_options'        => array(
						'header_layout'         => 'center',
						'g_nav_scrolled_layout' => 'nav-center',
					),
					'lightning_header_top_options'   => array(
						'header_top_hidden'     => false,
						'header_top_tel_hidden' => false,
						'header_top_btn_hidden' => false,
						'header_top_hidden_menu_and_contact' => true,
					),
					'vk_campaign_text'               => array(
						'display_position' => 'header_append',
					),
					'lightning_header_trans_options' => array(
						'trans_mode' => 'none',
					),
					'vk_font_selector'               => $fonts,
				),
			),
		);
		// theme_mods 書き換え用に $theme_slug も filter に渡している.
		return apply_filters( 'lightning_base_presets', $base_presets, $theme_slug );
	}

	/**
	 * 色のプリセット
	 *
	 * 注意 : theme_mods_ の方は # 不要
	 */
	public static function get_color_presets() {
		$theme_slug                              = get_option( 'stylesheet' );
		$lightning_header_trans_options_standard = array(
			'enable' => false,
		);
		$page_header_white                       = array(
			'common' => array(
				'text_color'    => '#333',
				'cover_color'   => '#fff',
				'cover_opacity' => 0.9,
				'height_min'    => 10,
				'image_fixed'   => 'fixed',
			),
			'post'   => array(
				'text_color'    => '#333',
				'cover_color'   => '#fff',
				'cover_opacity' => 0.9,
			),
			'page'   => array(
				'text_color'    => '#333',
				'cover_color'   => '#fff',
				'cover_opacity' => 0.9,
			),
		);
		$page_header_black                       = array(
			'common' => array(
				'text_color'    => '#fff',
				'cover_color'   => '#000',
				'cover_opacity' => 0.7,
				'height_min'    => 10,
				'image_fixed'   => 'fixed',
			),
			'post'   => array(
				'text_color'    => '#fff',
				'cover_color'   => '#000',
				'cover_opacity' => 0.7,
			),
			'page'   => array(
				'text_color'    => '#fff',
				'cover_color'   => '#000',
				'cover_opacity' => 0.7,
			),
		);

		$colors = array(
			'normal'              => array(
				'label'   => __( '標準 白 / キーカラー / 灰' ),
				'options' => array(
					'lightning_theme_options'        => array(
						'color_header_bg'       => '#fff',
						'color_global_nav_bg'   => '#fff',
						'global_nav_border_top' => true,
						'bg_texture'            => '',
					),
					'lightning_header_top_options'   => array(
						'header_top_background_color'    => '#fcfcfc',
						'header_top_text_color'          => '',
						'header_top_border_bottom_color' => '#f5f5f5',
					),
					'vk_page_header'                 => $page_header_white,
					'lightning_header_trans_options' => array(
						'enable'             => 'normal',
						'background_color'   => '',
						'background_opacity' => '',
						'text_color'         => '',
					),
					'vk_campaign_text'               => array(
						'display_position'      => 'header_append',
						'main_background_color' => '#dd9933',
					),
					'vk_footer_option'               => array(
						'footer_background_color' => '#F3F3F3',
						'footer_text_color'       => '#333',
					),
					'vkExUnit_sns_options'           => array(
						'snsBtn_color' => '',
					),
					'theme_mods_' . $theme_slug      => array(
						'background_image' => '',
						'background_color' => 'ffffff',
					),
				),
			),
			'normal_black'        => array(
				'label'   => __( '標準 白 / キーカラー / 黒' ),
				'options' => array(
					'lightning_theme_options'        => array(
						'color_header_bg'       => '#fff',
						'color_global_nav_bg'   => '#fff',
						'global_nav_border_top' => true,
						'bg_texture'            => '',
					),
					'lightning_header_top_options'   => array(
						'header_top_background_color'    => '#fcfcfc',
						'header_top_text_color'          => '',
						'header_top_border_bottom_color' => '#f5f5f5',
					),
					'vk_page_header'                 => $page_header_black,
					'lightning_header_trans_options' => array(
						'enable'             => 'normal',
						'background_color'   => '',
						'background_opacity' => '',
						'text_color'         => '',
					),
					'vk_campaign_text'               => array(
						'display_position'      => 'header_append',
						'main_background_color' => '#dd9933',
					),
					'vk_footer_option'               => array(
						'footer_background_color' => '#000',
						'footer_text_color'       => '#ccc',
					),
					'vkExUnit_sns_options'           => array(
						'snsBtn_color' => '',
					),
					'theme_mods_' . $theme_slug      => array(
						'background_image' => '',
						'background_color' => 'ffffff',
					),
				),
			),
			'normal_header_trans' => array(
				'label'   => __( '標準 ヘッダー白透過 / キーカラー / 灰' ),
				'options' => array(
					'lightning_theme_options'        => array(
						'color_header_bg'           => '#000',
						'color_global_nav_bg'       => '#000',
						'global_nav_border_top'     => true,
						'top_slide_text_color_1'    => '#fff',
						'top_slide_cover_color_1'   => '#000',
						'top_slide_cover_opacity_1' => 70,
						'bg_texture'                => '',
					),
					'lightning_header_top_options'   => array(
						'header_top_background_color'    => '#fcfcfc',
						'header_top_text_color'          => '',
						'header_top_border_bottom_color' => '#f5f5f5',
					),
					'vk_page_header'                 => $page_header_black,
					'lightning_header_trans_options' => array(
						'enable'             => 'all',
						'background_color'   => '#fff',
						'background_opacity' => 0.1,
						'text_color'         => '#fff',
					),
					'vk_campaign_text'               => array(
						'display_position'      => 'header_prepend',
						'main_background_color' => '#dd9933',
					),
					'vk_footer_option'               => array(
						'footer_background_color' => '#F3F3F3',
						'footer_text_color'       => '#333',
					),
					'vkExUnit_sns_options'           => array(
						'snsBtn_color' => '',
					),
					'theme_mods_' . $theme_slug      => array(
						'background_image' => '',
						'background_color' => 'ffffff',
					),
				),
			),
		);
		// theme_mods 書き換え用に $theme_slug も filter に渡している.
		return apply_filters( 'lightning_color_presets', $colors, $theme_slug );
	}

	public static function check_parm_exist( $all_array, $reference_key ) {

		$error = array();
		// 必須じゃないサブ項目.
		$exclude_sub_keys = array( 'color_key', 'image', 'image_sp', 'top_slide_text_color_1', 'top_slide_cover_color_1', 'top_slide_cover_opacity_1' );
		// 全てのプリセットをループ.
		foreach ( $all_array as $preset_key => $checking_preset ) {
			// プリセットのオプション項目をループ.
			foreach ( $checking_preset['options'] as $checking_option_name => $option_value ) {
				// 基準プリセットに 検証中のオプションが存在するかどうか.
				if ( ! isset( $all_array[ $reference_key ]['options'][ $checking_option_name ] ) ) {
					$error[] = '基準プリセット ' . $reference_key . ' に オプション項目 ' . $checking_option_name . ' がありません';
				}
				// オプションの中の配列項目をループ.
				if ( is_array( $option_value ) ) {
					foreach ( $option_value as $child_key => $child_value ) {

						// 基準プリセットに 検証中のオプション副項目が存在するかどうか.
						if ( ! in_array( $child_key, $exclude_sub_keys ) ) {
							if ( ! isset( $all_array[ $reference_key ]['options'][ $checking_option_name ][ $child_key ] ) ) {
								$error[] = '基準プリセット ' . $reference_key . ' に オプション項目 ' . $checking_option_name . ' の副項目 ' . $child_key . ' がありません';
							}
						}
					}
				}
			}
			// 基準プリセットをループ.
			foreach ( $all_array[ $reference_key ]['options'] as $reference_option_name => $option_value ) {
				// 検証中のプリセットに 基準プリセットに存在するオプションが存在するかどうか
				if ( ! isset( $all_array[ $preset_key ]['options'][ $reference_option_name ] ) ) {
					$error[] = $preset_key . ' に オプション項目 ' . $reference_option_name . ' がありません';
				}
				// オプションの中の配列項目をループ.
				if ( is_array( $option_value ) ) {
					foreach ( $option_value as $child_key => $child_value ) {
						// 検証中のプリセットに 基準プリセットのオプション副項目が存在するかどうか.
						if ( ! in_array( $child_key, $exclude_sub_keys ) ) {
							if ( ! isset( $all_array[ $preset_key ]['options'][ $reference_option_name ][ $child_key ] ) ) {
								$error[] = $preset_key . ' に オプション項目 ' . $reference_option_name . ' の副項目 ' . $child_key . ' がありません';
							}
						}
					}
				}
			}
		}
		return $error;
	}

	public static function get_all_design_presets() {

		$base_presets = self::get_base_presets();
		$colors       = self::get_color_presets();

		$generated_pattens = array();
		foreach ( $base_presets as $base_preset_key => $base_preset_value ) {
			foreach ( $colors as $color_key => $color_value ) {

				$key                                = $base_preset_key . '_' . $color_key;
				$generated_pattens[ $key ]['label'] = $base_preset_value['label'] . ' ' . $color_value['label'];

				$options = wp_parse_args( $base_preset_value['options'], $color_value['options'] );

				// パターンのオプション項目をループする.
				foreach ( $base_preset_value['options'] as $option_name => $value_array ) {
					// 各オプションに入っている個別の設定値をループする
					foreach ( $value_array as $value_key => $value ) {
						$options[ $option_name ][ $value_key ] = $value;
					}
				}
				// カラーのオプション項目をループする.
				foreach ( $color_value['options'] as $option_name => $value_array ) {
					// 各オプションに入っている個別の設定値をループする.
					foreach ( $value_array as $value_key => $value ) {
						$options[ $option_name ][ $value_key ] = $value;
					}
				}
				$generated_pattens[ $key ]['options'] = $options;
			}
		}
		return $generated_pattens;
	}

	/**
	 * 選択された 基本プリセット と カラープリセット の値を結合して返す
	 */
	public static function get_generated_preset_options_array() {

		// 保存されているされているプリセットを取得.
		$preset      = get_option( 'lightning_design_preset' );
		$saved_base  = '';
		$saved_color = '';

		if ( isset( $preset['base'] ) ) {
			$saved_base = $preset['base'];
		}
		if ( isset( $preset['color'] ) ) {
			$saved_color = $preset['color'];
		}

		// プリセット配列を取得.
		$base_presets   = self::get_base_presets();
		$colors_presets = self::get_color_presets();

		$options = array();

		// パターンのオプション項目をループする.
		if ( ! empty( $base_presets[ $saved_base ]['options'] ) && is_array( $base_presets[ $saved_base ]['options'] ) ) {
			foreach ( $base_presets[ $saved_base ]['options'] as $option_name => $value_array ) {
				// 各オプションに入っている個別の設定値をループする.
				if ( ! empty( $value_array ) && is_array( $value_array ) ) {
					foreach ( $value_array as $value_key => $value ) {
						$options[ $option_name ][ $value_key ] = $value;
					}
				}
			}
		}

		// カラーのオプション項目をループする.
		if ( ! empty( $colors_presets[ $saved_color ]['options'] ) && is_array( $colors_presets[ $saved_color ]['options'] ) ) {
			foreach ( $colors_presets[ $saved_color ]['options'] as $option_name => $value_array ) {
				// 各オプションに入っている個別の設定値をループする.
				if ( ! empty( $value_array ) && is_array( $value_array ) ) {
					foreach ( $value_array as $value_key => $value ) {
						$options[ $option_name ][ $value_key ] = $value;
					}
				}
			}
		}
		return apply_filters( 'generated_preset_options_array', $options );
	}

	/**
	 * プレビュー画面で特定のフィルターの値のみ書き換え処理実行
	 *
	 * @param array  $option_value : フィルターで受け取るオプション値.
	 * @param string $option_name : フィルターで受け取るオプション名.
	 * */
	public static function change_preview_options( $option_value, $option_name ) {

		// 生成されたプリセットのオプション配列を取得.
		$current_presets = self::get_generated_preset_options_array();

		// プリセット指定がある場合.
		if ( ! empty( $current_presets ) && is_array( $current_presets[ $option_name ] ) ) {
			foreach ( $current_presets[ $option_name ] as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $child_key => $child_value ) {
						$option_value[ $key ][ $child_key ] = $child_value;
					}
				} else {
					// 受け取った元のoption値から上書き値が存在するものだけ書き換え.
					$option_value[ $key ] = $value;
				}
			}
		}
		return $option_value;
	}

	/**
	 * プレビュー画面で値を差し替えるフックを走らせる
	 */
	public static function run_filter_on_preview_page() {
		if ( is_customize_preview() ) {

			$current_presets = self::get_generated_preset_options_array();

			if ( $current_presets ) {
				foreach ( $current_presets as $option_name => $value ) {
					add_filter( 'option_' . $option_name, array( __CLASS__, 'change_preview_options' ), 10, 2 );
				}
			}
		}
	}

	/**
	 * refrect_preset_data()
	 *
	 * プリセットを元に options値を書き換える
	 * update_option の最後で改めプリセットの値でオプション上書き処理をする
	 * 上書きしたらプリセットの値を未設定に戻す
	 */
	public static function refrect_preset_data() {

		// プリセット設定を取得.
		$preset = get_option( 'lightning_design_preset' );

		// プリセット配列を取得.
		$current_presets = self::get_generated_preset_options_array();

		// 指定されたプリセット配列のデータが存在する場合.
		if ( ! empty( $current_presets ) ) {

			// 指定されたプリセット配列をループ.
			foreach ( $current_presets as $option_name => $preset_value ) {

				// 書き換え対象の既存のオプション値を取得.
				$now_option = get_option( $option_name );

				if ( is_array( $now_option ) ) {

					// 既存のオプション値とプリセットのオプション値を結合.
					$new_option = wp_parse_args( $now_option, $preset_value );

					// 既存の値は上書きしないといけないので更にループして上書き.
					foreach ( $preset_value as $key => $single_value ) {
						if ( is_array( $single_value ) ) {
							foreach ( $single_value as $child_key => $child_value ) {
								$new_option[ $key ][ $child_key ] = $child_value;
							}
						} else {
							$new_option[ $key ] = $single_value;
						}
					}
					update_option( $option_name, $new_option );

				} else {
					update_option( $option_name, $preset_value );
				}
			}
			// プリセットの指定をリセット.
			$option = array(
				'base'  => 'no_selected',
				'color' => 'no_selected',
			);
			update_option( 'lightning_design_preset', $option );
		}

	}


	/**
	 * Customize Register
	 *
	 * @param object $wp_customize : customize object.
	 */
	public function customize_register( $wp_customize ) {

		// コアで用意されている theme_mod は transport = 'postMessage' で js で処理される都合上リフレッシュされないので外す
		// $wp_customize->remove_setting( 'background_image' )->transport;
		// $wp_customize->remove_setting( 'background_color' )->transport;

		$wp_customize->add_setting(
			'preset_header',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new VK_Custom_Html_Control(
				$wp_customize,
				'preset_header',
				array(
					'label'            => __( 'Design Preset', 'lightning-g3-pro-unit' ),
					'section'          => 'lightning_design',
					'type'             => 'text',
					'custom_title_sub' => '',
					'custom_html'      => '<p><span style="color:red;font-weight:bold;">' . __( 'プリセットの変更を反映させる場合は一度保存してページを再読み込みしてください', 'lightning-g3-pro-unit' ) . '</span></p><p>' . __( 'カスタマイズ画面では選択したプリセットで指定された値（キーカラーなど）が優先して反映されるので、個別に指定しても反映されません。一旦保存して画面を再読み込みすると、プリセットで指定された値が各項目に保存されるので、プリセットのデザインを反映させた上で個別に変更可能になります。', 'lightning-g3-pro-unit' ) . '</p>',
					'priority'         => 50,
				)
			)
		);

		$choices      = array(
			'no_selected' => __( 'No select', 'lightning-g3-pro-unit' ),
		);
		$base_presets = self::get_base_presets();
		foreach ( $base_presets as $key => $value ) {
			$choices[ $key ] = $value['label'];
		}
		$wp_customize->add_setting(
			'lightning_design_preset[base]',
			array(
				'default'           => 'no_selected',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'lightning_design_preset[base]',
			array(
				'label'       => __( 'Base Preset', 'lightning-g3-pro-unit' ),
				'section'     => 'lightning_design',
				'settings'    => 'lightning_design_preset[base]',
				'type'        => 'select',
				'choices'     => $choices,
				'priority'    => 51,
				'description' => '',
			)
		);

		$choices       = array(
			'no_selected' => __( 'No select', 'lightning-g3-pro-unit' ),
		);
		$color_presets = self::get_color_presets();
		foreach ( $color_presets as $key => $value ) {
			$choices[ $key ] = $value['label'];
		}
		$wp_customize->add_setting(
			'lightning_design_preset[color]',
			array(
				'default'           => 'no_selected',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'lightning_design_preset[color]',
			array(
				'label'       => __( 'Color Preset', 'lightning-g3-pro-unit' ),
				'section'     => 'lightning_design',
				'settings'    => 'lightning_design_preset[color]',
				'type'        => 'select',
				'choices'     => $choices,
				'priority'    => 51,
				'description' => '',
			)
		);

	}
}

$preset_manager = new LTG3_Preset_Manager();
