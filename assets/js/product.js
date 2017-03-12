
jQuery(function($){
  
  var fullSizeImage = false;
  var zoomPanelSize = 500;
  
  var zoomImageContainer = '.images .zoom-image';
  var thumbnails = '.images .thumbnails a';
  
  
  function calcImageDetailWidths(){
    var imageDetailDistance = 50;
    
    var images=$(".images");
    var image=$(".woocommerce-main-image img");
    
    images.width(image.outerWidth()+2);
    $('.summary').width($('.summary').parent().width() - images.width() - imageDetailDistance);
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
  
  function getImageSize(imgSrc, callback){
    var image = new Image();
    
    image.onload = function(){
      var size = {
        width: image.width,
        height: image.height
      };
      
      console.log('Image size:', size.width, size.height);
      
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
    var imgSrc = $(zoomImageContainer + ' a').attr('href');
    
    getImageSize(imgSrc, cbImageLoaded);
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
    var imgSrc = $(this).attr('href');
    
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
  
  calcImageDetailWidths();
  
  calcImages();
  initZoom();
  highlightActiveImage($(thumbnails)[0]);
  
})
