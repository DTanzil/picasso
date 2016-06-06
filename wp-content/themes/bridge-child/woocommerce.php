<?php 
/*
Template Name: WooCommerce
*/ 
?>

<?php 
global $woocommerce;

$id = get_option('woocommerce_shop_page_id');
$shop = get_post($id);
$shop = get_post($id);
$sidebar = get_post_meta($id, "qode_show-sidebar", true);

if (get_query_var('paged')) {
    $paged = get_query_var('paged');
} elseif (get_query_var('page')) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

$content_style_spacing = "";
if(get_post_meta($id, "qode_margin_after_title", true) != ""){
	if(get_post_meta($id, "qode_margin_after_title_mobile", true) == 'yes'){
		$content_style_spacing = "padding-top:".esc_attr(get_post_meta($id, "qode_margin_after_title", true))."px !important";
	}else{
		$content_style_spacing = "padding-top:".esc_attr(get_post_meta($id, "qode_margin_after_title", true))."px";
	}
}

?>
<?php
    get_header();
    $id = get_option('woocommerce_shop_page_id');
?>
    <?php if(get_post_meta($id, "qode_page_scroll_amount_for_sticky", true)) { ?>
        <script>
        var page_scroll_amount_for_sticky = <?php echo get_post_meta($id, "qode_page_scroll_amount_for_sticky", true); ?>;
        </script>
    <?php } ?>
    <?php get_template_part( 'title' ); ?>

    <?php if($qode_options_proya['show_back_button'] == "yes") { ?>
        <a id='back_to_top' href='#'>
            <span class="fa-stack">
                <i class="fa fa-angle-up " style=""></i>
            </span>
        </a>
    <?php } ?>

    <?php
    $revslider = get_post_meta($id, "qode_revolution-slider", true);
    if (!empty($revslider)){ ?>
        <div class="q_slider"><div class="q_slider_inner">
        <?php echo do_shortcode($revslider); ?>
        </div></div>
    <?php
    }
    ?>
    <div class="container">

        <!-- Custom Breadcrumbs / Page categories link -->
        <div class="full_width">
            <div class="full_width_inner">
                <div class="vc_row wpb_row section vc_row-fluid  grid_section" style=" text-align:left;">
                    <div class=" section_inner clearfix"><div class="section_inner_margin clearfix"><div class="wpb_column vc_column_container vc_col-sm-12">
                        <div class="vc_column-inner "><div class="wpb_wrapper">
                            <div class="separator transparent" style="margin-top:20px; margin-bottom:20px;"></div>
                                <div class="wpb_text_column wpb_content_element ">
                                    <div class="wpb_wrapper">

                                        <?php
                                        $args = array(
                                                'delimiter' => '<span class="pi-slash"> / </span>',
                                                'home'        => _x( 'All', 'breadcrumb', 'woocommerce' ),
                                        );
                                        ?>
                                        <?php woocommerce_breadcrumb( $args ); ?>


                                    </div> 
                                </div> 
                </div></div></div></div></div></div>

                <div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;"><div class=" section_inner clearfix">
                    <div class="section_inner_margin clearfix"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner ">
                        <div class="wpb_wrapper">
                            <div class="separator small" style="background-color: #000; width:100%; height:1px;margin-top:30px;margin-bottom:0;"></div>
                                <div class="wpb_text_column wpb_content_element ">
                                    <div class="wpb_wrapper">                   
                                    </div> 
                                </div> 
                </div></div></div></div></div></div>
            </div>
        </div>

        <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
            <div class="overlapping_content"><div class="overlapping_content_inner">
        <?php } ?>
        <div class="container_inner default_template_holder clearfix" <?php qode_inline_style($content_style_spacing); ?>>
            <?php if(!is_singular('product')) { ?>
                <?php if($sidebar == "default" || $sidebar == "") : ?>
                    <?php woocommerce_content(); ?>
                <?php elseif($sidebar == "1" || $sidebar == "2"): ?>
                <?php global $woocommerce_loop;
                    $woocommerce_loop['columns'] = 3;
                ?>
                <?php if($sidebar == "1") : ?>
                    <div class="two_columns_66_33 woocommerce_with_sidebar grid2 clearfix">
                        <div class="column1">
                <?php elseif($sidebar == "2") : ?>
                    <div class="two_columns_75_25 woocommerce_with_sidebar grid2 clearfix">
                        <div class="column1">
                <?php endif; ?>
                            <div class="column_inner">
                                <?php woocommerce_content(); ?>
                            </div>
                        </div>
                        <div class="column2"><?php get_sidebar();?></div>
                    </div>
                <?php elseif($sidebar == "3" || $sidebar == "4"): ?>
                    <?php global $woocommerce_loop;
                        $woocommerce_loop['columns'] = 3;
                    ?>
                    <?php if($sidebar == "3") : ?>
                        <div class="two_columns_33_66 woocommerce_with_sidebar grid2 clearfix">
                            <div class="column1"><?php get_sidebar();?></div>
                            <div class="column2">
                    <?php elseif($sidebar == "4") : ?>
                        <div class="two_columns_25_75 woocommerce_with_sidebar grid2 clearfix">
                            <div class="column1"><?php get_sidebar();?></div>
                            <div class="column2">
                    <?php endif; ?>
                                <div class="column_inner">
                                    <?php woocommerce_content(); ?>
                                </div>
                            </div>
                        </div>
                <?php endif; ?>
            <?php } else {
                  woocommerce_content();
            } ?>
        </div>
        <?php if(isset($qode_options_proya['overlapping_content']) && $qode_options_proya['overlapping_content'] == 'yes') {?>
            </div></div>
        <?php } ?>
    </div>
<?php get_footer(); ?>