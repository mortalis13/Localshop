
$ = jQuery;

jQuery(function($){
  
  function calcImageDetailWidths(){
    var imageDetailDistance = 50;
    
    var images=$(".images");
    var image=$(".images .main-image img");
    
    images.width(image.outerWidth()+2);
    $('.summary').width($('.summary').parent().width() - images.width() - imageDetailDistance);
  }
  
  // var screenWidth = window.screen.width;
  var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
  console.log('screenWidth: ' + screenWidth);
  
  if(screenWidth > 640){
    console.log('using calcImageDetailWidths()')
    calcImageDetailWidths();
  }
  
})
