//update token
$("form").submit(function () {
    $("input[name='" + csfr_token_name + "']").val($.cookie(csfr_cookie_name));
});

$(document).ready(function () {
    //main slider
    $('#main-slider').on('init', function (e, slick) {
        var $firstAnimatingElements = $('#main-slider .item:first-child').find('[data-animation]');
        doAnimations($firstAnimatingElements);
    });
    $('#main-slider').on('beforeChange', function (e, slick, currentSlide, nextSlide) {
        var $animatingElements = $('#main-slider .item[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
        doAnimations($animatingElements);
    });
    $('#main-slider').slick({
        autoplay: true,
        autoplaySpeed: 9000,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        speed: 500,
        fade: (slider_fade_effect == 1) ? true : false,
        swipeToSlide: true,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#main-slider-nav .prev'),
        nextArrow: $('#main-slider-nav .next'),
    });

    function doAnimations(elements) {
        var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        elements.each(function () {
            var $this = $(this);
            var $animationDelay = $this.data('delay');
            var $animationType = 'animated ' + $this.data('animation');
            $this.css({
                'animation-delay': $animationDelay,
                '-webkit-animation-delay': $animationDelay
            });
            $this.addClass($animationType).one(animationEndEvents, function () {
                $this.removeClass($animationType);
            });
        });
    }

    $('#product_slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 300,
        arrows: true,
        fade: true,
        infinite: false,
        swipeToSlide: true,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#product-slider-nav .prev'),
        nextArrow: $('#product-slider-nav .next'),
        asNavFor: '#product_thumbnails_slider'
    });
    $('#product_thumbnails_slider').slick({
        slidesToShow: 7,
        slidesToScroll: 1,
        speed: 300,
        focusOnSelect: true,
        arrows: false,
        infinite: false,
        vertical: true,
        centerMode: false,
        arrows: true,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#product-thumbnails-slider-nav .prev'),
        nextArrow: $('#product-thumbnails-slider-nav .next'),
        asNavFor: '#product_slider'
    });

    $(document).ready(function () {
        baguetteBox.run('.product-slider');
    });

    $(document).ajaxStop(function () {
        baguetteBox.run('.product-slider');
    });

    $(document).on('click', '#product_thumbnails_slider .slick-slide', function () {
        var index = $(this).attr("data-slick-index");
        $('#product_slider').slick('slickGoTo', parseInt(index));
    });

    $('#blog-slider').slick({
        autoplay: true,
        autoplaySpeed: 4900,
        infinite: true,
        speed: 200,
        swipeToSlide: true,
        cssEase: 'linear',
        lazyLoad: 'progressive',
        prevArrow: $('#blog-slider-nav .prev'),
        nextArrow: $('#blog-slider-nav .next'),
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    //rate product
    $(document).on('click', '.rating-stars .label-star', function () {
        $('#user_rating').val($(this).attr('data-star'));
    });

    //mobile memu
    $(document).on('click', '.btn-open-mobile-nav', function () {
        if ($("#navMobile").hasClass('nav-mobile-open')) {
            $("#navMobile").removeClass('nav-mobile-open');
            $('#overlay_bg').hide();
        } else {
            $("#navMobile").addClass('nav-mobile-open');
            $('#overlay_bg').show();
        }
    });
    $(document).on('click', '#overlay_bg', function () {
        $("#navMobile").removeClass('nav-mobile-open');
        $('#overlay_bg').hide();
    });
    //close menu
    $(document).on('click', '.close-menu-click', function () {
        $("#navMobile").removeClass('nav-mobile-open');
        $('#overlay_bg').hide();
    });

});

//show mobile menu subcategories
$(document).on('click', '.nav-link-show-subcategories', function () {
    var category_id = $(this).attr("data-category-id");
    var category_name = $(this).attr("data-category-name");
    var parent_id = $(this).attr("data-parent-id");
    var category_link = $(this).attr("data-category-link");

    $(".nav-mobile .navbar-nav .nav-item").addClass('nav-item-hidden');
    $(".nav-item-category-" + category_id).removeClass('nav-item-hidden');
    $("#mb_category_all").removeClass('nav-item');

    $("#mb_category_all").removeClass('nav-item-hidden');
    $("#mb_category_all").addClass('nav-item');
    document.getElementById("mb_category_all").innerHTML = '<a href="' + category_link + '" class="nav-link">' + txt_all + '</a>';

    $("#mb_category_back").empty();
    document.getElementById("mb_category_back").innerHTML = '<a href="javascript:void(0)" class="nav-link" data-category-id="' + category_id + '"><strong><i class="icon-angle-left"></i>&nbsp;' + category_name + '</strong></a>';
});

$(document).on('click', '#mb_category_back .nav-link', function () {
    var category_id = $(this).attr("data-category-id");

    $("#mb_category_back").empty();
    if (parent_id == 0) {
        $(".nav-mobile .navbar-nav .nav-item").addClass('nav-item-hidden');
        $(".nav-item-category-0").removeClass('nav-item-hidden');
    } else {
        var parent_id = $("#mb_nav_link_cat_" + category_id).attr("data-parent-id");
        var category_name = $("#mb_nav_link_cat_" + parent_id).attr("data-category-name");

        if (parent_id == 0) {
            $(".nav-mobile .navbar-nav .nav-item").addClass('nav-item-hidden');
            $(".nav-item-category-0").removeClass('nav-item-hidden');
        } else {
            $(".nav-mobile .navbar-nav .nav-item").addClass('nav-item-hidden');
            $(".nav-item-category-0").removeClass('nav-item-hidden');

            $(".nav-mobile .navbar-nav .nav-item").addClass('nav-item-hidden');
            $(".nav-item-category-" + parent_id).removeClass('nav-item-hidden');
            document.getElementById("mb_category_back").innerHTML = '<a href="javascript:void(0)" class="nav-link" data-category-id="' + parent_id + '"><strong><i class="icon-angle-left"></i>&nbsp;' + category_name + '</strong></a>';
        }
    }
});

//search
$(document).on('click', '.mobile-search .search-icon', function () {
    if ($(".mobile-search-form").hasClass("display-block")) {
        $(".mobile-search-form").removeClass("display-block");
        $(".mobile-search .search-icon i").removeClass("icon-close");
        $(".mobile-search .search-icon i").addClass("icon-search")
    } else {
        $(".mobile-search-form").addClass("display-block");
        $(".mobile-search .search-icon i").removeClass("icon-search");
        $(".mobile-search .search-icon i").addClass("icon-close")
    }
});

//custom scrollbar
$(function () {
    $('.filter-custom-scrollbar').overlayScrollbars({});
    $('.search-results-location').overlayScrollbars({});
    $('.messages-sidebar').overlayScrollbars({});
    if ($('#message-custom-scrollbar').length > 0) {
        var instance_message_scrollbar = OverlayScrollbars(document.getElementById('message-custom-scrollbar'), {});
        instance_message_scrollbar.scroll({y: "100%"}, 0);
    }
});

/*mega menu*/
$(".mega-menu .nav-item").hover(function () {
    var menu_id = $(this).attr('data-category-id');
    $("#mega_menu_content_" + menu_id).show();
    $(".large-menu-item").removeClass('active');
    $(".large-menu-item-first").addClass('active');
    $(".large-menu-content-first").addClass('active');
    //$("#menu-overlay").show();
}, function () {
    var menu_id = $(this).attr('data-category-id');
    $("#mega_menu_content_" + menu_id).hide();
    //$("#menu-overlay").hide();
});

$(".mega-menu .dropdown-menu").hover(function () {
    $(this).show();
}, function () {
});

$(".large-menu-item").hover(function () {
    var menu_id = $(this).attr('data-subcategory-id');
    $(".large-menu-item").removeClass('active');
    $(this).addClass('active');
    $(".large-menu-content").removeClass('active');
    $("#large_menu_content_" + menu_id).addClass('active');
}, function () {
});

//scrollup
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $(".scrollup").fadeIn()
    } else {
        $(".scrollup").fadeOut()
    }
});
$(".scrollup").click(function () {
    $("html, body").animate({scrollTop: 0}, 700);
    return false
});

