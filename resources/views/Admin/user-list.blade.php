@include('Admin.header')
  <div class="userListPage ExplorerListPage">
      <div class="layout-content">
          <div class="layout-content-body">
              <div class="title-bar">

                  <h1 class="title-bar-title">
                    <span class="d-ib">User List</span>
                  </h1>
              </div>
              <div class="row gutter-xs">
                  <div class="col-xs-12">
                      <div class="card">
                          <div class="card-header">
                              <div class="card-actions">
                                  <button type="button" class="card-action card-toggler" title="Collapse"></button>
                              </div>
                              <strong>User list</strong><br>
                              <span style="color: purple">{{Session::get('block_unblock_success')}}</span>
                          </div>
                          <div class="card-body">
                              <table id="demo-datatables-5" class="table table-striped table-bordered table-wrap dataTable" cellspacing="0" width="100%">
                                  <thead>
                                      <tr>
                                          <th class="user-management-sr-no">Sr. No.</th>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th>Mobile</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>

                                  <tbody>
                                    @foreach($data as $key => $user)
                                      <tr>
                                          <td>{{++$key}}</td>
                                          <td>{{$user->first_name}} {{$user->last_name}}</td>
                                          <td>{{$user->email}}</td>
                                          <td>{{$user->mobile}}</td>
                                          <td class="">
                                            @if($user->status == 1)
                                              <button class="btn btn-danger btn-sm btn-labeled BlockUser-UserManagement" type="button" data-toggle="modal" data-target="#BlockUser" data-id="{{$user->id}}">
                                                <span class="btn-label">
                                                 <span class="icon icon-bank icon-lg icon-fw"></span>
                                                </span>
                                                  Block
                                              </button>
                                            @else
                                              <button class="btn btn-success btn-sm btn-labeled UnBlockUser-UserManagement" type="button"  data-toggle="modal" data-target="#UnBlockUser" data-id="{{$user->id}}">
                                                  <span class="btn-label">
                                                   <span class="icon icon-edit icon-lg icon-fw"></span>
                                                  </span>
                                                  Un-block
                                              </button>
                                            @endif
                                          </td>
                                      </tr>
                                    @endforeach
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>

          </div>
      </div>
  </div>
@include('Admin.footer')
<div id="BlockUser" tabindex="-1" role="dialog" class="modal fade">
 <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <span class="text-danger icon icon-times-circle icon-5x"></span>
          <h3 class="text-danger">Warning</h3>
          <h4>Are you want to block this user ?</h4>
          <div class="m-t-lg">
            <a class="btn btn-danger BlockUserUserManagementContinue" type="button">Continue</a>
            <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>      
</div>

<div id="UnBlockUser" tabindex="-1" role="dialog" class="modal fade">
 <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
          <span class="sr-only">Close</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <span class="text-info icon icon-check-circle icon-5x"></span>
          <h3 class="text-info">Info</h3>
          <h4>Are you want to unblock this item</h4>
          <div class="m-t-lg">
            <a class="btn btn-info UnBlockUserUserManagementContinue" type="button">Continue</a>
            <button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>      
</div>