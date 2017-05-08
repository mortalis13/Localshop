
jQuery(function($){
  
  function enableButton(button){
    $(button).removeAttr('disabled');
    $(button).removeClass('disabled');
  }
  
  function disableButton(button){
    $(button).attr('disabled', '');
    $(button).addClass('disabled');
  }
  
  
  // -------------- Send 1 email --------------
  
  $(".btnSendNewsletter").click(function(){
    var self = this;
    
    var idEmail = $(this).parents('tr').data('email-id');
    var data = {
      action: 'send_newsletter',
      id_email: idEmail
    };
    
    $.ajax({
      url: ajaxurl,
      data: data,
      type: 'POST',
      success: function(resp){
        enableButton(self);
        
        if(!resp || resp == '0'){
          console.error('ERROR sending email');
          alert('ERROR sending email');
        }
        else{
          alert('Email sent successfully');
        }
      },
      error: function(){
        enableButton(self);
        console.error('btnSendNewsletter - AJAX ERROR');
      }
    });
    
    disableButton(self);
  });
  
  
  // -------------- Delete email --------------
  
  $(".btnDeleteNewsletter").click(function(){
    var self = this;
    
    var idEmail = $(this).parents('tr').data('email-id');
    var data = {
      action: 'delete_newsletter',
      id_email: idEmail
    };
    
    if(!confirm('Want to delete this email?')){
      return;
    }
    
    $.ajax({
      url: ajaxurl,
      data: data,
      type: 'POST',
      success: function(resp){
        enableButton(self);
        
        if(!resp || resp == '0'){
          alert('ERROR deleting email');
        }
        
        location.reload();
      },
      error: function(){
        enableButton(self);
        console.log('btnDeleteNewsletter - ERROR');
      }
    });
    
    disableButton(self);
  });
  
  
  // -------------- Send all emails --------------
  
  $("#btnSendAllNewsletters").click(function(){
    var self = this;
    
    var data = {
      action: 'send_newsletter_all'
    };
    
    $.ajax({
      url: ajaxurl,
      data: data,
      type: 'POST',
      dataType: 'json',
      success: function(resp){
        enableButton(self);
        
        if(resp.status == 'success'){
          alert('All emails sent');
        }
        else if(resp.status == 'error_exception'){
          resp.ERRORS = resp.ERRORS.replace(/\\n/g, '\n');
          alert('ERRORS sending emails: \n\n' + resp.ERRORS);
        }
        else if(resp.status == 'error_send'){
          var list = resp.error_send_list;
          var list_text = '';
          for(var i in list){
            list_text += list[i] + '\n';
          }
          
          alert('ERROR sending these emails: \n\n' + list_text);
        }
      },
      error: function(){
        enableButton(self);
        console.log('btnSendAllNewsletters - ERROR');
      }
    });
    
    disableButton(self);
  });
  
  
  $("#preview").click(function(e){
    var previewWindow = window.open();
    var previewDocument = previewWindow.document;
    var previewBody = previewWindow.document.body;
    
    var previewHTML = $("#email-body").text();
    
    previewDocument.title = '= Newsletters Template Preview =';
    previewBody.innerHTML = previewHTML;
    
    return false;
  });
  
});
