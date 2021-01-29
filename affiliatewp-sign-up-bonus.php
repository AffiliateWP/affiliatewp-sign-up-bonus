<?php
/**
 * Plugin Name: AffiliateWP - Sign Up Bonus
 * Plugin URI: http://affiliatewp.com/addons/sign-up-bonus/
 * Description: Entice more affiliates to register by offering them a sign up bonus
 * Author: Sandhills Development, LLC
 * Author URI: https://sandhillsdev.com
 * Version: 1.1
 * Text Domain: affiliatewp-sign-up-bonus
 * Domain Path: languages
 *
 * AffiliateWP is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * AffiliateWP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AffiliateWP. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AffiliateWP_Sign_Up_Bonus' ) ) {

	final class AffiliateWP_Sign_Up_Bonus {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of AffiliateWP_Sign_Up_Bonus exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0
		 */
		private static $instance;


		/**
		 * The version number of AffiliateWP
		 *
		 * @since 1.0
		 */
		private $version = '1.1';

		/**
		 * Main AffiliateWP_Sign_Up_Bonus Instance
		 *
		 * Insures that only one instance of AffiliateWP_Sign_Up_Bonus exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @static var array $instance
		 * @return The one true AffiliateWP_Sign_Up_Bonus
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Sign_Up_Bonus ) ) {

				self::$instance = new AffiliateWP_Sign_Up_Bonus;
				self::$instance->hooks();

			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.1
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-sign-up-bonus' ), '1.1' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.1
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-sign-up-bonus' ), '1.1' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.1
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.1
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.1
		 *
		 * @return void
		 */
		private function hooks() {

			// plugin meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );

			add_action( 'affwp_insert_affiliate', array( $this, 'create_bonus_for_new_affiliate' ) );

			add_action( 'affwp_set_affiliate_status', array( $this, 'create_bonus_after_approval' ), 10, 3 );

			add_action( 'affwp_new_affiliate_end', array( $this, 'new_affiliate_options' ) );

			add_filter( 'affwp_settings_integrations', array( $this, 'settings' ) );
		}

		/**
		 * Modify plugin metalinks
		 *
		 * @access      public
		 * @since       1.1
		 * @param       array $links The current links array
		 * @param       string $file A specific plugin table entry
		 * @return      array $links The modified links array
		 */
		public function plugin_meta( $links, $file ) {
		    if ( $file == plugin_basename( __FILE__ ) ) {
		        $plugins_link = array(
		            '<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-sign-up-bonus' ) . '" href="http://affiliatewp.com/addons/" target="_blank">' . __( 'More add-ons', 'affiliatewp-sign-up-bonus' ) . '</a>'
		        );

		        $links = array_merge( $links, $plugins_link );
		    }

		    return $links;
		}

		/**
		 * Create the signup bonus
		 *
		 * @since 1.0
		 */
		public function create_bonus( $affiliate_id = 0, $sign_up_bonus = 0 ) {

			// return if no affiliate ID
			if ( ! $affiliate_id ) {
				return;
			}

			// get the sign up bonus
			$amount = isset( $sign_up_bonus ) && $sign_up_bonus ? $sign_up_bonus : affiliate_wp()->settings->get( 'sign_up_bonus' );

			// return if no sign up bonus
			if ( ! $amount ) {
				return;
			}

			$data = array(
				'affiliate_id' => $affiliate_id,
				'amount'       => $amount,
				'description'  => __( 'Sign Up Bonus', 'affiliatewp-sign-up-bonus' ),
				'status'       => 'unpaid'
			);

			// insert new referral for the sign up bonus
			affwp_add_referral( $data );
		}

		/**
		 * Create a signup bonus when an affiliate is created
		 *
		 * @since 1.0
		 */
		public function create_bonus_for_new_affiliate( $affiliate_id ) {

			$has_sign_up_bonus = isset( $_POST['has_sign_up_bonus'] ) ? true : false;
			$sign_up_bonus     = isset( $_POST['sign_up_bonus'] ) ? $_POST['sign_up_bonus'] : affiliate_wp()->settings->get( 'sign_up_bonus' );
			$is_pending        = isset( $_POST['status'] ) && 'pending' == $_POST['status'] ? true : false;

			//var_dump( $is_pending ); wp_die();

			$version = get_option( 'affwp_version' );

			// affiliate added manually through admin
			if ( is_admin() ) {

				// 1.6.5 or lower did not have new status option
				if ( version_compare( $version, '1.7', '<' ) ) {

					// only create if require approval is off
					if ( ! affiliate_wp()->settings->get( 'require_approval' ) ) {
						$this->create_bonus( $affiliate_id );
					}

				} elseif ( $has_sign_up_bonus ) {

					if ( $is_pending ) {

						// if affiliate is set to pending status, store the sign up bonus in affiliate meta so this can be awarded to them when they are approved
						affwp_add_affiliate_meta( $affiliate_id, 'sign_up_bonus', $sign_up_bonus );

					} else {
						// With AffiliateWP 1.7 or higher you can enable/disable bonus on per-affiliate basis
						$this->create_bonus( $affiliate_id, $sign_up_bonus );
					}

				}

			} else {
				// affiliate registered via front-end
				// only create bonus if affiliates don't need to be approved
				if ( ! affiliate_wp()->settings->get( 'require_approval' ) ) {
					$this->create_bonus( $affiliate_id );
				}

			}

		}

		/**
		 * Create the signup bonus once the affiliate has been approved
		 *
		 * @since 1.0
		 */
		public function create_bonus_after_approval( $affiliate_id, $status, $old_status ) {

			// use amount from affiliate meta if there is one
			$sign_up_bonus = affwp_get_affiliate_meta( $affiliate_id, 'sign_up_bonus', true );
			$amount        = $sign_up_bonus ? $sign_up_bonus : '';

			if ( 'active' == $status && 'pending' == $old_status ) {
				// create the sign up bonus
				$this->create_bonus( $affiliate_id, $amount );

				// delete affiliate meta since we no longer need it
				if ( $sign_up_bonus ) {
					affwp_delete_affiliate_meta( $affiliate_id, 'sign_up_bonus' );
				}
			}


		}

		/**
		 * Add options to new affiliate admin screen
		 *
		 * @since 1.1
		 */
		public function new_affiliate_options() {
			$version = get_option( 'affwp_version' );

			if ( version_compare( $version, '1.7', '<' ) ) {
				return;
			}
		?>

			<tr class="form-row">

				<th scope="row">
					<label for="has_sign_up_bonus"><?php _e( 'Sign Up Bonus', 'affiliatewp-sign-up-bonus' ); ?></label>
				</th>

				<td>
					<input type="checkbox" name="has_sign_up_bonus" id="has_sign_up_bonus" value="1" <?php checked( 1, 1 ); ?> />
					<p class="description"><?php _e( 'Award sign up bonus?', 'affiliatewp-sign-up-bonus' ); ?></p>
				</td>

			</tr>

			<tr class="form-row">

				<th scope="row">
					<label for="sign_up_bonus"><?php _e( 'Sign Up Bonus Amount', 'affiliatewp-sign-up-bonus' ); ?></label>
				</th>

				<td>
					<input class="regular-text" type="text" name="sign_up_bonus" id="sign_up_bonus" value="<?php echo affiliate_wp()->settings->get( 'sign_up_bonus' ); ?>" />
					<p class="description"><?php _e( 'How much should this affiliate be awarded as a sign up bonus? Leaving blank will default to the global amount', 'affiliatewp-sign-up-bonus' ); ?></p>
				</td>

			</tr>

			<?php
		}

		/**
		 * Settings
		 *
		 * @since 1.0
		*/
		public function settings( $settings = array() ) {

			$settings[ 'sign_up_bonus' ] = array(
				'name' => __( 'Affiliate Sign Up Bonus', 'affiliatewp-sign-up-bonus' ),
				'desc' => __( 'Enter the amount an affiliate should receive when they register.', 'affiliatewp-sign-up-bonus' ),
				'type' => 'number',
				'size' => 'small',
				'step' => '1.0',
				'std' => ''
			);

			return $settings;

		}

	}

	/**
	 * The main function responsible for returning the one true AffiliateWP_Sign_Up_Bonus
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $affiliatewp_sign_up_bonus = affiliatewp_sign_up_bonus(); ?>
	 *
	 * @since 1.1
	 * @return object The one true AffiliateWP_Sign_Up_Bonus Instance
	 */
	function affiliatewp_sign_up_bonus() {

	    if ( ! class_exists( 'Affiliate_WP' ) ) {

	        if ( ! class_exists( 'AffiliateWP_Activation' ) ) {
	            require_once 'includes/class-activation.php';
	        }

	        $activation = new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
	        $activation = $activation->run();
	    } else {
	        return AffiliateWP_Sign_Up_Bonus::instance();
	    }

	}
	add_action( 'plugins_loaded', 'affiliatewp_sign_up_bonus', 100 );

}
