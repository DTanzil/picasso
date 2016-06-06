jQuery(function($) {

    // if id exists on page, scroll to designated content area    
    if($("#scrollhere").length) {
        $('html, body').animate({
                scrollTop: $("#scrollhere").offset().top
        }, 3000);
    }

	// show customer reviews
	$("#pi-review-button").click(function(){
        // toggle only on first click 
		if ( $('#pi-customer-reviews').text().length == 0 ) {
			// $(".upsells").hide();
            $("#reviews").detach().appendTo($("#pi-customer-reviews"));
            $('html, body').animate({
                scrollTop: $("#pi-customer-reviews").offset().top
            }, 2000);
		}
	});


    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
           return null;
        }
        else{
           return results[1] || 0;
        }
    }

    // show customer reviews based on param value ?showreview=yes
    var showreview = $.urlParam('showreview');
    if(showreview == 'yes' && $("#reviews").length) {
        $("#pi-review-button").trigger( "click" );
    }
    
    // when clicking on product color selection dots, translate the selected color to hidden select dropdown 
    $("a.pi-color").click(function(){
        $(".pi-color span").removeClass("selected");
    	$(this).find("span").addClass("selected");
    	$('#pa_colour').find('option[selected="selected"]').removeAttr("selected");
    	var color = $(this).find("span").attr("title");
    	$option = $("#pa_colour").find("option[value='"+color+"']");
		$option.attr("selected", "selected");
    });


    // when clicking on product main image selection dots, change picture according to product gallery
    $(".images a.woocommerce-main-image").clone().removeClass("woocommerce-main-image").prependTo($(".thumbnails")); // clone shop product main image to list of thumbnails
   
    $(".pi-thumbnails-dots a").click(function(){
        $(".pi-thumbnails-dots a span").removeClass("selected");
        $("span", this).addClass("selected");
        var num = $("span", this).attr("tabindex");
    	var newImage = $(".thumbnails a:nth-child("+num+") img").clone();
    	var url = $(".thumbnails a:nth-child("+num+")").attr("href");
        var w = $(".images a.woocommerce-main-image img").width();

        $(".images a.woocommerce-main-image img").fadeOut(300, function(){ 
            $(".images a.woocommerce-main-image").attr("href", url);
            $(".images a.woocommerce-main-image img").attr("width", w).replaceWith(newImage).fadeIn(200); 
            // $(".images a.woocommerce-main-image img").fadeIn(200);
        });
    });

    $("span.pi-to-cart").click(function(){
        window.location = $(this).attr("href");
    });
  
    // Perform AJAX login on login form submit
    $('form#piajaxlogin').on('submit', function(e){

        $('form#piajaxlogin p.status').show().text(ajax_login_object.loadingmessage);
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#piajaxlogin #username').val(), 
                'password': $('form#piajaxlogin #password').val(), 
                'security': $('form#piajaxlogin #security').val() }})
            .done(function( html ) {
                $( "#results" ).append( html );
                $('form#piajaxlogin p.status').text(html.message);
                if (html.loggedin == true){
                    var url = ajax_login_object.redirecturl + "?showreview=yes";
                    window.location.replace(url);
                }
            });
        e.preventDefault();
    });








});