$(document).ready(function () {
   $('#user_login_form').on('submit', function(event){
      event.preventDefault();
   
      var user_email_address = $("#user_email_address").val();
      var user_password = $("#user_password").val();

      $.ajax({
         type: "POST",
         url: url + "/users/ajaxLogin",
         dataType: "json",
         data: {
            user_email_address: user_email_address,
            user_password: user_password,
         },
         success : function(data){
            if(data.success){
               window.location.replace(url + '/users/index');
            }else{
               console.log(data);
               $('.errors').empty();
               $.each(data, function (key, val) { 
                  $(`.errors-${key}`).text(val);
               });
            }
         },
      });
   });
});
