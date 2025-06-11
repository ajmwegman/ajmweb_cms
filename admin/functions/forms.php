<?php
# FORM BUILDER FOR WEBSTORE MANAGER VERSION 0.2
#
# Compatible with Bootstrap 5


# INPUT FIELD
function input($type, $name, $value="", $id="", $extra="") {
	
	if(empty($type) || empty($name) ) {
		return "could not render inputfield, check values";
	}
	else {
	
		$input 		 = '';
		$_id 	  	 = empty($id)  	 ? '' : 'id="'.$id.'"';
		$_type 		 = empty($type)  ? '' : 'type="'.$type.'"';
		$_name 		 = empty($name)  ? '' : 'name="'.$name.'"';
		$_value 	 = !isset($value) ? '' : 'value="'.$value.'"';
		$_placeholder = empty($placeholder) ? '' : 'placeholder="'.$placeholder.'"';
		
		$addon = ($_type == 'type="password"') ? ' autocomplete="off"' : ''; 
		$addon .= empty($extra) ? 'class="form-control"' : ' '.$extra;
		$addon .= empty($_placeholder) ? '' : ' '.$_placeholder;
		
		if($type == 'hidden') {
			$input .= '<input '.$_type.' '.$_name.' '.$_id.' '.$_value.' '.$addon.'>';
		}
		else {	
			$input .= '<input '.$_type.' '.$_name.' '.$_id.' '.$_value.' '.$addon.'>';
		}
	}
	return $input;
}

function checkbox($name, $value, $label, $content = '', $extra = '') {
    // Check if required parameters are empty
    if (empty($name) || empty($value) || empty($label)) {
        return 'Could not render checkbox, check values';
    }

    // Initialize input variable
    $input = '';

    // Set additional attributes and checked attribute if content matches value
    $add = $extra;
    $add .= ($content == $value) ? ' checked="checked"' : '';

    // Generate HTML for checkbox element
    $input .= '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $add . '> ';
    $input .= '<label for="' . $name . '">' . $label . '</label>' . "\n";

    return $input;
}


function radio($name, $value, $label, $content, $inline='', $order='') {
	//echo "<br >content: ".$content;
	//echo "<br >value: ".$value;
	
	if(empty($name) || empty($value) || empty($label) || empty($content) ) {
		return "could not render radiobutton, check values";
	}
	else {
	
	$input = '';
	
	$class = ($inline == 'inline') ? 'class="checkbox-inline"' : 'class="checkbox"';
	$check = ($content == $value) ? 'checked="checked"' : '';
	
	$input .= '<label for="'.$name.$order.'" '.$class.'>';
	$input .= '<input type="radio" name="'.$name.'" id="'.$name.$order.'" value="'.$value.'" '.$check.'> ';
	$input .= $label;
	$input .= "</label>\n";
	}
	
	return $input;
}

# INPUT FIELD
function textarea($name, $value, $extra='class="form-control"') {
	
	$input = '';	
    $input .= '<textarea name="'.$name.'" id="'.$name.'" '.$extra.'>';
	$input .= $value;
    $input .= "</textarea>\n";		
	
	return $input;

}

function selectbox($title, $name, $value='', $codes = array(), $names = array(), $extra='', $inline='no') {
	
    
    $codes_count = (is_array($codes) ? count($codes) : 0);
    $names_count = (is_array($names) ? count($names) : 0);
    
    if($codes_count != $names_count) {
        
        return 'Error: codes and names not equal';
    } elseif($codes_count < 1 || $names_count < 1) {
        return 'Error: no section build.';
    } else {

        $input = '<div class="form-group">';


        $input .= ($inline === 'inline') ? '<label for="'.$name.'" class="col-sm-3">'.$title.'</label>' : '<label for="'.$name.'">'.$title.'</label>';

        $input .= ($inline === 'inline') ? '<div class="col-sm-9">' : '';

        $input .= '<select name="'.$name.'" id="'.$name.'" '.$extra.'>';

        $input .= '<option value="">Maak een keuze...</option>';

        foreach (array_combine($codes, $names) as $code => $name) {
                $input .= '<option value="' . $code . '" ';

                $input .= ($code == $value ? 'selected=selected' : '');

                $input .= '>' . $name . '</option>';
        }
        $input .= '</select>';

        $input .= ($inline === 'inline') ? '</div>' : '';

        $input .= '</div>';
    }
	return $input;
	  
}
?>