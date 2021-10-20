@extends('layout.inside')
@section('content')

      @php
        use Carbon\Carbon;
      @endphp
      <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Cilents Management</h1>
          <hr class="divider d-md-block">
          <a href="{{ url('client/create') }}" class="btn btn-primary generate-link" style="margin-bottom: 20px;" >
              <span class = "add-btn text-white" > + Add Client </span>
          </a>

          @if (session('success'))

              <div class="alert alert-success alert-dismissible fade show mb-10" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <span>{{ session('success') }}</span>
              </div>
          @endif

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Show All</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered"  width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <!-- <th>Link</th>
                      <th>Link Status</th> -->
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <!-- <th width="5%">Link</th>
                      <th>Link Status</th> -->
                      <th width="20%">Actions</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @if( count($clients) > 0 )
                    @foreach( $clients as $key => $client)
                    <tr>
                      <td> {{ $clients->lastItem() - $key }}  </td>
                      <td> <a href="{{ url('client/show/'.$client->id) }}" > {{ ucfirst($client->name) }} </a></td>
                      <td>{{ $client->email }}</td>
     
                      <td> 

                        <a class="btn btn-primary btn-sm text-white" href="{{ url('client/show/'.$client->id) }}" target="_blank" >
                            View
                        </a>
                        <a class="btn btn-danger btn-sm text-white delete" data-toggle="modal" data-target="#deleteModal" data-id="{{ $client->id }}" >
                            Delete
                        </a>
                      </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                      <td colspan="4" class="text-center"> <b>No Files Available.</b></td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  {{ $clients->links() }}
                </div>
              </div>
            </div>
          </div>

            <div class="modal fade" id="generateLinkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Generate Link</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    
                        <div class="form-group row">
                          
                          <div class="col-sm-12">
                            <label for="generatedlink">Link</label>
                            <input type="text" class="form-control" id="generatedlink" >
                          </div>
                          
                          <div class="col-sm-3">
                            <label for=""></label>
                            <button class="btn btn-primary form-control btn-sm" onclick="copyToClipboard()" >
                              Copy
                            </button>
                          </div>

                          <div class="col-sm-3">
                            <label for=""></label>
                            <button class="btn btn-success form-control btn-sm" id="sendemail">
                              <i class="fa fa-spinner fa-spin icon-spinner" id="send-email-spin" style="display: none"></i>
                              <span id="send-email-text">Send Email</span>
                            </button>
                          </div>

                        </div>

                        <div class="alert alert-success" id="email-msg" style="display: none;"></div>

                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Ok</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Client</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <form action="{{ action('App\Http\Controllers\ClientController@deleteClient' ) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                        @csrf
                        <div class="form-group row">
                          
                          <div class="col-sm-12">
                            Are you sure you want to Delete this client ?
                          </div>
                          <input type="hidden" name="id" id="delete-client-id" value="">

                        </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-danger" type="submit">Yes, Delete</button>
                    <button class="btn btn-primary" type="button" data-dismiss="modal">No</button>
                  </div>
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


          $('.delete').unbind('click').bind('click', function(){

              $('#delete-client-id').val( $(this).data('id') );
              $('#deleteModal').modal('show');

          });

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