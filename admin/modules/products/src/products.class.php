<?php
class products {
	
/*
id
title
location
description
sort_num
image
modified
active
*/
	private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    
    public function getProductInfo( $id ) {
        
		$sql = "SELECT * FROM group_products WHERE id = :id";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ 'id' => $id ] );

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		return($row);
	}

    public function getAllProducts() {

        $sql = "SELECT * FROM group_products ORDER BY sort_num DESC";

		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $row;	
	}
	
	public function getImages( $hash ) {

		if(!$hash) {
			return false;
		} else {

			$sql = "SELECT * FROM group_product_images WHERE hash = :hash ORDER BY sort_num ASC";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'hash' => $hash ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            return $row;
		}
	}	
    
	public function getImagesById( $id ) {

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT * FROM group_product_images WHERE id = :id LIMIT 1";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'id' => $id ] );

			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            return $row;
		}
	}
    
    public function getFirstImage( $hash ) {

		if(!$hash) {
			return false;
		} else {

			$sql = "SELECT * FROM group_product_images WHERE hash = :hash ORDER BY sort_num ASC LIMIT 1";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'hash' => $hash ] );

			$row = $stmt->fetch();
        
            if(!empty($row)) {
		        return($row);
			} else {
				return false;
			}
		}
	}
    
	public function getImageName( $id ) {

		if(!$id) {
			return false;
		} else {

			$sql = "SELECT image FROM group_product_images WHERE id = :id";

			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( [ 'id' => $id ] );

			$row = $stmt->fetch();
			
			if(!empty($row)) {
		        return($row);
			} else {
				return false;
			}
		}
	}
    
    public function check_extension($filename, $allowed) {
        
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

    public function rotateImage($id, $angle, $image_map) {
        $imageInfo = $this->getImagesById($id);

        if ($imageInfo && count($imageInfo) > 0) {
            // Stel dat je afbeeldingsnaam of een unieke identifier opgeslagen is in de database
            $imageName = $imageInfo[0]['image']; // of een andere relevante kolom

            // Construeer het pad naar de afbeelding
            $imagePath = $image_map . $imageName;

            // Laden van de afbeelding
            $source = imagecreatefromjpeg($imagePath); // Pas aan voor andere formaten

            // Roteren van de afbeelding
            $rotate = imagerotate($source, $angle, 0);

            // Opslaan van de geroteerde afbeelding
            imagejpeg($rotate, $imagePath);

            // Geheugen vrijgeven
            imagedestroy($source);
            imagedestroy($rotate);

            return true;
        }

        return false;
    }

    public function image_resize($extension, $max_size, $image_map, $temp_map, $foto_name, $quality) {
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