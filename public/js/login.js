$(document).ready(function () {
   $('#admin_login_form').on('submit', function(event){
      event.preventDefault();
   
      var admin_email_address = $("#admin_email_address").val();
      var admin_password = $("#admin_password").val();

      $.ajax({
         type: "POST",
         url: url + "/admins/ajaxLogin",
         dataType: "json",
         data: {
            admin_email_address: admin_email_address,
            admin_password: admin_password,
         },
         success : function(data){
            if(data.code == 200){
               window.location.replace( url + '/admins/index');
            }else{
               console.log(data);
               $('.errors').empty();
               $.each(data.errors, function (key, val) { 
                  $(`.errors-${key}`).text(val);
               });
            }
         },
      });
   });
});
