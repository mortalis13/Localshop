
$(document).ready(function(){
  
  var mainImageA = '.images .main-image a';
  var mainImageImg = '.images .main-image img';
  var thumbnails = '.images .thumbnails .zoom';
  
  var opts = {deeplinking: false, social_tools: false};
  $("[rel^='prettyPhoto']").prettyPhoto(opts);
  
  $(thumbnails).unbind('click');
  $(thumbnails).bind('click', function(e){
    e.preventDefault();
  //   // $(mainImageA).attr('href', $(this).attr('href'));
  //   // $(mainImageImg).attr('src', $(this).attr('href'));
  //   $(mainImageA).data('thumb-id', $(thumbnails).index(this));
  });
  
  $(mainImageA).click(function(e){
    e.preventDefault();
    
    var n = $(this).data('thumb-id');
    var thumb = $(thumbnails).eq(n);
    $.prettyPhoto.initialize.call(thumb);
  });
  
});
