@extends('layout.outside')
@section('content')
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
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                    <div class="form-group">
                       <p id="demo"></p>
                       <p class="mb-3"><strong>Upload Files</strong> </p>
                        <p class="text-muted">Doc, pdf and jpeg files can be uploaded.</p>
                        <form action="{{ url('public/api/uploads') }}" class="dropzone white b-a b-3x b-dashed b-primary p-a rounded p-5 text-center" data-plugin="dropzone" data-option="{url: 'api/dropzone'}" enctype="multipart/form-data">
                          <div class="dz-message">
                              <h4 class="my-4">Drop files here or click to upload.</h4>
                              
                          </div>
                        </form>
                    </div>

                  <hr>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
@endsection

@section('scripts')
  
  <script src="{{ url( 'public/vendor/dropzone/dist/dropzone.js' ) }}"></script>
  <script type="text/javascript">
  
    $(document).ready(function(){

      Dropzone.autoDiscover = false;
      Dropzone.options.myAwesomeDropzone = false;
      
      var myDropzone = Dropzone.options.myAwesomeDropzone = {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        success: function(file, response){
            console.log('here');
        }
      };
      
    });

  </script>
<script>
// Set the date we're counting down to
var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();

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
  }
}, 1000);
</script>

@endsection