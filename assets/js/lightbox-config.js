
$(document).ready(function(){
  
  var opts = {
    fadeDuration: 100,
    imageFadeDuration: 100,
    resizeDuration: 500,
    wrapAround: true,
  };
  lightbox.option(opts);
  
  var mainImageA = '.images .main-image a';
  var mainImageImg = '.images .main-image img';
  var thumbnails = '.images .thumbnails .zoom';
  
  $(thumbnails).click(function(e){
    e.preventDefault();
  });
  
  $(mainImageA).click(function(e){
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
