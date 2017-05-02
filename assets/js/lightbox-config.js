
$(document).ready(function(){
  
  var minScreenSize = 500;
  
  var opts = {
    fadeDuration: 100,
    imageFadeDuration: 100,
    resizeDuration: 500,
    wrapAround: true
  };
  lightbox.option(opts);
  
  var mainImageA = '.images .main-image a';
  var mainImageImg = '.images .main-image img';
  var thumbnails = '.images .thumbnails .zoom';
  
  $(thumbnails).click(function(e){
    e.preventDefault();
  });
  
  $(mainImageA).click(function(e){
    var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

    if(screenWidth < minScreenSize || screenHeight < minScreenSize){
      return true;
    }
    
    e.preventDefault();
    
    lightbox.enabled = true;
    
    if(!$(this).data('lightbox')){
      var n = $(this).data('thumb-id');
      var thumb = $(thumbnails).eq(n);
      thumb.click();
      
      // lightbox.enabled = false;
    }
  });
  
  $('.lb-fullsize').click(function(){
    var src = $(".lb-image").attr('src');
    window.open(src);
  });
  
});
