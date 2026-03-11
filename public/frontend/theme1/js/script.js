(function($) {
    "use strict";
	/*------------------------------------------
        = Preloader
    -------------------------------------------*/
    function preloader() {
        if($('.preloader').length) {
            $('.preloader').delay(100).fadeOut(500, function() {

                //active wow
                wow.init();

            });
        }
    }

    /*------------------------------------------
        = Back To Top
    -------------------------------------------*/
    $("body").append("<a href='#' class='back-to-top'><i class='icofont-rounded-up'></i></a>");

    function toggleBackToTopBtn() {
        var amountScrolled = 800;
        if ($(window).scrollTop() > amountScrolled) {
            $("a.back-to-top").fadeIn("slow");
        } else {
            $("a.back-to-top").fadeOut("slow");
        }
    }

    $(".back-to-top").on("click", function() {
        $("html,body").animate({
            scrollTop: 0
        }, 900);
        return false;
    })

    /*------------------------------------------
        = WOW ANIMATION SETTING
    -------------------------------------------*/
    var wow = new WOW({
        boxClass:     'wow',      // default
        animateClass: 'animated', // default
        offset:       0,          // default
        mobile:       true,       // default
        live:         true        // default
    });

	/*------------------------------------------
        = FullHeight
    -------------------------------------------*/
    if($('.fullHeight').length){
        $('.fullHeight').css({'height':($(window).height())+'px'});
        $(window).resize(function(){
        $('.fullHeight').css({'height':($(window).height())+'px'});
        });
      }

    /*------------------------------------------
        = FullHeight
    -------------------------------------------*/
    $(function() {

        $(' #case-success-hover-style > .case-success-post > .thumb ').each( function() { $(this).hoverdir(); } );

    });

    /*------------------------------------------
        = ACTIVE POPUP IMAGE
    -------------------------------------------*/
    if ($(".fancybox").length) {
        $(".fancybox").fancybox({
            openEffect  : "elastic",
            closeEffect : "elastic",
            wrapCSS     : "project-fancybox-title-style"
        });
    }


    /*------------------------------------------
        = POPUP VIDEO
    -------------------------------------------*/
    if ($(".video-btn").length) {
        $(".video-btn").on("click", function(){
            $.fancybox({
                href: this.href,
                type: $(this).data("type"),
                'title'         : this.title,
                helpers     : {
                    title : { type : 'inside' },
                    media : {}
                },

                beforeShow : function(){
                    $(".fancybox-wrap").addClass("gallery-fancybox");
                }
            });
            return false
        });
    }




      // Navbar
      var nav = $('.main-navigation');

      $(window).scroll(function () {
          if ($(this).scrollTop() > 50) {
              nav.addClass("fixed-header");
          } else {
              nav.removeClass("fixed-header");
          }
      });


      $(document).ready(function(){
          var dropDown = $('.dropdown');
          //Show dropdown on hover only for desktop devices
          if($(window).innerWidth() > 767){
              dropDown.on({
                  mouseenter: function () {
                      dropDown.clearQueue();
                      $(this).find('>.dropdown-menu').addClass('show');
                  },
                  mouseleave: function () {
                      $(this).find('>.dropdown-menu').removeClass('show');
                  }
              });

          }

          //Show dropdown on click only for mobile devices
          if($(window).innerWidth() < 768) {
              dropDown.on('click', function(event){

                  // Avoid having the menu to close when clicking
                  event.stopPropagation();

                  // close all the siblings
                  $(this).siblings().removeClass('show');
                  $(this).siblings().find('>.dropdown-menu').removeClass('show');

                  // close all the submenus of siblings
                  $(this).siblings().find('>.dropdown-menu').parent().removeClass('show');

                  // opening the one you clicked on
                  $(this).find('>.dropdown-menu').toggleClass('show');
                  $(this).siblings('>.dropdown-menu').toggleClass('show');
              });
          }
      });

    /*------------------------------------------
        = Service Slider
    -------------------------------------------*/
    if ($(".service-slider").length) {
        $(".service-slider").owlCarousel({
            autoplay:true,
            smartSpeed: 300,
            margin: 30,
            loop:true,
            autoplayHoverPause:true,
            dots: false,
            nav: true,
            navText: ['<i class="icofont-rounded-left"></i>','<i class="icofont-rounded-right"></i>'],
            responsive: {
                0 : {
                    items: 1
                },

                550 : {
                    items: 2
                },

                992 : {
                    items: 3
                },

                1200 : {
                    items: 3
                }
            }
        });
    }

    /*------------------------------------------
        = Testimonials Slider
    -------------------------------------------*/
    if ($(".attorney-slider").length) {
        $(".attorney-slider").owlCarousel({
            center: true,
            autoplay:true,
            smartSpeed: 600,
            margin: 0,
            loop:true,
            autoplayHoverPause:true,
            dots: false,
            nav: true,
            navText: ['<i class="icofont-rounded-left"></i>','<i class="icofont-rounded-right"></i>'],
            responsive: {
                0 : {
                    items: 1
                },

                550 : {
                    items: 2
                },

                992 : {
                    items: 3
                },

                1200 : {
                    items: 3
                }
            }
        });
    }

    /*------------------------------------------
        = Testimonials Slider
    -------------------------------------------*/
    if ($(".testimonials-slider").length) {
        $(".testimonials-slider").owlCarousel({
            center: true,
            autoplay:true,
            smartSpeed: 600,
            margin: 30,
            loop:true,
            autoplayHoverPause:true,
            dots: false,
            nav: false,
            navText: ['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right "></i>'],
            responsive: {
                0 : {
                    items: 1
                },

                550 : {
                    items: 2
                },

                992 : {
                    items: 2
                },

                1200 : {
                    items: 2
                }
            }
        });
    }

    /*------------------------------------------
        = Testimonials Slider
    -------------------------------------------*/
    if ($(".sideber-slider").length) {
        $(".sideber-slider").owlCarousel({
            center: true,
            autoplay:true,
            smartSpeed: 600,
            margin: 30,
            loop:true,
            autoplayHoverPause:true,
            dots: true,
            nav: false,
            navText: ['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right "></i>'],
            responsive: {
                0 : {
                    items: 1
                },

                550 : {
                    items: 1
                },

                992 : {
                    items: 1
                },

                1200 : {
                    items: 1
                }
            }
        });
    }

    /*------------------------------------------
        = Testimonials Slider
    -------------------------------------------*/
    if ($(".partner-slider").length) {
        $(".partner-slider").owlCarousel({
            autoplay:true,
            smartSpeed: 600,
            margin: 30,
            loop:true,
            autoplayHoverPause:true,
            dots: false,
            nav: false,
            navText: ['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right "></i>'],
            responsive: {
                0 : {
                    items: 2
                },

                550 : {
                    items: 4
                },

                992 : {
                    items: 6
                },

                1200 : {
                    items: 6
                }
            }
        });
    }

    /*==========================================================================
        WHEN DOCUMENT LOADING
    ==========================================================================*/
    $(window).on('load', function() {

        preloader();

    });

	/*==========================================================================
        WHEN WINDOW SCROLL
    ==========================================================================*/
    $(window).on("scroll", function() {
    	toggleBackToTopBtn();
    });

})(jQuery);
