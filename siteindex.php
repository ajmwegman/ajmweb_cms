    <!-- article with menu
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
	<div class="container">

      <!-- Three columns of text below the carousel -->
      <div class="row mt-4">
        <h3>U kunt ons bereiken via de onderstaande webites.</h3>
            <?  
          $sql = "SELECT * FROM config ORDER BY loc_website ASC";
               
          $data = $pdo->query($sql)->fetchAll();
// and somewhere later:
          
          $i = 0;
          $option = '';
          foreach ($data as $c) {
    
            if($i%4==0) { $option .= "<div class=\"row\">\n"; }
			  
			  $option .= "<div class=\"col-lg-3\">\n";
              
              $loc_site = strtolower($c['loc_website']);
			  $websitename = str_replace(array("http://www.", "https://www."), "", $loc_site);
			  
              $website = str_replace("/", "", $websitename);
			  
			  $point = $c['loc_pointers'];
              
              $pointer = preg_split('/\r\n|[\r\n]/', $point);
			       
              $option .= "<p>";
              $option .= "<strong>".ucwords($website)."</strong>\n";
			  $option .= "<br><a href=\"".$c['loc_website']."\" class=\"\" target=\"_blank\">".$website."</a>\n";
				
				$sites = array_unique($pointer);
				foreach($pointer as $key) {    
    				$option .= '<br><a href="http://www.'.$key.'" class="" target="_blank">'.$key.'</a>';    
				}
				
				$option .= "</p>\n";
				
				$option .= "</div>\n"; // sluit col 3
				
				$i++;
				
				if($i%4==0) { $option .= "</div>\n\n"; } // /row
				
}
           print $option;
          ?>
        </div><!-- /.col-lg-4 -->

        
      </div><!-- /.row -->
      
      </div><!-- /.marketing -->
