@extends('layout.outside')
@section('content')

    <style type="text/css">
      .error {
          color: #5a5c69;
          font-size: 6rem !important;
          position: relative;
          line-height: 1;
          width: 12.5rem;
      }
    </style>
    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="container-fluid" style="padding-top: 1.5rem;">

          <!-- 404 Error Text -->
          <div class="text-center">
            <div class="error mx-auto text-danger">404</div>
            <p class="lead text-gray-800 mb-5">This Link has been expired. Please contact concerened person.</p>
          </div>

        </div>
        </div>

      </div>

    </div>
@endsection
