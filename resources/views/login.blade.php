<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    {{-- Bootstrap-5 css file --}}
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">

      {{-- jquery js file --}}
      <script src="jquery/jquery.min.js"></script>

</head>
<div class="main-section">
    <div class="content-section">
        <div class="col-md-4 offset-md-4 mt-5">
        <div class="card">
        <div class="card-header">
            <h2>Login</h2>
        </div>
        <div class="card-body">
        
            <div class="mb-2">
                <label for="">Email</label>
            <input type="email" id="email" class="form-control" placeholder="Please Enter Email...">

         </div>

         <div class="mb-2">
            <label for="">Password</label>
          <input type="password" id="password" class="form-control" placeholder="Please Enter Password...">
        
          </div>

          <div class="mb-2">
          <button class="btn btn-primary" id="login">Login</button>
        
          </div>
        
        </div>
       
    </div>
</div>
    </div>
</div>
<body>
    
{{-- Bootstrap-5 js --}}
    <script src="bootstrap/bootstrap.js"></script>
  

<script>
    $(document).ready(function() {
        $("#login").on("click", function() {
          var email =  $("#email").val();
           var password = $("#password").val();

           $.ajax({
               url: "/api/login",
               type: "POST",
               contentType: 'application/json',
               data : JSON.stringify({
                 email : email,
                 password : password
               }),
               success: function(response){

                localStorage.setItem('api_token', response.token);

                

                window.location.href = "/posts";
               },
               error:function(xhr,status,error) {
                  alert('Error:' + xhr.responseText);
               }
              
               
           });
        });
    });
</script>


</body>
</html>