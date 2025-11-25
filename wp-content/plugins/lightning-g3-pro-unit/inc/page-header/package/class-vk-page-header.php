<?php // phpcs:ignore
/**
 * Page Header Customize Function
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

/******************************************
 * Template Tags
 * /

/*
		is_theme()
		default_option()
		get_options_all()
		get_post_type()
		get_all_post_types_info()
		get_header_image_url_on_page()
		get_header_image_url()
/*
/*
	Customizer
	page meta box
	print head style
	is_use_page_ancestor()
	use_page_ancestor_change_page_title()
	use_page_ancestor_add_current_title()

/*-------------------------------------------*/

if ( ! class_exists( 'VK_Page_Header' ) ) {

	/******************************************
	 * Customize_register.
	 */

	add_action( 'customize_register', 'vk_page_header_customize_register' );
	function vk_page_header_customize_register( $wp_customize ) {

		/******************************************
		 * Add text control description
		 */
		class VK_Page_Header_Custom_Text_Control extends WP_Customize_Control {
			public $type         = 'customtext';
			public $description  = ''; // we add this for the extra description.
			public $input_before = '';
			public $input_after  = '';
			public $num_step     = '';
			public $num_min      = '';
			public $num_max      = '';
			public function render_content() {
				?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php $style = ( $this->input_before || $this->input_after ) ? ' style="width:50%"' : ''; ?>
				<div>
				<?php echo wp_kses_post( $this->input_before ); ?>
				<?php
				$step = '';
				$min  = '';
				$max  = '';
				if ( 'text' === $this->type ) {
					$type = 'text';
				} elseif ( 'number' === $this->type ) {
					$type = 'number';
					if ( $this->num_step ) {
						$step = ' step="' . esc_attr( $this->num_step ) . '"';
					}
					if ( $this->num_min ) {
						$min = ' min="' . esc_attr( $this->num_min ) . '"';
					} else {
						$min = ' min="0"';
					}
					if ( $this->num_max ) {
						$max = ' max="' . esc_attr( $this->num_max ) . '"';
					}
				}
				?>
				<input type="<?php echo $type; ?>"<?php echo $step . $min . $max; ?> value="<?php echo esc_attr( $this->value() ); ?>"<?php echo $style; ?> <?php $this->link(); ?> />
				<?php echo wp_kses_post( $this->input_after ); ?>
				</div>
				<div><?php echo wp_kses_post( $this->description ); ?></div>
			</label>
				<?php
			} // public function render_content() {
		} // class VK_Page_Header_Custom_Text_Control extends WP_Customize_Control
	}

	/**
	 * VK_Page_Header
	 */
	class VK_Page_Header {

		public static $version     = '0.2.0';
		private static $post_types = array( 'post' => 0 );

		/**
		 * Constructer
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'wp_head', array( $this, 'dynamic_header_css' ), 5 );
			add_action( 'add_meta_boxes', array( $this, 'add_pagehead_setting_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_custom_fields' ), 10, 2 );

			add_filter( 'lightning_page_header_title_html', array( $this, 'use_page_ancestor_change_page_title' ) );
			add_action( 'lightning_entry_body_before', array( $this, 'use_page_ancestor_add_current_title' ) );

			add_filter( 'lightning_page_header_title_html', array( $this, 'change_post_title_replace' ) );
			add_filter( 'lightning_is_entry_header', array( $this, 'is_normal_post_title_display' ) );
		}

		/******************************************
		 * Template Tags
		 */


		/**
		 * テーマで使用されているかプラグインで使用されているか
		 * is_theme()
		 *
		 * @return boolean
		 */
		public static function is_theme() {
			$path = __FILE__;
			preg_match( '/\/themes\//', $path, $m );
			if ( $m ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Customize default option
		 *
		 * @param string $post_type post type.
		 * @param array  $prop option array.
		 * @return array $default default options.
		 */
		public static function get_customize_default_option( $post_type, $prop ) {
			global $vk_page_header_default;
			$default = '';
			if ( isset( $vk_page_header_default[ $post_type ][ $prop ] ) ) {
				$default = $vk_page_header_default[ $post_type ][ $prop ];
			}
			return $default;
		}

		/**
		 * すべてのオプション値を返す
		 * 各投稿タイプで未設定の値がある場合は共通設定を割り振って返す
		 */
		public static function get_options_all() {

			global $vk_page_header_default;
			$options = get_option( 'vk_page_header' );

			// オプション値が存在しない or 空で保存されている or 配列じゃない場合に初期値で上書き.
			// 通常ありえないが $options の値が空でもなく配列でもない場合に、あとの処理で Fatal error になる、
			// これを防ぐため ! is_array( $options ) でのチェックを実施.
			if ( empty( $options ) || ! is_array( $options ) ) {
				// 未定の場合は共通を引っ張る仕様なので、初期で共通の違う設定を適用したい他の投稿タイプで誤動作を引き起こすため強制的に一旦保存する.
				update_option( 'vk_page_header', $vk_page_header_default );
				$options = get_option( 'vk_page_header' );
			}

			$post_types_all = self::get_all_post_types_info();

			// とりあえず投稿タイプ毎で共通設定を引き継がせるため common だけ全項目存在するように整形.

			// まずは common で存在している各項目名でループ.
			foreach ( $vk_page_header_default['common'] as $key => $common_value_default ) {
				// 保存値に 該当の保存項目の値がなかったら.
				if ( ! isset( $options['common'][ $key ] ) ) {
					// 初期でcommonに指定してある値を代入.
					$options['common'][ $key ] = $common_value_default;
				}

				// 他の投稿タイプもループしながら値が未設定だったら common の値を入れる
				foreach ( $post_types_all as $post_type_name => $post_type_label ) {

					if ( 'cover_opacity' === $key &&
						isset( $options[ $post_type_name ][ $key ] ) &&
						( 0 === $options[ $post_type_name ][ $key ] || '0' === $options[ $post_type_name ][ $key ] )
					) {
						/*
						透過 0 などの指定がある場合のみ0で保存されている場合のみそのまま適用なので
						共通項目での上書きは行わない
						*/
					} elseif ( empty( $options[ $post_type_name ][ $key ] ) ) {
						// 投稿タイプに対する保存項目が空だった場合.

						// 共通が保存されていたら共通を適用.
						if ( isset( $options['common'][ $key ] ) ) {
							$options[ $post_type_name ][ $key ] = $options['common'][ $key ];

						} else {
							// 共通が保存されていない場合
							// 投稿タイプ毎のデフォルト値があれば.
							if ( isset( $vk_page_header_default[ $post_type_name ][ $key ] ) ) {
								// 投稿タイプ毎のデフォルト値を適用.
								$options[ $post_type_name ][ $key ] = $vk_page_header_default[ $post_type_name ][ $key ];
							} else {
								// 共通のデフォルト値を適用.
								$options[ $post_type_name ][ $key ] = $common_value_default;
							}
						}
					}
				}
			}
			return $options;
		}

		public static function get_options_post_type() {

			$options_all = self::get_options_all();

			// 現在表示中のページの投稿タイプ情報を取得.
			$post_type_info = VK_Helpers::get_post_type_info();

			$post_type = $post_type_info['slug'];
			// 絞り込み検索などで query の post_type が any で投げられる事がある.
			// bbPress user など $options_all に存在しない投稿タイプもある.
			if ( 'any' === $post_type || ! isset( $options_all[ $post_type ] ) ) {
				$post_type = 'common';
			}
			$options = $options_all[ $post_type ];

			return $options;
		}


		/**
		 * 投稿タイプ情報
		 */
		public static function get_all_post_types_info() {

			// gets all custom post types set PUBLIC.
			$args = array(
				'public' => true,
				// '_builtin' => false,
			);

			$custom_types        = get_post_types( $args, 'objects' );
			$custom_types_labels = array();

			foreach ( $custom_types as $custom_type ) {
				$custom_types_labels[ $custom_type->name ] = $custom_type->label;
			}

			return $custom_types_labels;
		}

		/**
		 * 指定の投稿IDのカスタムフィールドに保存されている画像を取得
		 *
		 * @param string $id : post id.
		 * @param string $size : スマホ画像かどうか.
		 *
		 * @return string : image url
		 */
		public static function get_header_image_url_on_page( $id, $size ) {

			// カスタムフィールドに保存されている画像を取得.
			$cf_saved_value    = get_post_meta( $id, 'vk_page_header_image', true );
			$cf_saved_value_sp = get_post_meta( $id, 'vk_page_header_image_sp', true );

			if ( $cf_saved_value || $cf_saved_value_sp ) {

				// スマホ画像のリクエストの時.
				if ( 'sp' === $size ) {

					// スマホ用画像が登録されている場合.
					if ( $cf_saved_value_sp ) {
						// スマホ用画像で上書き.
						$cf_saved_value = $cf_saved_value_sp;
					}
				} elseif ( 'sp' !== $size && $cf_saved_value_sp && ! $cf_saved_value ) {
					// PC版リクエスト / 画像の登録がスマホしかない
					// スマホの画像で上書き.
					$cf_saved_value = $cf_saved_value_sp;
				}
			}
			return $cf_saved_value;
		}

		/**
		 * ヘッダー画像のURLを取得
		 *
		 * @param string $size :　sp(スマホ用)かどうか.
		 * @param string $cf_saved_type : 保存されている画像の値 (id か url ).
		 * @return string $image_url : 画像URL
		 */
		public static function get_header_image_url( $size, $cf_saved_type ) {

			$options        = self::get_options_post_type();
			$post_type_info = VK_Helpers::get_post_type_info();

			$image_url = '';
			if ( isset( $options['image'] ) && $options['image'] ) {
				$image_url = $options['image'];
			}

			if ( ! empty( $options['image_sp'] ) && $size === 'sp' ) {
				$image_url = $options['image_sp'];
			}

			if ( is_singular() ) {
				global $post;
			}

			/***
			 * 固定ページの場合
			 */

			// 検索結果ページでも $post_type_info['slug'] == 'page' に反応してしまうため
			// ! is_search() && ! is_404() を追加.
			if ( 'page' === $post_type_info['slug'] && ! is_search() && ! is_404() ) {

				// 今の固定ページに登録されているPC版画像を取得
				$cf_saved_value = self::get_header_image_url_on_page( $post->ID, $size );

				// 表示中の固定ページ自体にページヘッダー画像の登録がない場合.
				if ( ! $cf_saved_value ) {
					// 先祖階層でヘッダー画像が登録されているか確認.

					// 先祖階層を取得.
					$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
					foreach ( $ancestors as $ancestor ) {

						$cf_saved_value_ancestor = '';

						// 親階層から順に画像を取得し、下階層に画像があれば上書きしていく.
						$cf_saved_value_ancestor = self::get_header_image_url_on_page( $ancestor, $size );

						// 画像の登録がある場合.
						if ( $cf_saved_value_ancestor ) {

							$cf_saved_value = $cf_saved_value_ancestor;

						}
					}
				}

				// 固定ページで画像の登録があった場合のみ $image_url を上書きする.
				if ( isset( $cf_saved_value ) && $cf_saved_value ) {

					// 画像が id で保存されている場合に URL に変換.
					if ( 'id' === $cf_saved_type ) {
						$image_url = wp_get_attachment_image_src( $cf_saved_value, 'full', false );
						// 元のメディアが削除されて画像が取得出来ない事があるため、画像がある時だけ上書き.
						if ( $image_url ) {
							$image_url = $image_url[0];
						}
					} else {
						$image_url = $cf_saved_value;
					}
				}
			} elseif ( is_single() ) {
				// デフォルトレイアウトじゃない場合.
				if ( 'post_title_and_meta' === $options['element'] || 'post_title' === $options['element'] ) {
					// アイキャッチを使用しない指定だった場合.
					if ( ! isset( $options['image_type'] ) || 'normal' !== $options['image_type'] ) {
						// アイキャッチ画像がある場合.
						$url = get_the_post_thumbnail_url( $post->id, 'large' );
						if ( $url ) {
							// アイキャッチで上書き.
							$image_url = $url;
						}
					}
				}
			}

			return $image_url;
		}

		/******************************************
		 * Customizer
		 */

		/**
		 * Customize Register
		 *
		 * @param object $wp_customize : customize object.
		 * @return void
		 */
		public function customize_register( $wp_customize ) {

			global $customize_setting_prefix;
			global $customize_section_priority;
			global $vk_page_header_default;
			global $vk_page_header_output_class;

			$wp_customize->add_panel(
				'vk_page_header_setting',
				array(
					'title'    => $customize_setting_prefix . __( 'Page Header Setting', 'lightning-g3-pro-unit' ),
					'priority' => $customize_section_priority,
				)
			);

			$custom_types = array(
				'common' => __( 'Common', 'lightning-g3-pro-unit' ),
			);
			$custom_types = wp_parse_args( self::get_all_post_types_info(), $custom_types );
			foreach ( $custom_types as $name => $label ) {

				$wp_customize->add_section(
					'vk_page_header_setting[' . $name . ']',
					array(
						'title' => esc_html( $label ),
						'panel' => 'vk_page_header_setting',
					)
				);

				// element type.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][element]',
					array(
						'default'           => self::get_customize_default_option( $name, 'element' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_attr',
					)
				);
				if ( 'page' === $name ) {
					$choices = array(
						'page_title'          => __( 'Page title', 'lightning-g3-pro-unit' ),
						'ancestor_page_title' => __( 'Ancestor page title', 'lightning-g3-pro-unit' ),
					);
					$default = 'page_title';
				} else {
					$choices = array(
						'post_type_name'      => __( 'Post type name', 'lightning-g3-pro-unit' ),
						'post_title'          => __( 'Post title', 'lightning-g3-pro-unit' ),
						'post_title_and_meta' => __( 'Post title and meta', 'lightning-g3-pro-unit' ),
					);
					$default = 'post_type_name';
				}
				$wp_customize->add_control(
					'vk_page_header[' . $name . '][element]',
					array(
						'label'    => __( 'Display elements', 'lightning-g3-pro-unit' ) . ' [ ' . esc_html( $label ) . ' ]',
						'section'  => 'vk_page_header_setting[' . $name . ']',
						'settings' => 'vk_page_header[' . $name . '][element]',
						'type'     => 'radio',
						'choices'  => $choices,
					)
				);

				// Sub Text.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][sub_text]',
					array(
						'default'           => self::get_customize_default_option( $name, 'sub_text' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'wp_kses_post',
					)
				);

				$wp_customize->add_control(
					'vk_page_header[' . $name . '][sub_text]',
					array(
						'label'           => __( 'Sub text', 'lightning-g3-pro-unit' ),
						'section'         => 'vk_page_header_setting[' . $name . ']',
						'settings'        => 'vk_page_header[' . $name . '][sub_text]',
						'type'            => 'text',
						'active_callback' => function ( $control ) use ( $name ) {
							if ( $control->manager->get_setting( 'vk_page_header[' . $name . '][element]' )->value() === 'post_type_name' ) {
								return true;
							} else {
								return false;
							}
						},
					)
				);

				// text color.
				if ( 'common' === $name ) {
					$selector = $vk_page_header_output_class;
				} else {
					$selector = '.post-type-' . $name . ' ' . $vk_page_header_output_class;
				}

				$wp_customize->selective_refresh->add_partial(
					'vk_page_header[' . $name . '][text_color]',
					array(
						'selector'        => $selector,
						'render_callback' => '',
					)
				);

				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][text_color]',
					array(
						'default'           => self::get_customize_default_option( $name, 'text_color' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'sanitize_hex_color',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][text_color]',
						array(
							'label'    => __( 'Text color', 'lightning-g3-pro-unit' ),
							'section'  => 'vk_page_header_setting[' . $name . ']',
							'settings' => 'vk_page_header[' . $name . '][text_color]',
						)
					)
				);

				// text shadow color.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][text_shadow_color]',
					array(
						'default'           => self::get_customize_default_option( $name, 'text_shadow_color' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'sanitize_hex_color',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][text_shadow_color]',
						array(
							'label'    => __( 'Text shadow color', 'lightning-g3-pro-unit' ),
							'section'  => 'vk_page_header_setting[' . $name . ']',
							'settings' => 'vk_page_header[' . $name . '][text_shadow_color]',
						)
					)
				);

				// text align.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][text_align]',
					array(
						'default'           => self::get_customize_default_option( $name, 'text_align' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_attr',
					)
				);
				$wp_customize->add_control(
					'vk_page_header[' . $name . '][text_align]',
					array(
						'label'    => __( 'Text align', 'lightning-g3-pro-unit' ),
						'section'  => 'vk_page_header_setting[' . $name . ']',
						'settings' => 'vk_page_header[' . $name . '][text_align]',
						'type'     => 'radio',
						'choices'  => array(
							'left'   => __( 'Left', 'lightning-g3-pro-unit' ),
							'center' => __( 'Center', 'lightning-g3-pro-unit' ),
							'right'  => __( 'Right', 'lightning-g3-pro-unit' ),
						),
					)
				);

				// min height.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][height_min]',
					array(
						'default'           => self::get_customize_default_option( $name, 'height_min' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_attr',
					)
				);

				$wp_customize->add_control(
					new VK_Page_Header_Custom_Text_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][height_min]',
						array(
							'label'       => __( 'Minimum height', 'lightning-g3-pro-unit' ),
							'section'     => 'vk_page_header_setting[' . $name . ']',
							'settings'    => 'vk_page_header[' . $name . '][height_min]',
							'type'        => 'number',
							'description' => '',
							'input_after' => 'rem',
						)
					)
				);

				// cover color.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][cover_color]',
					array(
						'default'           => self::get_customize_default_option( $name, 'cover_color' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'sanitize_hex_color',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][cover_color]',
						array(
							'label'    => __( 'Cover color', 'lightning-g3-pro-unit' ),
							'section'  => 'vk_page_header_setting[' . $name . ']',
							'settings' => 'vk_page_header[' . $name . '][cover_color]',
						)
					)
				);

				// cover opacity.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][cover_opacity]',
					array(
						'default'           => self::get_customize_default_option( $name, 'cover_opacity' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_attr',
					)
				);

				$wp_customize->add_control(
					new VK_Page_Header_Custom_Text_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][cover_opacity]',
						array(
							'label'       => __( 'Cover opacity', 'lightning-g3-pro-unit' ),
							'section'     => 'vk_page_header_setting[' . $name . ']',
							'settings'    => 'vk_page_header[' . $name . '][cover_opacity]',
							'type'        => 'number',
							'num_step'    => 0.05,
							'num_min'     => 0,
							'num_max'     => 1,
							'description' => __( 'Please enter a number from 0 to 1', 'lightning-g3-pro-unit' ),
						)
					)
				);

				// Image //////////////////////////////////.
				if ( 'page' === $name ) {
					$description = __( 'If you want to change the image of a specific page, you can set it from the editing screen of each fixed page.', 'lightning-g3-pro-unit' ) . '<br>';
				} else {
					$description = '';
				}

				if ( 'common' !== $name ) {
					$description .= __( 'When not set, the image of [ Common ] is applied.', 'lightning-g3-pro-unit' );
				}

				$image_default = '';
				if ( 'common' === $name ) {
					$image_default = $vk_page_header_default['common']['image'];
				}

				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][image]',
					array(
						'default'           => self::get_customize_default_option( $name, 'image' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_url',
					)
				);

				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][image]',
						array(
							'label'    => __( 'Background image (PC)', 'lightning-g3-pro-unit' ),
							'section'  => 'vk_page_header_setting[' . $name . ']',
							'settings' => 'vk_page_header[' . $name . '][image]',
						)
					)
				);

				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][image_sp]',
					array(
						'default'           => '',
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_url',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'vk_page_header[' . $name . '][image_sp]',
						array(
							'label'    => __( 'Background image (Mobile)', 'lightning-g3-pro-unit' ),
							'section'  => 'vk_page_header_setting[' . $name . ']',
							'settings' => 'vk_page_header[' . $name . '][image_sp]',
						)
					)
				);

				// image_type.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][image_type]',
					array(
						'default'           => self::get_customize_default_option( $name, 'image_type' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_attr',
					)
				);

				$wp_customize->add_control(
					'vk_page_header[' . $name . '][image_type]',
					array(
						'label'           => __( 'Background image of single page', 'lightning-g3-pro-unit' ),
						'section'         => 'vk_page_header_setting[' . $name . ']',
						'settings'        => 'vk_page_header[' . $name . '][image_type]',
						'type'            => 'radio',
						'choices'         => array(
							'normal'         => __( 'No eye-catching image', 'lightning-g3-pro-unit' ),
							'post_thumbnail' => __( 'If an eye-catching image is registered, apply the eye-catching image', 'lightning-g3-pro-unit' ),
						),
						'active_callback' => function ( $control ) use ( $name ) {
							$setting = 'vk_page_header[' . $name . '][element]';
							if ( $control->manager->get_setting( $setting )->value() === 'post_title' ||
								$control->manager->get_setting( $setting )->value() === 'post_title_and_meta'
								) {
								return true;
							} else {
								return false;
							}
						},
					)
				);

				// Image fixed.
				$wp_customize->add_setting(
					'vk_page_header[' . $name . '][image_fixed]',
					array(
						'default'           => self::get_customize_default_option( $name, 'image_fixed' ),
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => 'esc_attr',
					)
				);
				$wp_customize->add_control(
					'vk_page_header[' . $name . '][image_fixed]',
					array(
						'label'       => __( 'Background image position', 'lightning-g3-pro-unit' ),
						'section'     => 'vk_page_header_setting[' . $name . ']',
						'settings'    => 'vk_page_header[' . $name . '][image_fixed]',
						'type'        => 'radio',
						'choices'     => array(
							'normal' => __( 'normal', 'lightning-g3-pro-unit' ),
							'fixed'  => __( 'Fixed', 'lightning-g3-pro-unit' ),
						),
						'description' => __( '* Not fixed on iOS', 'lightning-g3-pro-unit' ),
					)
				);
			}
		}

		/**
		 * Page meta box
		 *
		 * @return void
		 *
		 * static にすると環境によってmetabox内のコールバック関数が反応しない
		 */
		public function add_pagehead_setting_meta_box() {

			// 投稿トップは固定ページでなくアーカイプページ判定されるので、
			// 投稿トップにわりあてた固定ページで指定したカラム数は反映されない。
			// よって、誤解を避けるためにレイアウト設定を含む Lightningデザイン設定のmetabox自体表示しないようにする.
			if ( isset( $_GET['post'] ) && $_GET['post'] === get_option( 'page_for_posts' ) && 'page' === get_option( 'show_on_front' ) ) {
				return;
			}

			add_meta_box( 'vk_page_header_meta_box', __( 'Page Header Image', 'lightning-g3-pro-unit' ), array( $this, 'vk_page_header_meta_box_content' ), 'page', 'normal', 'high' );
		}

		public function vk_page_header_meta_box_content() {
				self::fields_form();
		}

		public function fields_form() {
			$custom_fields_array = self::custom_fields_array();
			$befor_custom_fields = '';
			VK_Custom_Field_Builder::form_table( $custom_fields_array, $befor_custom_fields );
		}

		public function save_custom_fields() {
			if ( ! is_customize_preview() ) {
				$custom_fields_array = self::custom_fields_array();
				VK_Custom_Field_Builder::save_cf_value( $custom_fields_array );
			}
		}

		public static function custom_fields_array() {

			$custom_fields_array = array(
				'vk_page_header_subtext'  => array(
					'label'       => __( 'Page header subtext', 'lightning-g3-pro-unit' ),
					'type'        => 'text',
					'description' => '',
					'required'    => false,
				),
				'vk_page_header_image'    => array(
					'label'       => __( 'Page header bg image', 'lightning-g3-pro-unit' ),
					'type'        => 'image',
					'description' => '',
					'required'    => false,
				),
				'vk_page_header_image_sp' => array(
					'label'       => __( 'Page header bg image', 'lightning-g3-pro-unit' ) . ' ( ' . __( 'Mobile', 'lightning-g3-pro-unit' ) . ' )',
					'type'        => 'image',
					'description' => '',
					'required'    => false,
				),
			);
			return $custom_fields_array;
		}


		public static function get_layout( $layout = 'default' ) {
			$options = self::get_options_post_type();
			if ( is_single() ) {
				// 表示タイプが 標準レイアウトじゃない（記事タイトルや日付など）場合.
				if ( ! empty( $options['element'] ) ) {
					$layout = $options['element'];
				}
			}
			return $layout;
		}


		/*
			print head style
		/*-------------------------------------------*/

		public function dynamic_header_css() {

			/*
			アウター部分のCSS
			/*-------------------------------------------*/
			if ( ! is_front_page() ) {

				$title_outer_dynamic_css = '';
				$options                 = self::get_options_post_type();

				// ヘッダー背景画像URL取得
				$image_url    = self::get_header_image_url( '', 'id' );
				$image_url_sp = self::get_header_image_url( 'sp', 'id' );

				$variables_dynamic_css = '';
				if ( $image_url ) {
					$variables_dynamic_css .= ':root{
						--vk-page-header-url : url(' . esc_url( $image_url ) . ');
					}';
				}
				if ( $image_url ) {
					$variables_dynamic_css .= '
					@media ( max-width:575.98px ){
						:root{
							--vk-page-header-url : url(' . esc_url( $image_url_sp ) . ');
						}
					}';
				}

				if ( isset( $options['text_color'] ) && $options['text_color'] ) {
					$title_outer_dynamic_css .= 'color:' . $options['text_color'] . ';';
				} elseif ( self::get_layout() == 'post_title_and_meta' || self::get_layout() == 'post_title' ) {
					$title_outer_dynamic_css .= 'color:#fff;';
				}

				if ( isset( $options['text_shadow_color'] ) && $options['text_shadow_color'] ) {
					$title_outer_dynamic_css .= 'text-shadow:0px 0px 10px ' . $options['text_shadow_color'] . ';';
				}

				if ( isset( $options['text_align'] ) && $options['text_align'] ) {
					// left 指定の場合は出力しないようにしたかったが、中央揃えがデフォルトのスキンもあるので、leftでもcss出力
					// if ( $options['text_align'] != 'left' ){.
					$title_outer_dynamic_css .= 'text-align:' . $options['text_align'] . ';';
					// }
				}

				if ( isset( $options['bg_color'] ) && $options['bg_color'] ) {
					$title_outer_dynamic_css .= 'background-color:' . $options['bg_color'] . ';';
				}

				if ( $image_url ) {
					$title_outer_dynamic_css .= 'background: var(--vk-page-header-url, url(' . esc_url( $image_url ) . ') ) no-repeat 50% center;';
					$title_outer_dynamic_css .= 'background-size: cover;

					';
					if ( isset( $options['image_fixed'] ) && 'fixed' === $options['image_fixed'] ) {
						$title_outer_dynamic_css .= 'background-attachment: fixed;';
					}
				}

				if ( isset( $options['height_min'] ) && $options['height_min'] ) {
					$title_outer_dynamic_css .= 'min-height:' . $options['height_min'] . 'rem;';

				}

				// アウター部分のセレクタと結合<div class=""></div>
				if ( $title_outer_dynamic_css ) {
					// 対象とするclass名を取得.
					global $vk_page_header_output_class;
					$title_outer_dynamic_css = $vk_page_header_output_class . '{ position:relative;' . $title_outer_dynamic_css . '}';

				}

				// カバー部分.
				if ( ! empty( $options['cover_color'] ) || isset( $options['cover_opacity'] ) ) {

					$title_outer_dynamic_css .= $vk_page_header_output_class . '::before{
						content:"";
						position:absolute;
						top:0;
						left:0;
						background-color:' . $options['cover_color'] . ';
						opacity:' . $options['cover_opacity'] . ';
						width:100%;
						height:100%;
					}';
				}

				// CSS が存在している場合のみ出力.
				if ( $title_outer_dynamic_css ) {

					$dynamic_css = $variables_dynamic_css . $title_outer_dynamic_css;

					// delete before after space.
					$dynamic_css = trim( $dynamic_css );
					// convert tab and br to space.
					$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
					// Change multiple spaces to single space.
					$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

					$dynamic_css = '/* page header */' . $dynamic_css;

					// 出力を実行.
					global $vk_page_header_enqueue_handle_style;
					wp_add_inline_style( $vk_page_header_enqueue_handle_style, $dynamic_css );
				}
			}
		}

		public static function is_use_page_ancestor() {
			if ( is_page() ) {
				global $post;
				$subtext   = '';
				$options   = self::get_options_post_type();
				$ancestors = array_reverse( get_ancestors( $post->ID, 'page' ) );
				if ( ! empty( $ancestors[0] ) && 'ancestor_page_title' === $options['element'] ) {
					return $ancestors[0];
				}
			}
		}
		public static function use_page_ancestor_change_page_title( $html ) {
			$ancestor_id = self::is_use_page_ancestor();
			global $post;
			$subtext = '';
			if ( is_singular() ) {
				// 先祖ページのタイトルを表示する場合.
				if ( $ancestor_id ) {
					// 先祖階層のタイトル名で上書き.
					$html    = '<div class="page-header-title">' . get_the_title( $ancestor_id ) . '</div>';
					$subtext = get_post_meta( $ancestor_id, 'vk_page_header_subtext', true );
				} else {
					$subtext = get_post_meta( $post->ID, 'vk_page_header_subtext', true );
				}
			}
			// サブテキストがある場合に追加.
			if ( $subtext ) {
				$html .= '<div class="page-header-subtext">' . wp_kses_post( apply_filters( 'lightning_page-header-subtext', $subtext ) ) . '</div>';
			}
			return $html;
		}

		public static function use_page_ancestor_add_current_title() {
			if ( is_page() && self::is_use_page_ancestor() ) {
				global $post;
				// ページヘッダーを表示しない場合は固定ページタイトルも表示しない.
				if ( ! empty( $post->_lightning_design_setting['hide_page_header'] ) ) {
					return;
				}
				echo '<header class="entry-header"><h1 class="entry-title entry-title--post-type--' . get_post_type() . '">' . get_the_title( $post->ID ) . '</h1></header>';
			}
		}

		/**
		 * ページヘッダーの置換HTML
		 *
		 * @param string $html : ページヘッダーに出力する文字列.
		 * @return string $html : 置換するHTML
		 */
		public static function change_post_title_replace( $html ) {
			$options = self::get_options_post_type();
			if ( is_archive() || ( is_single() && 'post_type_name' === $options['element'] ) || ( is_home() && ! is_front_page() ) ) {
				if ( ! empty( $options['sub_text'] ) ) {
					$html .= '<div class="page-header-subtext">' . wp_kses_post( $options['sub_text'] ) . '</div>';
				}
			} elseif ( is_single() ) {
				if ( 'post_title_and_meta' === $options['element'] || 'post_title' === $options['element'] ) {
					$html = '<h1 class="page-header-title">' . esc_html( get_the_title() ) . '</h1>';
				}
				if ( isset( $options['element'] ) && 'post_title_and_meta' === $options['element'] ) {
					$entry_meta_options                = array();
					$entry_meta_options['class_outer'] = 'entry_meta page-header-subtext';
					$html                             .= lightning_get_entry_meta( $entry_meta_options );
				}
			}
			return $html;
		}

		/**
		 * ページヘッダーに投稿タイトルを表示するかどうか？
		 *
		 * @param boolean $flag : オプションに保存されたフラグ値.
		 * @return boolean
		 */
		public static function is_normal_post_title_display( $flag ) {
			if ( is_single() ) {
				$options = self::get_options_post_type();
				if ( isset( $options['element'] ) ) {
					if ( 'post_title' === $options['element'] || 'post_title_and_meta' === $options['element'] ) {
						$flag = false;
					}
				}
			}
			return $flag;
		}
	} // class VK_Page_Header

	// フックではずしやすいようにグローバル変数にいれている.
	$vk_page_header = new VK_Page_Header();

}
