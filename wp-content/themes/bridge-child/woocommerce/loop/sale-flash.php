<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

?>
<?php 
	$new = get_post_meta($product->id, '_featured');
	if($new[0] === 'yes') {
		// label that product is new (featured)
		echo apply_filters('woocommerce_sale_flash', '<span class="onsale onsale-outter new"><span class="onsale-inner">'.__( 'NEW', 'woocommerce' ).'</span></span>', $post, $product); 
		return;
	}
?>
<?php if ($product->is_on_sale()) : ?>

	<?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale onsale-outter"><span class="onsale-inner">'.__( 'Sale', 'woocommerce' ).'</span></span>', $post, $product); ?>

<?php endif; ?>