$('#loginform').on('submit', function(e){
      e.preventDefault();
      $("#login_btn").attr('disabled', true);
      var name = $("#username").val();
      var pass = $("#password").val();   
      if(name == "") {
         $("#username").closest('.form-group').addClass('has-error');
         noty({
            width: 200,
            text: 'Please Enter the Username',
            type: 'error',
            dismissQueue: true,
            timeout: 3000,
            layout: 'top'
         });
      }else {
         $("#username").closest('.form-group').removeClass('has-error');
         $("#username").closest('.form-group').addClass('has-success');
      }
      if(pass == "") {
         $("#password").closest('.form-group').addClass('has-error');
         noty({
            width: 200,
            text: 'Please Enter the Password',
            type: 'error',
            dismissQueue: true,
            timeout: 3000,
            layout: 'top'
         });
      }else {
         $("#password").closest('.form-group').removeClass('has-error');
         $("#password").closest('.form-group').addClass('has-success');
      }
      if(name && pass ) {
            $.ajax({
                  url : 'models/_login.php',
                  type : 'post',
                  data : {
                        username:name,
                        password:pass
                  },
                  dataType : 'json',
                  success : function(response) {
                        $("#login_btn").attr('disabled', false);
                        if(response.is_success == true) {
                              noty({
                                    width: 200,
                                    text: response.data,
                                    type: 'success',
                                    dismissQueue: true,
                                    timeout: 3000,
                                    layout: 'top',
                              });
                              window.location='starter.php';
                        } else {
                              noty({
                                    width: 200,
                                    text: response.data,
                                    type: 'error',
                                    dismissQueue: true,
                                    timeout: 3000,
                                    layout: 'top',
                              });
                        }
                  }
            }); 
      }
});