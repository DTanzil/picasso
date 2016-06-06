<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>

<?php 

	// prepare ajax load more button parameters
	$query = $wp_query->query_vars; 
	$orderby = $query['orderby'];
	$term = $query['term'];
	$order = $query['order'];
	$meta_key = $query['meta_key'];
	$meta_value = $query['meta_value'];
	$s = $query['s'];

	echo do_shortcode("[ajax_load_more button_label='LOAD MORE' post_type='product' offset='12' posts_per_page='8' orderby='{$orderby}' meta_key='{$meta_key}' search='{$s}' meta_value='{$meta_value}' meta_compare='IN' order='{$order}' taxonomy='product_cat' taxonomy_terms='{$term}' taxonomy_operator='IN']");
?>

<nav class="woocommerce-pagination">
</nav>
