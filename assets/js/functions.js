
jQuery(function($){
  
  var waitHandler;
  
  function startSignupWait() {
    var button = $("#signup-submit");
    // button.find('.signup-caption').html('');
    button.find('.signup-caption').remove();
    button.addClass('loader-4');
  }
  
  function stopSignupWait() {
    var button = $("#signup-submit");
    button.removeClass('loader-4');
    button.prepend('<span class="signup-caption">Sign Up</span>');
  }
  
  function startSignupWait1() {
    var counter = 0;
    var limit = 3;
    
    var button = $("#signup-submit span");
    var dotIcon = '<i class="fa fa-circle"></i>';
    
    var animDur = 150;
    
    var $dotIcon = $(dotIcon);
    $dotIcon.css('opacity', 0);
    button.html($dotIcon);
    $dotIcon.animate({opacity: 1}, animDur);
    
    clearInterval(waitHandler);
    waitHandler = setInterval(function(){
      if(counter < limit-1){
        $dotIcon = $(dotIcon);
        $dotIcon.css('opacity', 0);
        button.append($dotIcon);
        $dotIcon.animate({opacity: 1}, animDur);
        
        counter++;
      }
      else{
        $dotIcon = $(dotIcon);
        $dotIcon.css('opacity', 0);
        button.html($dotIcon);
        $dotIcon.animate({opacity: 1}, animDur);
        
        counter = 0;
      }
    }, 600);
  }

  function stopSignupWait1() {
    clearInterval(waitHandler);
    $("#signup-submit").html("Sign Up");
  }
  
  $('#signup-form').submit(function(){
    var thisForm = $(this);
    
    var url = this.action
    
    var email = $("#signup-email").val()
    var emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    
    if(!emailRegex.test(email)){
      alert("Email is not valid");
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
          thisForm.find('.signup-message').html('Your email added to the news list');
        }
      },
      error: function(jqXHR, textStatus, errorThrown){
        console.log('error: ' + textStatus + ', ' + errorThrown)
        stopSignupWait();
      }
    });
    
    return false;
  })
  
})
