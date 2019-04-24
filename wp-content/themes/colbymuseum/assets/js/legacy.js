jQuery(document).ready(function () {
  jQuery(window).resize(function () {
    resizeCheck();
  });

  jQuery('#top-right-info').click(function () {
    jQuery('.hidden-container').not('#info-container').hide();
    jQuery('#info-container').stop().fadeToggle(function () {
      jQuery('.hidden-container').not(this).hide();
    });
  });

  jQuery('#top-right-location').click(function () {
    jQuery('.hidden-container').not('#location-container').hide();
    jQuery('#location-container').stop().fadeToggle(function () {
      jQuery('.hidden-container').not(this).hide();
    });
  });

  jQuery('#top-right-search').click(function () {
    jQuery('.hidden-container').not('#search-container').hide();
    jQuery('#search-container').stop().fadeToggle(function () {
      jQuery('#searchTop').focus();
      jQuery('.hidden-container').not(this).hide();
    });
  });

  jQuery('#top-right-links').click(function () {
    jQuery('.hidden-container').hide();
    jQuery('#location-links').stop().fadeToggle(function () {
      jQuery('.hidden-container').not(this).hide();
    });
  });

  jQuery('.search-form').submit(function () {
    if (jQuery(this).children("input[type='search']").val().trim().length === 0) {
      alert('A search term must be entered.');
      jQuery(this).children("input[type='search']").focus();
      return false;
    }
  });

  jQuery('#searchform').submit(function () {
    if (jQuery(this).children("input[type='text']").val().trim().length === 0) {
      alert('A search term must be entered.');
      jQuery(this).children("input[type='text']").focus();
      return false;
    }
  });

  jQuery('#top-right-social img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr('src', '/wp-content/themes/colbymuseum/images/share-icon-red.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('#newsletter-channel img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr(
      'src', '/wp-content/themes/colbymuseum/images/museum-newsletter-icon-hover.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('#facebook-channel img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr('src', '/wp-content/themes/colbymuseum/images/facebook-icon-footer-red.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('#twitter-channel img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr('src', '/wp-content/themes/colbymuseum/images/twitter-icon-footer-red.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('#vimeo-channel img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr('src', '/wp-content/themes/colbymuseum/images/vimeo-icon-footer-red.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('#instagram-channel img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr('src', '/wp-content/themes/colbymuseum/images/instagram-icon-footer-red.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('#joinButton img').hover(function () {
    jQuery(this).attr('data-src-orig', jQuery(this).attr('src'));
    jQuery(this).attr('src', '/wp-content/themes/colbymuseum/images/support-hover.png');
  }, function () {

    jQuery(this).attr('src', jQuery(this).attr('data-src-orig'));
  });

  jQuery('.post-type-archive .exhibition-link-left').click(function () {
    if (jQuery(this).text().indexOf('Highlights') != -1) {
      return;
    }

    var $divJump = jQuery('header.topHeader:contains(' + jQuery(this).text() + ')');
    if ($divJump.length) {
      $divJump.scrollView();
    }

    return false;
  });

  jQuery('#responsive-side-header').html(jQuery('.nav-collapse .section-header').html());

  jQuery('.highlightSlide,#highlightSlide').flexslider2({
      animation: 'slide',
      slideshow: 'true',
      animationSpeed: 3000,
      slideshowSpeed: 4000,
      animationLoop: false,
      touch: true,
      itemWidth: 600,
      prevText: '<',
      nextText: '>',
      move: 1,
      pauseOnHover: true,
      directionNav: true,
    });

  jQuery('.exhibitionSlide').flexslider2({
  });

  jQuery('#highlightSlide .slides a,.exhibitionSlide .slides a').hover(function () {
    jQuery(this).attr('title', '');
  },

  function () {
    jQuery(this).attr('title', jQuery(this).attr('data-title'));
  });

  jQuery('#highlightSlide .slides a, .exhibitionSlide .slides a').click(function () {
    jQuery(this).attr('title', jQuery(this).attr('data-title'));
  });

  jQuery('.span9 iframe').attr('src', function () {
    if (this.src.indexOf('vimeo') > -1)
     return this.src + '?title=false&byline=false&portrait=false&color=fff';
  });

  jQuery('#top-right-info,#top-right-location,#top-right-search,#search-container')
    .on('click', function (e) {
      e.stopPropagation();
    });

  jQuery(document).on('click', function (e) {
    if (jQuery(e.target).parents('#search-container').length) {
      e.stopPropagation();
    } else {
      jQuery('.hidden-container').fadeOut();
    }
  });

  if (typeof (jQuery.fancybox) != 'undefined') {
    jQuery(document).unbind('click.fb-start');

    jQuery.each(jQuery('.exhibitionSlide ul.slides a, #highlightSlide ul.slides a'), function () {
      jQuery(this).attr('rel', jQuery(this).attr('data-rel'));
    });

    jQuery('.exhibitionSlide ul.slides a, #highlightSlide ul.slides a').fancybox({
      maxWidth: '95%',
      maxHeight: '80%',
      autoSize: true,
      arrows: true,
      helpers: {
        overlay: {
                  css: { background: 'rgba(0, 0, 0, 0.75)' },
                  locked: false,
                },
        title: {
          type: 'outside',
        },
      },
      padding: 0,
      margin: 0,
    });

    jQuery("a[href$='.jpg'],a[href$='.png'],a[href$='.gif'],a.fancybox").fancybox({
      fitToView: false,
      maxWidth: '95%',
      maxHeight: '80%',
      autoSize: true,
      arrows: true,
      helpers: {
        overlay: {
                  css: { background: 'rgba(0, 0, 0, 0.75)' },
                  locked: false,
                },
        title: {
              type: 'outside',
            },
      },
      padding: 0,
      margin: 0,
    });
  }

  // Change fixed menus to relative if menu clicked so users can scroll
  // (absolute positioning does not allow longer menus to be scrolled on shorter devices...
  jQuery('.colby-header .btn-navbar, .sidebar-fixed .btn-navbar').click(function () {
    jQuery(this).attr('data-clicked', '1');
    resizeSidebar(jQuery(this));
  });

  resizeCheck();
});

