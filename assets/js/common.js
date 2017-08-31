$(document).ready(function() {
    /* Search */
    $('.button-search').bind('click', function() {
        url = $('base').attr('href') + 'index.php?route=product/search';

        var filter_name = $('input[name=\'filter_name\']').attr('value')

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        location = url;
    });

    $('#header input[name=\'filter_name\']').keydown(function(e) {
        if (e.keyCode == 13) {
            url = $('base').attr('href') + 'index.php?route=product/search';

            var filter_name = $('input[name=\'filter_name\']').attr('value')

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            location = url;
        }
    });

    $('img.sprite').each(function() {
        _width = $(this).css('width') / 2;
    })


    /* Ajax Cart */
    $('#cart').live('mouseleave', function() {
        $('#cart .totals').removeClass('active');
        $('.cart-items').slideUp(100);
        $('.collapse-cart').slideUp(100);
    })
    $('.collapse-cart').live('click', function() {
        $('#cart .totals').removeClass('active');
        $('.cart-items').slideUp(500);
        $('.collapse-cart').slideUp(500);
    })


    $('#cart .totals').live('mouseenter', function() {

        _isHovering = true;

        if (!$('#cart .totals').hasClass('loaded')) {
            $.ajax({
                url: 'index.php?route=checkout/cart/update',
                dataType: 'json',
                success: function(json) {
                    if (json['output']) {
                        $('#cart .totals .cart-items').html(json['output']);
                        $('#cart .totals').live('mouseleave', function() {
                            _isHovering = false;
                        })

                        if ($('#cart .cart-items .empty').length < 1) {
                            $('.cart-items').animate({opacity: '1'}, 50, function() {

                                if (_isHovering == true) {
                                    $('#cart .totals').addClass('active');
                                    $('#cart .totals').addClass('loaded');
                                    $('.cart-items, .collapse-cart').show(0);
                                }
                            });
                        }
                    }
                }
            });
        } else {
            $('#cart .totals').live('mouseleave', function() {
                _isHovering = false;
            })

            if ($('#cart .cart-items .empty').length < 1) {
                $('.cart-items').animate({opacity: '1'}, 50, function() {

                    if (_isHovering == true) {
                        $('#cart .totals').addClass('active');
                        $('#cart .totals').addClass('loaded');
                        $('.cart-items, .collapse-cart').show(0);
                    }
                });
            }
        }


    })








    /* Mega Menu */
    $('#menu ul > li > a + div').each(function(index, element) {
        // IE6 & IE7 Fixes
        if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
            var category = $(element).find('a');
            var columns = $(element).find('ul').length;

            $(element).css('width', (columns * 143) + 'px');
            $(element).find('ul').css('float', 'left');
        }

        var menu = $('#menu').offset();
        var dropdown = $(this).parent().offset();

        i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

        if (i > 0) {
            $(this).css('margin-left', '-' + (i + 5) + 'px');
        }
    });

    // IE6 & IE7 Fixes
    if ($.browser.msie) {
        if ($.browser.version <= 6) {
            $('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');

            $('#column-right + #content').css('margin-right', '195px');

            $('.box-category ul li a.active + ul').css('display', 'block');
        }

        if ($.browser.version <= 7) {
            $('#menu > ul > li').bind('mouseover', function() {
                $(this).addClass('active');
            });

            $('#menu > ul > li').bind('mouseout', function() {
                $(this).removeClass('active');
            });
        }
    }

    $('.success img, .warning img, .attention img, .information img').live('click', function() {
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
        });
    });

    $('.hide-popup').bind('click', function() {
        $('.popup').fadeOut();
        return false;
    })
    hovering_inside_popup = false;
    $('.popup-content').hover(function() {
        hovering_inside_popup = true;
    }, function() {
        hovering_inside_popup = false;
    });
    $('.popup').bind('click', function() {
        if (!hovering_inside_popup) {
            $('.popup').fadeOut();
        }
    })

    $('.display-popup').bind('click', function() {
        $($(this).attr('href')).fadeIn();
        return false;
    })

    $('#login-password').keydown(function(e) {
        if (e.keyCode == 13) {
            $(this).parent('form').submit();
            return false;
        }
    });

    $('#search_box').live('click', function() {
        $('#search_field').focus();
    })

    $('#search_field').focus(function() {
        $('#search_box').addClass('focus');
        $(this).focusout(function() {
            $('#search_box').removeClass('focus');
        })

    });

    $(document).scroll(function() {
        if (($(window).scrollTop()) < 60) {
            $('#cart').removeClass('fixed');
        }
        if (($(window).scrollTop()) < 10) {
            $('.header-account-bar').removeClass('fixed');
        }
    })

    $('.wrapper').each(function() {
        html = $(this).html();
        if (html.length < 1) {
            $(this).hide();
        }
    })


    $('#login_header #login-email, #login_header #login-password').change(function() {
        $('#login-response').fadeOut('fast');
        $('#login_header #login-email, #login_header #login-password').removeClass('error');
    })

    $('#login_header').live('submit', function() {
        $.ajax({
            url: 'index.php?route=account/login/ajax',
            type: 'post',
            data: $('#login_header #login-email, #login_header #login-password, #login_header input[name=redirect]'),
            dataType: 'json',
            success: function(json) {
                if (json['fail']) {

                    $('#login-response > div').html(json['fail']);
                    $('#login_header #login-email, #login_header #login-password').removeClass('error');

                    $('#login-response').fadeOut('fast', function() {
                        $('#login_header #login-email').select();
                        $('#login_header #login-email, #login_header #login-password').addClass('error');
                        $('#login-response').fadeIn('fast');
                    });

                }

                if (json['success']) {
                    location = json['success'];
                }
            }
        })
        return false;
    });
});

