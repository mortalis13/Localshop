
$ = jQuery;

jQuery(function($){
  
  var DISABLE_ZOOM = false;
  
  var fullSizeImage = false;
  var zoomPanelSize = 500;
  
  var zoomImageContainer = '.images .zoom-image';
  // var thumbnails = '.images .thumbnails a';
  var thumbnails = '.images .thumbnails .zoom';
  
  
  function calcImageDetailWidths(){
    var imageDetailDistance = 50;
    
    var images=$(".images");
    var image=$(".woocommerce-main-image img");
    
    images.width(image.outerWidth()+2);
    $('.summary').width($('.summary').parent().width() - images.width() - imageDetailDistance);
  }
  
  function calcImages(){
    var minItemW = 50;
    var imageN = 5;
    var distanceR = 15;
    var paddingRL = 0;
    var borderW = 0;
    
    var totalImages = $(thumbnails).length;
    
    // var mainImageW = $('.images .main-image').outerWidth();
    var mainImageW = $('.images .main-image')[0].getBoundingClientRect().width;
    
    // var itemW = (mainImageW + distanceR) / imageN - (2*paddingRL + distanceR + 2*borderW);
    var itemW = (mainImageW - distanceR * (imageN - 1)) / imageN - 2 * borderW;
    console.log('Thumbnail width 1: ' + itemW);
    
    itemW = Math.floor((itemW - 0.1) * 10) / 10;
    console.log('Thumbnail width 2: ' + itemW);
    
    if(itemW < minItemW){
      imageN = (mainImageW + distanceR) / (2 * borderW + minItemW + distanceR);
      imageN = Math.floor(imageN);
      
      itemW = (mainImageW - distanceR * (imageN - 1)) / imageN - 2 * borderW;
      itemW = Math.floor((itemW - 0.1) * 10) / 10;
      
      console.log('Recalculating thumbnails, new N: ' + imageN);
    }
    
    $(thumbnails).removeClass('last');
    
    $(thumbnails).each(function(id, item){
      if((id+1) % imageN == 0 || (id + 1) == totalImages){
        $(this).addClass('last');
      }
      
      $(this).width(itemW);
      $(this).filter('.last').css('margin-right', 0);
      $(this).not('.last').css('margin-right', distanceR);
    });
  }
  
  function getImageSize(imgSrc, callback){
    var image = new Image();
    
    image.onload = function(){
      var size = {
        width: image.width,
        height: image.height
      };
      
      // console.log('Image size:', size.width, size.height);
      
      if(callback != undefined){
        callback(size);
      }
    }
    
    image.src = imgSrc;
  }
  
  function cbImageLoaded(imgSize){
    var zoomWindowWidth = zoomPanelSize;
    var zoomWindowHeight = zoomPanelSize;
    
    var imgWidth = imgSize.width;
    var imgHeight = imgSize.height;
    
    if(imgWidth < zoomPanelSize || imgHeight < zoomPanelSize){
      console.log('full-image')
      
      zoomWindowWidth = Math.min(imgWidth, imgHeight) + 2;
      zoomWindowHeight = zoomWindowWidth;
    }
    
    $(zoomImageContainer + ' img').elevateZoom({
      zoomWindowPosition: 'zoom-anchor',
      zoomWindowWidth: zoomWindowWidth,
      zoomWindowHeight: zoomWindowHeight,
      // gallery: 'gallery-images_id',
    });
  }
  
  function initZoom(){
    if(!DISABLE_ZOOM){
      var imgSrc = $(zoomImageContainer + ' a').attr('href');
      getImageSize(imgSrc, cbImageLoaded);
    }
  }
  
  
  function highlightActiveImage(item){
    $(thumbnails).each(function(){
      $(this).css('box-shadow', 'none');
    });
    
    $(item).css('box-shadow', '0px 0px 3px 1px #dd6d27');
  }
  
  
  // --------------------------------
  
  $(thumbnails).mouseover(function(){
    var $img = $(this).find('img');
    // var imgSrc = $(this).attr('href');
    var imgSrc = $img.attr('url');
    
    $(zoomImageContainer + ' a').attr('href', imgSrc);
    $(zoomImageContainer + ' img').attr('src', imgSrc);
    $(zoomImageContainer + ' img').attr('srcset', imgSrc);
    
    $(zoomImageContainer + ' img').data('zoom-image', imgSrc);
    
    initZoom();
    
    // var ezObj = $(zoomImageContainer + ' img').data('elevateZoom');
    // ezObj.swaptheimage(imgSrc, imgSrc);
    
    highlightActiveImage(this);
  });
  
  
  // --------------------------------
  
  if(window.screen.width > 640){
    console.log('using calcImageDetailWidths()')
    calcImageDetailWidths();
  }
  if(MOBILE_DEVICE || window.screen.width < 480){
    console.log('disabling zoom')
    DISABLE_ZOOM = true;
  }
  
  calcImages();
  initZoom();
  highlightActiveImage($(thumbnails)[0]);
  
})
