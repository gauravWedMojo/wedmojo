@include('Admin/header')
      <div class="profilePage"></div>
      <div class="layout-content">
        <div class="profile">
          <div class="profile-header">
            <div class="profile-cover">
              <div class="profile-container">
                <div class="profile-card">
                  <div class="profile-avetar">
                    @if(!Auth::guard('admin')->user()->profile)
                      <img class="profile-avetar-img" width="128" height="128" src="{{asset('Admin/img/0180441436.jpg')}}" alt="{{Auth::guard('admin')->user()->name}}">
                    @else
                      @if(App::environment() == 'local')
                        <img class="profile-avetar-img" width="128" height="128" src="{{asset('Admin/img')}}/{{Auth::guard('admin')->user()->profile}}" alt="{{Auth::guard('admin')->user()->name}}">
                      @else
                        <img class="profile-avetar-img" width="128" height="128" src="{{url('public/Admin/img')}}/{{Auth::guard('admin')->user()->profile}}" alt="{{Auth::guard('admin')->user()->name}}">
                      @endif
                    @endif
                  </div>
                  <div class="profile-overview">
                    <h1 class="profile-name">{{Auth::guard('admin')->user()->name}}</h1>
               
                    <p>{{Auth::guard('admin')->user()->about_me}}</p>
                  </div>
                 
                </div>
                <div class="profile-tabs">
                  <ul class="profile-nav">
                    <li class="active">
                      <a href="#">Details</a>
                    </li>
                    
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="profile-body">
             <div class="col-md-6">
              <div class="card">
                
                <div class="card-body">
                  <div class="card-search">
                   
                    <div class="card-search-results">
                      <div class="timeline">
                        <div class="timeline-item">
                          <div class="timeline-segment">
                            <div class="timeline-divider"></div>
                          </div>
                          <div class="timeline-content"></div>
                        </div>
                        <div class="timeline-item">
                          <div class="timeline-segment">
                            <div class="timeline-media bg-primary circle sq-24">
                              <div class="icon icon-check"></div>
                            </div>
                          </div>
                          <div class="timeline-content">
                           <div class="timeline-row">
                              <div class="media">
                                <div class="media-body">
                                  <h5 class="m-y-0">Email</h5>
                                  <p>
                                    <small>{{Auth::guard('admin')->user()->email}}</small>
                                  </p>
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                         <div class="timeline-item">
                          <div class="timeline-segment">
                            <div class="timeline-media bg-primary circle sq-24">
                              <div class="icon icon-check"></div>
                            </div>
                          </div>
                          <div class="timeline-content">
                           <div class="timeline-row">
                              <div class="media">
                                <div class="media-body">
                                  <h5 class="m-y-0">Phone</h5>
                                  <p>
                                    <small>{{Auth::guard('admin')->user()->mobile}}</small>
                                  </p>
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                
                <div class="card-body">
                  <div class="card-search">
                   
                    <div class="card-search-results">
                      <div class="timeline">
                        <div class="timeline-item">
                          <div class="timeline-segment">
                            <div class="timeline-divider"></div>
                          </div>
                          <div class="timeline-content"></div>
                        </div>
                        <div class="timeline-item">
                          <div class="timeline-segment">
                            <div class="timeline-media bg-primary circle sq-24">
                              <div class="icon icon-check"></div>
                            </div>
                          </div>
                          <div class="timeline-content">
                           <div class="timeline-row">
                              <div class="media">
                                <div class="media-body">
                                  <h5 class="m-y-0">Address</h5>
                                  <p>
                                    <small>{{Auth::guard('admin')->user()->address}}</small>
                                  </p>
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                         <div class="timeline-item">
                          <div class="timeline-segment">
                            <div class="timeline-media bg-primary circle sq-24">
                              <div class="icon icon-check"></div>
                            </div>
                          </div>
                          <div class="timeline-content">
                           <div class="timeline-row">
                              <div class="media">
                                <div class="media-body">
                                  <h5 class="m-y-0">Location</h5>
                                  <p>
                                    <small>{{Auth::guard('admin')->user()->location}}</small>
                                  </p>
                                </div>
                              </div>
                              
                            </div>
                          </div>
                        </div>
                        
                        
                  
                      </div>
                    </div>
                  </div>
                 
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
             <a href="{{url('admin/edit-profile')}}" class="">       
              <button class="btn btn-primary btn-sm pull-right" type="button">Edit profile</button> 
             </a>
             <a href="{{url('admin/change-password')}}" >       
                <button class="btn btn-primary btn-sm pull-right margin-right-5" type="button">Change Password</button> 
              </a>
                  </div>
          </div>
        </div>
      </div>
      
    </div>
@include('Admin/footer')
