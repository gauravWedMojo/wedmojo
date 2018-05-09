@include('Admin/header')

<div class="profilePage"></div>
<div class="layout-content">
    <div class="layout-content-body">
        <div class="title-bar">

            <h1 class="title-bar-title">
              <span class="d-ib">Edit Profile /</span>
              <a href="profile.php">Back</a>
            </h1>
            <p class="title-bar-description">
                <small>Welcome to News App</small>
            </p>
        </div>

        <div class="row gutter-xs">


            <div class="col-md-8 card panel-body  " id="">
                <div class="col-sm-12 col-md-12">

                    <div class="demo-form-wrapper">
                        <form class="form form-horizontal" action="{{url('admin/edit-profile')}}" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="email-2" class=" control-label">Email</label>

                                    <input id="" class="form-control" name="email" type="text" value="{{Auth::guard('admin')->user()->email}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="email-2" class=" control-label">Phone</label>

                                    <input id="" name="mobile" class="form-control" type="text" value="{{Auth::guard('admin')->user()->mobile}}">
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="email-2" class=" control-label">Add profile pic</label>

                                    <input id="" name="profile" class="form-control" type="file">
                                </div>

                            </div>
                   
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="email-2" class=" control-label">Location</label>

                                    <input id="" name="location" class="form-control" type="text" value="{{Auth::guard('admin')->user()->location}}">
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <label for="about" class=" control-label">About me</label>

                                    <textarea id="" name="about_me" class="form-control" rows="5">{{Auth::guard('admin')->user()->about_me}}</textarea>
                                </div>
                            </div>
                             <div class="form-group">
                                <div class="col-md-8">
                                    <label for="address" class=" control-label">Address</label>

                                    <textarea id="" name="address" class="form-control" rows="5">{{Auth::guard('admin')->user()->address}}</textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class=" col-sm-8  col-md-8 ">
                                    <button class="btn btn-primary " type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>



@include('Admin/footer')