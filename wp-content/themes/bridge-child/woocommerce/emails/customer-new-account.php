<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>


<?php 


			// load customer data and user email into email template
			// $user_email = $user_login;
			// $user       = get_user_by('login', $user_login);
			// if ( $user ) {
			//     $user_email = $user->user_email;
			// }

	  //       // create md5 code to verify later
	        
			// $code = get_user_meta($user->ID, 'activationcode', true); 

	  //       // make it into a code to send it to user via email
	  //       $string = array('id'=>$user->ID, 'code'=>$code);

	  //        // create the url
	  //       $url = get_site_url(). '/my-account/?p=' .base64_encode( serialize($string));
	        // basically we will edit here to make this nicer
	        // $html = 'Please click the following links <br/><br/> <a href="'.$url.'">'.$url.'</a>';

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'woocommerce' ), esc_html( $blogname ), esc_html( $user_login ) ); ?></p>

<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>

	<p><?php printf( __( "Your password has been automatically generated: <strong>%s</strong>", 'woocommerce' ), esc_html( $user_pass ) ); ?></p>

<?php endif; ?>

<p><?php printf( __( 'You would receive another email that contains a confirmation link. Please use that link to activate your account.', 'woocommerce' )); ?></p>
<p><?php printf( __( 'Once activated, you can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), wc_get_page_permalink( 'myaccount' ) ); ?></p>

<?php $customer_id = get_current_user_id(); ?>


<?php do_action( 'woocommerce_email_footer', $email ); ?>