<?php 
    require 'init.php';
    
    if( ! $user->permission("video","upload") ) {
        header("Location: /login?returnto=".urlencode($_SERVER['REQUEST_URI']));   
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload to Devio.tv</title>
</head>
<body>
<?php 
    include('includes/global/styles.php');
    include('includes/global/header.php');
?>
<div class="container">
    <div class="alert alert-info"><strong>Notice</strong> Pending functionality.</div>
    <div class="page-header">
      <h1>Upload to Devio <small>MP4 only for alpha release</small></h1>
    </div>
    <div class="upload">
        <form role="form">
        <div class="col-md-6">
          <div class="form-group">
            <label for="Video Title">Video Title</label>
            <input type="text" class="form-control" id="Video Title" placeholder="Video Title">
          </div>
          <div class="form-group">
            <label for="Description">Description</label>
            <textarea type="text" class="form-control" id="Description" placeholder="Description"></textarea>
          </div>
          <div class="form-group">
            <label for="Tags">Tags</label>
            <input type="text" class="form-control" id="Tags" placeholder="Tags seperated by commas">
          </div>
          <div class="form-group">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-picture"></i>
                <span> Select PNG Thumbnail (optional)</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="imgUpload" type="file" name="files[]" multiple>
            </span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group" id="video_uploader">
            <label for="exampleInputFile">Select Video</label>
            <div class="well">
            <h2>DRAG FILE TO UPLOAD</h2>
            <h4>OR</h4>
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span> Select MP4 Video</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="videoUpload" type="file" name="files[]" multiple>
            </span>
            <div id="files" class="files"></div>
            <p class="help-block">MP4 Foramt Only</p>
            </div>
            <div class="form-group">
                <label for="Tags">Allow backers to Download?</label>
                <input type="checkbox">
            </div>
          </div>
          <div class="progress progress-striped active">
            <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
              <span class="sr-only">45% Complete</span>
            </div>
          </div>
        <button type="submit" class="btn btn-default right">Publish to Devio</button>
        </div>
        </form>
    </div>
    <div class="alert alert-info"><strong>Notice</strong> Until the backer system is fully implemented any user will be able save videos with download enabled.</div>
</div>
<?php 
    include('includes/global/footer.php');
    include('includes/global/scripts.php'); 
?>
<!--<script src="assets/scripts/uploader/jquery.ui.widget.js"></script>-->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="assets/scripts/uploader/jquery.iframe-transport.js"></script>
<script src="assets/scripts/uploader/jquery.fileupload.js"></script>
<script src="assets/scripts/uploader/jquery.fileupload-process.js"></script>
<script src="assets/scripts/uploader/jquery.fileupload-image.js"></script>
<script src="assets/scripts/uploader/jquery.fileupload-video.js"></script>
<script src="assets/scripts/uploader/jquery.fileupload-validate.js"></script>
<style type="text/css">
@charset "UTF-8";
/*
 * jQuery File Upload Plugin CSS 1.3.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

.fileinput-button {
  position: relative;
  overflow: hidden;
}
.fileinput-button input {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  opacity: 0;
  -ms-filter: 'alpha(opacity=0)';
  font-size: 200px;
  direction: ltr;
  cursor: pointer;
}

/* Fixes for IE < 8 */
@media screen\9 {
  .fileinput-button input {
    filter: alpha(opacity=0);
    font-size: 100%;
    height: 100%;
  }
}
video { 
    width: 100%;
}
</style>
<script type="text/javascript">

    $(document).ready(function(){
        var uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
        $("#videoUpload").fileupload({
            url: "/uploads/",
            dataType: 'json',
            autoUpload: false,
            acceptFileTypes: /(\.|\/)(mp4)$/i,
            limitMultiFileUploads: 1,
            maxNumberOfFiles: 1,
            maxFileSize: 5000000000,
            dropZone: $(".well"),
            disableVideoPreview: false
        }).on("fileuploadadd",function(e,data){
            $('#files').html("");
            data.context = $('<div/>').appendTo('#files');
            $.each(data.files, function (index, file) {
                var node = $('<p/>')
                        .append($('<span/>').text(file.name));
                if (!index) {
                    node
                        .append('<br>')
                        .append(uploadButton.clone(true).data(data));
                }
                node.appendTo(data.context);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                file = data.files[index],
                node = $(data.context.children()[index]);
            if (file.preview) {
                node
                    .prepend('<br>')
                    .prepend(file.preview);
            }
            if (file.error) {
                node
                    .append('<br>')
                    .append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button')
                    .text('Upload')
                    .prop('disabled', !!data.files.error);
            }
        });
    });
</script>
</body>
</html>