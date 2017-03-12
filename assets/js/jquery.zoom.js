/*!
  Zoom 1.7.18
  license: MIT
  http://www.jacklmoore.com/zoom
*/
(function ($) {
  var defaults = {
    url: false,
    callback: false,
    target: false,
    duration: 120,
    on: 'mouseover', // other options: grab, click, toggle
    touch: true, // enables a touch fallback
    onZoomIn: false,
    onZoomOut: false,
    magnify: 1
  };

  // Core Zoom Logic, independent of event listeners.
  $.zoom = function(target, source, img, magnify) {
    var targetHeight,
      targetWidth,
      sourceHeight,
      sourceWidth,
      xRatio,
      yRatio,
      offset,
      $target = $(target),
      position = $target.css('position'),
      $source = $(source);
      
    var $img = $(img);
    var $zr, zrSize, zrPosition, imageLeft, imageTop, imageRight, imageBottom;

    // The parent element needs positioning so that the zoomed element can be correctly positioned within.
    target.style.position = /(absolute|fixed)/.test(position) ? position : 'relative';
    target.style.overflow = 'hidden';
    img.style.width = img.style.height = '';

    $(img)
      .addClass('zoomImg')
      .css({
        position: 'absolute',
        top: 0,
        left: 0,
        // opacity: 0,
        width: img.width * magnify,
        height: img.height * magnify,
        border: 'none',
        maxWidth: 'none',
        maxHeight: 'none'
      })
      .appendTo(target);

    return {
      init: function() {
        targetWidth = $target.outerWidth();
        targetHeight = $target.outerHeight();

        if (source === target) {
          sourceWidth = targetWidth;
          sourceHeight = targetHeight;
        } else {
          sourceWidth = $source.outerWidth();
          sourceHeight = $source.outerHeight();
        }

        xRatio = img.width / sourceWidth;
        yRatio = img.height / sourceHeight;
        // xRatio = (img.width - targetWidth) / sourceWidth;
        // yRatio = (img.height - targetHeight) / sourceHeight;

        offset = $source.offset();
        
        $zr = $('#zoom-region');
        
        imageLeft   = offset.left;
        imageTop    = offset.top;
        imageRight  = imageLeft + sourceWidth;
        imageBottom = imageTop + sourceHeight;
        
        // zrSize = Math.min(sourceWidth, targetWidth / xRatio);
        // $zr.width(zrSize-2); // -2*border
        // $zr.height(zrSize-2);
        // zrPosition = zrSize/2;
      },
      
      move: function (e) {
        zrSize = Math.min(sourceWidth, targetWidth / xRatio);
        $zr.width(zrSize-2); // -2*border
        $zr.height(zrSize-2);
        zrPosition = zrSize/2;
        
        var zoomRegionLeft = Math.max(Math.min(e.pageX, imageRight  - zrPosition) - zrPosition, imageLeft);
        var zoomRegionTop  = Math.max(Math.min(e.pageY, imageBottom - zrPosition) - zrPosition, imageTop);
        
        if($('#zoom-region').is(':visible')) {
          $zr.offset({left: zoomRegionLeft, top: zoomRegionTop});
        }
        
        // var left = (e.pageX - offset.left);
        // var top  = (e.pageY - offset.top);
        
        var left = (zoomRegionLeft - offset.left);
        var top  = (zoomRegionTop  - offset.top);
        
        left = Math.max(Math.min(left, sourceWidth), 0);
        top  = Math.max(Math.min(top, sourceHeight), 0);

        img.style.left = (left * -xRatio) + 'px';
        img.style.top  = (top * -yRatio)  + 'px';
        
        // console.log(targetWidth)
        // console.log(img.style.top)
      }
      
    };
  };

  $.fn.zoom = function (options) {
    return this.each(function () {
      var
      settings = $.extend({}, defaults, options || {}),
      //target will display the zoomed image
      target = settings.target && $(settings.target)[0] || this,
      //source will provide zoom location info (thumbnail)
      source = this,
      $source = $(source),
      img = document.createElement('img'),
      $img = $(img),
      mousemove = 'mousemove.zoom',
      clicked = false,
      touched = false;

      // If a url wasn't specified, look for an image element.
      if (!settings.url) {
        var srcElement = source.querySelector('img');
        if (srcElement) {
          settings.url = srcElement.getAttribute('data-src') || srcElement.currentSrc || srcElement.src;
        }
        if (!settings.url) {
          return;
        }
      }

      $source.one('zoom.destroy', function(position, overflow){
        $source.off(".zoom");
        target.style.position = position;
        target.style.overflow = overflow;
        img.onload = null;
        $img.remove();
      }.bind(this, target.style.position, target.style.overflow));

      img.onload = function () {
        var zoom = $.zoom(target, source, img, settings.magnify);

        function start(e) {
          // zoom.init();
          // zoom.move(e);

          // Skip the fade-in for IE8 and lower since it chokes on fading-in
          // and changing position based on mousemovement at the same time.
          // $img.stop()
          // .fadeTo($.support.opacity ? settings.duration : 0, 1, $.isFunction(settings.onZoomIn) ? settings.onZoomIn.call(img) : false);
          
          // $img.css('opacity', 1);
        }

        function stop() {
          // $img.stop().fadeTo(settings.duration, 0, $.isFunction(settings.onZoomOut) ? settings.onZoomOut.call(img) : false);
          
          // $img.css('opacity', 0);
        }

        // Mouse events
        if (settings.on === 'grab') {
          $source
            .on('mousedown.zoom',
              function (e) {
                if (e.which === 1) {
                  $(document).one('mouseup.zoom',
                    function () {
                      stop();

                      $(document).off(mousemove, zoom.move);
                    }
                  );

                  start(e);

                  $(document).on(mousemove, zoom.move);

                  e.preventDefault();
                }
              }
            );
        } else if (settings.on === 'click') {
          $source.on('click.zoom',
            function (e) {
              if (clicked) {
                // bubble the event up to the document to trigger the unbind.
                return;
              } else {
                clicked = true;
                start(e);
                $(document).on(mousemove, zoom.move);
                $(document).one('click.zoom',
                  function () {
                    stop();
                    clicked = false;
                    $(document).off(mousemove, zoom.move);
                  }
                );
                return false;
              }
            }
          );
        } else if (settings.on === 'toggle') {
          $source.on('click.zoom',
            function (e) {
              if (clicked) {
                stop();
              } else {
                start(e);
              }
              clicked = !clicked;
            }
          );
        } else if (settings.on === 'mouseover') {
          zoom.init(); // Preemptively call init because IE7 will fire the mousemove handler before the hover handler.

          $source
            .on('mouseenter.zoom', start)
            .on('mouseleave.zoom', stop)
            .on(mousemove, zoom.move);
        }

        // Touch fallback
        if (settings.touch) {
          $source
            .on('touchstart.zoom', function (e) {
              e.preventDefault();
              if (touched) {
                touched = false;
                stop();
              } else {
                touched = true;
                start( e.originalEvent.touches[0] || e.originalEvent.changedTouches[0] );
              }
            })
            .on('touchmove.zoom', function (e) {
              e.preventDefault();
              zoom.move( e.originalEvent.touches[0] || e.originalEvent.changedTouches[0] );
            })
            .on('touchend.zoom', function (e) {
              e.preventDefault();
              if (touched) {
                touched = false;
                stop();
              }
            });
        }
        
        if ($.isFunction(settings.callback)) {
          settings.callback.call(img);
        }
      };

      img.src = settings.url;
    });
  };

  $.fn.zoom.defaults = defaults;
}(window.jQuery));
