/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/wp-content/themes/colbycollege/dist/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(3);

__webpack_require__(2);

/***/ }),
/* 1 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/* HoverIntent */
(function (e) {
  e.fn.hoverIntent = function (t, n, r) {
    var i = { interval: 100, sensitivity: 7, timeout: 0 };
    if ((typeof t === 'undefined' ? 'undefined' : _typeof(t)) === 'object') {
      i = e.extend(i, t);
    } else if (e.isFunction(n)) {
      i = e.extend(i, { over: t, out: n, selector: r });
    } else {
      i = e.extend(i, { over: t, out: t, selector: n });
    }
    var s = void 0,
        o = void 0,
        u = void 0,
        a = void 0;
    var f = function f(e) {
      s = e.pageX;
      o = e.pageY;
    };
    var l = function l(t, n) {
      n.hoverIntent_t = clearTimeout(n.hoverIntent_t);
      if (Math.abs(u - s) + Math.abs(a - o) < i.sensitivity) {
        e(n).off('mousemove.hoverIntent', f);
        n.hoverIntent_s = 1;
        return i.over.apply(n, [t]);
      }
      u = s;
      a = o;
      n.hoverIntent_t = setTimeout(function () {
        l(t, n);
      }, i.interval);
    };
    var c = function c(e, t) {
      t.hoverIntent_t = clearTimeout(t.hoverIntent_t);
      t.hoverIntent_s = 0;
      return i.out.apply(t, [e]);
    };
    var h = function h(t) {
      var n = jQuery.extend({}, t);
      var r = this;
      if (r.hoverIntent_t) {
        r.hoverIntent_t = clearTimeout(r.hoverIntent_t);
      }
      if (t.type == 'mouseenter') {
        u = n.pageX;
        a = n.pageY;
        e(r).on('mousemove.hoverIntent', f);
        if (r.hoverIntent_s != 1) {
          r.hoverIntent_t = setTimeout(function () {
            l(n, r);
          }, i.interval);
        }
      } else {
        e(r).off('mousemove.hoverIntent', f);
        if (r.hoverIntent_s == 1) {
          r.hoverIntent_t = setTimeout(function () {
            c(n, r);
          }, i.timeout);
        }
      }
    };
    return this.on({ 'mouseenter.hoverIntent': h, 'mouseleave.hoverIntent': h }, i.selector);
  };
})(jQuery);

// Bootstrap hover...
(function ($, window, undefined) {
  // outside the scope of the jQuery plugin to
  // keep track of all dropdowns
  var $allDropdowns = $();

  // if instantlyCloseOthers is true, then it will instantly
  // shut other nav items when a new one is hovered over
  $.fn.dropdownHover = function (options) {
    // the element we really care about
    // is the dropdown-toggle's parent
    $allDropdowns = $allDropdowns.add(this.parent());

    return this.each(function () {
      var $this = $(this),
          $parent = $this.parent(),
          defaults = {
        delay: 500,
        instantlyCloseOthers: true
      },
          data = {
        delay: $(this).data('delay'),
        instantlyCloseOthers: $(this).data('close-others')
      },
          settings = $.extend(true, {}, defaults, options, data),
          timeout = void 0;

      $parent.hover(function (event) {
        // so a neighbor can't open the dropdown
        if (!$parent.hasClass('open') && !$this.is(event.target)) {
          return true;
        }

        if (settings.instantlyCloseOthers === true) $allDropdowns.removeClass('open');

        window.clearTimeout(timeout);
        $parent.addClass('open');
      }, function () {
        timeout = window.setTimeout(function () {
          $parent.removeClass('open');
        }, settings.delay);
      });

      // this helps with button groups!
      $this.hover(function () {
        if (settings.instantlyCloseOthers === true) $allDropdowns.removeClass('open');

        window.clearTimeout(timeout);
        $parent.addClass('open');
      });

      // handle submenus
      $parent.find('.dropdown-submenu').each(function () {
        var $this = $(this);
        var subTimeout = void 0;
        $this.hover(function () {
          window.clearTimeout(subTimeout);
          $this.children('.dropdown-menu').show();
          // always close submenu siblings instantly
          $this.siblings().children('.dropdown-menu').hide();
        });
      });
    });
  };

  $(document).ready(function () {
    // apply dropdownHover to all elements with the data-hover="dropdown" attribute
    $('[data-hover="dropdown"]').dropdownHover();
  });
})(jQuery, window);