$(function () {
    $(".search-select a").click(function () {
        $(".search-select .btn").text($(this).text());
        $(".search-select .btn").val($(this).text());
        $(".search_type_input").val($(this).attr("data-value"));
    });
});

$(document).on('click', '.quantity-select-product .dropdown-menu .dropdown-item', function () {
    $(".quantity-select-product .btn span").text($(this).text());
    $("input[name='product_quantity']").val($(this).text());
});


$(document).on('click', '.btn-modal-location', function () {
    var data = {
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/get_countries",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("dropdown_location").innerHTML = obj.content;
            }
        }
    });
});

//show phone number
$(document).on('click', '#show_phone_number', function () {
    $(this).hide();
    $("#phone_number").show();
});


/*
 *------------------------------------------------------------------------------------------
 * AUTH FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//login
$(document).ready(function () {
    $("#form_login").submit(function (event) {
        var form = $(this);
        if (form[0].checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            var inputs = form.find("input, select, button, textarea");
            var serializedData = form.serializeArray();
            serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
            serializedData.push({name: "sys_lang_id", value: sys_lang_id});
            $.ajax({
                url: base_url + "auth_controller/login_post",
                type: "post",
                data: serializedData,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.result == 1) {
                        location.reload();
                    } else if (obj.result == 0) {
                        document.getElementById("result-login").innerHTML = obj.error_message;
                    }
                }
            });
        }
        form[0].classList.add('was-validated');
    });
});

//send activation email
function send_activation_email(id, token) {
    $('#result-login').empty();
    $('.spinner-activation-login').show();
    var data = {
        'id': id,
        'token': token,
        'type': 'login',
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $('#submit_review').prop("disabled", true);
    $.ajax({
        type: "POST",
        url: base_url + "auth_controller/send_activation_email_post",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('.spinner-activation-login').hide();
                document.getElementById("result-login").innerHTML = obj.success_message;
            } else {
                location.reload();
            }
        }
    });
}

//send activation email register
function send_activation_email_register(id, token) {
    $('#result-register').empty();
    $('.spinner-activation-register').show();
    var data = {
        'id': id,
        'token': token,
        'type': 'register',
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $('#submit_review').prop("disabled", true);
    $.ajax({
        type: "POST",
        url: base_url + "auth_controller/send_activation_email_post",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                $('.spinner-activation-register').hide();
                document.getElementById("result-register").innerHTML = obj.success_message;
            } else {
                location.reload();
            }
        }
    });
}

/*
 *------------------------------------------------------------------------------------------
 * VARIATION FUNCTIONS
 *------------------------------------------------------------------------------------------
 */
function select_product_variation_option(variation_id, variation_type, selected_option_id) {
    var data = {
        'variation_id': variation_id,
        'selected_option_id': selected_option_id,
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "select-variation-option-post",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                if (obj.html_content_price != "") {
                    document.getElementById("product_details_price_container").innerHTML = obj.html_content_price;
                }
                if (obj.html_content_stock != "") {
                    document.getElementById("text_product_stock_status").innerHTML = obj.html_content_stock;
                    if (obj.stock_status == 0) {
                        $(".btn-product-cart").attr("disabled", true);
                    } else {
                        $(".btn-product-cart").attr("disabled", false);
                    }
                }
                if (obj.html_content_slider != "") {
                    $('#product_slider').slick('unslick');
                    $('#product_thumbnails_slider').slick('unslick');
                    document.getElementById("product_slider_container").innerHTML = obj.html_content_slider;
                    $('#product_slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        speed: 300,
                        arrows: true,
                        fade: true,
                        infinite: false,
                        swipeToSlide: true,
                        cssEase: 'linear',
                        lazyLoad: 'progressive',
                        prevArrow: $('#product-slider-nav .prev'),
                        nextArrow: $('#product-slider-nav .next'),
                        asNavFor: '#product_thumbnails_slider'
                    });
                    $('#product_thumbnails_slider').slick({
                        slidesToShow: 7,
                        slidesToScroll: 1,
                        speed: 300,
                        focusOnSelect: true,
                        arrows: false,
                        infinite: false,
                        vertical: true,
                        centerMode: false,
                        arrows: true,
                        cssEase: 'linear',
                        lazyLoad: 'progressive',
                        prevArrow: $('#product-thumbnails-slider-nav .prev'),
                        nextArrow: $('#product-thumbnails-slider-nav .next'),
                        asNavFor: '#product_slider'
                    });
                }
            }

            if (variation_type == 'dropdown') {
                get_sub_variation_options(variation_id, selected_option_id);
            }
        }
    });
}

