<?php
/**
 * Login Form
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php //if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="col2-set clearfix" id="customer_login">

    <div class="col-1">

<?php //endif; ?>

<?php //if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

    </div>

    <div class="col-2">

        <form method="post" class="register">

            <?php do_action( 'woocommerce_register_form_start' ); ?>


            <p class="form-row form-row-wide">
                <label><?php _e( 'Email Address', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="email" class="input-text placeholder" placeholder="<?php _e('Email', 'woocommerce'); ?>" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
            </p>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                <p class="form-row form-row-wide">
                    <label><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="text" class="input-text placeholder" placeholder="<?php _e('Username', 'woocommerce'); ?>" maxlength="10" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
                </p>

            <?php endif; ?>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
    
                <p class="form-row form-row-wide">
                    <label><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="password" class="input-text placeholder" placeholder="<?php _e('Password', 'woocommerce'); ?>" name="password" id="reg_password" />
                </p>

            <?php endif; ?>

            <!-- User Terms Agreement -->
            <p class="form-row form-row-wide">
               By clicking “Create Account” you agree to the terms and condition of the following <a href="#">Universal Terms of Service</a> and <a href="#">Privacy Policy.</a>
            </p>

            <!-- Spam Trap -->
            <div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

            <?php do_action( 'woocommerce_register_form' ); ?>
            <?php do_action( 'register_form' ); ?>

            <p class="form-row">
                <?php wp_nonce_field( 'woocommerce-register' ); ?>
                <input type="submit" class="button" name="register" value="<?php _e( 'Create Account', 'woocommerce' ); ?>" />
            </p>

            <?php do_action( 'woocommerce_register_form_end' ); ?>

        </form>

    </div>

</div>
<?php //endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>