if (jQuery.fn.accordion) {
  jQuery(document).ready(function () {
    jQuery('.tboot-accordion').accordion({
      autoHeight: true,
      heightStyle: 'content'
    });
  }); // Fix for jQ Accordions
}
/* imgsizer (flexible images for fluid sites) */
var imgSizer = {
  Config: { imgCache: [], spacer: '/path/to/your/spacer.gif' },
  collate: function collate(aScope) {
    var isOldIE = document.all && !window.opera && !window.XDomainRequest ? 1 : 0;
    if (isOldIE && document.getElementsByTagName) {
      var c = imgSizer;
      var imgCache = c.Config.imgCache;
      var images = aScope && aScope.length ? aScope : document.getElementsByTagName('img');
      for (var i = 0; i < images.length; i++) {
        images[i].origWidth = images[i].offsetWidth;
        images[i].origHeight = images[i].offsetHeight;
        imgCache.push(images[i]);
        c.ieAlpha(images[i]);
        images[i].style.width = '100%';
      }
      if (imgCache.length) {
        c.resize(function () {
          for (var _i = 0; _i < imgCache.length; _i++) {
            var ratio = imgCache[_i].offsetWidth / imgCache[_i].origWidth;
            imgCache[_i].style.height = imgCache[_i].origHeight * ratio + 'px';
          }
        });
      }
    }
  },
  ieAlpha: function ieAlpha(img) {
    var c = imgSizer;
    if (img.oldSrc) {
      img.src = img.oldSrc;
    }
    var src = img.src;
    img.style.width = img.offsetWidth + 'px';
    img.style.height = img.offsetHeight + 'px';
    img.style.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + src + '\', sizingMethod=\'scale\')';
    img.oldSrc = src;
    img.src = c.Config.spacer;
  },
  resize: function resize(func) {
    var oldonresize = window.onresize;
    if (typeof window.onresize !== 'function') {
      window.onresize = func;
    } else {
      window.onresize = function () {
        if (oldonresize) {
          oldonresize();
        }
        func();
      };
    }
  }
};

