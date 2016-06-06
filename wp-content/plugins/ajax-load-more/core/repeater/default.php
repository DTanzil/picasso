<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $qode_options_proya;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
    $woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
    return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
    $classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
    $classes[] = 'last';
?>
<?php $classes[] = 'product'; ?>
<li <?php post_class( $classes ); ?>>

    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <div class="top-product-section">

            <a href="<?php the_permalink(); ?>" class="product-category">
                <span class="image-wrapper">
                    <span><?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?></span>
                <?php
                    /**
                     * woocommerce_before_shop_loop_item_title hook
                     *
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_template_loop_product_thumbnail - 10
                     */
                    do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>
                </span>
            </a>
            
            <?php do_action('qode_woocommerce_after_product_image'); ?>

        </div>

        <span class="pi-parent-cats">             
            <?php 
                // list two parents categories of this product
                $product_id = $product->id;
                $cats = pi_get_product_categories($product_id);
                foreach ($cats as $key => $cat) {
                    $slug = $cat->slug;
                    echo "<a href='". get_term_link( $slug, 'product_cat' ) ."'>{$cat->name}</a>";
                    if($key%2 == 0 && count($cats) > 1) echo ', ';
                }
            ?>    
        </span>

        <a href="<?php the_permalink(); ?>" class="product-category product-info">
            <h6><?php the_title(); ?></h6>

            <?php if(isset($qode_options_proya['woo_products_show_title_sep']) && $qode_options_proya['woo_products_show_title_sep'] == 'yes') { ?>
                <div class="separator after-title-spearator small center"></div>
            <?php } ?>

            <?php
                /**
                 * woocommerce_after_shop_loop_item_title hook
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
        </a>

        <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>