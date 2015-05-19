<?php
/**
 * Plugin Name: AffiliateWP - Sign Up Bonus
 * Plugin URI: http://affiliatewp.com/addons/sign-up-bonus/
 * Description: Entice more affiliates to register by offering them a sign up bonus
 * Author: Pippin Williamson and Andrew Munro
 * Author URI: http://affiliatewp.com
 * Version: 1.0.1
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
 *
 * @package Sign Up Bonus
 * @category Core
 * @author Andrew Munro
 * @version 1.0
 */


/**
 * Create the signup bonus
 * 
 * @since 1.0
 */
function affwp_sub_create_bonus( $affiliate_id = 0 ) {

	// return if no affiliate ID
	if ( ! $affiliate_id ) {
		return;
	}

	// get the sign up bonus
	$sign_up_bonus = affiliate_wp()->settings->get( 'sign_up_bonus' ); 

	// return if no sign up bonus
	if ( ! $sign_up_bonus ) {
		return;
	}

	$data = array(
		'affiliate_id' => $affiliate_id,
		'amount'       => $sign_up_bonus,
		'description'  => __( 'Sign Up Bonus', 'affiliatewp-sign-up-bonus' ),
		'status'       => 'unpaid'
	);

	// insert new referral for the sign up bonus
	affwp_add_referral( $data );
}

/**
 * Create a signup bonus when an affiliate registers
 * 
 * @since 1.0
 */
function affwp_sub_create_bonus_at_registration( $affiliate_id ) {

	// return if affiliates must be approved first
	if ( affiliate_wp()->settings->get( 'require_approval' ) ) {
		return;
	}
	
	// create the sign up bonus
	affwp_sub_create_bonus( $affiliate_id );

}
add_action( 'affwp_insert_affiliate', 'affwp_sub_create_bonus_at_registration' );

/**
 * Create the signup bonus once the affiliate has been approved
 * 
 * @since 1.0
 */
function affwp_sub_create_bonus_after_approval( $affiliate_id, $status, $old_status ) {

	if ( 'active' == $status && 'pending' == $old_status ) {
		// create the sign up bonus
		affwp_sub_create_bonus( $affiliate_id );
	}

}
add_action( 'affwp_set_affiliate_status', 'affwp_sub_create_bonus_after_approval', 10, 3 );

/**
 * Settings
 * 
 * @since 1.0
*/
function affwp_sub_admin_settings( $settings = array() ) {

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
add_filter( 'affwp_settings_integrations', 'affwp_sub_admin_settings' );