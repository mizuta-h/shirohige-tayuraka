<?php
/**
 * Ltg_Breadcrumb_Controller
 * Controll breadcrumb position
 *
 * @package vektor-inc/lightning-g3-pro-unit
 */

use VektorInc\VK_Breadcrumb\VkBreadcrumb;

/**
 * Ltg_Breadcrumb_Controller
 */
class Ltg_Breadcrumb_Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register', array( __CLASS__, 'customize_register' ) );
		add_action( 'wp_head', array( __CLASS__, 'push_breadcrumb_to_action' ) );
		add_filter(
			'lightning_is_breadcrumb_position_normal',
			function( $return ) {
				if ( ! self::is_position_normal() ) {
					$return = false;
				}
				return $return;
			}
		);
	}

	/**
	 * Replace check
	 *
	 * @return boolean
	 */
	public static function is_position_normal() {

		$options = get_option( 'lightning_theme_options' );

		if ( empty( $options['breadcrumb']['position'] ) ) {
			return true;
		}
		if ( 'default' === $options['breadcrumb']['position'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Push Breadcrumb to action hook
	 *
	 * @return void
	 */
	public static function push_breadcrumb_to_action() {

		if ( self::is_position_normal() ) {
			return;
		}

		$options = get_option( 'lightning_theme_options' );

		$positions = array(
			'header--after'             => 'lightning_page_header_before',
			'default'                   => '',
			'footer-top-widget--before' => 'lightning_site_body_apepend',
			'footer--before'            => 'lightning_site_footer_before',
		);

		// パンくずが標準でない位置指定の場合.
		if ( ! empty( $positions[ $options['breadcrumb']['position'] ] ) ) {
			$hook = $positions[ $options['breadcrumb']['position'] ];

			$priority = 10;
			if ( ! empty( $options['breadcrumb']['priority'] ) ) {
				$priority = esc_html( $options['breadcrumb']['priority'] );
			}

			add_action(
				$hook,
				function () {
					if ( apply_filters( 'lightning_is_breadcrumb', true, 'breadcrumb' ) ) {
						$vk_breadcrumb      = new VkBreadcrumb();
						$breadcrumb_options = array(
							'id_outer'        => 'breadcrumb',
							'class_outer'     => 'breadcrumb',
							'class_inner'     => 'container',
							'class_list'      => 'breadcrumb-list',
							'class_list_item' => 'breadcrumb-list__item',
						);
						$vk_breadcrumb->the_breadcrumb( $breadcrumb_options );
					}
				},
				$priority
			);
		}
	}

	/**
	 * Customize Register
	 *
	 * @param object $wp_customize WP_Customize.
	 */
	public static function customize_register( $wp_customize ) {
		$wp_customize->add_section(
			'lightning_breaadcrumb',
			array(
				'title' => __( 'パンくずリスト設定', 'lightning' ),
				'panel' => 'lightning_layout',
			)
		);

		$wp_customize->add_setting(
			'lightning_theme_options[breadcrumb][position]',
			array(
				'default'           => 'default',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'lightning_theme_options[breadcrumb][position]',
			array(
				'label'    => __( 'パンくずリストの位置', 'lightning' ),
				'section'  => 'lightning_breaadcrumb',
				'settings' => 'lightning_theme_options[breadcrumb][position]',
				'type'     => 'select',
				'choices'  => array(
					'header--after'             => __( 'ヘッダーの下', 'lightning' ),
					'default'                   => __( 'ページヘッダーの下（標準）', 'lightning' ),
					'footer-top-widget--before' => __( 'フッター上部ウィジェットエリアの上', 'lightning' ),
					'footer--before'            => __( 'フッターの上', 'lightning' ),
				),
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'lightning_theme_options[breadcrumb][position]',
			array(
				'selector'        => '.breadcrumb-list',
				'render_callback' => '',
			)
		);

		$wp_customize->add_section(
			'lightning_container',
			array(
				'title'    => __( 'Container Setting', 'lightning-g3-pro-unit' ),
				'panel'    => 'lightning_layout',
				'priority' => 100,
			)
		);

		$wp_customize->add_setting(
			'lightning_theme_options[breadcrumb][hook_priority]',
			array(
				'default'           => '',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new VK_Custom_Text_Control(
				$wp_customize,
				'lightning_theme_options[breadcrumb][hook_priority]',
				array(
					'label'       => __( 'アクションフックのプライオリティ', 'lightning-g3-pro-unit' ),
					'section'     => 'lightning_breaadcrumb',
					'settings'    => 'lightning_theme_options[breadcrumb][hook_priority]',
					'type'        => 'number',
					'description' => __( 'よくわからない場合は空欄でかまいません。', 'lightning-g3-pro-unit' ),
					'input_after' => '',
				)
			)
		);

	}
}

new Ltg_Breadcrumb_Controller();
