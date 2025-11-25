<?php
if ( ! class_exists( 'main_404_customize' ) ) {
	class main_404_customize {

		public static $version = LIGHTNING_G3_PRO_UNIT_VERSION;

		public function __construct() {
			add_action( 'init', array( $this, 'add_post_type_post_type_404' ) );
			add_filter( 'lightning_is_main_section_template', array( $this, 'disable_default' ) );
			add_action( 'lightning_main_section_append', array( $this, 'display_404_content' ) );
		}

		public static function get_404_posts() {
			if ( is_404() ) {
				$args      = array(
					'posts_per_page'   => -1,
					'post_type'        => 'post_type_404',
					'post_status'      => 'publish',
					'order'            => 'ASC',
					'orderby'          => 'menu_order',
					'suppress_filters' => true,
				);
				$posts_404 = get_posts( $args );
				return $posts_404;
			}
			return;
		}

		public static function get_404_post() {
			$post404 = '';
			if ( is_404() ) {
				$posts_404 = self::get_404_posts();
				if ( $posts_404 ) {
					foreach ( $posts_404 as $post_404 ) {
						$post404 = $post_404;
					}
				}
			}
			return $post404;
		}

		public static function disable_default( $return ) {
			if ( is_404() ) {
				$posts_404 = self::get_404_posts();
				if ( $posts_404 ) {
					$return = false;
				}
			}
			return $return;
		}

		public static function display_404_content() {
			if ( is_404() ) {
				$post_404 = self::get_404_post();
				if ( $post_404 ) {
					echo apply_filters( 'the_content', $post_404->post_content );
					$url = get_edit_post_link( $post_404->ID );
					if ( $url ) {
						echo '<a href="' . esc_url( $url ) . '" class="vk_pageContent_editBtn btn btn-outline-primary btn-sm veu_adminEdit" target="_blank">' . __( 'Edit this area', 'vk-blocks' ) . '</a>';
					}
				}
			}
		}

		/**
		 * 404用投稿タイプ追加
		 *
		 * @return void
		 */
		public static function add_post_type_post_type_404() {
			register_post_type(
				'post_type_404', // カスタム投稿タイプのスラッグ.
				array(
					'labels'          => array(
						'name'          => __( '404 Page', 'lightning-g3-pro-unit' ),
						'singular_name' => __( '404 Page', 'lightning-g3-pro-unit' ),
					),
					'public'          => false,
					'show_ui'         => true,
					'show_in_menu'    => true,
					'menu_position'   => 20,
					'capability_type' => array( 'post_type_manage', 'post_type_manages' ),
					'map_meta_cap'    => true,
					'has_archive'     => false,
					'menu_icon'       => 'dashicons-admin-page',
					'supports'        => array( 'title', 'editor' ),
					'show_in_rest'    => true,
				)
			);
		}


	} // class main_404_customize {

	new main_404_customize();
}
