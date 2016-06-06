<?php
	
	if ( ! function_exists('write_log')) {
	    function write_log ( $log )  {
	      if ( is_array( $log ) || is_object( $log ) ) {
	         error_log( print_r( $log, true ) );
	      } else {
	         error_log( $log );
	      }
	    }
	}

	// enqueue the child theme stylesheet
	function wp_schools_enqueue_scripts() {
		wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
		wp_enqueue_style( 'childstyle' );

	}
	add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);

	function load_javascript_files() {
		wp_register_script('picasso_script', get_stylesheet_directory_uri() . '/js/picasso.js', array('jquery'), true );
		wp_enqueue_script('picasso_script');

		wp_localize_script( 'picasso_script', 'ajax_login_object', array( 
	        'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        'redirecturl' => 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",
	        'loadingmessage' => __('Sending user info, please wait...')
	    ));
	}
	add_action('wp_enqueue_scripts', 'load_javascript_files');

	// override testimonials shortcode
	function child_testimonials($atts, $content = null) {
        
        $deafult_args = array(
            "number"					=> "-1",
			"order_by"					=> "date",
			"order"						=> "DESC",
            "category"					=> "",
            "author_image"				=> "",
            "text_color"				=> "",
            "text_font_size"			=> "",
            "author_text_font_weight"	=> "",
            "author_text_color"			=> "",
			"author_text_font_size"		=> "",
            "show_navigation"			=> "",
            "navigation_style"			=> "",
            "auto_rotate_slides"		=> "",
            "animation_type"			=> "",
            "animation_speed"			=> ""
        );

        extract(shortcode_atts($deafult_args, $atts));

        $html                           = "";
        $testimonial_text_inner_styles  = "";
        $testimonial_p_style			= "";
        $navigation_button_radius		= "";
        $testimonial_name_styles        = "";

		if($text_font_size != "" || $text_color != ""){
			$testimonial_p_style = " style='";
			if($text_font_size != ""){
				$testimonial_p_style .= "font-size:". $text_font_size . "px;";
			}
			if($text_color != ""){
				$testimonial_p_style .= "color:". $text_color . ";";
			}
			$testimonial_p_style .= "'";
		}

        if($text_color != "") {
            $testimonial_text_inner_styles  .= "color: ".$text_color.";";
            $testimonial_name_styles        .= "color: ".$text_color.";";
        }

		if($author_text_font_weight != '') {
			$testimonial_name_styles .= 'font-weight: '.$author_text_font_weight.';';
		}

        if($author_text_color != "") {
            $testimonial_name_styles .= "color: ".$author_text_color.";";
        }

		if($author_text_font_size != "") {
			$testimonial_name_styles .= "font-size: ".$author_text_font_size."px;";
		}

        $args = array(
            'post_type' => 'testimonials',
            'orderby' => $order_by,
            'order' => $order,
            'posts_per_page' => $number
        );

        if ($category != "") {
            $args['testimonials_category'] = $category;
        }

		$animation_type_data = 'fade';
		switch($animation_type) {
			case 'fade':
			case 'fade_option' :
				$animation_type_data = 'fade';
				break;
			case 'slide':
			case 'slide_option':
				$animation_type_data = 'slide';
				break;
			default:
				$animation_type_data = 'fade';
		}

        $html .= "<div class='testimonials_holder clearfix ".$navigation_style."'>";
        $html .= '<div class="testimonials testimonials_carousel" data-show-navigation="'.$show_navigation.'" data-animation-type="'.$animation_type_data.'" data-animation-speed="'.$animation_speed.'" data-auto-rotate-slides="'.$auto_rotate_slides.'">';
        $html .= '<ul class="slides">';

        query_posts($args);
        if (have_posts()) :
            while (have_posts()) : the_post();
                $author = get_post_meta(get_the_ID(), "qode_testimonial-author", true);
                $website = get_post_meta(get_the_ID(), "qode_testimonial_website", true);
                $company_position = get_post_meta(get_the_ID(), "qode_testimonial-company_position", true);
                $text = get_post_meta(get_the_ID(), "qode_testimonial-text", true);
				$testimonial_author_image = wp_get_attachment_image_src(get_post_thumbnail_id(), "full");

                $html .= '<li id="testimonials' . get_the_ID() . '" class="testimonial_content">';
                $html .= '<div class="testimonial_content_inner"';

                $html .= '>';

				if($author_image == "yes"){
					$html .= '<div class="testimonial_image_holder">';
					$html .= '<img src="'. $testimonial_author_image[0] .'" />';
					$html .= '</div>';
				}
                $html .= '<div class="testimonial_text_holder">';
                $html .= '<div class="testimonial_text_inner" style="'.$testimonial_text_inner_styles.'">';
                
                $html .= '<p class="testimonial_author" style="'.$testimonial_name_styles.'">' . $author;

                if($website != "") {
                    $html .= '<span class="author_company_divider"> - </span><span class="author_company">' . $website.'</span>';
                }

                $html .= '</p>';
                $html .= '<p'. $testimonial_p_style .'>' . trim($text) . '</p>';

                
                $html .= '</div>'; //close testimonial_text_inner
                $html .= '</div>'; //close testimonial_text_holder

                $html .= '</div>'; //close testimonial_content_inner
                $html .= '</li>'; //close testimonials
            endwhile;
        else:
            $html .= __('Sorry, no posts matched your criteria.', 'qode');
        endif;

        wp_reset_query();
        $html .= '</ul>';//close slides
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

	function child_review_testimonials($atts, $content = null) { 
        
        $deafult_args = array(
            "number"					=> "-1",
			"order_by"					=> "date",
			"order"						=> "DESC",
            "category"					=> "",
            "author_image"				=> "",
            "text_color"				=> "",
            "text_font_size"			=> "",
            "author_text_font_weight"	=> "",
            "author_text_color"			=> "",
			"author_text_font_size"		=> "",
            "show_navigation"			=> "",
            "navigation_style"			=> "",
            "auto_rotate_slides"		=> "",
            "animation_type"			=> "",
            "animation_speed"			=> ""
        );

        extract(shortcode_atts($deafult_args, $atts));

        $html                           = "";
        $testimonial_text_inner_styles  = "";
        $testimonial_p_style			= "";
        $navigation_button_radius		= "";
        $testimonial_name_styles        = "";

		if($text_font_size != "" || $text_color != ""){
			$testimonial_p_style = " style='";
			if($text_font_size != ""){
				$testimonial_p_style .= "font-size:". $text_font_size . "px;";
			}
			if($text_color != ""){
				$testimonial_p_style .= "color:". $text_color . ";";
			}
			$testimonial_p_style .= "'";
		}

        if($text_color != "") {
            $testimonial_text_inner_styles  .= "color: ".$text_color.";";
            $testimonial_name_styles        .= "color: ".$text_color.";";
        }

		if($author_text_font_weight != '') {
			$testimonial_name_styles .= 'font-weight: '.$author_text_font_weight.';';
		}

        if($author_text_color != "") {
            $testimonial_name_styles .= "color: ".$author_text_color.";";
        }

		if($author_text_font_size != "") {
			$testimonial_name_styles .= "font-size: ".$author_text_font_size."px;";
		}

        $args = array(
            'post_type' => 'testimonials',
            'orderby' => $order_by,
            'order' => $order,
            'posts_per_page' => $number
        );

        if ($category != "") {
            $args['testimonials_category'] = $category;
        }

		$animation_type_data = 'fade';
		switch($animation_type) {
			case 'fade':
			case 'fade_option' :
				$animation_type_data = 'fade';
				break;
			case 'slide':
			case 'slide_option':
				$animation_type_data = 'slide';
				break;
			default:
				$animation_type_data = 'fade';
		}

	$args = array(
		'post_id' => $atts['id'],
		'orderby' => 'comment_date',
		'order' => 'DESC',
		'post_status' => 'publish',
		'post_type' => 'product',
		'status' => 'approve'
		);
	$comments = get_comments( $args ); 
	
	if($comments) {

		$html .= "<div class='testimonials_holder clearfix ".$navigation_style."'>";
        $html .= '<div class="testimonials testimonials_carousel" data-show-navigation="'.$show_navigation.'" data-animation-type="'.$animation_type_data.'" data-animation-speed="'.$animation_speed.'" data-auto-rotate-slides="'.$auto_rotate_slides.'">';
        $html .= '<ul class="slides">';

		foreach ($comments as $key => $comment) {
				$author = $comment->comment_author;
                
                $website = '';
                $company_position = '';
                $text = $comment->comment_content;
                // $website = get_post_meta(get_the_ID(), "qode_testimonial_website", true);
                // $company_position = get_post_meta(get_the_ID(), "qode_testimonial-company_position", true);
                // $text = get_post_meta(get_the_ID(), "qode_testimonial-text", true);
				// $testimonial_author_image = wp_get_attachment_image_src(get_post_thumbnail_id(), "full");

				$testimonial_author_image = get_avatar( $comments[$key], apply_filters( 'woocommerce_review_gravatar_size', '60' ), '' ); 
                
                $html .= '<li id="testimonials' . get_the_ID() . '" class="testimonial_content">';
                $html .= '<div class="testimonial_content_inner"';

                $html .= '>';

				if($author_image == "yes"){
					$html .= '<div class="testimonial_image_holder">';
					// $html .= '<img src="'. $testimonial_author_image[0] .'" />';
					$html .= $testimonial_author_image;
					$html .= '</div>';
				}
                $html .= '<div id="pi-customer-testimonials" class="testimonial_text_holder">';
                $html .= '<div class="testimonial_text_inner" style="'.$testimonial_text_inner_styles.'">';
                
                if($website != "") {
                    $html .= '<span class="author_company_divider"> - </span><span class="author_company">' . $website.'</span>';
                }

                $html .= '</p>';
                $html .= '<p'. $testimonial_p_style .'>' . trim($text) . '</p>';
				$html .= '<p class="testimonial_author" style="'.$testimonial_name_styles.'">' . $author;

                
                $html .= '</div>'; //close testimonial_text_inner
                $html .= '</div>'; //close testimonial_text_holder

                $html .= '</div>'; //close testimonial_content_inner
                $html .= '</li>'; //close testimonials
		}

		$html .= '</ul>';//close slides
        $html .= '</div>';
        $html .= '</div>';
        return $html;
	} 

	return '';
    }

  
	// remove testimonials shortcode, replace it with child testimonials shortcode
	function wpa_add_child_testimonials(){
		remove_shortcode('testimonials');
	    add_shortcode( 'testimonials', 'child_testimonials' );
   	    add_shortcode( 'reviewtestimonials', 'child_review_testimonials' );
	}
	add_action( 'wp_loaded', 'wpa_add_child_testimonials' );



	// get page hierarchy for Full Width with Menu Links page template 
	function pi_get_page_hierarchy($id){
		
		$children = array();
		$ancestors = get_post_ancestors($id);
		$parent = $ancestors[0];

		if($parent) { //if its a CHILD page
		    $children['parent'] = wp_list_pages("title_li=&include=".$parent."&echo=0&link_after=<span class='pi-slash'>/</span>");
		    $children['children'] = wp_list_pages("title_li=&child_of=".$parent."&echo=0&link_after=<span class='pi-slash'>/</span>");

		}  else { //if it's a PARENT page
		    $children['parent'] = wp_list_pages("title_li=&include=".get_the_ID()."&echo=0&link_after=<span class='pi-slash'>/</span>");
		    $children['children'] = wp_list_pages("title_li=&child_of=".get_the_ID()."&echo=0&link_after=<span class='pi-slash'>/</span>");
		}

		return $children;
	}

	// get individual product category parents
	function pi_get_product_categories($product_id){
		$cats = wc_get_product_terms($product_id, 'product_cat');
		$featured_cats = array_slice($cats, -2);
		return $featured_cats;		
	}

	// functions to change Woocommerce Rp to IDR
	add_filter( 'woocommerce_currencies', 'add_my_currency' );
	function add_my_currency( $currencies ) {
	     $currencies['IDR'] = __( 'Indonesian Rupiah', 'woocommerce' );
	     return $currencies;
	}

	add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);
	function add_my_currency_symbol( $currency_symbol, $currency ) {
	     switch( $currency ) {
	          case 'IDR': $currency_symbol = 'IDR'; break;
	     }
	     return $currency_symbol;
	}

	// create custom search product
	function pi_search_product() {

		$aa = '<label class="screen-reader-text" for="s">' . __( 'Search for:', 'woocommerce' ) . '</label>';

		$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div id="pi-search-product">
			<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'SEARCH PRODUCT ...', 'woocommerce' ) . '" />
			<input type="submit" id="searchsubmit" value="" style="visibility:hidden;" />
			<input type="hidden" name="post_type" value="product" />
		</div>
		</form>
		<div class="" style="margin-left:-20%;margin-right:-20%; margin-bottom:5%;border-bottom:1px solid black;"></div>';


		return $form;
	}

	// Change Woocommerce breadcrumb Home URL
	add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
	function woo_custom_breadrumb_home_url() {
		$shop_id = get_option('woocommerce_shop_page_id');
		$shop= get_page($shop_id);
	    return $shop->guid;
	}

	// Change Woocommerce catalog ordering 
	add_filter('woocommerce_catalog_orderby', 'pi_catalog_ordering');
	// modify shop page sorting display
	function pi_catalog_ordering() {
		$catalog_orderby_options = array(
			'menu_order' => __( 'Default sorting ', 'woocommerce' ),
			'date'       => __( 'Sort by newness', 'woocommerce' ),
			'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sort by price: high to low', 'woocommerce' )
		);
		return $catalog_orderby_options;
	}

	// Edit Woocommerce action hooks 
	// - remove woocommerce_result_count from product pages
	// - remove woocommerce_output_related_products from individual product page
	// - add pi_woocommerce_add_to_wishlist 
	// - add pi_woocommerce_reviews 
	function child_woocommerce_hooks() {
	    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
   	    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
   	    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

	    add_action('woocommerce_single_product_summary', 'pi_woocommerce_add_to_wishlist', 30);
	    add_action('woocommerce_after_single_product_summary', 'pi_woocommerce_reviews', 18);
	    add_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 15);
	}
	add_action( 'wp_loaded', 'child_woocommerce_hooks');

	// redirect all product review submission to the review submission page
	function pi_product_review_redirect($location) {
		return "http://picassohome.co.id/customer-reviews/";
	}
	add_filter( 'comment_post_redirect', 'pi_product_review_redirect', 2 );

	// add wishlist button to single product page
	function pi_woocommerce_add_to_wishlist() {
		echo '<div id="pi-single-product-wishlist">';
		echo do_shortcode('[yith_wcwl_add_to_wishlist label="<span>Add to Wishlist</span>"]'); 
		echo '</div>';
	}

	// place customer reviews above related products
	function pi_woocommerce_reviews(){
		echo '<div id="pi-customer-reviews"></div>';
	}


	/**
     * Output WooCommerce content. The Template for displaying product archives, including the main shop page which is a post type archive.
     *
     * This function is only used in the optional 'woocommerce.php' template
     * which people can add to their themes to add basic woocommerce support
     * without hooks or modifying core templates.
     *
     * @access public
     * @return void
     */
    function woocommerce_content() {

        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();
            	echo pi_search_product(); 
                woocommerce_get_template_part( 'content', 'single-product' );

            endwhile;

        } else {

            ?>

            <?php do_action( 'woocommerce_archive_description' ); ?>

            <?php if ( have_posts() ) : ?>

                <?php do_action('woocommerce_before_shop_loop'); ?>
                <!-- search product on individual page -->
                <?php echo pi_search_product(); ?>

                <?php woocommerce_product_loop_start(); ?>

                    <?php woocommerce_product_subcategories(); ?>
                    <?php var_dump("AFAF"); die(); ?>
                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php woocommerce_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action('woocommerce_after_shop_loop'); ?>

            <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

                <?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>

            <?php endif;

        }
    }


	/**
	 * Edit: Show colored dots for color attributes
	 * Output a list of variation attributes for use in the cart forms.
	 *
	 * @param array $args
	 * @since 2.4.0
	 */
	function wc_dropdown_variation_attribute_options( $args = array() ) {
		$args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
			'options'          => false,
			'attribute'        => false,
			'product'          => false,
			'selected' 	       => false,
			'name'             => '',
			'id'               => '',
			'class'            => '',
			'show_option_none' => __( 'Choose an option', 'woocommerce' )
		) );

		$options   = $args['options'];
		$product   = $args['product'];
		$attribute = $args['attribute'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class     = $args['class'];

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		//picasso edit: create colored dots for color attributes
		if ( $product && taxonomy_exists( $attribute ) && $attribute == 'pa_colour') {
			    $terms = get_terms($attribute);
			    foreach ($terms as $key => $term) {
			    	// create color selection dots
			    	if ( in_array( $term->slug, $options ) ) {
					    $class_selected = $term->slug == $args['selected'] ? "selected" : "";
					    echo "<a class='pi-color'><span title='". esc_attr( $term->slug ) ."' class='rectangle ". $class_selected ."' style='background-color:".$term->description.";'>&nbsp;</span></a>";
					}
			    }
		}
		
		// $custom_style = $attribute == 'pa_colour' ? "display:none;" : "";

		echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" style="' . esc_attr( $custom_style ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $args['show_option_none'] ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			// use dropdown unless attribute is colour
			if ( $product && taxonomy_exists( $attribute )) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
				}
			}	
		}

		echo '</select>';
	}

	add_action('woocommerce_before_add_to_cart_form', 'pi_static_color_attr');

	// create static version of color selection dots if applicable
	function pi_static_color_attr(){
		global $product;
		$attributes = $product->get_attributes();

		foreach ( $attributes as $attr ) :
			if ( empty( $attr['is_visible'] ) || ( $attr['is_taxonomy'] && ! taxonomy_exists( $attr['name'] ) ) ) continue;
			if ( $attr['name'] !== 'pa_colour' ) continue;
			
			$color_values = get_the_terms( $product->id, $attr['name']);

			echo '<div>'.wc_attribute_label( $attr['name'] ).':';
			foreach ( $color_values as $color ) {
				echo "<a><span title='". esc_attr( $color->slug ) ."' class='rectangle' style='background-color:".$color->description.";'>&nbsp;</span></a>";
			}
			echo '</div>';
		endforeach;
	}

	/**
	 * Add new register fields for WooCommerce registration.
	 *
	 * @return string Register fields HTML.
	 */
	function wooc_extra_register_fields() {
		?>

		<p class="form-row form-row-first">
		<label for="reg_billing_first_name"><?php _e( 'Name', 'woocommerce' ); ?> <span class="required">*</span></label>
			<input type="text" style="margin-right: 15px;" class="input-text pi-input-half-width" name="billing_first_name" placeholder="<?php _e('First', 'woocommerce'); ?>" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
			<input type="text" class="input-text pi-input-half-width" name="billing_last_name" placeholder="<?php _e('Last', 'woocommerce'); ?>" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
		</p>

		
		<div class="clear"></div>

		<?php
	}

	add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

	/**
	 * Validate the extra register fields.
	 *
	 * @param  string $username          Current username.
	 * @param  string $email             Current email.
	 * @param  object $validation_errors WP_Error object.
	 *
	 * @return void
	 */
	function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
		if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
			$validation_errors->add( 'billing_first_name_error', __( 'Please enter a valid first name.', 'woocommerce' ) );
		}

		if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
			$validation_errors->add( 'billing_last_name_error', __( 'Please enter a valid last name.', 'woocommerce' ) );
		}


		// if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
		// 	$validation_errors->add( 'billing_phone_error', __( '<strong>Error</strong>: Phone is required!.', 'woocommerce' ) );
		// }
	}

	add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );


	/**
	 * Save the extra register fields.
	 *
	 * @param  int  $customer_id Current customer ID.
	 *
	 * @return void
	 */
	function wooc_save_extra_register_fields( $customer_id ) {
		if ( isset( $_POST['billing_first_name'] ) ) {
			// WordPress default first name field.
			update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

			// WooCommerce billing first name.
			update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		}

		if ( isset( $_POST['billing_last_name'] ) ) {
			// WordPress default last name field.
			update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

			// WooCommerce billing last name.
			update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		}

		// if ( isset( $_POST['billing_phone'] ) ) {
		// 	// WooCommerce billing phone
		// 	update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
		// }
	}

	add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


	// /**
	//  * Validate the extra Account fields.
	//  *
	//  * @param  string $username          Current username.
	//  * @param  string $email             Current email.
	//  * @param  object $validation_errors WP_Error object.
	//  *
	//  * @return void
	//  */
	// function piuseracc_validate_extra_account_fields( $errors ) {
		
	// 	// var_dump($_POST);
	// 	//$errors->add( 'account_dob_m', __( 'Please enter a valid date of birth.', 'woocommerce' ) );
		
	// 	if ( !isset($_POST['account_dob_m']) || !isset($_POST['account_dob_d']) || !isset($_POST['account_dob_y']) ) {
	// 		$errors->add( 'account_dob_m', __( 'Please enter a valid date of birth.', 'woocommerce' ) );
	// 	}

	// 	if ( isset( $_POST['account_dob_m'] ) && $_POST['account_dob_m'] === "" ) {
			
	// 		$errors->add( 'account_dob_m', __( 'Please enter a valid date of birth.', 'woocommerce' ) );
	// 	}

	// 	if ( isset( $_POST['account_dob_d'] ) && $_POST['account_dob_d'] === "" ) {
	// 		$errors->add( 'account_dob_d', __( 'Please enter a valid date of birth.', 'woocommerce' ) );
	// 	}

	// 	if ( isset( $_POST['account_dob_y'] ) && $_POST['account_dob_y'] === "" ) {
	// 		$errors->add( 'account_dob_y', __( 'Please enter a valid date of birth.', 'woocommerce' ) );
	// 	}

	// 	// var_dump($errors);
	// 	// die();


	// }

	// add_action( 'woocommerce_save_account_details_errors', 'piuseracc_validate_extra_account_fields');


	/**
	 * Save the extra account date of birth fields.
	 *
	 * @param  int  $customer_id Current customer ID.
	 *
	 * @return void
	 */
	function piuseracc_save_extra_account_fields( $customer_id ) {
		if ( isset( $_POST['account_dob_m'] ) && isset( $_POST['account_dob_d'] ) && isset( $_POST['account_dob_y'] ) ) {
			update_user_meta( $customer_id, 'account_dob_m', sanitize_text_field( $_POST['account_dob_m'] ) );
			update_user_meta( $customer_id, 'account_dob_d', sanitize_text_field( $_POST['account_dob_d'] ) );
			update_user_meta( $customer_id, 'account_dob_y', sanitize_text_field( $_POST['account_dob_y'] ) );
		} else {
			wc_clear_notices();
			wc_add_notice( 'Date of birth' . ' ' . __( 'is a required field.', 'woocommerce' ), 'error' );
			wp_safe_redirect( 'http://picassohome.co.id/my-account/personal-information/edit-address/billing/' );
			die();
		}			
	}

	add_action( 'woocommerce_customer_save_address', 'piuseracc_save_extra_account_fields' );

	// redirect logged in users to my account page
	function pi_redirect_user_myaccount() {
		if (is_user_logged_in()) :
		    wp_safe_redirect(  wc_get_page_permalink( 'myaccount' ) );
		    exit;
		endif;		
	}

	// redirect guests to login page
	function pi_redirect_guest_login() {
		if (!is_user_logged_in()) :
		    wp_safe_redirect(  wc_get_page_permalink( 'myaccount' ) );
		    exit;
		endif;		
	}

	// Add specific CSS class by filter
	add_filter( 'body_class', 'pi_add_woocommerce_account_classes' );
	function pi_add_woocommerce_account_classes( $classes ) {
		// add 'class-name' to the $classes array
		global $post;
		if($post->post_name == "personal-information") {
			$classes[] = 'woocommerce-account woocommerce-page';
		}
		if($post->post_name == "order-history") {
			$classes[] = 'woocommerce-account woocommerce-page';
		}
		// return the $classes array
		return $classes;
	}

	// Handle required fields in saving account details
	add_filter( 'woocommerce_save_account_details_required_fields', 'pi_woocommerce_save_account_details_required_fields' );
	function pi_woocommerce_save_account_details_required_fields($required_fields) {
		$required_fields = array('account_email' => __( 'Email address', 'woocommerce' ));
		return $required_fields;
	}	

	// change My Account success messages
	add_filter( 'woocommerce_add_success', function( $message ) {
	    if( $message == 'Account details changed successfully.' ) {
	        $message = 'Password and email updated successfully.';	    	
	    } 
	    elseif ( $message == 'Address changed successfully.' ) {
	    	 $message = 'Account details updated successfully.';	 
	    }

	    return $message;
	});

	add_filter('woocommerce_lost_password_message', function($message) {
		$message = __( 'If you do not receive the confirmation link, please check your spam folder.', 'woocommerce' );
		return $message;
	});

	function wc_ninja_remove_password_strength() {
		if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
			wp_dequeue_script( 'wc-password-strength-meter' );
		}
	}
	add_action( 'wp_print_scripts', 'wc_ninja_remove_password_strength', 100 );

	// custom breadcrumbs in page content for custom template
	function pi_page_custom_breadcrumbs(){
		$links = pi_get_page_hierarchy($post->ID);
		echo '<div class="vc_row wpb_row section vc_row-fluid  grid_section" style=" text-align:left;"><div class=" section_inner clearfix"><div class="section_inner_margin clearfix"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="separator transparent" style="margin-top:20px; margin-bottom:20px;"></div><div class="wpb_text_column wpb_content_element "><div class="wpb_wrapper"><ul class="pi-page-hierarchy">';

			echo $links['parent'];
			echo $links['children'];
		
		echo '</div></div></div></div></div></div></div></div><div class="vc_row wpb_row section vc_row-fluid" style=" text-align:left;"><div class=" section_inner clearfix"><div class="section_inner_margin clearfix"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="separator small" style="background-color: #000; width:100%; height:1px;margin-top:30px; margin-bottom:20px;"></div><div class="wpb_text_column wpb_content_element "><div class="wpb_wrapper"></div></div></div></div></div></div></div></div>';
	}

	// insert starting html for fluid content in grid for custom template
	function pi_page_html_start(){
		echo '<div class="vc_row wpb_row section vc_row-fluid pi-page grid_section" style=" text-align:left;"><div class=" section_inner clearfix"><div class="section_inner_margin clearfix"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="wpb_text_column wpb_content_element "><div class="wpb_wrapper">';
	}

	// insert closing html for fluid content in grid for custom template
	function pi_page_html_end(){
		echo '</div></div> </div></div></div></div></div></div></div>';
	}

	/**
	 * Change the add to cart text on wishlist
	 */
	function pi_archive_custom_wishlist_button_text($id) {				
		foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$cart_product = $values['data'];
			if( $id == $cart_product->id ) return true;
		}
		return false;
	}

	/**
	 * Change the wishlist title on wishlist page
	 */
	add_filter( 'yith_wcwl_wishlist_title', 'pi_wcwl_wishlist_title'); 
	function pi_wcwl_wishlist_title($page_title) {
		$page_title = 'Wishlist';
		return '<h2>' . $page_title . '</h2>';
	}

	/**
	 * Add Hi, {first_name} to all the page once user logged in
	 */
	add_filter( 'wp_nav_menu_items', 'add_user_link', 10, 2 );
	function add_user_link( $items, $args ) {
		$current_user = wp_get_current_user();
		// $username = $current_user->user_login;
		$username = $current_user->user_firstname;
		
		//make sure to only display first name 
		if(preg_match('/\s/',$username))
			$username = chop($username);

	    if (is_user_logged_in() && $args->theme_location == 'right-top-navigation') {
			$hi_menu_item = "<span style='display:block; font-size:10px;text-transform:none; height:12px; margin-top:-12px;'>Hi,$username</span><span>MY ACCOUNT</span>";
			$items = str_replace("<span>MY ACCOUNT</span>",$hi_menu_item,$items);
	        // $items .= '<li><a id="pi-current-user" href="'. wc_get_page_permalink( 'myaccount' ) .'">Hi, '.$username.'</a></li>';
	    }

	    return $items;
	}

	// Woocommerce New Customer Admin Notification Email
	add_action('woocommerce_created_customer', 'admin_email_on_registration', '10', '3');
	function admin_email_on_registration($customer_id, $new_customer_data, $password_generated) {
		wp_new_user_notification( $customer_id );
	}

	// modify Woocommerce cart cross sells product total number
	add_filter( 'woocommerce_cross_sells_total', function($posts_per_page) {
		$posts_per_page = 1;
		return $posts_per_page;
	});
	// modify Woocommerce cart cross sells product total column
	add_filter( 'woocommerce_cross_sells_columns', function($columns) {
		$columns = 1;
		return $columns;
	});

	// modify Woocommerce recent order title on Order History page
	add_filter('woocommerce_my_account_my_orders_title', function($title){
		$title = 'Order History';
		return $title;
	});

	// modify Woocommerce view order url on Order History page
	add_filter('woocommerce_get_view_order_url', 'pi_get_view_order_url', 10, 2);
	function pi_get_view_order_url($view_order_url, $order) {
		$view_order_url = wc_get_endpoint_url( 'view-order', $order->id, 'http://picassohome.co.id/my-account/order-history/');
		return $view_order_url;
	};

	// modify Woocommerce my acount order columns on Order History page
	add_filter('woocommerce_my_account_my_orders_columns', function($my_orders_columns){
		$my_orders_columns = array(
			'order-number'  => __( 'Order ID', 'woocommerce' ),
			'order-date'    => __( 'Date', 'woocommerce' ),
			'order-total'   => __( 'Total Price', 'woocommerce' ),
			'order-status'  => __( 'Status', 'woocommerce' ),
			'order-actions' => '&nbsp;',
		);

		return $my_orders_columns;
	});
	
	// add_filter( 'woocommerce_shop_order_search_fields', 'woocommerce_shop_order_search_order_total' );
 
	// function woocommerce_shop_order_search_order_total( $search_fields ) {
	//   $search_fields[] = '_sku'; 
	//   return $search_fields;
	 
	// }



	// log user in from individual product page with ajax
	function ajax_login_init(){
	    // Enable the user with no privileges to run ajax_login() in AJAX
	    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
	}

	// Execute the action only if the user isn't logged in
	if (!is_user_logged_in()) {
	    add_action('init', 'ajax_login_init');
	}

	// process ajax login individual product page
	function ajax_login(){

	    // First check the nonce, if it fails the function will break
	    check_ajax_referer( 'ajax-login-nonce', 'security' );

	    // Nonce is checked, get the POST data and sign user on
	    $info = array();
	    $info['user_login'] = $_POST['username'];
	    $info['user_password'] = $_POST['password'];
	    $info['remember'] = true;

	    $user_signon = wp_signon( $info, false );
	    if ( is_wp_error($user_signon) ){
	        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
	    } else {
	        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
	    }

	    die();
	}

	function customer_reset_pw_success_redirect($user) {
		wp_redirect( add_query_arg( 'reset', 'true', wc_get_page_permalink( 'myaccount' ) ) );
		exit;
	}

	add_action( 'woocommerce_customer_reset_password', 'customer_reset_pw_success_redirect', 10, 2 );

	function customer_redirect_from_reset_pw() {
		if ( isset( $_GET['reset'] ) ) {
			wc_print_notice( __( 'Your password has been reset.', 'woocommerce' ) . ' <a href="' . wc_get_page_permalink( 'myaccount' ) . '">' . __( 'Log in', 'woocommerce' ) . '</a>' );
		} 
	}

	add_action('woocommerce_before_customer_login_form', 'customer_redirect_from_reset_pw');

	

	add_filter('woocommerce_default_address_fields', function($fields) {
		if($fields['city']) {
			$fields['city']['placeholder'] = _x( 'City name', 'placeholder', 'woocommerce' );
		}
		return $fields;
	});

	function pi_thankyou_order_received_text($text, $order) {
		$text = __( '<i class="fa fa-check" style="color:#279BAD;margin-right:5px;font-size:18px;"></i>Thank you, you have successfully placed an order. Please check your email for the invoice.', 'woocommerce' );
		return $text;
	}
	add_filter('woocommerce_thankyou_order_received_text', 'pi_thankyou_order_received_text', '10','2');


	add_filter('woocommerce_checkout_must_be_logged_in_message', function($text){
		$text = __( 'You must be logged in to checkout. If you do not have an account with us, <a href="http://picassohome.co.id/sign-up/">click here to sign up for an account.</a>', 'woocommerce' );
		return $text;
	});

	// this is just to prevent the user log in automatically after register
	function wc_registration_redirect( $redirect_to ) {
	        wp_logout();
	        wp_redirect( '/account-verification/?piact=errorneedactivate&q=');
	        exit;
	}
	// when user login, we will check whether this guy email is verify
	function wp_authenticate_user( $userdata ) {
	        $isActivated = get_user_meta($userdata->ID, 'is_activated', true);
	        $isAdmin = get_user_meta($userdata->ID, 'wp_user_level', 10);
	        
	        if ($isAdmin) return $userdata;
	        
	        if ( !$isActivated ) {
	        		$time = time();
	                $userdata = new WP_Error(
	                                'inkfool_confirmation_error',
	                                __( '<strong>ERROR:</strong> Your account has to be activated before you can login. <a href="/account-verification/?time='.$time.'&piact=resenduseremail&u='.$userdata->ID.'"> You can resend the email by clicking here</a>', 'inkfool' )
	                                );
	        }
	        return $userdata;
	}
	// when a user register we need to send them an email to verify their account
	function my_user_register($user_id) {
	        // get user data
	        $user_info = get_userdata($user_id);
	        // create md5 code to verify later
	        $code = md5(time());
	        // make it into a code to send it to user via email
	        $string = array('id'=>$user_id, 'code'=>$code);
	        
	        // create the activation code and activation status
	        update_user_meta($user_id, 'is_activated', 0);
	        update_user_meta($user_id, 'activationcode', $code);
	        

	        // create the url
	        $url = get_site_url(). '/account-verification/?piact=redirectfromactvurl&p=' .base64_encode( serialize($string));
	        // basically we will edit here to make this nicer
	        $html = 'Please click the following link to activate your account at Picasso Home: <br/><br/> <a target="_blank" href="'.$url.'">'.$url.'</a>';
	        
	        // send an email out to user
	        wc_mail($user_info->user_email, __('Please Verify Your Account'), $html);
	}

	function pi_send_cust_verification($user_id) {
		// get user data
        $user_info = get_userdata($user_id);

        $url = wc_get_page_permalink( 'myaccount' );
        $html = 'Congratulations! Your account at Picasso Home has now been activated. <br/><br/> You can access your account area to view your orders and change your password here:<a href="'.$url.'">'.$url.'</a> <br/><br/>If you have any questions, contact the administrator at picasso@picassohome.co.id';
        
        // send an email out to user
        wc_mail($user_info->user_email, __('Account Successfully Activated'), $html);
	}


	add_filter( 'pi_acc_message', 'pi_get_acc_message' );
	function pi_get_acc_message($message)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['piact'])) {

			$action = trim($_GET['piact']);
	        // check whether we get the activation message
	        if(isset($_GET['p']) && $action == 'redirectfromactvurl'){
	                $data = unserialize(base64_decode($_GET['p']));
	                $code = get_user_meta($data['id'], 'activationcode', true);
	                // check whether the code given is the same as ours
	                if($code != $data['code']){
        				$message = "<h3><strong>Error:</strong> Activation fails, please contact our administrator. </h3>";		                    
	                } 
	        }

			if(isset($_GET['q'])){
				if($action == 'errorneedactivate')
	                $message = "<h3>Thank You for registering to Picasso Home. Your account has to be activated before you can login. Please check your email.</h3>";
	         	if($action == 'successresendemail')
	                $message = "<h3><strong>Success:</strong> Your activation email has been sent. Please check your email.</h3>";
	               
				if($action == 'successactivateusr')
					$message = "<h3><strong>Success:</strong> Your account has been activated! </h3>";
	        }
	       
		}
		return $message;
	}

	// during initialize, display account verification pages if param variables are applicable
	function my_init(){
		
		$url = explode('?', 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		
		$postid = url_to_postid( $url[0] ); //http://picassohome.co.id/account-verification/

		$acc_message = "";

		// if page is meant for account verification purposes
		if($postid == 527) {
			if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['piact'])) {

				$action = trim($_GET['piact']);
		        // check whether we get the activation message
		        if(isset($_GET['p']) && $action == 'redirectfromactvurl'){
		                $data = unserialize(base64_decode($_GET['p']));
		                $code = get_user_meta($data['id'], 'activationcode', true);
		                // check whether the code given is the same as ours
		                if($code == $data['code']){
	                        // update the db on the activation process
	                        update_user_meta($data['id'], 'is_activated', 1);
	                        pi_send_cust_verification($data['id']);
	                        wp_safe_redirect( 'http://picassohome.co.id/account-verification/?piact=successactivateusr&q=' );	
	        				exit;
		                }
		        }
		       
		        if(isset($_GET['u']) && $action == 'resenduseremail'){
	                my_user_register($_GET['u']);
	        		wp_safe_redirect( 'http://picassohome.co.id/account-verification/?piact=successresendemail&q=' );	
	        		exit;
		        }			
			}
		}


	}
	// hooks handler
	add_action( 'init', 'my_init' );
	add_filter('woocommerce_registration_redirect', 'wc_registration_redirect');
	add_filter('wp_authenticate_user', 'wp_authenticate_user',10,2);
	add_action('user_register', 'my_user_register',10,2);

	add_action('personal_options', 'pi_personal_options');
	// add a column in user profile to show whether account has been activated
	function pi_personal_options($user)
	{
		$user_role = $user->roles[0];
		$user_id = $user->ID;
		$activated = get_user_meta($user_id,'is_activated'); 
		$spam = 'No';

		if(!empty($user_role) && ($user_role != 'customer' || $activated[0] === '1')) $spam = 'Yes';
	
		$text = "<p><b>Has user activated their account? <span style='margin-left:10px;font-weight:normal;'>$spam</span>";
		echo $text;
	}


?>
