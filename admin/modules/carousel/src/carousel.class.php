<?php
class carousel {
	
/*
id
subject
location
description
sortnum
image
modified
active
*/
        /** @var PDO */
        private PDO $pdo;

        function __construct($pdo) {
                $this->pdo = $pdo;
    }

	function getAllImages() {

        $sql = "SELECT * FROM group_carousel ORDER BY sortnum DESC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}
	
	function getImage( $field, $id ) {

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_carousel WHERE {$field} = ?";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute([$id]);

			$row = $stmt->fetch();
			
			if(!empty($row)) {
		        return($row);
			} else {
				return false;
			}
		}
	}
    
    function getCarouselSettings($group_id) {

		$sql = "SELECT * FROM group_carousel_settings WHERE group_id = :group_id";

        $stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'group_id' => $group_id ] );
		
        $row = $stmt->fetch();
		
        if(!empty($row)) {
		      return($row);
		} else {
            return false;
        }
	}
    
    function check_extension($filename, $allowed) {
        
        $filename = strtolower($filename) ; 
        //print_r($allowed);

        $exts = pathinfo($filename, PATHINFO_EXTENSION);

        if ($filename != '') 
        { 
            if (!in_array(end(explode(".", strtolower($filename))), $allowed)) 
            { 
               return false;
            }
            else
            {
               return $exts; 
            } 
        } 
    }

    function image_resize($extension, $max_size, $image_map, $temp_map, $foto_name, $quality) {
        /*
        echo "<br />extension: ".$extension;	
        echo "<br />max_size: ".$max_size;
        echo "<br />image_map: ".$image_map;
        echo "<br />temp_map: ".$temp_map;
        echo "<br />quality: ".$quality;
        echo $temp_map.$foto_name;
        */
        //echo '<img src="'.$image_map.$foto_name.'" />';

        if(!isset($foto_name) || empty($foto_name)) { 
            return "geen fotonaam bekend";
        } else {

        if(!is_dir($image_map)) { 
           $make_dir = mkdir($image_map);
           $chmod_new_map = chmod($image_map, 0777);
        }

        //$chmod_temp = chmod($temp_map . $foto_name, 0644);

        if($extension == "jpg" || $extension == "pjpg" || $extension == "jpeg") 
        { 
            $photo = imagecreatefromjpeg($temp_map.$foto_name);
        }

        if($extension == "png") 
        { 
            $photo = imagecreatefrompng($temp_map.$foto_name);
        }

        if($extension == "gif") 
        { 
            $photo = imagecreatefromgif($temp_map.$foto_name);
        }

        $photo_width = imagesx ($photo); 
        $photo_height = imagesy ($photo);

        if ($photo_width > $max_size OR $photo_height > $max_size) 
        { 
            if ($photo_width == $photo_height) 
            { 
                $image_width = $max_size; 
                $image_height = $max_size; 
            } 

            elseif ($photo_width > $photo_height) 
            { 
                $value = $photo_width / $max_size; 
                $image_width = $max_size; 
                $image_height = round ($photo_height / $value); 
            } 

            else 
            {
            $value = $photo_height / $max_size; 
            $image_height = $max_size; 
            $image_width = round ($photo_width / $value); 
            } 
        } 

        else 
        { 
            $image_width = $photo_width; 
            $image_height = $photo_height; 
        } 

        $create_image = imagecreatetruecolor ($image_width, $image_height);

        if($extension == "png") 
        {
            // integer representation of the color black (rgb: 0,0,0)
            $background = imagecolorallocate($create_image, 0, 0, 0);
            // removing the black from the placeholder
            imagecolortransparent($create_image, $background);

            // turning off alpha blending (to ensure alpha channel information 
            // is preserved, rather than removed (blending with the rest of the 
            // image in the form of black))
            imagealphablending($create_image, false);

            // turning on alpha channel information saving (to ensure the full range 
            // of transparency is preserved)
            imagesavealpha($create_image, true);
        }

        if($extension == "gif") {

            $background = imagecolorallocate($create_image, 0, 0, 0);
            // removing the black from the placeholder
            imagecolortransparent($create_image, $background);
        }

        $create_copy = imagecopyresampled ($create_image, $photo, 0, 0, 0, 0, $image_width, $image_height, $photo_width, $photo_height); 

        if($extension == "jpg" || $extension == "pjpg" || $extension == "jpeg") 
        { 
            $photo = imagejpeg($create_image, $image_map . $foto_name, $quality);
        }

        if($extension == "png") 
        { 
            $quality = $quality / 10;
            $photo = imagepng($create_image, $image_map . $foto_name, $quality);
        }

        if($extension == "gif") 
        { 
            $photo = imagegif($create_image, $image_map . $foto_name, $quality);
        }

        $chmod = chmod($image_map . $foto_name, 0644);
        }
    }
}
?>