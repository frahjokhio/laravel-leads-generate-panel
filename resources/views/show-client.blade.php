@extends('layout.inside')
@section('content')

      @php
        use Carbon\Carbon;
      @endphp

      <style type="text/css">

        .highlighted{
            color: #1cc88a;
            /*font-weight: bold;*/
        }

        td{
          border-top: none !important ;
          font-family: serif !important;
          font-size: 14px !important;
        }

        .table-bordered td, .table-bordered th {
             border: 0px solid #e3e6f0; 
        }
      </style>

      <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Show Client</h1>
          <hr class="divider d-md-block">


          @if (session('success'))

              <div class="alert alert-success alert-dismissible fade show mb-10" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <span>{{ session('success') }}</span>
              </div>
          @endif

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
            
            <div class="col-lg-12" style="margin-bottom: 20px">

              <button class="btn btn-info generate-link mr-3 float-right" data-id="{{ $client->id }}" >
                  <i class="fa fa-spinner fa-spin icon-spinner" id="generate-link-spin" style="display: none"></i>
                  <span id="generate-link-text">Generate Link</span>
              </button>
              
            </div>

          </div>
          <div class="row">
              <div class="col-lg-12">
                <div class="card mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Client Details</h6>
                </div>
                <div class="card-body">

                  <div class="table-responsive">
                      <table class="table table-bordered"  width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th width="5%"></th>
                            <th width="30%">Reciever</th>
                            <th width="30%">Email Address</th>
                            <th width="10%">Links</th>
                            <th width="10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td>{{ ucfirst($client->name) }}</td>
                            <td>{{ $client->email }}</td>
                            <td> {{ $client->client_links()->count() }} </td>

                            <td>
                              <a class="btn text-white" href="{{ url('client/edit/'.$client->id) }}" target="_blank" data-toggle='tooltip' data-placement='bottom' title='Edit'>
                                  <i class="fas fa-edit text-primary"></i>
                              </a>
                              <a class="btn text-white" href="{{ url('files/'.$client->id) }}" target="_blank" data-toggle='tooltip' data-placement='bottom' title='Files' >
                                <i class="fa fa-file text-primary" aria-hidden="true"></i>
                              </a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered" width="80%" cellspacing="0" style="width: 80%;margin-left: 8rem;">
                        <thead>
                          <tr>
                            <th width="5%"></th>
                            <th width="40%">Generated Link</th>
                            <th width="20%">is Emailed ?</th>
                            <th width="15%">Status</th>
                            <th width="20%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          

                            @if( count($links) > 0)
                                @foreach( $links as $getLink )
                                  <tr>
                                  <td></td>
                                  <td class="generated-link">
                                    @if( $getLink->link != "" ) {{ url('upload/'.$getLink->link ) }} @endif 
                                  </td>

                                  <td class="is_emailed">

                                    @if( $getLink->is_email == 'yes') 
                                        <span class="text-success"> {{ ucfirst( $getLink->is_email ) }} </span> 
                                    @else
                                      {{ ucfirst( $getLink->is_email ) }}
                                    @endif

                                  </td>
                                  <td>
                                    @if( $getLink->link_expire != "" )
                                      @if( $getLink->link_expire->gt( Carbon::now() ) ) 
                                          
                                          @if( $getLink->link_used == 'yes' )
                                              <strong class="text-success">Submitted</strong>
                                          @else
                                              <strong class="text-success">Active</strong>
                                          @endif
                                          
                                      @else
                                          <strong class="text-danger">Expired</strong>
                                      @endif
                                    @endif
                                  </td>

                                  <td>
                                    <a class="btn copy-link" href="javascript:;" data-toggle='tooltip' data-placement='bottom' title='Copy'>
                                        <i class="fas fa-copy text-primary"></i>
                                    </a>
                                    <a class="btn sendemail" href="javascript:;" data-toggle='tooltip' data-placement='bottom' title='Send In Email' data-id="{{ $getLink->client_id }}" data-linkid="{{ $getLink->id }}">
                                      <i class="fa fa-spinner fa-spin icon-spinner send-email-spin" style="display: none"></i>
                                      <i class="fa fa-envelope text-primary send-email-text" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn set-expire-time-link" href="javascript:;" data-toggle='tooltip' data-placement='bottom' title='Set Link Expire Time' data-id="{{ $getLink->id }}" data-time="{{ $getLink->link_expire }}">
                                      <i class="fas fa-clock text-primary"></i>
                                    </a>
                                    <a class="btn delete" href="javascript:;" data-toggle='tooltip' data-placement='bottom' title='Delete' data-id="{{ $getLink->id }}">
                                      <i class="fas fa-trash text-danger"></i>
                                    </a>
                                  </td>
                                  </tr>
                                @endforeach
                            @else
                              <tr>
                                <td colspan="5" class="text-center"> No Links Available.</td>
                              </tr>
                            @endif
                        </tbody>
                      </table>
                  </div>
                  <div class="row float-right">
                    <div class="col-sm-12">
                      {{ $links->links() }}
                    </div>
                  </div>
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
                    <button class="btn btn-primary" type="button" data-dismiss="modal" onclick="location.reload()">Ok</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Link</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <form action="{{ action('App\Http\Controllers\ClientController@deleteLink' ) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                        @csrf
                        <div class="form-group row">
                          
                          <div class="col-sm-12">
                            Are you sure you want to Delete this link ?
                          </div>
                          <input type="hidden" name="id" id="delete-link-id">

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


            <div class="modal fade" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Set Link Expire Time</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{ action('App\Http\Controllers\ClientController@setExpiryTime' ) }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                        <div class="form-group row">
                          @csrf
                          <div class="col-sm-4">
                            <label for="time">Time<small>( in Hours )</small></label>
                          </div>
                          <div class="col-sm-4">
                            <input type="number" class="form-control" name="time" min="1" id="time">
                            <p id="demo"></p>
                          </div>
                          <input type="hidden" name="id" value="" id="link-id">

                        </div>

                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Email</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group row">
                        <div class="col-sm-12"> Email Sent Successfully.</div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-primary" type="button">Ok</button>
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
          document.getElementById('generated-link').select();
          document.execCommand('copy');
      }

      $(document).ready(function(){

          $('.delete').unbind('click').bind('click', function(){
              $('#delete-link-id').val( $(this).data('id') );
              $('#deleteModal').modal('show');
          });


          $('.set-expire-time-link').unbind('click').bind('click', function(e){
                e.preventDefault();
                //$('#demo').text('');
                $('#link-id').val( $(this).data('id') );
                $('#timeModal').modal('show');
          });


          $('.copy-link').unbind('click').bind('click', function(e){
              e.preventDefault();

              var orginalElem = $(this).parents('tr').find('.generated-link');

              //$(this).parents('tr').find('.generated-link').execCommand('copy');
              const text = $.trim( $(this).parents('tr').find('.generated-link').text() );
              const elem = document.createElement("input");
              document.body.appendChild(elem);
              elem.value = text;
              elem.select();
              document.execCommand("copy");
              document.body.removeChild(elem);
              //document.write("Copied to clipboard!");

               $(orginalElem).addClass('highlighted');
                setTimeout(function () {
                    $(orginalElem).removeClass('highlighted');
                }, 2000);


              console.log( $.trim( $(this).parents('tr').find('.generated-link').text() ) );
          });


          $('[data-toggle="tooltip"]').tooltip();

          setTimeout(function() {
              $('.alert').fadeOut('fast');
          }, 3000);


          var number = document.getElementById('time');

          // Listen for input event on numInput.
          number.onkeydown = function(e) {
              if(!((e.keyCode > 95 && e.keyCode < 106)
                || (e.keyCode > 47 && e.keyCode < 58) 
                || e.keyCode == 8)) {
                  return false;
              }
          }

          $('.generate-link').unbind('click').bind('click', function(){

              $('#email-msg').text('').hide();

              var clientId = $(this).data('id');

              $('#generatedlink').val('');

              $(this).find('#send-email-spin').hide();
              $(this).find('#send-email-text').show();

              var spinner = $(this).find('#generate-link-spin');
              var spinText = $(this).find('#generate-link-text');

              $(spinner).show();
              $(spinText).hide();

              var jsonData = {
                'id' : clientId,
                "_token": "{{ csrf_token() }}"
              };
              var request = $.ajax({

                  url: $('.server-url').val()+'/public/'+'generate-link',
                  data: jsonData,
                  type: 'POST',
                  dataType:'json'
              });

              request.done(function(data){
                  
                  $(spinner).hide();
                  $(spinText).show();
                  location.reload();

              });
          });

          $('.sendemail').unbind('click').bind('click', function(e){

              e.preventDefault();


              var parentElem = $(this).parents('tr');

              var spinner = $(this).find('.send-email-spin');
              var spinText = $(this).find('.send-email-text');

              $(spinner).show();
              $(spinText).hide();

              var jsonData = {
                'id' : $(this).data('id'),
                'link' : $.trim( $(this).parents('tr').find('.generated-link').text() ),
                'link_id' : $(this).data('linkid'),
                "_token": "{{ csrf_token() }}"
              };
              var request = $.ajax({

                  url: $('.server-url').val()+'/public/'+'send-email',
                  data: jsonData,
                  type: 'POST',
                  dataType:'json'
              });

              request.done(function(data){

                  $(spinner).hide();
                  $(spinText).show();

                  $(parentElem).find('.is_emailed').html('<span class="text-success">Yes</span>');
                  $('#emailModal').modal('Show');
                  //$('#email-msg').text( data.message ).show().delay(3000).fadeOut();

              });

          });

          function setTimer(timerondate){

              // Set the date we're counting down to
              var countDownDate = new Date(timerondate).getTime();

              // Update the count down every 1 second
              var x = setInterval(function() {

                // Get today's date and time
                var now = new Date().getTime();
                  
                // Find the distance between now and the count down date
                var distance = countDownDate - now;
                  
                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                  
                // Output the result in an element with id="demo"
                document.getElementById("demo").innerHTML = days + "d " + hours + "h "
                + minutes + "m " + seconds + "s ";
                  
                // If the count down is over, write some text 
                if (distance < 0) {
                  clearInterval(x);
                  document.getElementById("demo").innerHTML = "EXPIRED";
                  $('#demo').removeClass('text-warning').addClass('text-danger');
                }
              }, 1000);
          }


      });
  </script>
  <script>

  // Set the date we're counting down to
 /* var countDownDate = new Date("{{ $client->link_expire }}").getTime();

  // Update the count down every 1 second
  var x = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();
      
    // Find the distance between now and the count down date
    var distance = countDownDate - now;
      
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";
      
    // If the count down is over, write some text 
    if (distance < 0) {
      clearInterval(x);
      document.getElementById("demo").innerHTML = "EXPIRED";
      $('#demo').removeClass('text-warning').addClass('text-danger');
    }
  }, 1000);*/
</script>

@endsection