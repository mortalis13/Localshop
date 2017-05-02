
$ = jQuery;

jQuery(function($){
  
  function calcImageDetailWidths(){
    var imageDetailDistance = 50;
    
    var images=$(".images");
    var image=$(".images .main-image img");
    
    images.width(image.outerWidth()+2);
    $('.summary').width($('.summary').parent().width() - images.width() - imageDetailDistance);
  }
  
  if(screenWidth > 640){
    console.log('using calcImageDetailWidths()')
    calcImageDetailWidths();
  }
  
})
