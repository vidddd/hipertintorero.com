jQuery(document).ready(function() {
    jQuery('.mega-menu >ul>li>ul').removeClass('sub-menu');
    jQuery('.search-cart .search-box form').addClass('search-form');
    jQuery('.search-cart .search-box form input[type=search]').addClass('search-field');
    jQuery('form.search-form input[type=search]').attr('placeholder','Search');
    jQuery('.product-thumb .uc-product-add-to-cart-form input[type=submit]').addClass('btn cart-button');
    jQuery('aside.widget-search form input[type=search]').attr('placeholder','Search');
    
    jQuery('.feature-style-2 .row .col-sm-4').eq(1).find('.feature-item').addClass('feature-item_clock');
    jQuery('.feature-style-2 .row .col-sm-4').eq(2).find('.feature-item').addClass('feature-item_price');
    
    var submitIcon = $('.search-box-icon .fa-search');
    var inputBox = $('.search-field');
    var searchBox = $('.search-cart .search-box .search-form');
    var isOpen = false;
    submitIcon.on('click', function() {
        if (isOpen == false) {
             jQuery('.search-cart .search-box form').css('display','block');
            searchBox.addClass('searchbox-open');
            inputBox.focus();
            isOpen = true;
        } else {
            searchBox.removeClass('searchbox-open');
            inputBox.focusout();
            isOpen = false;
        }
    });
});
