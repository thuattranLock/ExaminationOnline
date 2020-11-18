$(document).ready(function() {    
   $(".toggle").on('click', function(){
      var x =  $(this).attr("data-toggle");
      var y = $(`.${x}`).attr('type');
      if(y === "password"){
         $(`.${x}`).attr('type', 'text');
      }else{
         $(`.${x}`).attr('type', 'password');
      }
   });
});

$(document).ready(function () {
   $('#alert').removeClass("alert alert-success");

   jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });
   jQuery.validator.addMethod("pwStrong", function (value, element) {
      if (/^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value)) {
         return true;
      } else {
         return false;
      };
   }, "The password must contain at least 1 number, at least 1 lower case letter, and at least 1 upper case letter");
   jQuery.validator.addMethod("phone", function (value, element) {
      if (/([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/.test(value)) {
         return true;
      } else {
         return false;
      };
   }, "Please specify a valid phone number");
   var validator = $('#user_form_register').validate({
      rules: {
         user_email_address: {
            required: true,
            email: true,
            remote: {
               url: url+ "/users/ajaxCheckEmail",
               type: "post",
            }
         },
         user_password: {
            pwStrong: true,
            minlength: 6,
            required: true
         },
         user_confirm_password: {
            required: true,
            equalTo: "#user_password"
         },
         user_name:{
            required: true,
            minlength: 3
         },
         user_gender: {
            required: true
         },
         user_address: {
            required: true
         },
         user_avatar: { 
            required: true,
            accept: "image/*"
         },
         user_phone: {
            required: true,
            phone: true,
            minlength: 10,
            maxlength: 10,
         }
      },
      errorPlacement: function (error, element) {
         if(element.attr("name") == "user_confirm_password"){
            error.appendTo("#errorConfirmPass");
         }else if(element.attr("name") == "user_password") {
            error.appendTo("#errorPass");        
         } else {
            error.insertAfter(element)
         }
      },
      messages: {
         user_email_address: {
            remote: "Email already in use!"
         },
      },
   });
   
   $("#user_form_register").on('submit', function (e) { 
      e.preventDefault();
      if($("#user_form_register").valid()){
         $.ajax({
            url: url+ '/users/ajaxRegister',
            method: "POST",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
               $('#userRegister').attr('disabled', 'disabled');
            },
            success: function(data) {
               if (data.success) {
                  $("#alert").addClass("alert alert-success");
                  $('#alert').text(data.message);
		            $('#user_form_register')[0].reset();
                  validator.resetForm();
                  $("html, body").animate({ scrollTop: 0 }, "slow");
               }
               $('#userRegister').attr('disabled', false);
               return false;
            }
          })
      }
   });
});
