(function($){
  "use strict";
  $("#primary-menu-mobile").mmenu({
    extensions	: [ 'effect-slide-menu', 'pageshadow' ],
    counters	: true,
    navbar 		: {
      title		: 'Menu'
    },
    navbars		: [
       {
            position	: 'top',
            content		: [
              'prev',
              'title',
              'close'
            ]
          }],
    slidingSubmenus : false
  });
  var API = $("#primary-menu-mobile").data("mmenu");
  $("#menu-button").on('click', function() {
    API.open();
  });
  //$('.collapse').collapse();
  var owl = $(".owl1");
  owl.owlCarousel({
    loop:true,
    items: 5 ,
    margin:50, nav:true,
    lazyLoad:true, dots:false,
    responsive:{
        0:{
            items:2
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
  });

  /*--------------------------------------------*/
  /* ---------------- Mini Cart ----------------*/
  /*--------------------------------------------*/
  var cartIcon = $('.mini-cart__button');
  var cartBox = $('.mini-cart__content');
  var isOpen = false;
  cartIcon.on('click', function(){
    if(isOpen == false){
      cartBox.addClass('mini-cart__content-open');
      isOpen = true;
    } else {
      cartBox.removeClass('mini-cart__content-open');
      isOpen = false;
    }
  });
  cartIcon.on('mouseup', function(){
    return false;
  });
  cartBox.on('mouseup', function(){
    return false;
  });
  $(document).on('mouseup', function(){
    if(isOpen == true){
      cartIcon
        .css('display','block')
        .click();
    }
  });

  $.scrollUp({
    scrollName: 'scrollUp', // Element ID
    topDistance: '300', // Distance from top before showing element (px)
    topSpeed: 300, // Speed back to top (ms)
    animation: 'fade', // Fade, slide, none
    animationInSpeed: 500, // Animation in speed (ms)
    animationOutSpeed: 500, // Animation out speed (ms)
    scrollText: '<i class="fa fa-angle-up"></i>', // Text for element
    activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
  });
  $('.thumblink').click(function(e){
      $('.attachment-shop-single').hide();
      $('.imagen'+$(this).attr('rel')).fadeIn(1000);
      e.preventDefault();
  });
})(jQuery);
