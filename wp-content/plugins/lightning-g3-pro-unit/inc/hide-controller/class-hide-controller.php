<?php
/**
 * Hide Controller of Lightning.
 *
 * @package Lightning
 */

class LTG_Hide_Controller {
	public function __construct() {
		add_action( 'lightning_design_setting_meta_fields', array( __CLASS__, 'add_hide_controll_meta' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'css_hide' ) );
		self::hide_filters();
	}

	public static function get_hide_items() {
		$hide_items = array(
			'site_header'     => array(
				'label' => __( 'Don\'t display Header', 'lightning-g3-pro-unit' ),
			),
			'page_header'     => array(
				'label' => __( 'Don\'t display Page Header', 'lightning-g3-pro-unit' ),
			),
			'breadcrumb'      => array(
				'label' => __( 'Don\'t display Breadcrumb', 'lightning-g3-pro-unit' ),
			),
			'site_footer'     => array(
				'label' => __( 'Don\'t display Footer', 'lightning-g3-pro-unit' ),
			),
			'mobile_menu_btn' => array(
				'label' => __( 'Don\'t display Mobile Menu Button', 'lightning-g3-pro-unit' ),
			),
			'mobile_fix_nav'  => array(
				'label' => __( 'Don\'t display Mobile Fix Nav', 'lightning-g3-pro-unit' ),
			),
		);
		return $hide_items;
	}

	public static function add_hide_controll_meta() {
		$form  = '';
		$form .= '<h4>' . __( 'Hidden setting', 'lightning-g3-pro-unit' ) . '</h4>';

		$form .= '<ul>';

		$hide_items = self::get_hide_items();
		global $post;
		$saved_post_meta = get_post_meta( $post->ID, '_lightning_design_setting', true );

		foreach ( $hide_items as $key => $hide_item ) {
			$id      = '_lightning_design_setting[hide_' . $key . ']';
			$checked = '';
			if ( ! empty( $saved_post_meta[ 'hide_' . $key ] ) ) {
				$checked = ' checked';
			}
			$form .= '<li class="vk_checklist_item vk_checklist_item-style-vertical">' . '<input type="checkbox" id="' . $id . '" name="' . $id . '" value="true"' . $checked . '  class="vk_checklist_item_input"><label for="' . $id . '" class="vk_checklist_item_label">' . $hide_item['label'] . '</label></li>';
		}

		$form .= '</ul>';
		echo $form;
	}

	public static function hide_filters() {

		$hide_items = self::get_hide_items();
		foreach ( $hide_items as $key => $value ) {
			add_filter( "lightning_is_{$key}", array( __CLASS__, 'is_hide' ), 10, 2 );
		}

	}

	public static function is_hide( $return, $hook_point ) {
		if ( is_singular() ) {
			global $post;
			if ( ! empty( $post->_lightning_design_setting[ 'hide_' . $hook_point ] ) ) {
				$return = false;
			}
		}
		if ( is_front_page() ) {
			if ( 'page_header' === $hook_point || 'breadcrumb' === $hook_point ) {
				$return = false;
			}
		}
		return $return;
	}

	public static function css_hide() {
		if ( is_singular() ) {
			global $post;
			$saved_post_meta = get_post_meta( $post->ID, '_lightning_design_setting', true );

			$custom_css = '';
			if ( ! empty( $saved_post_meta['hide_mobile_menu_btn'] ) ) {
				$custom_css .= '.vk-mobile-nav-menu-btn { display:none; }';
			}
			if ( ! empty( $saved_post_meta['hide_mobile_fix_nav'] ) ) {
				$custom_css .= '.mobile-fix-nav { display:none; }';
			}
			if ( $custom_css ) {
				wp_add_inline_style( 'lightning-design-style', $custom_css );
			}
		}
	}
}

$ltg_project_hide_controller = new LTG_Hide_Controller();
