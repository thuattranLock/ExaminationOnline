$(document).ready(function () {
   $('#alert').removeClass("alert alert-success");

   $('#admin_register_form').on('submit', function(event){
      event.preventDefault();

      var admin_email_address = $("#admin_email_address").val();
      var admin_password = $("#admin_password").val();
      var confirm_admin_password = $("#confirm_admin_password").val();
      
      $.ajax({
         type: "POST",
         url: url + "/admins/ajaxRegister",
         dataType: "json",
         data: {
            admin_email_address: admin_email_address,
            admin_password: admin_password,
            confirm_admin_password: confirm_admin_password
         },
         success : function(data){
            if(data.code == 404){
               $('.errors').empty();
               $.each(data.errors, function (key, val) { 
                  $(`.errors-${key}`).text(val);
               });
            }
            if(data.code == 200 ){
               $(".errors").empty();
               $("#alert").addClass("alert alert-success");
               $('#alert').text(data.data.message);
            }      
         },
      });
   });
});
