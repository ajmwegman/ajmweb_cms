<?php
class siteConfig {

  function __construct( $pdo ) {
    $this->pdo = $pdo;
  }

  function area_list( $shop_id ) {

    $input = '<div class="row">';
      
    $sql = "SELECT area_id, name FROM area ORDER BY name";
    $stmt = $this->pdo->prepare( $sql );

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
    foreach ( $result as $row => $data ) {
        
      $sql2 = "SELECT * FROM area_link WHERE area_id = :area_id AND shop_id = :shop_id";
      $stmt = $this->pdo->prepare( $sql2 );

      $stmt->execute( [ "area_id" => $data[ 'area_id' ], "shop_id" => $shop_id ] );
    
        $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $input .= '<div class="col-md-3">';
        $input .= '<div class="form-check">';

        $input .= '<input name="area_box" type="checkbox" id="box' . $data[ 'area_id' ] . '" 
        class="autosave_checkbox" value="' . $data[ 'area_id' ] . '"';

         foreach ( $result2 as $row => $var ) {
            $input .= ( $var[ 'area_id' ] == $data[ 'area_id' ] ) ? "checked=checked" : "";
         }
        
      $input .= ' /> ';
      $input .= '<label class="form-check-label" for="box' . $data[ 'area_id' ] . '">'.$data['name'].'</label>';
        
        $input .= "</div>"; // form-ckeck
        $input .= "</div>"; // col

    }
    
    $input .= "</div>"; // row
    
    return $input;
  }    
}
?>