;(function($){
    'use strict';

    /*
    |----------------------------------------------------------------------------
    |  Recent Product Carousel
    |----------------------------------------------------------------------------
    */
    $('.recent-product-carousel').owlCarousel({
        loop:true,
        margin:30,
        nav:true,
        navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    });



    /*
    |----------------------------------------------------------------------------
    |  Brand Carousel
    |----------------------------------------------------------------------------
    */
    $('.brand-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:2
            },
            600:{
                items:3
            },
            1000:{
                items:6
            }
        }
    });



    /*
    |----------------------------------------------------------------------------
    |  List/Grid View
    |----------------------------------------------------------------------------
    */
    $('#list').on('click', function(event){
        event.preventDefault();
        $('#products .item').addClass('list-group-item');
        $('#list').addClass('current');
        $('#grid').removeClass('current');
    });

    $('#grid').on('click', function(){
        event.preventDefault();
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
        $('#grid').addClass('current');
        $('#list').removeClass('current');
    });


    /*
    |----------------------------------------------------------------------------
    |  Time Down
    |----------------------------------------------------------------------------
    */
   $('#time-down').syotimer({
        year: 2020,
        month: 5,
        day: 9,
        hour: 20,
        minute: 30
    });



    /*
    |----------------------------------------------------------------------------
    |  Select Picker
    |----------------------------------------------------------------------------
    */
    $('.selectpicker').selectpicker({
        style: 'btn-transparent',
        size: 4
    });


    /*
    |----------------------------------------------------------------------------
    |  Range Slider 
    |----------------------------------------------------------------------------
    */
    $("#ex18a").slider({min  : 0, max  : 1000, value: 500, labelledby: 'ex18-label-1'});
    $("#ex18b").slider({min  : 0, max  : 1000, value: [150, 500], labelledby: ['ex18-label-2a', 'ex18-label-2b']});

         

    /*
    |----------------------------------------------------------------------------
    |   Isotop Gallery JS
    |----------------------------------------------------------------------------
    */
    jQuery(window).on("load resize",function(e){
        var $container = $('.isotope-grid'),
        colWidth = function () {
            var w = $container.width(), 
            columnNum = 1,
            columnWidth = 0;
        //Select what will be your porjects columns according to container widht
        if (w > 1040)     { columnNum  = 4; }  
        else if (w > 850) { columnNum  = 3; }  
        else if (w > 768) { columnNum  = 2; }  
        else if (w > 480) { columnNum  = 2; }
        else if (w > 300) { columnNum  = 1; }
        columnWidth = Math.floor(w/columnNum);

        //Default item width and height
        $container.find('.grid-item').each(function() {
            var $item = $(this), 
            width = columnWidth-30;
            $item.css({ width: width});
        }); 
        return columnWidth;
    },
    isotope = function () {
        $container.isotope({
            resizable: true,
            itemSelector: '.grid-item',
            masonry: {
                columnWidth: colWidth(),
                layoutMode: 'fitRows',
                gutterWidth: 10
            }
        });
    };
    isotope(); 

        // bind filter button click
        $('.isotope-filters').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            $container.isotope({ filter: filterValue });
        });

        // change active class on buttons
        $('.isotope-filters').each( function( i, buttonGroup ) {
            var $buttonGroup = $( buttonGroup );
            $buttonGroup.on( 'click', 'button', function() {
                $buttonGroup.find('.active').removeClass('active');
                $( this ).addClass('active');
            });
        }); 
    });


    /*
    |----------------------------------------------------------------------------
    |   Magnific Popup JS
    |----------------------------------------------------------------------------
    */
    $('.image-large').magnificPopup({
        type: 'image',
         gallery:{
            enabled:true
          }
    });
    $('.play-video').magnificPopup({
        type: 'iframe'
    });
    $.extend(true, $.magnificPopup.defaults, {
    iframe: {
        patterns: {
           youtube: {
              index: 'youtube.com/', 
              id: 'v=', 
              src: 'http://www.youtube.com/embed/%id%?autoplay=1' 
          }
        }
    }
    });


    /*
    |----------------------------------------------------------------------------
    | SCROLL TO TOP
    |----------------------------------------------------------------------------
    */
    $(".scroll").on('click',function(){
        $("html,body").animate({scrollTop:$("body").offset().top},"1000");
        return false
    });



}(jQuery));