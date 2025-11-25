<?php
/**
 * VK Developer Tool
 *
 * @package VK Developer Tool
 */

 /**
  * VK Developer Tool
  */
class VK_Developer_Tool {

	public function __construct() {
		add_action( 'customize_register', array( __CLASS__, 'register_customize' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_css' ) );
		self::print_action_hook();
	}

	/**
	 * Print action hook point.
	 *
	 * @return void
	 */
	public static function print_action_hook() {
		if ( is_customize_preview() ) {
			$hooks = self::action_hook_list();
			foreach ( $hooks as $hook ) {
				add_action(
					$hook,
					function() use ( $hook ) {
						$option = get_option( 'vk_developer_tool_options' );
						if ( empty( $option['display_action_hook_position'] ) ) {
							return;
						}
						echo '<div class="vk-developer-tool-action-hook">';
						echo '<div class="vk-developer-tool-action-hook-text">' . esc_html( $hook ) . '</div>';
					},
					0
				);
				add_action(
					$hook,
					function() use ( $hook ) {
						$option = get_option( 'vk_developer_tool_options' );
						if ( empty( $option['display_action_hook_position'] ) ) {
							return;
						}
						echo '</div>';
					},
					2147483647
				);
			}
		}
	}

	public static function load_css() {
		if ( is_customize_preview() ) {
			$path    = wp_normalize_path( dirname( __FILE__ ) );
			$css_url = str_replace( wp_normalize_path( ABSPATH ), site_url() . '/', $path ) . '/css/developer-tool.css';
			wp_enqueue_style( 'vk-developer-tool', $css_url, array(), '0.1' );
		}
	}

	public static function action_hook_list() {
		return apply_filters( 'vk_developer_tool_action_hook_list', array() );
	}

	/**
	 * Default Option.
	 */
	public static function get_options() {
		$options = get_option( 'vk_developer_tool_options' );

		$default = array(
			'display_action_hook_position' => false,
		);
		return wp_parse_args( $options, $default );
	}

	/**
	 * Customizer.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer.
	 */
	public static function register_customize( $wp_customize ) {

		$wp_customize->add_setting(
			'lightning_develop_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new VK_Custom_Html_Control(
				$wp_customize,
				'lightning_develop_title',
				array(
					'label'            => __( 'Developer Tool', 'lightning-g3-pro-unit' ),
					'section'          => 'lightning_function',
					'type'             => 'text',
					'custom_title_sub' => '',
					'custom_html'      => '',
				)
			)
		);

		// Diaplay Setting.
		$wp_customize->add_setting(
			'vk_developer_tool_options[display_action_hook_position]',
			array(
				'default'           => false,
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => array( 'VK_Helpers', 'sanitize_boolean' ),
			)
		);
		$wp_customize->add_control(
			'vk_developer_tool_options[display_action_hook_position]',
			array(
				'label'       => __( 'Display Action Hook Position', 'lightning-g3-pro-unit' ),
				'section'     => 'lightning_function',
				'settings'    => 'vk_developer_tool_options[display_action_hook_position]',
				'type'        => 'checkbox',
				'description' => '<ul class="admin-custom-discription"><li>' . __( 'Display the position and name of the action hook only on the customize screen.', 'lightning-g3-pro-unit' ) . '</li><li>' . __( 'Some action hooks are not displayed even if checked.', 'lightning-g3-pro-unit' ) . '</li></ul>',
			)
		);

	}

	public static function make_file( $file_name ) {
		$action_hook_list = (array) self::action_hook_list();
		$hook_file        = fopen( $file_name, 'w' );

		// ファイル内で使う変数を無理矢理文字列変数化
		$get_option    = '$option = get_option( \'vk_developer_tool_options\' );';
		$common_option = '$option[\'display_action_hook_position\']';

		// 共通で使う文字列変数を定義
		$common_content = <<<EOM

		$get_option
		if ( empty( $common_option ) ) {
			return;
		}

EOM;

		// ファイルの中身
		$file_content = <<<EOM
<?php
/**
 * Developer Tool Action Hooks
 *
 * @package VK Developer Tool
 */


EOM;
		if ( ! empty( $action_hook_list ) ) {
			foreach ( $action_hook_list as $action_hook ) {
				$file_content .= <<<EOM

// $action_hook
add_action(
	'$action_hook',
	function(){
		$common_content
		?>
		<div class="vk-developer-tool-action-hook">
		<div class="vk-developer-tool-action-hook-text">$action_hook</div>
		<?php
	},
	0
);

add_action(
	'$action_hook',
	function () {
		$common_content
		?>
		</div>
		<?php
	},
	2147483647
);

EOM;

			}
		}

		fwrite( $hook_file, $file_content );
		fclose( $hook_file );
	}
}

new VK_Developer_Tool();
