
jQuery(function($){
  
  var DEBUG_MODE = false;
  
  // var showZoomRegion = false;
  var showZoomRegion = true;
  var fullSizeImage = false;
  var zoomPanelSize = 500;
  
  var zoomImageContainer = '.images .zoom-image';
  var thumbnails = '.images .thumbnails a';
  var tagetPanel = '#zoom-panel';
  
  var targetImg;
  
  if(DEBUG_MODE){
    $("#zoom-panel").show();
  }
  
  
  function calcImageDetailWidths(){
    var imageDetailDistance = 50
    
    var images=$(".images")
    var image=$(".woocommerce-main-image img")
    
    images.width(image.outerWidth()+2)
    $('.summary').width($('.summary').parent().width() - images.width() - imageDetailDistance)
  }
  
  function calcImages(){
    var mainImageW = $('.images .main-image').outerWidth();
    
    var imageN = 5;
    var marginRight = 15;
    var paddingRL = 0;
    var borderW = 0;
    
    var itemW = (mainImageW + marginRight) / imageN - (2*paddingRL + marginRight + 2*borderW);
    
    $(thumbnails).each(function(){
      $(this).width(itemW);
    });
  }
  
  function initZoom(){
    fullSizeImage = false;
    
    // fixZoomPanelSize();
    
    var url = $(zoomImageContainer).find('a').attr('href');
    $(zoomImageContainer).zoom({
      target: tagetPanel,
      url: url,
      
      callback: function(){
        // $('#zoom-panel').css('left', 0);
        // var left = $('.summary').offset().left - $(zoomImageContainer).offset().left;
        // $('#zoom-panel').offset({
        //   left: left
        // });
        
        targetImg = this;
        if(targetImg.width <= $(tagetPanel).width()){
          fullSizeImage = true;
        }
      }
      
    });
  }
  
  function highlightActiveImage(item){
    $(thumbnails).each(function(){
      $(this).css('box-shadow', 'none');
    });
    
    $(item).css('box-shadow', '0px 0px 3px 1px #dd6d27');
  }
  
  function fixZoomPanelSize() {
    var w = $('#zoom-panel img').width();
    var h = $('#zoom-panel img').height();
    
    if($('#zoom-panel').width() != zoomPanelSize){
      $('#zoom-panel').width(zoomPanelSize).height(zoomPanelSize);
    }
    
    if(w < zoomPanelSize || h < zoomPanelSize){
      var newSize = Math.min(w, h);
      $('#zoom-panel').width(newSize).height(newSize);
    }
    
    // if(fullSizeImage){
    //   var newSize = Math.min(targetImg.width, targetImg.height);
    //   $('#zoom-panel').width(newSize).height(newSize);
    // }
  }
  
  
  // --------------------------------
  
  $(zoomImageContainer).mouseover(function(){
    if(showZoomRegion && !fullSizeImage){
      $('#zoom-region').show();
    }
    
    $('#zoom-panel').show();
    // fixZoomPanelSize();
    
    // $('#zoom-panel').css('left', 0);
    // var left = $('.summary').offset().left - $(zoomImageContainer).offset().left;
    var left = $('.summary').offset().left;
    $('#zoom-panel').offset({
      left: left
    });
  });
  
  $(zoomImageContainer).mouseout(function(){
    if(!DEBUG_MODE){
      $('#zoom-region').hide();
      $('#zoom-panel').hide();
    }
    
    // $('#zoom-region').hide();
    // $('#zoom-panel').hide();
  });
  
  $(thumbnails).mouseover(function(){
    var $img = $(this).find('img');
    $(zoomImageContainer + ' a').attr('href', $(this).attr('href'));
    $(zoomImageContainer + ' img').attr('src', $(this).attr('href'));
    
    $(zoomImageContainer + ' img').attr('alt', $img.attr('alt'));
    $(zoomImageContainer + ' img').attr('title', $img.attr('title'));
    $(zoomImageContainer + ' img').attr('srcset', $img.attr('srcset'));
    
    highlightActiveImage(this);
    
    $(zoomImageContainer).trigger('zoom.destroy');
    initZoom();
  });
  
  
  calcImageDetailWidths();
  
  calcImages();
  initZoom();
  highlightActiveImage($(thumbnails)[0]);
  
})
