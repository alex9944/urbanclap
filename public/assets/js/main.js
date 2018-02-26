

"use strict";
var contactForm, headerHeight, init, mailChimp, mapInit, openMobileMenu;

if (!window.requestAnimationFrame) {
  window.requestAnimationFrame = function(callback, element) {
    var currTime, id, lastTime, timeToCall;
    currTime = (new Date).getTime();
    timeToCall = Math.max(0, 16 - (currTime - lastTime));
    id = window.setTimeout((function() {
      callback(currTime + timeToCall);
    }), timeToCall);
    lastTime = currTime + timeToCall;
    return id;
  };
}

mapInit = function() {

  /* Initialize google map */
  var location, map, mapOptions, mapType, marker, styles;
  map = null;
  location = new google.maps.LatLng(40.6743890, -73.9455);
  styles = [
    {
      featureType: "all",
      elementType: "all",
      stylers: [
        {
          saturation: -100
        }
      ]
    }
  ];
  mapOptions = {
    zoom: 11,
    center: location,
    scrollwheel: false,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'fantonica']
    }
  };
  map = new google.maps.Map(document.getElementById("map"), mapOptions);
  mapType = new google.maps.StyledMapType(styles, {
    name: "Grayscale"
  });
  marker = new google.maps.Marker({
    position: new google.maps.LatLng(40.6743890, -73.9455),
    map: map,
    title: 'Some marker'
  });
  map.mapTypes.set('fantonica', mapType);
  return map.setMapTypeId('fantonica');
};

openMobileMenu = function() {

  /* Initialize mabile menu */
  return $('.open-mobile-menu').on('click', function(e) {
    e.stopPropagation();
    if ($('.nav').hasClass('menu-opened')) {
      return $('.nav').removeClass('menu-opened');
    } else {
      $('.nav').addClass('menu-opened');
      return $(document).one('click', function() {
        return $('.nav').removeClass('menu-opened');
      });
    }
  });
};

$.fn.smoothMenu = function() {

  /* Initialize smooth scroll navigation */
  var sections, self, updateActive;
  self = this;
  this.on("click", function(e) {
    var target;
    e.preventDefault();
    target = $(this.getAttribute('href'));
    if (target.length) {
      return $('body, html').animate({
        scrollTop: target.offset().top
      }, 1000);
    }
  });
  sections = $('.section, .sub-section, .header, .container');
  updateActive = function() {
    var i, len, nav, section;
    for (i = 0, len = sections.length; i < len; i++) {
      section = sections[i];
      if (window.scrollY > section.offsetTop - 100 && window.scrollY < (section.offsetTop + section.clientHeight)) {
        nav = self.filter(".nav [href='#" + section.id + "']");
        if (!nav.hasClass('active')) {
          nav.addClass('active').parent().siblings().find('a').removeClass('active');
        }
      }
    }
    return requestAnimationFrame(updateActive);
  };
  return updateActive();
};

mailChimp = function() {
  return $('.subscribe').each(function(form) {
    var action;
    if (action = $(form).attr('action')) {
      return $(form).ajaxChimp({
        url: action,
        callback: function(res) {
          $(form).find('.alert').removeClass('alert-success alert-danger');
          if (res.result === "success") {
            $(form).find('.alert').html(res.msg).addClass('alert-success');
          }
          if (res.result === "error") {
            return $(form).find('.alert').html(res.msg).addClass('alert-danger');
          }
        }
      });
    }
  });
};

contactForm = function() {
  return $('.contacts').on('submit', function(e) {
    e.preventDefault();
    return $.ajax({
      type: "post",
      url: "sendmail.php",
      data: $(this).serializeArray(),
      success: (function(_this) {
        return function(data) {
          return $(_this).find('.alerts-wrap').append('<div class="alert alert-success alert-dismissible"> <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button> <p>Message sent successfully</p> </div>');
        };
      })(this)
    });
  });
};

headerHeight = function() {
  var height;
  height = $('.top-bar').height();
  $('.header-section').css({
    paddingTop: $('.top-bar').height()
  });
  if (window.scrollY > height) {
    $('.top-bar').addClass('waypoint');
  } else {
    $('.top-bar').removeClass('waypoint');
  }
  return requestAnimationFrame(headerHeight);
};

init = function() {

  /* Initialize all plugins after window load */
  $('.site-wrapper').addClass('in');
  openMobileMenu();
  headerHeight();
  mailChimp();
  contactForm();
  $('[data-countdown]').each(function() {
    return $(this).countdown($(this).data('countdown'), function(e) {
      return $(this).html(e.strftime('<div><span>%D</span><span>days</span></div><div><span>%H</span><span>hours</span></div><div><span>%M</span><span>min</span></div> <div><span>%S</span><span>sec</span></div>'));
    });
  });
  $('.smooth-scrolling').smoothMenu();
  $('.slider').slick({
    arrows: false,
    dots: true,
    slidesToShow: 1,
    adaptiveHeight: true
  });
  $('.slider-gallery').slick({
    arrows: false,
    dots: true,
    slidesToShow: 4,
    slidesToScroll: 4,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3
        }
      }, {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });
  $('.testimonials-slider').slick({
    arrows: false,
    dots: true,
    slidesToShow: 1
  });
  if ($.isFunction($.mmenu)) {
    $('.canvas-off-nav').mmenu({
      canvasOff: false
    });
  }
  new WOW({
    mobile: false
  }).init();
  window.sr = new scrollReveal({
    easing: 'hustle',
    mobile: true
  });
  return baguetteBox.run('.slider-gallery', {});
};

$(window).on('load', function() {
  setTimeout(init, 1000);
  $('.loader').addClass('up');
  return $('body').removeClass('no-scroll');
});
