
jQuery(function($){
  
  var DISABLE_ZOOM = false;
  // var DISABLE_ZOOM = true;
  
  var fullSizeImage = false;
  var zoomPanelSize = 500;
  
  var zoomImageContainer = '.images .zoom-image';
  var thumbnails = '.images .thumbnails .zoom';
  
  
  function calcThumbnails(){
    var IE7_BROWSER = $('html').hasClass('ie7');
    
    var minItemW = 50;
    var imageN = 5;
    var distanceR = 15;
    var paddingRL = 5;
    var borderW = 1;
    
    var totalImages = $(thumbnails).length;
    
    // var mainImageW = $('.images .main-image').outerWidth();
    var boundingRect = $('.images .main-image')[0].getBoundingClientRect();
    var mainImageW = boundingRect.width;
    if(mainImageW === undefined){
      mainImageW = boundingRect.right - boundingRect.left;
    }
    
    /*
      -- Calculating thumbnail width to fill all thumbnails within the main image width --
      Original equation (total width eq: (2 borders + 2 paddings + image width) for N images + N-1 distances between images)
      W = (2*b + 2*p + w)*N + d*(N-1)
      
      Each image width
      w = (W - d*(N-1)) / N - (2*b + 2*p)
    */
    
    var itemW = calcItemWidth();
    console.log('Thumbnail width: ' + itemW);
    
    if(itemW < minItemW){
      imageN = calcImageCount();
      itemW = calcItemWidth();
      console.log('Recalculating thumbnails, new N: ' + imageN + ' new Thumbnail width: ' + itemW);
    }
    
    $(thumbnails).removeClass('last');
    
    $(thumbnails).each(function(id, item){
      if((id+1) % imageN == 0 || (id + 1) == totalImages){
        $(this).addClass('last');
      }
      
      if(IE7_BROWSER){
        $(this).outerWidth(itemW);
      }
      else{
        $(this).width(itemW);
      }
      
      $(this).filter('.last').css('margin-right', 0);
      $(this).not('.last').css('margin-right', distanceR);
    });
    
    
    function calcImageCount(){
      var imageN = (mainImageW + distanceR) / (2*borderW + 2*paddingRL + minItemW + distanceR);
      imageN = Math.floor(imageN);
      return imageN;
    }
    
    function calcItemWidth(){
      // var itemW = (mainImageW + distanceR) / imageN - (2*paddingRL + distanceR + 2*borderW);
      // var itemW = (mainImageW - distanceR * (imageN - 1)) / imageN - (2*borderW);
      // var itemW = (mainImageW - distanceR * (imageN - 1)) / imageN - (2*borderW + 2*paddingRL);
      
      var itemWidth = (mainImageW - distanceR * (imageN - 1)) / imageN - (2*borderW + 2*paddingRL);
      itemWidth = Math.floor((itemWidth - 0.1) * 10) / 10;
      return itemWidth;
    }
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
    zoomPanelSize = Math.min(zoomPanelSize, $('#zoom-anchor').width());
    
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
      zoomWindowHeight: zoomWindowHeight
    });
  }
  
  function initZoom(){
    if(!DISABLE_ZOOM){
      $('.zoomContainer').remove();
      $('.zoomWindowContainer').remove();
      
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
  
  
  // var screenWidth = window.screen.width;
  var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  console.log('screenWidth: ' + screenWidth);
  
  if(MOBILE_DEVICE || screenWidth < 640){
    console.log('disabling zoom');
    DISABLE_ZOOM = true;
  }
  
  calcThumbnails();
  initZoom();
  highlightActiveImage($(thumbnails)[0]);
  
})
