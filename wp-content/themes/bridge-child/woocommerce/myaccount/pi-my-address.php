<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) {
	$page_title = apply_filters( 'woocommerce_my_account_my_address_title', __( 'My Addresses', 'woocommerce' ) );
	$get_addresses    = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing Address', 'woocommerce' ),
		'shipping' => __( 'Shipping Address', 'woocommerce' )
	), $customer_id );
} else {
	$page_title = apply_filters( 'woocommerce_my_account_my_address_title', __( 'My Address', 'woocommerce' ) );
	$get_addresses    = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' =>  __( 'Billing Address', 'woocommerce' )
	), $customer_id );
}

$col = 1;
?>

<p class="myaccount_address">
	<?php //echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); ?>
</p>

<?php if ( ! wc_ship_to_billing_address_only() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) echo '<div class="col2-set addresses">'; ?>

<?php foreach ( $get_addresses as $name => $title ) : ?>

	<div>
		<address>
			<?php
				$address = apply_filters( 'woocommerce_my_account_my_address_formatted_address', array(
					'first_name'  => get_user_meta( $customer_id, $name . '_first_name', true ),
					'last_name'   => get_user_meta( $customer_id, $name . '_last_name', true ),
					'company'     => get_user_meta( $customer_id, $name . '_company', true ),
					'address_1'   => get_user_meta( $customer_id, $name . '_address_1', true ),
					'address_2'   => get_user_meta( $customer_id, $name . '_address_2', true ),
					'city'        => get_user_meta( $customer_id, $name . '_city', true ),
					'state'       => get_user_meta( $customer_id, $name . '_state', true ),
					'postcode'    => get_user_meta( $customer_id, $name . '_postcode', true ),
					'country'     => get_user_meta( $customer_id, $name . '_country', true ),
					'phone'       => get_user_meta( $customer_id, $name . '_phone', true )
				), $customer_id, $name );

				$formatted_address = WC()->countries->get_formatted_address( $address );
			?>
		</address>

		<?php 
			// get full name for country and state
			global $woocommerce;
			$customer_country_code = $woocommerce->customer->get_country(); //country code
			$address['country'] = WC()->countries->countries[$address['country']];			
			$list_states = WC()->countries->get_states($customer_country_code);
			$address['state'] = $list_states[$address['state']];

			$result = array('Full Name' => "{$address['first_name']} {$address['last_name']}", 
							'Company Name' => "{$address['company']}",
							'Email Address' => "{$user->user_email}",
							'Phone Number' => "{$address['phone']}",
							'Date of Birth' => "{$user->account_dob_d} {$user->account_dob_m} {$user->account_dob_y}",
							'Address' => " {$address['address_1']}",
							'city' => "&nbsp;&nbsp;{$address['city']}, {$address['state']} {$address['postcode']}",
							'country' => "&nbsp;&nbsp;{$address['country']}",
						);
		?>
		
		<div class="pi-border">
			<?php foreach ($result as $key => $value) { ?>
				<?php $title = ($key == 'city' || $key == 'country') ? '' : $key; ?>
				<div><span class="pi-myaddress-title"><?php echo $title; ?></span><span class="pi-myaddress-separator"><?php if(!empty($title)) echo ":"; ?></span><?php echo $value; ?> </div>
			<?php } ?>
		</div>

		<div class="title" style="text-align:center;">

			<a id="pi-button" class="edit" href="<?php echo wc_get_endpoint_url( 'edit-address', $name ); ?>" target="_self" class="qbutton  medium center" style="color: #ffffff; background-color: #727272; padding:10px 70px;"><?php _e( 'EDIT YOUR PERSONAL INFORMATION', 'woocommerce' ); ?></a>
		</div>
	</div>

<?php endforeach; ?>

<?php if ( ! wc_ship_to_billing_address_only() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) echo '</div>'; ?>
