<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$page_title   = ( $load_address === 'billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );
?>

<?php wc_print_notices(); ?>

<?php if ( ! $load_address ) : ?>

	<?php wc_get_template( 'myaccount/my-address.php' ); ?>

<?php else : ?>

<div class="wpb_column vc_column_container vc_col-sm-8 vc_col-md-8 vc_col-lg-6">
	<form method="post" id="customer_address">
		<h3><?php //echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h3>

		<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

		<?php foreach ( $address as $key => $field ) : ?>

			<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>

			<!-- place birth of date after company name -->
			<?php if($key == 'billing_company') { ?>
				<p class="form-row form-row-wide pi-select">
					<label for="account_dob"><?php _e( 'Date of Birth', 'woocommerce' ); ?><span class="required">*</span></label>
						<?php 
							$user = wp_get_current_user();
							$months = array('January','February','March','April','May','June','July','August','September','October','November','December');
							$dayStart = 1; $dayEnd = 31;
							$yearStart = 1947; $yearEnd = 2006;
						?>
						
						<!-- BOD Day -->
						<select name="account_dob_d">
							<option disabled <?php if(empty($user->account_dob_d)) echo "selected"; ?>> - Day - </option>
							<?php for ($i=$dayStart; $i <= $dayEnd; $i++) { ?>
								<?php $selected = (isset($user->account_dob_d) && $user->account_dob_d == $i) ? 'selected' : ''; ?>
								<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
							<?php } ?>
						</select>
						
						<!-- BOD Month -->
						<select name="account_dob_m">
							<option disabled <?php if(empty($user->account_dob_m)) echo "selected"; ?>> - Month -</option>
							<?php foreach ($months as $key => $value) { ?>
								<?php $selected = (isset($user->account_dob_m) && $user->account_dob_m == $value) ? 'selected' : ''; ?>
								<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>
												
						<!-- BOD Year -->
						<select name="account_dob_y">
							<option disabled <?php if(empty($user->account_dob_y)) echo "selected"; ?>> - Year - </option>
							<?php for ($i=$yearStart; $i <= $yearEnd; $i++) { ?>
								<?php $selected = (isset($user->account_dob_y) && $user->account_dob_y == $i) ? 'selected' : ''; ?>
								<option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
							<?php } ?>
						</select>


				</p>
				<!-- <input type="text" name="search" value="Go" style="float: right; margin-right:-13px;width: 45%;">
					<div style="overflow: hidden;">
				    <input type="text" name="term" style="width: 80%;overflow: hidden;">
				   </div> -->


			<?php } ?>

		<?php endforeach; ?>

		<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

		<p>
			<input type="submit" class="button" name="save_address" value="<?php esc_attr_e( 'Save Changes', 'woocommerce' ); ?>" />
			<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
			<input type="hidden" name="action" value="edit_address" />
		</p>

	</form>
</div>
<?php endif; ?>