function get_sub_variation_options(variation_id, selected_option_id) {
    var data = {
        "variation_id": variation_id,
        "selected_option_id": selected_option_id,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        url: base_url + "get-sub-variation-options",
        type: "POST",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 1) {
                if(selected_option_id == ""){
                    document.getElementById("variation_dropdown_" + obj.subvariation_id).innerHTML = "";
                }else{
                    document.getElementById("variation_dropdown_" + obj.subvariation_id).innerHTML = obj.html_content;
                }
            }
        }
    });
}

/*
 *------------------------------------------------------------------------------------------
 * NUMBER SPINNER FUNCTIONS
 *------------------------------------------------------------------------------------------
 */
//number spinner
$(document).on('click', '.product-add-to-cart-container .number-spinner button', function () {
    update_number_spinner($(this));
});

function update_number_spinner(btn) {
    var btn = btn,
        oldValue = btn.closest('.number-spinner').find('input').val().trim(),
        newVal = 0;
    if (btn.attr('data-dir') == 'up') {
        newVal = parseInt(oldValue) + 1;
    } else {
        if (oldValue > 1) {
            newVal = parseInt(oldValue) - 1;
        } else {
            newVal = 1;
        }
    }
    btn.closest('.number-spinner').find('input').val(newVal);
}


$(document).on("input keyup paste change", ".number-spinner input", function () {
    var val = $(this).val();
    val = val.replace(",", "");
    val = val.replace(".", "");
    if (!$.isNumeric(val)) {
        val = 1;
    }
    if (isNaN(val)) {
        val = 1;
    }
    $(this).val(val);
});

$(document).on("input paste change", ".cart-item-quantity .number-spinner input", function () {
    var data = {
        'product_id': $(this).attr("data-product-id"),
        'cart_item_id': $(this).attr("data-cart-item-id"),
        'quantity': $(this).val(),
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "update-cart-product-quantity",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
});
$(document).on("click", ".cart-item-quantity .btn-spinner-minus", function () {
    update_number_spinner($(this));
    var cart_id = $(this).attr("data-cart-item-id");
    if ($("#q-" + cart_id).val() != 0) {
        $("#q-" + cart_id).change();
    }
});
$(document).on("click", ".cart-item-quantity .btn-spinner-plus", function () {
    update_number_spinner($(this));
    var cart_id = $(this).attr("data-cart-item-id");
    $("#q-" + cart_id).change();
});

/*
 *------------------------------------------------------------------------------------------
 * REVIEW FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//make review
$(document).on('click', '#submit_review', function () {
    var user_rating = $.trim($('#user_rating').val());
    var user_review = $.trim($('#user_review').val());
    var product_id = $.trim($('#review_product_id').val());
    var limit = parseInt($("#product_review_limit").val());
    if (!user_rating) {
        $('.rating-stars').addClass('invalid-rating');
        return false;
    } else {
        $('.rating-stars').removeClass('invalid-rating');
    }
    var data = {
        "review": user_review,
        "rating": user_rating,
        "product_id": product_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $('#submit_review').prop("disabled", true);
    $.ajax({
        type: "POST",
        url: base_url + "product_controller/make_review",
        data: data,
        success: function (response) {
            $('#submit_review').prop("disabled", false);
            if (response == "voted_error") {
                $('.error-reviewed').show();
            } else if (response == "error_own_product") {
                $('.error-own-product').show();
            } else {
                document.getElementById("review-result").innerHTML = response;
            }
        }
    });
});

$(document).on('click', '.rate-product .rating-stars label', function () {
    $('.rate-product  .rating-stars label i').removeClass("icon-star");
    $('.rate-product  .rating-stars label i').addClass("icon-star-o");
    var selected_star = $(this).attr("data-star");
    $('.rate-product  .rating-stars label').each(function () {
        var star = $(this).attr("data-star");
        if (star <= selected_star) {
            $(this).find('i').removeClass("icon-star-o");
            $(this).find('i').addClass("icon-star");
        }
    });
});

$(document).on('click', '.rate-product .label-star-open-modal', function () {
    var product_id = $(this).attr("data-product-id");
    var rate = $(this).attr("data-star");
    $("#rateProductModal #review_product_id").val(product_id);
    $("#rateProductModal #user_rating").val(rate);
});
$(document).on('click', '.btn-add-review', function () {
    var product_id = $(this).attr("data-product-id");
    $("#rateProductModal #review_product_id").val(product_id);
});


//load more review
function load_more_review(product_id) {
    var limit = parseInt($("#product_review_limit").val());
    var data = {
        "product_id": product_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_review_spinner").show();
    $.ajax({
        method: "POST",
        url: base_url + "product_controller/load_more_review",
        data: data
    })
        .done(function (response) {
            setTimeout(function () {
                $("#load_review_spinner").hide();
                document.getElementById("review-result").innerHTML = response
            }, 1000);
        })
}

//delete review
function delete_review(review_id, product_id, user_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var limit = parseInt($("#product_review_limit").val());
            var data = {
                "id": review_id,
                "product_id": product_id,
                "user_id": user_id,
                "limit": limit,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                method: "POST",
                url: base_url + "product_controller/delete_review",
                data: data
            })
                .done(function (response) {
                    document.getElementById("review-result").innerHTML = response;
                })
        }
    });
}


/*
*------------------------------------------------------------------------------------------
* USER REVIEW FUNCTIONS
*------------------------------------------------------------------------------------------
*/

//add user review
$(document).on('click', '#submit_user_review', function () {
    var user_rating = $.trim($('#user_rating').val());
    var user_review = $.trim($('#user_review').val());
    var seller_id = $.trim($('#review_seller_id').val());
    var limit = parseInt($("#user_review_limit").val());

    if (!user_rating) {
        $('.rating-stars').addClass('invalid-rating');
        return false;
    } else {
        $('.rating-stars').removeClass('invalid-rating');
    }
    var data = {
        "review": user_review,
        "rating": user_rating,
        "seller_id": seller_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $('#submit_user_review').prop("disabled", true);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/add_user_review",
        data: data,
        success: function (response) {
            $('#submit_user_review').prop("disabled", false);
            if (response == "voted_error") {
                $('.error-reviewed').show();
            } else {
                location.reload();
            }
        }
    });
});

