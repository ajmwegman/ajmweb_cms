<?php
$s = 0;
foreach($sections as $row => $value) {
	
	$class = ($s == 0) ? 'primary d-flex align-items-center' : 'd-flex align-items-center';
	$location = $value['location'];
	$content = $value['content'];
	
	$section = '
	<section id="'.str_replace('#', '', $location).'" class="'.$class.'">
		<div class="container">
      		<div class="row">
   				'.$content.'
			</div>
		</div>
	</section>
	';
	
	echo $section;
	$s++;
}
?>