function addToCart(product_id) {
    
    $.ajax({
        url: 'index.php?route=checkout/cart/update',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information, .error').remove();

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                    $('.warning').fadeIn('slow');

                    /*
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');*/
                }
            }

            if (json['success']) {
                $('#cart_total').html(json['total']);

                if (json['output']) {
                    $('#cart .totals .cart-items').html(json['output']);
                }

                if ($(window).scrollTop() > 65) {
                    $('#cart').addClass('fixed');
                }
                
                if ($(window).scrollTop() > 15) {
                    $('.header-account-bar').addClass('fixed');
                }

                if ($('#cart').hasClass('Empty')) {
                    if (!$('body').hasClass('new-vehicle-parts')) {
                        $('#cart').animate({marginLeft: '0px'}, 100);
                    }
                    $('#cart .totals .cart-items').animate({width: '240px'}, 100);
                    if (!$('body').hasClass('new-vehicle-parts')) {
                        $('#cart .totals').animate({width: '230px'}, 100, function() {
                            $('#cart').removeClass('Empty');
                        });
                    } else {
                        $('#cart').removeClass('Empty');
                    }
                }

                $('#cart .totals').addClass('active');
                $('.cart-items').slideDown(500, function() {

                });
                $('.collapse-cart').slideDown(500);

                /*$('html, body').animate({scrollTop: 0}, 300);*/
            }
        }
    });
}

function removeCart(key) {
    $.ajax({
        url: 'index.php?route=checkout/cart/update',
        type: 'post',
        data: 'remove=' + key,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['output']) {

                $('#cart .totals .cart-items').html(json['output']);

                $('#cart_total').html(json['total']);

                $('#cart .content').html(json['output']);
            }
        }
    });
}

function removeVoucher(key) {
    $.ajax({
        url: 'index.php?route=checkout/cart/update',
        type: 'post',
        data: 'voucher=' + key,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['output']) {
                $('#cart_total').html(json['total']);

                $('#cart .content').html(json['output']);
            }
        }
    });
}

function addToWishList(product_id) {
    $.ajax({
        url: 'index.php?route=account/wishlist/update',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#wishlist_total').html(json['total']);

                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        }
    });
}

function addToCompare(product_id) {
    $.ajax({
        url: 'index.php?route=product/compare/update',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();

            if (json['success']) {
                $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                $('.success').fadeIn('slow');

                $('#compare_total').html(json['total']);

                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        }
    });
}
