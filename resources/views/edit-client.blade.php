@extends('layout.inside')
@section('content')

      @php
        use Carbon\Carbon;
      @endphp
      <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Edit Client</h1>
          <hr class="divider d-md-block">


          @if (session('error'))

              <div class="alert alert-danger alert-dismissible fade show mb-10" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <sapn>{{ session('error') }}</sapn>
              </div>

          @endif

          <!-- DataTales Example -->
          <div class="row">
              <div class="col-lg-6">
                <div class="p-5">

                  <form action="{{ action('App\Http\Controllers\ClientController@update' ) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                      <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                      <input type="hidden" name="id" value="{{ $client->id }}">
                      <div class="form-group">
                        <label for="exampleInputPassword1">Name</label>
                        <input type="text" class="form-control" id="exampleInputPassword1" name="name" value="{{ $client->name }}">
                        @if( $errors->first('name') )
                          <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                      </div>

                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ $client->email }}" name="email">
                        @if( $errors->first('email') )
                          <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                      </div>
                      
                      <button type="submit" class="btn btn-primary">Submit</button>
                      <button type="button" onclick="location.href = '{{ url('/') }}';" class="btn btn-danger">Cancel</button>

                    </form>
                </div>
              </div>
            </div>


            <input type="hidden" class="server-url" value="{{ url('/') }}">

        </div>
        <!-- /.container-fluid -->
@endsection

@section('scripts')
  <script>

      function copyToClipboard() {
          document.getElementById('generatedlink').select();
          document.execCommand('copy');
      }

      $(document).ready(function(){

          setTimeout(function() {
              $('.alert').fadeOut('fast');
          }, 3000);

          $('.generate-link').unbind('click').bind('click', function(){

              $('#email-msg').text('').hide();

              var clientId = $(this).data('id');

              var spinner = $(this).find('#generate-link-spin');
              var spinText = $(this).find('#generate-link-text');

              $(spinner).show();
              $(spinText).hide();

              var jsonData = {
                'id' : clientId
              };
              var request = $.ajax({

                  url: $('.server-url').val()+'/public/api/'+'generate-link',
                  data: jsonData,
                  type: 'POST',
                  dataType:'json'
              });

              request.done(function(data){

                  $(spinner).hide();
                  $(spinText).show();

                  var link = $('.server-url').val() + '/upload/' + data.data.link;
                  $('#generatedlink').val(link);
                  $('#sendemail').data('id', clientId );
                  $('#generateLinkModal').modal('show');

              });
          });

          $('#sendemail').unbind('click').bind('click', function(){

              var clientId = $(this).data('id');

              var spinner = $(this).find('#send-email-spin');
              var spinText = $(this).find('#send-email-text');

              $(spinner).show();
              $(spinText).hide();

              var jsonData = {
                'id' : clientId,
                'link' : $('#generatedlink').val()
              };
              var request = $.ajax({

                  url: $('.server-url').val()+'/public/api/'+'send-email',
                  data: jsonData,
                  type: 'POST',
                  dataType:'json'
              });

              request.done(function(data){

                  $(spinner).hide();
                  $(spinText).show();

                  $('#email-msg').text( data.message ).show().delay(3000).fadeOut();

              });

          });


      });
  </script>

@endsection