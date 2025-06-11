<?php
session_start();
$sessid = session_id();

error_reporting( E_ALL ^ E_DEPRECATED );
ini_set( "display_errors", 1 );

include( "../system/database.php" );

require_once( "src/database.class.php" );
require_once( "src/menulist.class.php" );
require_once( "functions/forms.php" );

$db   	 = new database($pdo);
$menu 	 = new menu($pdo);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<meta name="keywords" content="">
<meta name="revisit-after" content="7 days">
<meta name="format-detection" content="telephone=no">
<title>
CMS
</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	
	
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> 
	

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<!-- drag and drop -->	
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script> 

<!-- Bootstrap core CSS -->
<link href="css/main.css" rel="stylesheet">
	
<script src="/admin/js/global.js"></script>

<script type="application/javascript">
$( document ).ready( function () {
	
	  //$('#article').summernote();

} );
</script>
<script>
    $(document).ready(function() {
        $("#your_summernote").summernote();
        $('.dropdown-toggle').dropdown();
    });
	</script>
	<style>

	</style>
</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Integrate Summernote with Bootstrap 5 in PHP/HTML</h4>
                    </div>
                    <div class="card-body">

                        <form action="#">
                            <div class="mb-3">
                                <label>Big Description</label>
                                <textarea name="description" id="your_summernote" rows="4"></textarea>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>