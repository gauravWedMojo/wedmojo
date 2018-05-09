<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wedmojo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <link rel="manifest" href="manifest.json">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#2c3e50">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="stylesheet" href="{{asset('Admin/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{asset('Admin/css/elephant.min.css')}}">
    <link rel="stylesheet" href="{{asset('Admin/css/application.min.css')}}">
    <link rel="stylesheet" href="{{asset('Admin/css/login-2.min.css')}}">
  </head>
  <body>
    <div class="login">
      <div class="login-body">
        <a class="login-brand" href="#">
          <img class="img-responsive" src="{{asset('Admin/img/logo-blk.png')}}" alt="logo">
        </a>
        <div class="login-form">
          <form data-toggle="validator" id="reset_password_form" action="{{url('/admin/reset-password/')}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" name="token" value="{{Request::segment(3)}}">
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" class="form-control password" type="password" name="password" minlength="6" data-msg-minlength="Password must be 6 characters or more." data-msg-required="Please enter your password." required>
            </div>
            <div class="form-group">
              <label for="password">Confirm Password</label>
              <input id="password" class="form-control confirm_password" type="password" name="confirm_password" minlength="6" data-msg-minlength="Password must be 6 characters or more." data-msg-required="Confirm password." required>
              <span class="err"></span>
            </div>
            <button class="btn btn-primary btn-block save_password" type="submit">Done</button>
          </form>
        </div>
      </div>
      
    </div>
    <script src="{{asset('Admin/js/vendor.min.js')}}"></script>
    <script src="{{asset('Admin/js/elephant.min.js')}}"></script>
    <script src="{{asset('Admin/js/application.min.js')}}"></script>
    <script type="text/javascript">
      $(document).ready(function(){
          $('.save_password').on('click',function(e){
              e.preventDefault();
              var password = $('.password').val();
              var confirm_password = $('.confirm_password').val();
              $('.err').empty();
              if(password != confirm_password){
                $('.err').text('Confirm password must be same.').css('color','red');
              }else{
                $('#reset_password_form').submit();
              }
          });
      });

    </script>
   
  </body>
</html>