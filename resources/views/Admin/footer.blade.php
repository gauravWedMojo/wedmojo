<div class="layout-footer">
    <div class="layout-footer-body">
        <small class="copyright">2018 &copy; Wedmojo Pvt. Ltd.</small>
    </div>
</div>
</div>
<script src="{{asset('Admin/js/jquery.min.js')}}"></script>
<script src="{{asset('Admin/js/vendor.min.js')}}"></script>
    <script src="{{asset('Admin/js/elephant.min.js')}}"></script>
    <script src="{{asset('Admin/js/application.min.js')}}"></script>
    <script src="{{asset('Admin/js/profile.min.js')}}"></script>
     <script src="{{asset('Admin/js/demo.min.js')}}"></script>
   
    <script type="text/javascript">
      $(window).load(function(){

        // For Driver Page
        if ( $('.profilePage').length ) {
          $('.sidenav-item').removeClass("active");
          $('.profilePageNav').addClass("active");
        }
        if ( $('.dashboardPage').length ) {
          $('.sidenav-item').removeClass("active");
          $('.dashboardPageNav').addClass("active");
        }
        
        if ( $('.userListPage').length ) {
          $('.sidenav-item').removeClass("active");
          $('.accNav-a').addClass("active");
          $('.accountNav').addClass("open");
          $('.accountNav ul').css("display","block");
        }
        if ( $('.serviceProviderPage').length ) {
          $('.sidenav-item').removeClass("active");
          $('.accNav-b').addClass("active");
          $('.accountNav').addClass("open");
          $('.accountNav ul').css("display","block");
        }
        if ( $('.verificationPage').length ) {
          $('.sidenav-item').removeClass("active");
          $('.verificationNav').addClass("active");
        }
      });
      $(window).scroll(function (){
          var window_top = $(window).scrollTop();
          var div_top = $('.tabs-new').position().top;
          console.log(window_top);
          console.log(div_top);
          if (window_top > div_top) {
              $('.tabs-new').addClass('stick');
              $('.tabs-new').parents('.layout-content-body').removeClass('layout-content-body').addClass('layout-content-body-dummy');
          } else {
              $('.tabs-new').parents('.layout-content-body').removeClass('layout-content-body-dummy').addClass('layout-content-body')
          }

          if ( window_top < 74 ) {
            $('.tabs-new').removeClass('stick');
          }
      });
      $(document).ready(function(){
        
        $('.change-password-form-submit').on('click',function(e){
          e.preventDefault();
          var old_password = $('.ad_old_password').val().trim();
          var password = $('.ad_password').val().trim();
          var confirm_password = $('.ad_confirm_password').val().trim();
          $('.old_err').empty();
          $('.pass_err').empty();
          $('.c_pass_err').empty();
          if(old_password.length < 1){
            $('.old_err').text('Please enter old password.');
            return false;
          }
          var request = $.ajax({
            url : "{{url('check')}}",
            type : "POST",
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            data : {'old_password':old_password},
          });
          request.done(function(data){
            if(data == 0 ){
              $('.old_err').text('Old password is incorrect.');
              return false;
            }
          });
          if(password.length < 1){
            $('.pass_err').text('Please enter password.');
            return false;
          }
          if(confirm_password.length < 1){
            $('.c_pass_err').text('Please enter confirm password.');
            return false;
          }
          if(password != confirm_password){
            $('.c_pass_err').text('Confirm password must be same.');
            return false;
          }
          $('.change-password-form').submit();
        });
        $('.user-management-sr-no').click();

        $('.BlockUser-UserManagement').click(function(){
          var user_id = $(this).attr('data-id');
          console.log(user_id);
          $('.BlockUserUserManagementContinue').attr('href',"{{url('admin/block-unblock-user')}}/"+user_id+'/'+'0');
        });

         $('.UnBlockUser-UserManagement').click(function(){
          var user_id = $(this).attr('data-id');
          console.log(user_id);
          $('.UnBlockUserUserManagementContinue').attr('href',"{{url('admin/block-unblock-user')}}/"+user_id+'/'+'1');
        });
      });
    </script>
    
    
</body>
</html>