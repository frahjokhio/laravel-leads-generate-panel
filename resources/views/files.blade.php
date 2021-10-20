@extends('layout.inside')
@section('content')


      <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Files Uploaded by <span class="text-info">{{ $client->name }}</span></h1>
          <p class="mb-4"></p>

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
                      <th>File Name</th>
                      <th width="30%">Actions</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>File Name</th>
                      <th>Actions</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @if( count($files) > 0 )
                    @foreach( $files as $key => $file)
                    <tr>
                      <td> {{ $files->lastItem() - $key }} </td>
                      <td>{{ ucfirst($file->file_name) }}</td>
                      <td>

                        @php
                          preg_match("/[^\/]+$/", $file->file_path, $matches);
                          $getfileName = $matches[0];
                        @endphp
                        <a class="btn btn-info btn-sm text-white" href="{{ url('download/'.$file->id ) }}" >
                            Download
                        </a>
                      </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                      <td colspan="3" class="text-center"> <b>No Files Available.</b></td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  {{ $files->links() }}
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

                  var link = $('.server-url').val() + '/client/upload-files/' + data.data.link;
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

                  $('#email-msg').text( data.message ).show();

              });

          });


      });
  </script>

@endsection