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

  var cartIcon = $('.mini-cart__button');
  var cartBox = $('.mini-cart__content');
  var loginIcon = $('.user_login__button');
  var loginBox = $('.user_login__content');
  var isOpenCart = false;
  var isOpenUser = false;

  cartIcon.on('click', function(){
    if(isOpenCart == false){
      loginBox.removeClass('user-login__content-open');
      isOpenUser = false;
      cartBox.addClass('mini-cart__content-open');
      isOpenCart = true;
    } else {
      cartBox.removeClass('mini-cart__content-open');
      isOpenCart = false;
    }
  });
  cartIcon.on('mouseup', function(){
    return false;
  });
  cartBox.on('mouseup', function(){
    return false;
  });
  
  loginIcon.on('click', function(){
    if(isOpenUser == false){
      cartBox.removeClass('mini-cart__content-open');
      isOpenCart = false;
      loginBox.addClass('user-login__content-open');
      isOpenUser = true;
    } else {
      loginBox.removeClass('user-login__content-open');
      isOpenUser = false;
    }
  });
  loginIcon.on('mouseup', function(){
    return false;
  });
  loginBox.on('mouseup', function(){
    return false;
  });

  $.scrollUp({
    scrollName: 'scrollUp', 
    topDistance: '300', 
    topSpeed: 300, 
    animation: 'slide', 
    animationInSpeed: 500, 
    animationOutSpeed: 500, 
    scrollText: '<i class="fa fa-angle-up"></i>', 
    activeOverlay: false,
  });
  
  $('.thumblink').click(function(e){
      $('.attachment-shop-single').hide();
      $('.imagen'+$(this).attr('rel')).fadeIn(1000);
      e.preventDefault();
  });
  
  let cookieButton = $('#submit_button_cookie');
  let cookiepolicy = $('#cookiepolicy');
  console.log(getCookie('hipertintorero-cookie'));
  
  if(getCookie('hipertintorero-cookie')){
    cookiepolicy.hide();
  } 

  cookieButton.on('click', function(){
      setCookie('hipertintorero-cookie', '1', 100);
      cookiepolicy.hide();
  });

  function setCookie(name, value, daysToLive) {
      var cookie = name + "=" + encodeURIComponent(value);
      
      if(typeof daysToLive === "number") {
          cookie += "; max-age=" + (daysToLive*24*60*60);
          document.cookie = cookie;
      }
  }

  function getCookie(name) {
      var cookieArr = document.cookie.split(";");
      for(var i = 0; i < cookieArr.length; i++) {
          var cookiePair = cookieArr[i].split("=");
          if(name == cookiePair[0].trim()) {
              return decodeURIComponent(cookiePair[1]);
          }
      }
      return null;
  }
})(jQuery);
