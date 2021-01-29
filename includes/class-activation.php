<?php
/**
 * Activation handler.
 *
 * @package     AffiliateWP\ActivationHandler
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AffiliateWP Activation Handler Class.
 *
 * @since       1.0.0
 */
class AffiliateWP_Activation {

	/**
	 * Plugin name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $plugin_name;

	/**
	 * Plugin path.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $plugin_path;

	/**
	 * Plugin file.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $plugin_file;

	/**
	 * Has AffiliateWP?
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	public $has_affiliatewp;

	/**
	 * Setup the activation class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_path Plugin path.
	 * @param string $plugin_file Plugin file.
	 * @return void
	 */
	public function __construct( $plugin_path, $plugin_file ) {
		// We need plugin.php!
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$plugins = get_plugins();

		// Set plugin directory.
		$plugin_path       = array_filter( explode( '/', $plugin_path ) );
		$this->plugin_path = end( $plugin_path );

		// Set plugin file.
		$this->plugin_file = $plugin_file;

		// Set plugin name.
		if ( isset( $plugins[ $this->plugin_path . '/' . $this->plugin_file ]['Name'] ) ) {
			$this->plugin_name = str_replace( 'AffiliateWP - ', '', $plugins[ $this->plugin_path . '/' . $this->plugin_file ]['Name'] );
		} else {
			$this->plugin_name = __( 'This plugin', 'affiliatewp-sign-up-bonus' );
		}

		// Is AffiliateWP installed?
		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( 'AffiliateWP' === $plugin['Name'] ) {
				$this->has_affiliatewp = true;
				break;
			}
		}
	}


	/**
	 * Process plugin deactivation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run() {
		// Display notice.
		add_action( 'admin_notices', array( $this, 'missing_affiliatewp_notice' ) );
	}

	/**
	 * Display notice if AffiliateWP isn't installed
	 *
	 * @since       1.0.0
	 */
	public function missing_affiliatewp_notice() {

		if ( $this->has_affiliatewp ) {
			echo '<div class="error"><p>' . esc_html( $this->plugin_name ) . ' ' . sprintf( __( 'requires %s. Please activate it to continue.', 'affiliatewp-sign-up-bonus' ), '<a href="http://affiliatewp.com/" target="_blank">AffiliateWP</a>' ) . '</p></div>';
		} else {
			echo '<div class="error"><p>' . esc_html( $this->plugin_name ) . ' ' . sprintf( __( 'requires %s. Please install it to continue.', 'affiliatewp-sign-up-bonus' ), '<a href="http://affiliatewp.com/" target="_blank">AffiliateWP</a>' ) . '</p></div>';
		}
	}
}
