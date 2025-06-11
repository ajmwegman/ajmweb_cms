<form id="myDropzone" action ="../modules/theme/bin/upload.php"class="dropzone">
       <div class="fallback">
        <input name="file" type="file" />
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>      
  
    <script>
    // Initialize Dropzone
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#myDropzone", { 
    url: "../modules/theme/bin/upload.php", // Replace with the URL of your PHP upload script
    maxFiles: 1, // Limit to one file upload
    acceptedFiles: 'image/*', // Accept only image files
     init: function() {
            this.on("success", function(file, response) {
                // Retrieve the uploaded image URL from the response
                var thumbnailUrl = JSON.parse(response).thumbnailUrl;
                // Update the Dropzone preview thumbnail with the uploaded image
                file.previewElement.querySelector(".dz-image img").src = thumbnailUrl;
                // Disable further file uploads
                this.disable();
            });
            // Retrieve and display the latest uploaded image on page load
            var thumbnailUrl = "<?php echo $themeConfig->getLatestImage(); ?>";
            if (thumbnailUrl) {
                var mockFile = { name: "uploaded_image", size: 12345 };
                myDropzone.emit("addedfile", mockFile);
                myDropzone.emit("thumbnail", mockFile, thumbnailUrl);
                myDropzone.emit("complete", mockFile);
                myDropzone.disable();
            }
        }
    });
</script>