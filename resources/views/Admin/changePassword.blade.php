@include('Admin.header')
    <div class="profilePage"></div>
    <div class="layout-content">
        <div class="layout-content-body">
            <div class="title-bar">

                <h1 class="title-bar-title">
                  <span class="d-ib">Change Password /</span>
                  <a href="profile.php">Back</a>
                </h1>
                <p class="title-bar-description">
                    <small>Welcome to Wedmojo</small>
                </p>
            </div>
            <div class="row gutter-xs">
                <div class="col-md-8 card panel-body  " id="">
                    <div class="col-sm-12 col-md-12">

                        <div class="demo-form-wrapper">
                            <form class="form form-horizontal change-password-form" action="{{url('/admin/change-password/')}}" method="POST">
                            {{csrf_field()}}
                                <span style="color:green">{{Session::get('password_changed')}}</span>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <label for="" class=" control-label">Old Password</label>
                                        <input id="" class="form-control ad_old_password" name="old_password" type="password" required minlength="6">
                                        <span class="old_err" style="color:red"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <label for="" class=" control-label">New Password</label>
                                        <input id="" class="form-control ad_password" name="password" type="password" required minlength="6">
                                        <span class="pass_err" style="color:red"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <label for="" class=" control-label">Confirm Password</label>
                                        <input id="" class="form-control ad_confirm_password" name="confirm_password" type="password" required minlength="6">
                                        <span class="c_pass_err" style="color:red"></span>
                                    </div>
                                </div>
                              
                                <div class="form-group">
                                    <div class=" col-sm-8  col-md-8 ">
                                        <button class="btn btn-primary btn-block change-password-form-submit" type="button">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@include('Admin.footer')