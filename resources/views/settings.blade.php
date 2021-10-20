@extends('layout.inside')
@section('content')

      @php
        use Carbon\Carbon;
      @endphp
      <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Settings</h1>
          <hr class="divider d-md-block">


          @if (session('error'))

              <div class="alert alert-danger alert-dismissible fade show mb-10" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <sapn>{{ session('error') }}</sapn>
              </div>

          @endif

          @if (session('success'))

              <div class="alert alert-success alert-dismissible fade show mb-10" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <sapn>{{ session('success') }}</sapn>
              </div>

          @endif

          <!-- DataTales Example -->
          <div class="row">
              <div class="col-lg-6">
                <div class="p-5">
                  <form action="{{ action('App\Http\Controllers\SettingsController@storeOrUpdate' ) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                      <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Allowed Files Text</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" name="text" value= "{{ ( isset($allowed) && isset($allowed->text) ) ? $allowed->text : '' }}">
                        <input type="hidden" name="type" value="allowed_files">
                        @if( $errors->first('text') )
                          <small class="text-danger">{{ $errors->first('text') }}</small>
                        @endif
                      </div>
                      
                      <button type="submit" class="btn btn-primary">Submit</button>
                      <!-- <button type="button" onclick="location.href = '{{ url('/') }}';" class="btn btn-danger">Cancel</button> -->

                    </form>
                </div>
              </div>
            </div>

        </div>
        <!-- /.container-fluid -->
@endsection

@section('scripts')
  <script>

      $(document).ready(function(){

          setTimeout(function() {
              $('.alert').fadeOut('fast');
          }, 3000);

      });
  </script>

@endsection