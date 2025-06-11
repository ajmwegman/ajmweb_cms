<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>

    </style>

</head>
<body>
<div class="upload_container">
<br clear="all" />
<center>
<div style="width:350px" align="center">
<div id='preview'></div>
<form id="image_upload_form" method="post" enctype="multipart/form-data" action='image_upload.php' autocomplete="off">
<div class="browse_text">Browse Image File:</div>
<div class="file_input_container">
<div class="upload_button"><input type="file" name="photo" id="photo" class="file_input" /></div>
</div><br clear="all">
</form>
</div>
</center>
<br clear="all" />
</div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
    <script>
$(document).ready(function() {
    
$('#photo').live('change', function() {
$("#preview").html('');
$("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
$("#image_upload_form").ajaxForm({
target: '#preview'
}).submit();
});
});
    </script>
</body>
</html>