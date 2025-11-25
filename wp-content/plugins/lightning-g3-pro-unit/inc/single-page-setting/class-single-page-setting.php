<?php
/*
	Template Tags
-------------------------------------------*/

/*
	get_all_hidden_items()
	get_all_post_types_info()
	is_single_hidden_item()
/*
/*
	Customizer


/*-------------------------------------------*/


if ( ! class_exists( 'Lightning_Single_Page_Setting' ) ) {

	class Lightning_Single_Page_Setting {

		private static $post_types = array( 'post' => 0 );

		public function __construct() {
			add_action( 'lightning_site_header_after', array( $this, 'is_single_hidden_item' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'wp_head', array( $this, 'dynamic_header_css' ), 5 );
			add_filter( 'lightning_next_prev_options', array( $this, 'next_prev_options' ) );
		}

		/*
				新着バッジの文言と表示日数の差し換え
				next_prev_options()
		/*-------------------------------------------*/
		public static function next_prev_options( $next_prev_options ) {
			$options   = lightning_get_theme_options();
			$post_type = get_post_type();

			if ( isset( $options['single_new_text'] ) && is_array( $options['single_new_text'] ) && array_key_exists( $post_type, $options['single_new_text'] ) ) {
				$next_prev_options['new_text'] = $options['single_new_text'][ $post_type ];
			}

			if ( isset( $options['single_new_date'] ) && is_array( $options['single_new_date'] ) && array_key_exists( $post_type, $options['single_new_date'] ) ) {
				if ( $options['single_new_date'][ $post_type ] === '-1' ) {
					$next_prev_options['new_date'] = INF;
				} else {
					$next_prev_options['new_date'] = (int) $options['single_new_date'][ $post_type ];
				}
			}

			return $next_prev_options;
		}

		/*
				get_all_post_types_info()
		/*-------------------------------------------*/
		public static function get_all_post_types_info() {

			// gets all custom post types set PUBLIC
			$args = array(
				'public' => true,
				// '_builtin' => false,
			);

			$custom_types        = get_post_types( $args, 'objects' );
			$custom_types_labels = array();

			unset( $custom_types['page'] );

			foreach ( $custom_types as $custom_type ) {
				$custom_types_labels[ $custom_type->name ] = $custom_type->label;
			}

			return $custom_types_labels;
		}

		private function get_all_hidden_items() {
			$items = array(
				'date'      => array(
					'label'   => __( 'Publish Date', 'lightning-g3-pro-unit' ),
					'class'   => '.page-header .entry-meta-item-date,.entry-header .entry-meta-item-date',
					'partial' => true,
				),
				'update'    => array(
					'label'   => __( 'Updated Date', 'lightning-g3-pro-unit' ),
					'class'   => '.page-header .entry-meta-item-updated,.entry-header .entry-meta-item-updated',
					'partial' => false,
				),
				'author'    => array(
					'label'   => __( 'Author', 'lightning-g3-pro-unit' ),
					'class'   => '.page-header .entry-meta-item-author,.entry-header .entry-meta-item-author',
					'partial' => false,
				),
				'next-prev' => array(
					'label'   => __( 'Next Prev', 'lightning-g3-pro-unit' ),
					'class'   => '.next-prev',
					'partial' => true,
				),
			);
			return $items;
		}

		/**
		 * $target を非表示にするかどうか
		 *
		 * @param $target string
		 */
		public static function is_single_hidden_item( $target ) {
			$options    = get_option( 'lightning_theme_options' );
			$post_stype = get_post_type();
			if ( ! empty( $options['single_hidden'][ $post_stype ][ $target ] ) ) {
				return true;
			} else {
				false;
			}
		}


		/*
			Customizer
		/*-------------------------------------------*/
		public function customize_register( $wp_customize ) {

			$wp_customize->add_panel(
				'lightning_single_page_setting',
				array(
					'title'    => 'Lightning ' . __( 'Single Page Setting', 'lightning-g3-pro-unit' ),
					'priority' => 538,
				)
			);
			$custom_types = self::get_all_post_types_info();

			foreach ( $custom_types as $post_type_slug => $post_type_label ) {

				// セクション追加
				$wp_customize->add_section(
					'lightning_single_page_setting[' . $post_type_slug . ']',
					array(
						'title' => esc_html( $post_type_label ),
						'panel' => 'lightning_single_page_setting',
					)
				);

				// 非表示見出し
				$wp_customize->add_setting(
					'lightning_single_hidden_title[' . $post_type_slug . ']',
					array(
						'sanitize_callback' => 'sanitize_text_field',
					)
				);
				$wp_customize->add_control(
					new VK_Custom_Html_Control(
						$wp_customize,
						'lightning_single_hidden_title[' . $post_type_slug . ']',
						array(
							'label'            => __( 'Hidden elements', 'lightning-g3-pro-unit' ),
							'section'          => 'lightning_single_page_setting[' . $post_type_slug . ']',
							'type'             => 'text',
							'custom_title_sub' => '',
							'custom_html'      => '',
							'priority'         => 0,
						)
					)
				);

				/*
					Items
				--------------------------------------------- */
				$items = self::get_all_hidden_items();

				foreach ( $items as $key => $item_value ) {
					$setting_name = 'lightning_theme_options[single_hidden][' . $post_type_slug . '][' . $key . ']';
					$wp_customize->add_setting(
						$setting_name,
						array(
							'default'           => '',
							'type'              => 'option',
							'capability'        => 'edit_theme_options',
							'sanitize_callback' => 'esc_attr',
						)
					);
					$wp_customize->add_control(
						$setting_name,
						array(
							'label'    => $item_value['label'] . ' [ ' . $post_type_label . ' ]',
							'section'  => 'lightning_single_page_setting[' . $post_type_slug . ']',
							'settings' => $setting_name,
							'type'     => 'checkbox',
						)
					);
					if ( $item_value['partial'] ) {
						$wp_customize->selective_refresh->add_partial(
							$setting_name,
							array(
								'selector'        => '.post-type-' . $post_type_slug . ' ' . $item_value['class'],
								'render_callback' => '',
							)
						);
					}

					// 新着表示オプション見出し
					$wp_customize->add_setting(
						'lightning_new_icon_options[' . $post_type_slug . ']',
						array(
							'sanitize_callback' => 'sanitize_text_field',
						)
					);

					$wp_customize->add_control(
						new VK_Custom_Html_Control(
							$wp_customize,
							'lightning_new_icon_options[' . $post_type_slug . ']',
							array(
								'label'            => 'New badge display options',
								'section'          => 'lightning_single_page_setting[' . $post_type_slug . ']',
								'type'             => 'text',
								'custom_title_sub' => '',
								'custom_html'      => '',
							)
						)
					);

					$wp_customize->add_setting(
						'lightning_theme_options[single_new_date][' . $post_type_slug . ']',
						array(
							'capability'        => 'edit_theme_options',
							'default'           => 7,
							'type'              => 'option',
							'sanitize_callback' => 'esc_attr',
						)
					);

					$wp_customize->add_control(
						new WP_Customize_Control(
							$wp_customize,
							'lightning_theme_options[single_new_date][' . $post_type_slug . ']',
							array(
								'label'       => 'number of days',
								'section'     => 'lightning_single_page_setting[' . $post_type_slug . ']',
								'settings'    => 'lightning_theme_options[single_new_date][' . $post_type_slug . ']',
								'type'        => 'number',
								'input_attrs' => array(
									'step' => '1',
									'min'  => '-1',
								),
							)
						)
					);

					$wp_customize->add_setting(
						'lightning_theme_options[single_new_text][' . $post_type_slug . ']',
						array(
							'capability'        => 'edit_theme_options',
							'default'           => 'New!!',
							'sanitize_callback' => 'sanitize_text_field',
							'type'              => 'option',
						)
					);

					$wp_customize->add_control(
						new WP_Customize_Control(
							$wp_customize,
							'lightning_theme_options[single_new_text][' . $post_type_slug . ']',
							array(
								'label'    => 'display text',
								'section'  => 'lightning_single_page_setting[' . $post_type_slug . ']',
								'settings' => 'lightning_theme_options[single_new_text][' . $post_type_slug . ']',
								'type'     => 'text',
							)
						)
					);
				}
			}
		}

		/*
			print head style
		/*-------------------------------------------*/

		public function dynamic_header_css() {

			$options = get_option( 'lightning_theme_options' );

			/*
			/*-------------------------------------------*/
			if ( is_single() ) {
				$dynamic_css = '';
				$items       = self::get_all_hidden_items();
				foreach ( $items as $key => $value ) {
					if ( self::is_single_hidden_item( $key ) ) {
						$dynamic_css .= $value['class'] . ' { display:none; }';
					}
				}
				// CSS が存在している場合のみ出力
				if ( $dynamic_css ) {

					// delete before after space
					$dynamic_css = trim( $dynamic_css );
					// convert tab and br to space
					$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
					// Change multiple spaces to single space
					$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

					$dynamic_css = '/* Single Page Setting */' . $dynamic_css;

					// 出力を実行
					wp_add_inline_style( 'lightning-design-style', $dynamic_css );
				}
			} // if( !is_front_page() ){
		} // public function skin_dynamic_css(){
	} // class Lightning_Single_Page_Setting

	$lightning_single_page_setting = new Lightning_Single_Page_Setting();

}
