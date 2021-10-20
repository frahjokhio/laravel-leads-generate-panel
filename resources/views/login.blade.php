@extends('layout.auth')
@section('content')
    
    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-3"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Leads Generation</h1>
                  </div>
                  <form action="{{ action('App\Http\Controllers\UserController@dologin' ) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" class="user">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" name="email" placeholder="Enter Email Address..." value="admin@mailinator.com">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" name="password" value="admin">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                    <!-- <a href="index.html" class="btn btn-primary btn-user btn-block">
                      Login
                    </a> -->
                  </form>
                  @if (session('error'))

		              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px" >
		                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		                    <span aria-hidden="true">×</span>
		                  </button>
		                  <sapn>{{ session('error') }}</sapn>
		              </div>

          		  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
@endsection

@section('scripts')
@endsection