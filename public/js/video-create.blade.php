@extends('admin.layout.inside')
@section('content')  
  <!-- page content -->
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Create Video</h3>
        </div>
      </div>

      <div class="clearfix"></div>

        <div class="col-md-12 col-sm-12  ">
          <div class="x_panel">
            <div class="x_content">
              
              <form action="{{ url('/public/api/create-video') }}" method="post" class="form-label-left input_mask" id="video_create_from">

                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Title <span class="required">*</span>
                      </label>
                      <div class="col-md-8 col-sm-8 ">
                        <input type="text" id="title"  name="title" class="form-control ">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Description <span class="required">*</span>
                      </label>
                      <div class="col-md-8 col-sm-8 ">
                        <textarea class="form-control" id="description"  name="description" rows="3" placeholder="Enter Description Here"></textarea>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label for="email" class="col-form-label col-md-3 col-sm-3 label-align">Categories*</label>
                      <div class="col-md-8 col-sm-8 ">
                        <select class="form-control categories" name="category_id">
                          <option>Choose Category</option>
                          @foreach( $categories as $category )
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="item form-group">
                      <label for="image_link" class="col-form-label col-md-3 col-sm-3 label-align">Video Thumbnail Image*</label>
                      <div class="col-md-8 col-sm-8 ">
                        <input type="file" name="image_link" id="image_link" data-filetype="testimonial" data-id="image_link" class="inputForm form-control input-sm">
                          <label class="label-register chsBtn chooseSliderSize" for="image_link"><span></span></label>
                          <span> Thumbnail should be of minimum size 400 x 400 </span>
                      </div>
                    </div>
                    
                    <!-- <div class="item form-group">
                      <label for="video_link" class="col-form-label col-md-3 col-sm-3 label-align">Upload Video*</label>
                      <div class="col-md-8 col-sm-8 ">
                        <input type="file" name="video_link" id="video_link" data-filetype="testimonial" data-id="video_link" class="inputForm form-control input-sm" accept="video/mp4,video/x-m4v,video/*">
                          <label class="label-register chsBtn chooseSliderSize" for="video_link"><span></span></label>
                          <span class="alert-error" id="video_create_first_name_error" style="display: none;"> </span>
                      </div>
                    </div> -->
                    <input type="hidden" id="video-uploaded-filename" name="video_link" value="">

                    <div class="item form-group">
                      <label for="video_link" class="col-form-label col-md-3 col-sm-3 label-align">Upload Video*</label>
                      <div class="col-md-3 col-sm-3 ">
                        <div class="dropzone" id="my-awesome-dropzone">
                          <div class="dz-preview dz-file-preview" style="min-height: 0px !important;">
                            <div class="dz-details">
                              <div class="dz-filename"><span data-dz-name></span></div>
                              <div class="dz-size" data-dz-size></div>
                              <img data-dz-thumbnail />
                            </div>
                            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                            <div class="dz-success-mark"><span>✔</span></div>
                            <div class="dz-error-mark"><span>✘</span></div>
                            <div class="dz-error-message"><span data-dz-errormessage></span></div>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="progress" style="display: none;">
                      <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div> -->
                    <div class="col-md-12 col-sm-12 ">
                      <div class="alert" id="add-video-msg" style="display:none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="add-video">
                        <i class="fa fa-spinner fa-spin icon-spinner" id="add-video-spin" style="display: none"></i><span id="add-video-text">Submit</span></button>
                        <button type="button" class="btn btn-primary" id="form-cancel" data-dismiss="modal">Cancel</button>
                    </div>
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" class="server-url" value="{{ url('/') }}">
  <!-- /page content -->
@endsection

