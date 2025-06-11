<?php
function get_land($id) {
	
	$select = "SELECT name FROM catalog_shippingcost WHERE id = '".$id."'";
	$result = mysql_query($select) or die("ERROR : " . mysql_error());
	
	$row = mysql_fetch_assoc($result);
	
	return $row['name'];
}

?>