@extends('layout.outside')
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
          font-size: 18px !important;
        }

        .table-bordered td, .table-bordered th {
             border: 0px solid #e3e6f0; 
        }
      </style>
    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> -->
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome {{ ucfirst($client->name) }}</h1>
                  </div>
                  @if( $link->link_used == 'no' )
                    <div class="form-group">
                       <p class="float-right">This link will expire in <strong id="demo" class="text-dark"></strong></p>
                       
                       <p class="mb-3"><strong>Upload Files</strong> </p>
                        <p class="text-muted"> {{ ( isset($allowed) && isset($allowed->text) ) ? $allowed->text : 'Doc, Docx, pdf, jpeg, jpg and png files can be uploaded.' }}</p>
                        <form action="{{ url('public/uploads') }}" id="myId" class="dropzone white b-a b-3x b-dashed b-primary p-a rounded p-5 text-center" data-plugin="dropzone" data-option="{url: url('public/uploads')}" enctype="multipart/form-data">
                          <div class="dz-message">
                              <h4 class="my-4">Drop files here or click to upload.</h4>
                          </div>
                        </form>
                    </div>
                    <input type="button" id='uploadfiles' value='Upload Files' class="btn btn-primary" >
                    <div class="alert alert-success" id="email-msg" style="display: none; margin-top: 10px;"></div>
                    <div class="alert alert-danger" id="email-msg" style="display: none;margin-top: 10px;"></div>
                  @else
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered"  width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>File Name</th>
                              <th width="20%">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if( count($clienFiles) > 0 )
                            @foreach( $clienFiles as $key => $file)
                            <tr>
                              <td>{{ $key + $clienFiles->firstItem() }}</td>
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
                          {{ $clienFiles->links() }}
                        </div>
                      </div>
                    </div>
                  @endif

                  <hr>

                </div>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" class="server-url" value="{{ url('/') }}">
        <input type="hidden" class="link" value="{{ $link->link }}">

      </div>

    </div>
@endsection

@section('scripts')
  
  <script src="{{ url( 'public/vendor/dropzone/dist/dropzone.js' ) }}"></script>

  <script type="text/javascript">
    
    Dropzone.autoDiscover = false;
    Dropzone.options.myAwesomeDropzone = false;
    $(document).ready(function(){

      /*$("#myId").dropzone({ 
        url: $('.server-url').val() + "/public/api/uploads" ,
        addRemoveLinks: true,
        acceptedFiles:".png, .jpg, .jpeg, .docx, .doc, .pdf",
        init: function () {
              this.on("success", function (file, response) {
                  var jsonResponse = JSON.parse(file.xhr.response);

                  $('.alert-success').html(jsonResponse.success).show().delay(3000).fadeOut();
              });
              this.on("sending", function (file, xhr, formData) {
                  formData.append('link', $('.link').val() );
              });
              this.on("removedfile", function (file) {

                  var jsonRes = JSON.parse(file.xhr.response);
                  removeFile(jsonRes.data.id);
                  //console.log(jsonRes, jsonRes.data.id);
              });
              this.on("complete", function(file) {
                  $(".dz-remove").html("<div><a class='dz-remove' href='javascript:undefined;' data-dz-remove='' data-toggle='tooltip' data-placement='top' title='This file will be removed from system.'>Remove file</a></div>");
                  $('[data-toggle="tooltip"]').tooltip()
              });
              this.on("error", function (file, errorMessage) {
                $('.alert-danger').html(errorMessage.error + '. Please wait ... ').show().delay(3000).fadeOut(function(){

                    window.location.href = $('.server-url').val() + '/404';

                });
              });
          }
      });*/

      var myDropzone = new Dropzone(".dropzone", { 
         autoProcessQueue: false,
         parallelUploads: 10, 
         maxFilesize: 500000000, // The name that will be used to transfer the file
         chunking: true,
         chunkSize: 2000000,
         timeout: 1800000000000,
         retryChunks: true,
         retryChunksLimit:5,
         acceptedFiles:".png, .jpg, .jpeg, .docx, .doc, .pdf",// Number of files process at a time (default 2)
         init: function () {
            this.on("sending", function (file, xhr, formData) {
                  formData.append('link', $('.link').val() );
                  formData.append('_token', "{{ csrf_token() }}" );
            });
            this.on("success", function (file, response) {
                var jsonResponse = JSON.parse(file.xhr.response);

                $('.alert-success').html(jsonResponse.success).show().delay(3000).fadeOut();
            });
            this.on('queuecomplete', function () {
                location.reload();
            });
            this.on("error", function (file, errorMessage) {
              $('.alert-danger').html(errorMessage.error + '. Please wait ... ').show().delay(3000).fadeOut(function(){

                  window.location.href = $('.server-url').val() + '/404';

              });
            });
         }
      });

      $('#uploadfiles').click(function(){
         myDropzone.processQueue();
      });
      
    });

    function removeFile(id){


      var jsonData = {
        'id' : id
      };
      var request = $.ajax({

          url: $('.server-url').val()+'/public/'+'remove-file',
          data: jsonData,
          type: 'POST',
          dataType:'json'
      });

      request.done(function(data){

          if(data.error)
            $('.alert-danger').html(data.error).show().delay(3000).fadeOut();

          if(data.success)
            $('.alert-success').html(data.success).show().delay(3000).fadeOut();

      });

    }

  </script>
<script>
  // Set the date we're counting down to
  var countDownDate = new Date("{{ $link->link_expire }}").getTime();

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
</script>

@endsection