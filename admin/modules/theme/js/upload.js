   $(function() {
      Dropzone.autoDiscover = false;
      var myDropzone = new Dropzone("#my-dropzone", { 
         url: "upload.php",
         maxFiles: 1, // limit to only one file
         autoProcessQueue: false,
         maxFilesize: 2,
         acceptedFiles: ".jpeg,.jpg,.png,.gif",
         addRemoveLinks: true
      });
      myDropzone.on("addedfile", function(file) {
         var removeButton = Dropzone.createElement("<button>Remove file</button>");
         var _this = this;
         removeButton.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            _this.removeFile(file);
         });
         file.previewElement.appendChild(removeButton);
      });
      myDropzone.on("complete", function(file) {
         myDropzone.removeFile(file);
      });
      myDropzone.on("success", function(file, response) {
         $("#uploaded_image").html(response);
      });
      $("#submit").click(function(e) {
         e.preventDefault();
         e.stopPropagation();
         myDropzone.processQueue();
      });
   });
