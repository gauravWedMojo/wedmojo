@include('Admin/header')
<div class="dashboardPage"></div>
<div class="layout-content">
    <div class="layout-content-body">
        
        <div class="title-bar">
            <h1 class="title-bar-title">
              <span class="d-ib">Dashboard</span>

            </h1>
            <p class="title-bar-description">
                <small>Welcome to MAI</small>
            </p>
            <div class="row">
                <div class="col-md-3">
                    <label>From</label>
                     <input class="form-control from_date" type="date" name="from_date" >
                     <div class="from_date_err"></div>
                </div>

                <div class="col-md-3">
                    <label>To</label>
                    <input class="form-control to_date" type="date" name="to_date">
                    <div class="to_date_err"></div>
                </div>

                <div class="col-md-3">
                    <label>Category</label>
                    <select class="form-control Category_id" name="Category_id">
                        <option value="">SELECT</option>
                        @foreach($categories as $value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input style="margin-top: 24px;" type="button" value="Filter" class="btn btn-lg btn-primary form-control filter_execute">
                </div>
                
            </div>
        </div>

        <div class="row gutter-xs">
            <div class="col-md-6 col-lg-3 col-lg-push-0">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-primary circle sq-48">
                        <span class="icon icon-user"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Total User</h6>
                                <h3 class="media-heading">
                        <span class="fw-l total_users">{{$total_users}}</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-push-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-danger circle sq-48">
                        <span class="icon icon-shopping-bag"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Total store</h6>
                                <h3 class="media-heading">
                        <span class="fw-l total_store">{{$total_store}}</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-pull-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-primary circle sq-48">
                        <span class="icon icon-clock-o"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Featured store</h6>
                                <h3 class="media-heading">
                        <span class="fw-l featured_stores">{{count($featured_stores)}}</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-pull-0">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-danger circle sq-48">
                        <span class="icon icon-usd"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Featured product</h6>
                                <h3 class="media-heading">
                        <span class="fw-l FeaturedProduct">{{count($FeaturedProduct)}}</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gutter-xs">
            <div class="col-md-6 col-lg-3 col-lg-push-0">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-primary circle sq-48">
                        <span class="icon icon-user"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Used free voucher</h6>
                                <h3 class="media-heading">
                        <span class="fw-l UsedFreeVoucher">{{count($UsedFreeVoucher)}} ({{$UsedFreeVoucherUsageTotal}} PHP)</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-push-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-danger circle sq-48">
                        <span class="icon icon-shopping-bag"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Used premium voucher</h6>
                                <h3 class="media-heading">
                        <span class="fw-l UsedPremiumVoucher">{{count($UsedPremiumVoucher)}} ({{$UsedPremiumVoucherUsageTotal}} PHP)</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-pull-3">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-primary circle sq-48">
                        <span class="icon icon-clock-o"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Premium subscription</h6>
                                <h3 class="media-heading">
                        <span class="fw-l total_premium_users">{{$total_premium_users}}</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-pull-0">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-middle media-left">
                                <span class="bg-danger circle sq-48">
                        <span class="icon icon-usd"></span>
                                </span>
                            </div>
                            <div class="media-middle media-body">
                                <h6 class="media-heading">Merchant subscriber</h6>
                                <h3 class="media-heading">
                        <span class="fw-l total_subscribed_store">{{$total_subscribed_store}}</span>
                      </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

        </div>
        <div class="row gutter-xs">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Downloader user</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-chart">
                            <canvas id="demo-visitors" data-chart="bar" data-animation="false" data-labels='["Aug 1", "Aug 2", "Aug 3", "Aug 4", "Aug 5", "Aug 6", "Aug 7", "Aug 8", "Aug 9", "Aug 10", "Aug 11", "Aug 12", "Aug 13", "Aug 14", "Aug 15", "Aug 16", "Aug 17", "Aug 18", "Aug 19", "Aug 20", "Aug 21", "Aug 22", "Aug 23", "Aug 24", "Aug 25", "Aug 26", "Aug 27", "Aug 28", "Aug 29", "Aug 30", "Aug 31"]' data-values='[{"label": "Visitors", "backgroundColor": "#2c3e50", "borderColor": "#2c3e50",  "data": [29432, 20314, 17665, 22162, 31194, 35053, 29298, 36682, 45325, 39140, 22190, 28014, 24121, 39355, 36064, 45033, 42995, 30519, 20246, 42399, 37536, 34607, 33807, 30988, 24562, 49143, 44579, 43600, 18064, 36068, 41605]}]' data-hide='["legend", "scalesX"]' height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Downloader store</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-chart">
                            <canvas id="demo-sales" data-chart="bar" data-animation="false" data-labels='["Aug 1", "Aug 2", "Aug 3", "Aug 4", "Aug 5", "Aug 6", "Aug 7", "Aug 8", "Aug 9", "Aug 10", "Aug 11", "Aug 12", "Aug 13", "Aug 14", "Aug 15", "Aug 16", "Aug 17", "Aug 18", "Aug 19", "Aug 20", "Aug 21", "Aug 22", "Aug 23", "Aug 24", "Aug 25", "Aug 26", "Aug 27", "Aug 28", "Aug 29", "Aug 30", "Aug 31"]' data-values='[{"label": "Sales", "backgroundColor": "#e74c3c", "borderColor": "#e74c3c",  "data": [3601.09, 2780.29, 1993.39, 4277.07, 4798.58, 6390.75, 3337.37, 6786.94, 5632.1, 5460.43, 3905.17, 3070.82, 4263.55, 7132.64, 6103.88, 6020.76, 4662.25, 4084.34, 3464.87, 4947.89, 4486.55, 5898.46, 5528.33, 3616.03, 3255.17, 7881.06, 7293.8, 6863.6, 3161.31, 6711.08, 7942.9]}]' data-hide='["legend", "scalesX"]' height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row gutter-xs">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-middle media-left">
                                    <span class="bg-gray sq-64 circle">
                        <span class="icon icon-flag"></span>
                                    </span>
                                </div>
                                <div class="media-middle media-body">
                                    <h3 class="media-heading">
                        <span class="fw-l"> {{$mostVisitesStoreDetail['count']}} Most Visited store</span>
                        <span class="fw-b fz-sm text-danger">
                          <!-- <span class="icon icon-caret-up"></span> -->
                          <!-- 15% -->
                        </span>
                      </h3>
                                    <small>{{$mostVisitesStoreDetail['store_name']}}</small>
                                    <p>{{$mostVisitesStoreDetail['category_name']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-middle media-left">
                                    <div class="media-chart">
                                        <canvas data-chart="doughnut" data-animation="false" data-labels='["Resolved", "Unresolved"]' data-values='[{"backgroundColor": ["#2c3e50", "#6185a8"], "data": [879, 377]}]' data-hide='["legend", "scalesX", "scalesY", "tooltips"]' height="64" width="64"></canvas>
                                    </div>
                                </div>
                                <div class="media-middle media-body">
                                    <h2 class="media-heading">
                        <span class="fw-l">{{$mostFavouriteStoreDetail['count']}}</span>
                        <small>Most favorite  Store</small>
                      </h2>
                                    <small>{{$mostFavouriteStoreDetail['store_name']}} </small>
                                    <p>{{$mostFavouriteStoreDetail['category_name']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-middle media-left">
                                    <div class="media-chart">
                                        <canvas data-chart="doughnut" data-animation="false" data-labels='["Resolved", "Unresolved"]' data-values='[{"backgroundColor": ["#6185a8", "#2c3e50"], "data": [879, 377]}]' data-hide='["legend", "scalesX", "scalesY", "tooltips"]' height="64" width="64"></canvas>
                                    </div>
                                </div>
                                <div class="media-middle media-body">
                                    <h2 class="media-heading">
                        <span class="fw-l mostUsedVoucherDetailCount">{{$mostUsedVoucherDetail['count']}}</span>
                        <small>Most used voucher</small>
                      </h2>
                                    <small class="mostUsedVoucherDetailvoucher_code">{{$mostUsedVoucherDetail['voucher_code']}}</small>
                                    <p class="mostUsedVoucherDetailcategory_name">{{$mostUsedVoucherDetail['category_name']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

    </div>
</div>

@include('Admin/footer')

<script type="text/javascript">
    $(document).ready(function(){
        $('.filter_execute').on('click',function(e){
            e.preventDefault();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            var Category_id = $('.Category_id').val();
            $('.from_date_err').empty();
            $('.to_date_err').empty();
            if(from_date.length<1){
                $('.from_date_err').text('Please select date.').css('color','red');
                return false;
            }
            if(to_date.length<1){
                $('.to_date_err').text('Please select date.').css('color','red');
                return false;
            }
            if(to_date <= from_date ){
                $('.from_date_err').text('Please select correct date.').css('color','red');
                return false;
            }

            $request = $.ajax({
                url : "{{url('Admin/get_dashboard_data')}}",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data : {"from_date":from_date,"to_date":to_date,"Category_id":Category_id}
            });

            $request.done(function(data){
                console.log(data);
                $('.total_users').text(data.total_users);
                $('.total_store').text(data.total_store);
                $('.total_premium_users').text(data.total_premium_users);
                $('.total_subscribed_store').text(data.total_subscribed_store);
                $('.UsedPremiumVoucher').text(data.UsedPremiumVoucher +' ('+data.UsedPremiumVoucherUsageTotal+' PHP )');
                $('.UsedFreeVoucher').text(data.UsedFreeVoucher +' ('+data.UsedFreeVoucherUsageTotal+' PHP )');
                $('.featured_stores').text(data.featured_stores);
                $('.FeaturedProduct').text(data.FeaturedProduct);

                // $('.mostUsedVoucherDetailCount').text(data.mostUsedVoucherDetail.count);
                // $('.mostUsedVoucherDetailvoucher_code').text(data.mostUsedVoucherDetail.voucher_code);
                // $('.mostUsedVoucherDetailcategory_name').text(data.mostUsedVoucherDetail.category_name);
            });

            $request.fail(function(data){
                console.log(data);
            });
        }); 
    });
</script>