jQuery(document).ready(function (jQuery) {
  jQuery('[rel=popover]').popover();
  if (jQuery.fn.carousel) {
    jQuery('.carousel').carousel({
      // Bootstrap Carousel settings
      interval: 6000,
      cycle: true
    });
  }

  // Colby log in/logout
  if (document.cookie.indexOf('ColbyTicket=') != -1) {
    jQuery('#colby-loginli,.loginli-top').html('<a href="https://www.colby.edu/ColbyMaster/logout/">Logout</a>');
  } else {
    jQuery('#colby-loginli,.loginli-top').html('<a href="#">Login</a>');
    var loginLinks = document.querySelectorAll('.loginli-top a, #colby-loginli a');

    if (!loginLinks) {
      return;
    }

    Array.prototype.forEach.call(loginLinks, function (loginLink) {
      loginLink.addEventListener('click', function (event) {
        event.preventDefault();
        loginCMS();
      });
    });
  }

  jQuery('a[data-slide="prev"]').click(function () {
    jQuery('#myCarousel').carousel('prev');
    return false;
  });

  jQuery('a[data-slide="next"]').click(function () {
    jQuery('#myCarousel').carousel('next');
    return false;
  });

  jQuery('.royalSlider').css('display', 'block'); // Show hidden RoyalSliders

  jQuery('#sectionMenu .btn-navbar').on('click', function (e) {
    if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
      // Firefox
      e.preventDefault();
      var $collapse = jQuery(this).siblings('.nav-collapse');
      $collapse.collapse('toggle');

      if ($collapse.hasClass('collapse')) {
        $collapse.removeClass('collapse');
        $collapse.addClass('in').css('height', 'auto');
      } else {
        $collapse.addClass('collapse');
        $collapse.removeClass('in').css('height', '0');
      }
    }
  });

  jQuery('.accordion .accordion-toggle').on('shown', function () {
    jQuery('.icon-chevron-down').removeClass('icon-chevron-down').addClass('icon-chevron-up');
  });

  jQuery('.accordion .accordion-toggle').on('hidden', function () {
    jQuery('.icon-chevron-up').removeClass('icon-chevron-up').addClass('icon-chevron-down');
  });

  if (typeof popover === 'function') {
    jQuery("a[rel='popover']").popover({ trigger: 'hover' });
  }

  jQuery('ol.commentlist a.comment-reply-link').each(function () {
    jQuery(this).addClass('btn btn-success btn-mini');
    return true;
  });

  jQuery('#cancel-comment-reply-link').each(function () {
    jQuery(this).addClass('btn btn-danger btn-mini');
    return true;
  });

  // Input placeholder text fix for IE
  if (jQuery.browser.ie) {
    // BRG added - only run for IE...
    jQuery('[placeholder]').focus(function () {
      var input = jQuery(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function () {
      var input = jQuery(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur();
  }

  // Prevent submission of empty form
  jQuery('[placeholder]').parents('form').submit(function () {
    jQuery(this).find('[placeholder]').each(function () {
      var input = jQuery(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
      }
    });
  });

  // Alumni RSS cleanup...
  jQuery('.rssSummary').each(function () {
    var dateText = jQuery(this).text().replace('Date:', '').replace('PM', 'p.m.').replace('AM', 'a.m.').replace('12:00 p.m.', 'noon');
    jQuery(this).text(dateText);
  });

  jQuery('#s').focus(function () {
    if (jQuery(window).width() < 940) jQuery(this).animate({ width: '200px' });
  });

  jQuery('#s').blur(function () {
    if (jQuery(window).width() < 940) jQuery(this).animate({ width: '100px' });
  });

  // GA event clicks...
  jQuery('#content-container a').click(function () {
    if (jQuery(this).attr('href')) {
      var extLink = jQuery(this).attr('href').replace(/^https?\:\/\//i, '');
      ga('send', 'event', 'content-container', window.location.pathname, extLink);
    }
  });
  jQuery('#myCarousel a,#frontSlideshow a').click(function () {
    if (jQuery(this).attr('href')) {
      var extLink = jQuery(this).attr('href').replace(/^https?\:\/\//i, '');
      ga('send', 'event', 'front-slideshow', window.location.pathname, extLink);
      // _gaq.push(['_trackEvent','front-slideshow',window.location.pathname, extLink]);
    }
  });
  jQuery('#sectionmenu a').click(function () {
    if (jQuery(this).attr('href')) {
      var extLink = jQuery(this).attr('href').replace(/^https?\:\/\//i, '');
      ga('send', 'event', 'section-menu', window.location.pathname, extLink);
      // _gaq.push(['_trackEvent', 'section-menu',window.location.pathname,extLink]);
    }
  });
  jQuery('#main-nav a').click(function () {
    if (jQuery(this).attr('href')) {
      var extLink = jQuery(this).attr('href').replace(/^https?\:\/\//i, '');
      ga('send', 'event', 'colby-nav', window.location.pathname, extLink);
      // _gaq.push(['_trackEvent', 'colby-nav',window.location.pathname, extLink]);
    }
  });
  jQuery('#colbytemplatefooter a').click(function () {
    if (jQuery(this).attr('href')) {
      var extLink = jQuery(this).attr('href').replace(/^https?\:\/\//i, '');
      ga('send', 'event', 'footer', window.location.pathname, extLink);
      // _gaq.push(['_trackEvent', 'footer',window.location.pathname, extLink]);
    }
  });

  var currentRequest = null;

  jQuery('#headSearchColby, #quick-links a').click(function (event) {
    if (currentRequest != null) currentRequest.abort();

    // Toggle search slidedown
    if (!jQuery('.topSlideDown').length) {
      // Not yet loaded on page...grab..
      var jQuerysearchDiv = '';
      jQuery('body').prepend('<div id="topLoader"><div class="loading"></div></div>');
      jQuery('#topLoader').slideDown();
      var activeTab = event.target.parentNode.id === 'quick-links' ? '3' : '1';
      currentRequest = jQuery.get('/wp-json/colby/templates/?template=library/inc/topSearch.php&activeTab=' + activeTab, function (data) {
        jQuerysearchDiv = jQuery(data.content);
        jQuery('body').prepend(data.content);
        applyTopEvents();

        jQuery('.topSlideDown').slideToggle(500, function () {
          jQuery('#topLoader').remove();
          jQuery('#searchBox').focus();
        });
      });
    } else {
      if (!jQuery('#tab1').hasClass('active')) {
        jQuery('.topSlideDown ul.nav li:nth-child(1) a').click();
      }
      if (jQuery('#tab1').hasClass('active') || !jQuery('.topSlideDown').is(':visible')) {
        jQuery('.topSlideDown').slideToggle(500, function () {
          jQuery('#searchBox').focus();
        });
      }
    }
    if (!jQuery('#tab1').hasClass('active')) {
      jQuery('.topSlideDown ul.nav li:nth-child(1) a').click();
    }
    return false;
  });

  jQuery('#quick-links').click(function () {
    if (!jQuery('.topSlideDown').length) {
      // Toggle quick links slidedown
      var jQuerysearchDiv = '';
      jQuery.get('/wp-json/colby/templates/?template=library/inc/topSearch.php&activeTab=3', function (data) {
        jQuerysearchDiv = jQuery(data);
        jQuery('body').prepend(data);

        applyTopEvents();

        jQuery('.topSlideDown').slideToggle(500, function () {
          if (!jQuery('#tab3').hasClass('active') // If Offices and Resources not visible, show...
          ) {
              jQuery('.topSlideDown ul.nav li:nth-child(3) a').click();
            }
        });
      });
    } else if (jQuery('.topSlideDown').is(':visible')) {
      if (!jQuery('#tab3').hasClass('active')) {
        jQuery('.topSlideDown ul.nav li:nth-child(3) a').click();
      } else jQuery('.topSlideDown').slideToggle(500);
    } else {
      if (!jQuery('#tab3').hasClass('active') // If Offices and Resources not visible, show...
      ) {
          jQuery('.topSlideDown ul.nav li:nth-child(3) a').click();
        }

      jQuery('.topSlideDown').slideToggle(500);
    }

    return false;
  });

  function applyTopEvents() {
    jQuery('.topSlideDown a[data-toggle="tab"]').on('shown', function (e) {
      var tabHash = jQuery(e.target).context.hash;
      if (tabHash == '#tab1') {
        jQuery('#searchBox').focus();
      }
      if (tabHash == '#tab2') jQuery('#searchDirectoryBox').focus();
    });
  }

  // Main navigation menu mouseover...
  var curMenu = '';

  var config = {
    over: function over() {
      jQuery('.highlightNav').removeClass('highlightNav');
      jQuery(this).addClass('highlightNav');
      jQuery(this).children('.mainNavSubmenu').stop(true, true).fadeIn({
        duration: 300,
        easing: 'linear'
      });
    },

    interval: 60,
    out: function out() {
      jQuery(this).removeClass('highlightNav');
      jQuery(this).children('.mainNavSubmenu').stop(true, true).fadeOut({
        duration: 100
      });
    }
  };
  jQuery('li.mainNavButton').hoverIntent(config);

  // Submenu navigation...
  jQuery('.nav li.menu-item a.dropdown-toggle').each(function () {
    if (jQuery(window).width() <= 964) {
      // jQuery("body").hasClass('mobile')) { //If on mobile device
      if (jQuery(this).attr('href').indexOf('http://') >= 0 && jQuery(this).attr('href') != '#') {
        // and navigation is link
        jQuery(this).siblings('ul').prepend('<li class="mobile-nav-show-link"><a href="' + jQuery(this).attr('href') + '">' + jQuery(this).text() + '</a></li>'); // add to beginning of dropdown menu
      }
    }
  });

  jQuery('.nav li.menu-item a.dropdown-toggle').on('click', function () {
    if (jQuery(window).width() > 964 // If not on mobile
    ) {
        window.location.href = jQuery(this).attr('href');
      } // Allow first navigation to be clicked...
  });

  if (typeof jQuery.fancybox !== 'undefined') {
    if (location.href.indexOf('/museum/') != -1) {
      return;
    }

    jQuery("a[href$='.jpg']:not(.nogallery),a[href$='.png']:not(.nogallery),a[href$='.gif']:not(.nogallery),a.fancybox").attr('rel', 'gallery').fancybox({
      padding: 35,
      maxWidth: '90%',
      helpers: {
        afterShow: function afterShow() {
          twttr.widgets.load();
        },

        title: {
          type: 'outside'
        },
        overlay: {
          css: { background: 'rgba(0, 0, 0, 0.75)' },
          locked: false
        }
      },
      openEffect: 'elastic',
      openSpeed: 300,
      closeEffect: 'elastic',
      closeSpeed: 300,
      closeClick: true,
      beforeLoad: function beforeLoad() {
        if (!jQuery(this.element).find('img').attr('title')) {
          this.title = jQuery(this.element).find('img').attr('alt');
        }
      }
    });
  }

  // Resize any iframe videos in post_content...
  var allVideos = jQuery('iframe').not('.noresize').not('iframe[src*=vimeo]');
  jQuery(allVideos).each(function () {
    // WordPress oembed isn't setting a width. If there's not a width, use the standard oEmbed aspect ratio...

    // WordPress oembed isn't setting a width. If there's not a width, use the standard oEmbed aspect ratio...
    if (jQuery(jQuery(this).parent()).hasClass('ath-vids')) var aspectRatio = 0.666;
    jQuery(this).data('aspectRatio', aspectRatio).removeAttr('height').removeAttr('width');
    if (!isNaN(parseInt(this.width))) {
      var aspectRatio = parseInt(this.height) / parseInt(this.width);
    } else var aspectRatio = 0.76;
    jQuery(this).data('aspectRatio', aspectRatio).removeAttr('height').removeAttr('width');

    var parent = jQuery(jQuery(this).parent());
    if (parent.hasClass('ath-vids') || parent.hasClass('mackenzie') || parent.hasClass('resize-vimeo') || parent.hasClass('besteman') || parent.hasClass('commencement-vid')) {
      var aspectRatio = parent.hasClass('besteman') || parent.hasClass('commencement-vid') || parent.hasClass('resize-vimeo') ? 0.5625 : 0.55;
      jQuery(this).data('aspectRatio', aspectRatio).removeAttr('height').removeAttr('width');
    }
  });

  jQuery('.slide-content iframe').attr('src', function () {
    return this.src + '?title=false&byline=false&portrait=false&color=fff';
  });

  jQuery(window).resize(function () {
    jQuery(allVideos).each(function () {
      var el = jQuery(this);
      var newWidth = jQuery(el).parent().width();
      jQuery(el).width(newWidth).height(newWidth * jQuery(el).data('aspectRatio'));
    });
  }).resize();
});

function loginCMS() {
  var history = 'ColbyPrevious=host&' + escape(window.location.host) + '&method&GET&protocol&' + escape(window.location.protocol.substring(0, window.location.protocol.length - 1)) + '&arguments&&local&0';

  var port = window.location.port;
  if (window.location.protocol == 'https:' && window.location.port == '443') {
    port = '';
  }

  if (window.location.protocol == 'http:' && window.location.port == '80') {
    port = '';
  }
  history = history + '&port&' + escape(port);

  var query_string = '';
  if (window.location.search.length > 0) {
    query_string = window.location.search.substring(1, window.location.search.length);
  }
  query_string += window.location.hash;
  history = history + '&query_string&' + escape(query_string);

  history = history + '&uri&' + escape(window.location.pathname);

  history += '; path=/; domain=colby.edu';
  document.cookie = history;
  window.location = 'https://www.colby.edu/ColbyMaster/login/';
}

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


window.addEventListener('load', function () {
  var htmlElement = document.documentElement;
  htmlElement.classList.replace('no-js', 'js');
});

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(0);
module.exports = __webpack_require__(1);


/***/ })
/******/ ]);
//# sourceMappingURL=Colby_College.js.map