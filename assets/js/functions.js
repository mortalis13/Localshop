
var DEBUG_BAR = false;
// var DEBUG_BAR = true;

var MOBILE_DEVICE = false;
if ( /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()) ){
  MOBILE_DEVICE = true;
}

// var screenWidth = window.screen.width;
var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

jQuery(function($){
  
  function logDebug(text){
    $(".debug-bar pre").append(text + '\n');
  }

  function test1(){
    var screenSize = screenWidth + ' x ' + screenHeight;
    logDebug('Screen size: ' + screenSize);
  }
  
  if(DEBUG_BAR){
    $(".debug-bar").show();
    test1();
  }
  
  // --------------------------------------------
  
  var waitHandler;
  
  function startSignupWait() {
    var button = $("#signup-submit");
    button.find('.signup-caption').remove();
    button.addClass('loader-4');
  }
  
  function stopSignupWait() {
    var button = $("#signup-submit");
    button.removeClass('loader-4');
    button.prepend('<span class="signup-caption">' + signUpText + '</span>');
  }
  
  // function startSignupWait1() {
  //   var counter = 0;
  //   var limit = 3;
    
  //   var button = $("#signup-submit span");
  //   var dotIcon = '<i class="fa fa-circle"></i>';
    
  //   var animDur = 150;
    
  //   var $dotIcon = $(dotIcon);
  //   $dotIcon.css('opacity', 0);
  //   button.html($dotIcon);
  //   $dotIcon.animate({opacity: 1}, animDur);
    
  //   clearInterval(waitHandler);
  //   waitHandler = setInterval(function(){
  //     if(counter < limit-1){
  //       $dotIcon = $(dotIcon);
  //       $dotIcon.css('opacity', 0);
  //       button.append($dotIcon);
  //       $dotIcon.animate({opacity: 1}, animDur);
        
  //       counter++;
  //     }
  //     else{
  //       $dotIcon = $(dotIcon);
  //       $dotIcon.css('opacity', 0);
  //       button.html($dotIcon);
  //       $dotIcon.animate({opacity: 1}, animDur);
        
  //       counter = 0;
  //     }
  //   }, 600);
  // }

  // function stopSignupWait1() {
  //   clearInterval(waitHandler);
  //   $("#signup-submit").html("Sign Up");
  // }
  
  $('#signup-form').submit(function(){
    var thisForm = $(this);
    
    var url = this.action
    
    var email = $("#signup-email").val()
    var emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    
    if(!emailRegex.test(email)){
      alert(signUpEmailNotValidText);
      return false;
    }
    
    startSignupWait();
    
    $.ajax({
      type: "POST",
      url: url,
      data: thisForm.serialize(),
      
      success: function(data) {
        stopSignupWait();
        
        if(data == 'true'){
          console.log('ok');
          thisForm.find('.signup-message').html(signUpEmailAddedText);
        }
      },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('error: ' + textStatus + ', ' + errorThrown)
        stopSignupWait();
      }
    });
    
    return false;
  });
  
  
  // --------------
  
  if(MOBILE_DEVICE && !$('html').hasClass('firefox')){
    $('.woocommerce-result-count').css({'margin-top': 0});
  }
  
});