@section('scripts')
<script src="{{ url( 'public/build/js/paginations.js' ) }}"></script>
<script src="{{ url( 'public/build/js/dropzone/dist/dropzone.js' ) }}"></script>
<script>
     $(document).ready(function(){


      Dropzone.autoDiscover = false;

      Dropzone.options.myAwesomeDropzone = {
        url: $('.server-url').val() + '/api/upload-video',
        dictDefaultMessage: "Click here to upload video File",
        paramName: "file",
        maxFilesize: 500000000, // The name that will be used to transfer the file
        maxFiles: 1,
        chunking: true,
        chunkSize: 2000000,
        timeout: 1800000000000,
        retryChunks: true,
        retryChunksLimit:5,
        createImageThumbnails:true,
        acceptedFiles:".mp4,.mkv,.mov,.ogg,.qt,.flv,.m3u8,.ts,.3gp,.avi,.wmv,.ogx,.oga,.ogv,.webm",
        addRemoveLinks: true,
        previewsContainer: ".dz-file-preview",
        success: function( file, response ){
              $('#video-uploaded-filename').val(response.name);

              var jsonResponse = JSON.parse(file.xhr.response);
              $('#video-uploaded-filename').val(jsonResponse.name);

             //console.log('suucess', response, JSON.parse(file.xhr.response)) // <---- here is your filename
        },
        chunksUploaded: function (file, done, response) {

              // do someting...
              done()
              //console.log(file, done);
        }
        
      };

      $('#my-awesome-dropzone').on('success', function() {
        var args = Array.prototype.slice.call(arguments);

        // Look at the output in you browser console, if there is something interesting
        console.log('success args', args);
      });
      // or disable for specific dropzone:
      // Dropzone.options.myDropzone = false;

      $(function() {
        // Now that the DOM is fully loaded, create the dropzone, and setup the
        // event listeners
        var myDropzone = new Dropzone("#my-awesome-dropzone");
        myDropzone.on("addedfile", function(file) {
          /* Maybe display some more file information on your page */
        });
      })


        $("#video_create_from").on('submit', (function(e) {
            e.preventDefault();

            $('#add-video').css('pointer-events', 'none');

            $('#video-create-msg').html('').hide();
            $('#add-video-spin').show();
            $('#add-video-text').hide();
            
           var formData = new FormData(this);
           var request = $.ajax({

              url: $('.server-url').val()+'/public/api/'+'create-video',
              data: formData,
              type: 'POST',
              dataType:'json',
              processData: false,
              contentType: false,
              cache: false,
              //headers: {"Authorization": "Bearer "+localStorage.getItem('token')}
           });

            request.done(function(data){
                
                $('#send-notification-clone').css('pointer-events', '');
                
                $('#add-video-spin').hide();
                $('#add-video-text').show();

                if( typeof data.response != "undefined" && data.response.code == 200 ){
                   $('#add-video-msg').html("video created successfully.").removeClass('alert-danger').addClass('alert-success').show().delay(2000).fadeOut(function(){
                      $('#notification-content').val('');
                      $(this).html('');
                      $(this).removeClass('alert-success');
                      $('#add-video').css('pointer-events', '');
                      window.location.href = $('.server-url').val()+'/admin/video';
                   });
                }

                if (typeof data.error != "undefined") {
                  $('#add-video').css('pointer-events', '');
                      console.log( data.error.messages );
                      var messagesHtml = '';
                      $.each(data.error.messages, function(i, inputMessgae) {
                          
                         // $.each(inputMessgae, function(i2, messgae) {
                              messagesHtml += '<span>' + inputMessgae + '</span><br>';
                          //});
                          
                      });

                      $('#add-video-msg').html(messagesHtml).addClass('alert-danger').removeClass('alert-success').show();
                  }


             });
            
        }));

        $("#video_update_form").on('submit', (function(e) {
            e.preventDefault();

            $('#update-video').css('pointer-events', 'none');

            $('#video-update-msg').html('').hide();
            $('#update-video-spin').show();
            $('#update-video-text').hide();
            
           var formData = new FormData(this);
           var request = $.ajax({

              url: $('.server-url').val()+'/public/api/'+'update-video',
              data: formData,
              type: 'POST',
              dataType:'json',
              processData: false,
              contentType: false,
              cache: false,
              //headers: {"Authorization": "Bearer "+localStorage.getItem('token')}
           });

            request.done(function(data){
                
                $('#send-notification-clone').css('pointer-events', '');
                
                $('#update-video-spin').hide();
                $('#update-video-text').show();

                if( typeof data.response != "undefined" && data.response.code == 200 ){
                   $('#update-video-msg').html("video updated successfully.").removeClass('alert-danger').addClass('alert-success').show().delay(2000).fadeOut(function(){
                      $('#notification-content').val('');
                      $(this).html('');
                      $(this).removeClass('alert-success');
                      $('#update-video').css('pointer-events', '');
                      //window.location.href = $('.server-url').val()+'/admin/videos'
                      $('.update-video-modal').modal('hide');
                      getlist(globalPage, '', globalkeyword);
                   });
                }

                if (typeof data.error != "undefined") {
                  $('#update-video').css('pointer-events', '');
                      console.log( data.error.messages );
                      var messagesHtml = '';
                      $.each(data.error.messages, function(i, inputMessgae) {
                          messagesHtml += '<span>' + inputMessgae + '</span><br>';
                      });

                      $('#update-video-msg').html(messagesHtml).addClass('alert-danger').removeClass('alert-success').show();
                  }
             });
            
        }));


     });
</script>

@endsection