//load more user review
function load_more_user_review(seller_id) {
    var limit = parseInt($("#user_review_limit").val());
    var data = {
        "seller_id": seller_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_review_spinner").show();
    $.ajax({
        method: "POST",
        url: base_url + "ajax_controller/load_more_user_review",
        data: data
    })
        .done(function (response) {
            setTimeout(function () {
                $("#load_review_spinner").hide();
                document.getElementById("user-review-result").innerHTML = response
            }, 1000);
        })
}

//delete user review
function delete_user_review(review_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "review_id": review_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                method: "POST",
                url: base_url + "ajax_controller/delete_user_review",
                data: data
            })
                .done(function (response) {
                    location.reload();
                })
        }
    });
}


/*
 *------------------------------------------------------------------------------------------
 * PRODUCT COMMENT FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

$(document).ready(function () {
    //add comment
    $("#form_add_comment").submit(function (event) {
        event.preventDefault();
        var is_logged_in = true;
        var is_valid = true;
        var form_serialized = $("#form_add_comment").serializeArray();
        object_serialized = {};
        $(form_serialized).each(function (i, field) {
            object_serialized[field.name] = field.value;
            if (field.name == "g-recaptcha-response") {
                g_recaptcha = field.value;
            }
        });
        if ($("#form_add_comment").find("#comment_name").length > 0) {
            is_logged_in = false;
        }
        if (is_logged_in == false) {
            if (str_lenght(object_serialized.name) < 1) {
                $('#comment_name').addClass("is-invalid");
                is_valid = false;
            } else {
                $('#comment_name').removeClass("is-invalid");
            }
            if (str_lenght(object_serialized.email) < 1) {
                $('#comment_email').addClass("is-invalid");
                is_valid = false;
            } else {
                $('#comment_email').removeClass("is-invalid");
            }
            if (is_recaptcha_enabled == true && is_logged_in == false) {
                if (g_recaptcha == "") {
                    $("#form_add_comment .g-recaptcha").addClass("is-recaptcha-invalid");
                    is_valid = false;
                } else {
                    $("#form_add_comment .g-recaptcha").removeClass("is-recaptcha-invalid");
                }
            }
        }
        if (str_lenght(object_serialized.comment) < 1) {
            $('#comment_text').addClass("is-invalid");
            is_valid = false;
        } else {
            $('#comment_text').removeClass("is-invalid");
        }

        if (!is_valid) {
            return false;
        }

        form_serialized.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        form_serialized.push({name: "limit", value: parseInt($("#product_comment_limit").val())});
        form_serialized.push({name: "sys_lang_id", value: sys_lang_id});

        $.ajax({
            url: base_url + "ajax_controller/add_comment",
            type: "post",
            data: form_serialized,
            success: function (response) {
                if (is_recaptcha_enabled == true && is_logged_in == false) {
                    grecaptcha.reset();
                }
                $("#form_add_comment")[0].reset();
                var obj = JSON.parse(response);
                if (obj.type == 'message') {
                    document.getElementById("message-comment-result").innerHTML = obj.html_content;
                } else {
                    document.getElementById("comment-result").innerHTML = obj.html_content;
                }
            }
        });
    });
});

//add subcomment
$(document).on('click', '.btn-submit-subcomment', function () {
    var comment_id = $(this).attr("data-comment-id");
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#form_add_subcomment_" + comment_id).ajaxSubmit({
        beforeSubmit: function () {
            var is_logged_in = true;
            var is_valid = true;
            var g_recaptcha = "";
            if ($("#form_add_subcomment_" + comment_id).find(".form-comment-name").length > 0) {
                is_logged_in = false;
            }
            var form_serialized = $("#form_add_subcomment_" + comment_id).serializeArray();
            object_serialized = {};
            $(form_serialized).each(function (i, field) {
                object_serialized[field.name] = field.value;
                if (field.name == "g-recaptcha-response") {
                    g_recaptcha = field.value;
                }
            });
            if (is_logged_in == false) {
                if (object_serialized.name.length < 1) {
                    $(".form-comment-name").addClass("is-invalid");
                    is_valid = false;
                } else {
                    $(".form-comment-name").removeClass("is-invalid");
                }
                if (object_serialized.email.length < 1 || !is_email(object_serialized.email)) {
                    $(".form-comment-email").addClass("is-invalid");
                    is_valid = false;
                } else {
                    $(".form-comment-email").removeClass("is-invalid");
                }
                if (is_recaptcha_enabled == true) {
                    if (g_recaptcha == "") {
                        $("#form_add_subcomment_" + comment_id + ' .g-recaptcha').addClass("is-recaptcha-invalid");
                        is_valid = false;
                    } else {
                        $("#form_add_subcomment_" + comment_id + ' .g-recaptcha').removeClass("is-recaptcha-invalid");
                    }
                }
            }
            if (object_serialized.comment.length < 1) {
                $(".form-comment-text").addClass("is-invalid");
                is_valid = false;
            } else {
                $(".form-comment-text").removeClass("is-invalid");
            }
            if (is_valid == false) {
                return false;
            }
        },
        type: "POST",
        url: base_url + "ajax_controller/add_comment",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.type == 'message') {
                document.getElementById("sub_comment_form_" + comment_id).innerHTML = obj.html_content;
            } else {
                document.getElementById("comment-result").innerHTML = obj.html_content;
            }
        }
    });
});

//load more comment
function load_more_comment(product_id) {
    var limit = parseInt($("#product_comment_limit").val());
    var data = {
        "product_id": product_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_comment_spinner").show();
    $.ajax({
        url: base_url + "ajax_controller/load_more_comment",
        type: "POST",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.type == 'comments') {
                setTimeout(function () {
                    $("#load_comment_spinner").hide();
                    document.getElementById("comment-result").innerHTML = obj.html_content;
                }, 500);
            }
        }
    });
}

//validate email
function is_email(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

//get string lenght
function str_lenght(str) {
    if (str == "" || str == null) {
        return 0;
    }
    str = str.trim();
    return str.length;
}

//delete comment
function delete_comment(comment_id, product_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var limit = parseInt($("#product_comment_limit").val());
            var data = {
                "id": comment_id,
                "product_id": product_id,
                "limit": limit,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                url: base_url + "ajax_controller/delete_comment",
                type: "POST",
                data: data,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.type == 'comments') {
                        document.getElementById("comment-result").innerHTML = obj.html_content;
                    }
                }
            });
        }
    });
}

//show comment box
function show_comment_box(comment_id) {
    $('.visible-sub-comment').empty();
    var limit = parseInt($("#product_comment_limit").val());
    var data = {
        "comment_id": comment_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/load_subcomment_box",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.type == 'form') {
                $('#sub_comment_form_' + comment_id).append(obj.html_content);
            }
        }
    });
}

/*
 *------------------------------------------------------------------------------------------
 * BLOG COMMENTS FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

$(document).ready(function () {
    //add comment
    $("#form_add_blog_comment").submit(function (event) {
        event.preventDefault();
        var is_logged_in = true;
        var is_valid = true;
        var form_serialized = $("#form_add_blog_comment").serializeArray();
        object_serialized = {};
        $(form_serialized).each(function (i, field) {
            object_serialized[field.name] = field.value;
            if (field.name == "g-recaptcha-response") {
                g_recaptcha = field.value;
            }
        });
        if ($("#form_add_blog_comment").find("#comment_name").length > 0) {
            is_logged_in = false;
        }
        if (is_logged_in == false) {
            if (str_lenght(object_serialized.name) < 1) {
                $('#comment_name').addClass("is-invalid");
                is_valid = false;
            } else {
                $('#comment_name').removeClass("is-invalid");
            }
            if (str_lenght(object_serialized.email) < 1) {
                $('#comment_email').addClass("is-invalid");
                is_valid = false;
            } else {
                $('#comment_email').removeClass("is-invalid");
            }
            if (is_recaptcha_enabled == true && is_logged_in == false) {
                if (g_recaptcha == "") {
                    $("#form_add_blog_comment .g-recaptcha").addClass("is-recaptcha-invalid");
                    is_valid = false;
                } else {
                    $("#form_add_blog_comment .g-recaptcha").removeClass("is-recaptcha-invalid");
                }
            }
        }
        if (str_lenght(object_serialized.comment) < 1) {
            $('#comment_text').addClass("is-invalid");
            is_valid = false;
        } else {
            $('#comment_text').removeClass("is-invalid");
        }

        if (!is_valid) {
            return false;
        }

        form_serialized.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        form_serialized.push({name: "limit", value: parseInt($("#blog_comment_limit").val())});
        form_serialized.push({name: "sys_lang_id", value: sys_lang_id});
        $.ajax({
            url: base_url + "ajax_controller/add_blog_comment",
            type: "post",
            data: form_serialized,
            success: function (response) {
                if (is_recaptcha_enabled == true && is_logged_in == false) {
                    grecaptcha.reset();
                }
                $("#form_add_blog_comment")[0].reset();
                var obj = JSON.parse(response);
                if (obj.type == 'message') {
                    document.getElementById("message-comment-result").innerHTML = obj.html_content;
                } else {
                    document.getElementById("comment-result").innerHTML = obj.html_content;
                }
            }
        });
    });
});

//load more blog comment
function load_more_blog_comment(post_id) {
    var limit = parseInt($("#blog_comment_limit").val());
    var data = {
        "post_id": post_id,
        "limit": limit,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $("#load_comment_spinner").show();
    $.ajax({
        url: base_url + "ajax_controller/load_more_blog_comments",
        type: "post",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.type == 'comments') {
                setTimeout(function () {
                    $("#load_comment_spinner").hide();
                    document.getElementById("comment-result").innerHTML = obj.html_content;
                }, 500);
            }
        }
    });
}

//delete blog comment
function delete_blog_comment(comment_id, post_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var limit = parseInt($("#blog_comment_limit").val());
            var data = {
                "comment_id": comment_id,
                "post_id": post_id,
                "limit": limit,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                url: base_url + "ajax_controller/delete_blog_comment",
                type: "post",
                data: data,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.type == 'comments') {
                        document.getElementById("comment-result").innerHTML = obj.html_content;
                    }
                }
            });
        }
    });
}


/*
 *------------------------------------------------------------------------------------------
 * MESSAGE FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//delete conversation
function delete_conversation(conversation_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "conversation_id": conversation_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                method: "POST",
                url: base_url + "message_controller/delete_conversation",
                data: data
            })
                .done(function (response) {
                    location.reload();
                })

        }
    });
}


/*
 *------------------------------------------------------------------------------------------
 * CART FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//remove from cart
function remove_from_cart(cart_item_id) {
    var data = {
        "cart_item_id": cart_item_id,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "cart_controller/remove_from_cart",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
};

//update cart product quantity
$(document).on('click', '.btn-cart-product-quantity-item', function () {
    var quantity = $(this).val();
    var product_id = $(this).attr("data-product-id");
    var data = {
        "product_id": product_id,
        "quantity": quantity,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "cart_controller/update_cart_product_quantity",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
});

$(document).ready(function () {
    $('#use_same_address_for_billing').change(function () {
        if ($(this).is(":checked")) {
            $('.cart-form-billing-address').hide();
        } else {
            $('.cart-form-billing-address').show();
        }
    });
});

//approve order product
function approve_order_product(id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (approve) {
        if (approve) {
            var data = {
                "order_product_id": id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "order_controller/approve_order_product_post",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
};


/*
 *------------------------------------------------------------------------------------------
 * OTHER FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//AJAX search
$(document).on("input paste focus", "#input_search", function () {
    var search_type = $('.search_type_input').val();
    var input_value = $(this).val();
    if (input_value.length < 2) {
        $('#response_search_results').hide();
        return false;
    }
    var data = {
        "search_type": search_type,
        "input_value": input_value,
        "lang_base_url": lang_base_url,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/ajax_search",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("response_search_results").innerHTML = obj.response;
                $('#response_search_results').show();
            }
            //search text
            $('#response_search_results ul li a').wrapInTag({
                words: [input_value]
            });
        }
    });
});

$(document).on('click', function (e) {
    if ($(e.target).closest(".top-search-bar").length === 0) {
        $("#response_search_results").hide();
    }
});

//search location
$(document).on("input paste focus", "#input_location", function () {
    var input_value = $(this).val();
    if (input_value.length < 2) {
        $('#response_search_location').hide();
        return false;
    }
    var data = {
        "input_value": input_value,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/search_location",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("response_search_location").innerHTML = obj.response;
                $('#response_search_location').show();
            }
            //search text
            $('#response_search_location ul li a').wrapInTag({
                words: [input_value]
            });
        }
    });
});

$.fn.wrapInTag = function (opts) {
    function getText(obj) {
        return obj.textContent ? obj.textContent : obj.innerText;
    }

    var tag = opts.tag || 'strong',
        words = opts.words || [],
        regex = RegExp(words.join('|'), 'gi'),
        replacement = '<' + tag + '>$&</' + tag + '>';
    $(this).contents().each(function () {
        if (this.nodeType === 3) {
            $(this).replaceWith(getText(this).replace(regex, replacement));
        } else if (!opts.ignoreChildNodes) {
            $(this).wrapInTag(opts);
        }
    });
};

//set location
$(document).on("click", "#response_search_location ul li a", function () {
    var country_id = $(this).attr('data-country');
    var state_id = $(this).attr('data-state');
    var city_id = $(this).attr('data-city');
    $('.input-location-filter').remove();
    if (country_id != null && country_id != 0) {
        $('.filter-location').append("<input type='hidden' value='" + country_id + "' name='country' class='input-location-filter'>");
    }
    if (state_id != null && state_id != 0) {
        $('.filter-location').append("<input type='hidden' value='" + state_id + "' name='state' class='input-location-filter'>");
    }
    if (city_id != null && city_id != 0) {
        $('.filter-location').append("<input type='hidden' value='" + city_id + "' name='city' class='input-location-filter'>");
    }
    $('#form-product-filters').submit();
});

$(document).on('click', function (e) {
    if ($(e.target).closest(".filter-location").length === 0) {
        $("#response_search_location").hide();
    }
});

//set site language
function set_site_language(lang_id) {
    var data = {
        "lang_id": lang_id,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        method: "POST",
        url: base_url + "home_controller/set_site_language",
        data: data
    })
        .done(function (response) {
            location.reload();
        })
}


//load more posts
function load_more_promoted_products() {
    $("#load_promoted_spinner").show();
    var data = {
        'offset': parseInt($("#promoted_products_offset").val()),
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/load_more_promoted_products",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                setTimeout(function () {
                    $("#promoted_products_offset").val(obj.offset);
                    $("#row_promoted_products").append(obj.html_content);
                    $("#load_promoted_spinner").hide();
                    if (obj.hide_button) {
                        $(".promoted-load-more-container").hide();
                    }
                }, 300);
            } else {
                setTimeout(function () {
                    $("#load_promoted_spinner").hide();
                    if (obj.hide_button) {
                        $(".promoted-load-more-container").hide();
                    }
                }, 300);
            }
        }
    });
}

//delete product
function delete_product(product_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "id": product_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                method: "POST",
                url: base_url + "product_controller/delete_product",
                data: data
            })
                .done(function (response) {
                    location.reload();
                })

        }
    });
}

//delete draft
function delete_draft(product_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "id": product_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                method: "POST",
                url: base_url + "product_controller/delete_draft",
                data: data
            })
                .done(function (response) {
                    location.reload();
                })
        }
    });
}

//delete product digital file
function delete_product_digital_file(product_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "product_id": product_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                url: base_url + "file_controller/delete_digital_file",
                type: "post",
                data: data,
                success: function (response) {
                    document.getElementById("digital_files_upload_result").innerHTML = response;
                }
            });
        }
    });
}

//delete product video preview
function delete_product_video_preview(product_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "product_id": product_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                url: base_url + "file_controller/delete_video",
                type: "post",
                data: data,
                success: function (response) {
                    document.getElementById("video_upload_result").innerHTML = response;
                }
            });
        }
    });
}

//delete product audio preview
function delete_product_audio_preview(product_id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "product_id": product_id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                url: base_url + "file_controller/delete_audio",
                type: "post",
                data: data,
                success: function (response) {
                    document.getElementById("audio_upload_result").innerHTML = response;
                }
            });
        }
    });
}

//send message
$("#form_send_message").submit(function (event) {
    event.preventDefault();
    var message_subject = $('#message_subject').val();
    var message_text = $('#message_text').val();
    var message_receiver_id = $('#message_receiver_id').val();
    var message_send_em = $('#message_send_em').val();

    if (message_subject.length < 1) {
        $('#message_subject').addClass("is-invalid");
        return false;
    } else {
        $('#message_subject').removeClass("is-invalid");
    }
    if (message_text.length < 1) {
        $('#message_text').addClass("is-invalid");
        return false;
    } else {
        $('#message_text').removeClass("is-invalid");
    }
    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serializeArray();
    serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
    serializedData.push({name: "sys_lang_id", value: sys_lang_id});
    $inputs.prop("disabled", true);
    $.ajax({
        url: base_url + "message_controller/add_conversation",
        type: "post",
        data: serializedData,
        success: function (response) {
            $inputs.prop("disabled", false);
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("send-message-result").innerHTML = obj.html_content;
                $("#form_send_message")[0].reset();
            }
            //send email
            if (message_send_em) {
                send_message_as_email(obj.sender_id, message_receiver_id, message_subject, message_text);
            }
        }
    });
});

function send_message_as_email(message_sender_id, message_receiver_id, message_subject, message_text) {
    var data = {
        'email_type': 'new_message',
        'sender_id': message_sender_id,
        "receiver_id": message_receiver_id,
        "message_subject": message_subject,
        "message_text": message_text,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/send_email",
        data: data,
        success: function (response) {
        }
    });
}

function get_states(val, map) {
    $('#select_states').children('option').remove();
    $('#select_cities').children('option').remove();
    $('#get_states_container').hide();
    $('#get_cities_container').hide();
    var data = {
        "country_id": val,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/get_states",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("select_states").innerHTML = obj.content;
                $('#get_states_container').show();
            } else {
                document.getElementById("select_states").innerHTML = "";
                $('#get_states_container').hide();
            }
            if (map) {
                update_product_map();
            }
        }
    });
}

function get_cities(val, map) {
    var data = {
        "state_id": val,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/get_cities",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("select_cities").innerHTML = obj.content;
                $('#get_cities_container').show();
            } else {
                document.getElementById("select_cities").innerHTML = "";
                $('#get_cities_container').hide();
            }
            if (map) {
                update_product_map();
            }
        }
    });
}

function update_product_map() {
    var country_text = $("#select_countries").find('option:selected').text();
    var country_val = $("#select_countries").find('option:selected').val();
    var state_text = $("#select_states").find('option:selected').text();
    var state_val = $("#select_states").find('option:selected').val();
    var address = $("#address_input").val();
    var zip_code = $("#zip_code_input").val();
    var data = {
        "country_text": country_text,
        "country_val": country_val,
        "state_text": state_text,
        "state_val": state_val,
        "address": address,
        "zip_code": zip_code,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "product_controller/show_address_on_map",
        data: data,
        success: function (response) {
            document.getElementById("map-result").innerHTML = response;
        }
    });
}

$(document).on('change', '#address_input', function () {
    update_product_map();
});
$(document).on('change', '#zip_code_input', function () {
    update_product_map();
});

$(document).on('click', '.btn-add-remove-wishlist', function () {
    var product_id = $(this).attr("data-product-id");
    var reload_page = $(this).attr("data-reload");
    if (reload_page == "0") {
        if ($(this).find("i").hasClass("icon-heart-o")) {
            $(this).find("i").removeClass("icon-heart-o");
            $(this).find("i").addClass("icon-heart");
        } else {
            $(this).find("i").removeClass("icon-heart");
            $(this).find("i").addClass("icon-heart-o");
        }
    }
    var data = {
        "product_id": product_id,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "ajax_controller/add_remove_wishlist",
        data: data,
        success: function (response) {
            if (reload_page == "1") {
                location.reload();
            }
        }
    });
});

//set main image session
$(document).on('click', '.btn-set-image-main-session', function () {
    var file_id = $(this).attr('data-file-id');
    var data = {
        "file_id": file_id,
        "sys_lang_id": sys_lang_id
    };
    $('.badge-is-image-main').removeClass('badge-success');
    $('.badge-is-image-main').addClass('badge-secondary');
    $(this).removeClass('badge-secondary');
    $(this).addClass('badge-success');
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/set_image_main_session",
        data: data,
        success: function (response) {
        }
    });
});

//set main image
$(document).on('click', '.btn-set-image-main', function () {
    var image_id = $(this).attr('data-image-id');
    var product_id = $(this).attr('data-product-id');
    var data = {
        "image_id": image_id,
        "product_id": product_id,
        "sys_lang_id": sys_lang_id
    };
    $('.badge-is-image-main').removeClass('badge-success');
    $('.badge-is-image-main').addClass('badge-secondary');
    $(this).removeClass('badge-secondary');
    $(this).addClass('badge-success');
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/set_image_main",
        data: data,
        success: function (response) {
        }
    });
});

//delete product image session
$(document).on('click', '.btn-delete-product-img-session', function () {
    var file_id = $(this).attr('data-file-id');
    var data = {
        "file_id": file_id,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_image_session",
        data: data,
        success: function () {
            $('#uploaderFile' + file_id).remove();
        }
    });
});

//delete product image
$(document).on('click', '.btn-delete-product-img', function () {
    var file_id = $(this).attr('data-file-id');
    var data = {
        "file_id": file_id,
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_image",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
});

$("#form_validate").submit(function () {
    $('.custom-control-validate-input').removeClass('custom-control-validate-error');
    setTimeout(function () {
        $('.custom-control-validate-input .error').each(function () {
            var name = $(this).attr('name');
            if ($(this).is(":visible")) {
                name = name.replace('[]', '');
                $('.label_validate_' + name).addClass('custom-control-validate-error');
            }
        });
    }, 100);
});

$('.custom-control-validate-input input').click(function () {
    var name = $(this).attr('name');
    name = name.replace('[]', '');
    $('.label_validate_' + name).removeClass('custom-control-validate-error');
});

//hide cookies warning
function hide_cookies_warning() {
    $(".cookies-warning").hide();
    var data = {
        "sys_lang_id": sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "home_controller/cookies_warning",
        data: data,
        success: function (response) {
        }
    });
}

$("#form_validate").validate();
$("#form_validate_search").validate();
$("#form_validate_search_mobile").validate();
$("#form_validate_payout_1").validate();
$("#form_validate_payout_2").validate();
$("#form_validate_payout_3").validate();
$("#form_validate_newsletter").validate();
$("#form_add_cart").validate();
$("#form_validate_checkout").validate();
//validate product variations
$("#form_add_cart").submit(function () {
    $('#form_add_cart .custom-control-variation input').each(function () {
        if ($(this).hasClass('error')) {
            var id = $(this).attr('id');
            $('#form_add_cart .custom-control-variation label').each(function () {
                if ($(this).attr('for') == id) {
                    $(this).addClass('is-invalid');
                }
            });
        } else {
            var id = $(this).attr('id');
            $('#form_add_cart .custom-control-variation label').each(function () {
                if ($(this).attr('for') == id) {
                    $(this).removeClass('is-invalid');
                }
            });
        }
    });
});

$(document).on('click', '.custom-control-variation input', function () {
    var name = $(this).attr('name');
    $('.custom-control-variation label').each(function () {
        if ($(this).attr('data-input-name') == name) {
            $(this).removeClass('is-invalid');
        }
    });
});

$(document).ready(function () {
    $('.validate_terms').submit(function (e) {
        if (!$('.custom-control-validate-input input').is(":checked")) {
            e.preventDefault();
            $('.custom-control-validate-input').addClass('custom-control-validate-error');
        } else {
            $('.custom-control-validate-input').removeClass('custom-control-validate-error');
        }
    });
});

$(document).on("input keyup paste change", ".validate_price .price-input", function () {
    var val = $(this).val();
    val = val.replace(',', '.');
    if ($.isNumeric(val) && val != 0) {
        $(this).removeClass('is-invalid');
    } else {
        $(this).addClass('is-invalid');
    }
});

$('input[type=radio][name=product_type]').change(function () {
    if (this.value == 'digital') {
        $('.listing_ordinary_listing').hide();
        $('.listing_take_offers').hide();
        $('.listing_sell_on_site input').prop('checked', true);
    } else {
        $('.listing_ordinary_listing').show();
        $('.listing_take_offers').show();
    }
});

$(document).ready(function () {
    $('.validate_price').submit(function (e) {
        $('.validate_price .validate-price-input').each(function () {
            var val = $(this).val();
            if (val != '') {
                val = val.replace(',', '.');
                if ($.isNumeric(val) && val != 0) {
                    $(this).removeClass('is-invalid');
                } else {
                    e.preventDefault();
                    $(this).addClass('is-invalid');
                    $(this).focus();
                }
            }
        });
    });
});

$(document).on("input keyup paste change keypress", ".price-input", function () {
    if (typeof thousands_separator == 'undefined') {
        thousands_separator = '.';
    }
    if (thousands_separator == '.') {
        var $this = $(this);
        if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
                (event.which != 0 && event.which != 8))) {
            event.preventDefault();
        }
        var text = $(this).val();
        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
        }
    } else {
        var $this = $(this);
        if ((event.which != 44 || $this.val().indexOf(',') != -1) &&
            ((event.which < 48 || event.which > 57) &&
                (event.which != 0 && event.which != 8))) {
            event.preventDefault();
        }
        var text = $(this).val();
        if ((text.indexOf(',') != -1) &&
            (text.substring(text.indexOf(',')).length > 2) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
        }
    }
});

//full screen
$(document).ready(function () {
    $("iframe").attr("allowfullscreen", "")
});

//delete quote request
function delete_quote_request(id, message) {
    swal({
        text: message,
        icon: "warning",
        buttons: [sweetalert_cancel, sweetalert_ok],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            var data = {
                "id": id,
                "sys_lang_id": sys_lang_id
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "bidding_controller/delete_quote_request",
                data: data,
                success: function (response) {
                    location.reload();
                }
            });
        }
    });
}

/*
 *------------------------------------------------------------------------------------------
 * LICENSE KEY FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//add license key
function add_license_keys(product_id) {
    var data = {
        'product_id': product_id,
        'license_keys': $('#textarea_license_keys').val(),
        'allow_dublicate': $("input[name='allow_dublicate_license_keys']:checked").val(),
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "product_controller/add_license_keys",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("result-add-license-keys").innerHTML = obj.success_message;
                $('#textarea_license_keys').val('');
            }
        }
    });
}

//delete license key
function delete_license_key(id, product_id) {
    var data = {
        'id': id,
        'product_id': product_id,
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "product_controller/delete_license_key",
        data: data,
        success: function (response) {
            $('#tr_license_key_' + id).remove();
        }
    });
}

//update license code list on modal open
$("#viewLicenseKeysModal").on('show.bs.modal', function () {
    var product_id = $('#license_key_list_product_id').val();
    var data = {
        'product_id': product_id,
        'sys_lang_id': sys_lang_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "product_controller/refresh_license_keys_list",
        data: data,
        success: function (response) {
            document.getElementById("response_license_key").innerHTML = response;
        }
    });
});


$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).on('change', '#ckMultifileupload', function () {
    var MultifileUpload = document.getElementById("ckMultifileupload");
    if (typeof (FileReader) != "undefined") {
        var MultidvPreview = document.getElementById("ckMultidvPreview");
        MultidvPreview.innerHTML = "";
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        for (var i = 0; i < MultifileUpload.files.length; i++) {
            var file = MultifileUpload.files[i];
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement("IMG");
                img.src = e.target.result;
                img.id = "Multifileupload_image";
                MultidvPreview.appendChild(img);
                $("#Multifileupload_button").show();
            }
            reader.readAsDataURL(file);
        }
    } else {
        alert("This browser does not support HTML5 FileReader.");
    }
});