jQuery.fn.scrollView = function () {
    return this.each(function () {
        jQuery('html, body').animate({
            scrollTop: jQuery(this).offset().top,
          }, 1000);
      });
  };

function resizeSidebar(destination) {
  if (true) {

    if (jQuery(destination).parent().children('.in').length) {
      jQuery('.colby-header').css('position', 'fixed');
      jQuery('.sidebar-fixed').css('position', 'fixed');
      jQuery('#sidebar1').css('marginLeft', '-20px');
      jQuery('#sidebar1').css('top', jQuery('#sidebar1').attr('data-top-orig'));
    } else {
      jQuery('.colby-header').css('position', 'relative');
      jQuery('.sidebar-fixed').css('position', 'relative');
      jQuery('#sidebar1').css('marginLeft', '0');
      jQuery('#sidebar1').attr('data-top-orig', parseInt(jQuery('#sidebar1').css('top'), 10));
      jQuery('#sidebar1').css('top', ('-' + parseInt(jQuery('#sidebar1')
        .offset().top - jQuery("header[role='banner']").height() - parseInt(jQuery('#sidebar1')
        .css('top'), 10)) + 'px'));

      if (jQuery(window).width() <= 768) {
        window.scrollTo(0, 0);
      }
    }
  }
}

function resizeCheck() {
  var width = jQuery(window).width();

  if (width <= 965) {
    if (jQuery('#sidebar2').length) {
      jQuery('#sidebar2').appendTo('#main');
    }
  } else {
    if (jQuery('#sidebar2').length) {
      jQuery('#sidebar2').insertAfter('#sidebar1');
    }
  }

  jQuery('#museum-homeslider .slides .item').css('height', jQuery(window).height());

  return;
}

// Listen for orientation changes
window.addEventListener('orientationchange', function () {
  if (jQuery('.colby-header .btn-navbar').parent().children('.in').length ||
      jQuery('.sidebar-fixed .btn-navbar').parent().children('.in').length) {
    resizeSidebar('#sidebar1');
  }
}, false);
