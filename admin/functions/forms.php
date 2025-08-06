<?php
# FORM BUILDER FOR WEBSTORE MANAGER VERSION 0.3
#
# Compatible with Bootstrap 5

# INPUT FIELD
function input($type, $name, $value="", $id="", $extra="") {
    if (empty($type) || empty($name)) {
        return "could not render inputfield, check values";
    } else {
        $input = '';
        $_id = empty($id) ? '' : 'id="'.$id.'"';
        $_type = empty($type) ? '' : 'type="'.$type.'"';
        $_name = empty($name) ? '' : 'name="'.$name.'"';
        $_value = !isset($value) ? '' : 'value="'.$value.'"';
        $_placeholder = empty($placeholder) ? '' : 'placeholder="'.$placeholder.'"';
        
        $addon = ($_type == 'type="password"') ? ' autocomplete="off"' : ''; 
        $addon .= empty($extra) ? 'class="form-control"' : ' '.$extra;
        $addon .= empty($_placeholder) ? '' : ' '.$_placeholder;
        
        $input .= '<input '.$_type.' '.$_name.' '.$_id.' '.$_value.' '.$addon.'>';
    }
    return $input;
}

# CHECKBOX FIELD
function checkbox($name, $value, $label, $content = '', $extra = '') {
    if (empty($name) || empty($value) || empty($label)) {
        return 'Could not render checkbox, check values';
    }
    $input = '';
    $add = $extra;
    $add .= ($content == $value) ? ' checked="checked"' : '';
    $input .= '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="' . $value . '" ' . $add . '> ';
    $input .= '<label for="' . $name . '">' . $label . '</label>' . "\n";
    return $input;
}

# RADIO BUTTON FIELD
function radio($name, $value, $label, $content, $inline='', $order='') {
    if (empty($name) || empty($value) || empty($label) || empty($content)) {
        return "could not render radiobutton, check values";
    } else {
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

# TEXTAREA FIELD
function textarea($name, $value, $extra='class="form-control"') {
    $input = '';    
    $input .= '<textarea name="'.$name.'" id="'.$name.'" '.$extra.'>';
    $input .= $value;
    $input .= "</textarea>\n";        
    return $input;
}

function selectboxFont($title, $name, $value='', $extra='', $inline='no') {
    $fontOptions = [
        'Arial', 'Helvetica', 'Georgia', 'Times New Roman', 'Verdana', 'Roboto', 
        'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Source Sans Pro', 
        'Noto Sans', 'Libre Baskerville', 'Merriweather', 'Playfair Display', 
        'Raleway', 'Nunito', 'Ubuntu', 'PT Sans', 'Droid Sans', 'Quicksand', 
        'Fira Sans', 'Oswald', 'Tahoma', 'Trebuchet MS'
    ];

    $input = '<div class="form-group">';
    $input .= ($inline === 'inline') ? '<label for="'.$name.'" class="col-sm-3">'.$title.'</label>' : '<label for="'.$name.'">'.$title.'</label>';
    $input .= ($inline === 'inline') ? '<div class="col-sm-9">' : '';
    $input .= '<select name="'.$name.'" id="'.$name.'" '.$extra.'>';
    $input .= '<option value="">Maak een keuze...</option>';

    foreach ($fontOptions as $fontName) {
        $input .= '<option value="' . htmlspecialchars($fontName) . '" ';
        $input .= (strcasecmp(trim($fontName), trim($value ?? '')) == 0 ? 'selected="selected"' : '');
        $input .= '>' . htmlspecialchars($fontName) . '</option>';
    }

    $input .= '</select>';
    $input .= ($inline === 'inline') ? '</div>' : '';
    $input .= '</div>';

    return $input;
}


function selectbox($title, $name, $value = '', $options = array(), $extra = '', $inline = 'no') {
    // Controleer of $options een array is, zo niet, zet het om naar een lege array
    if (!is_array($options)) {
        trigger_error('Fout: $options moet een array zijn in selectbox()', E_USER_WARNING);
        $options = array();
    }

    // Controleer of $value geen array is
    if (is_array($value)) {
        trigger_error('Fout: $value mag geen array zijn in selectbox()', E_USER_WARNING);
        $value = ''; // Zet $value terug naar een lege string als fallback
    }

    // Controleer of $extra geen array is
    if (is_array($extra)) {
        trigger_error('Fout: $extra mag geen array zijn in selectbox()', E_USER_WARNING);
        $extra = ''; // Zet $extra terug naar een lege string als fallback
    }

    // Begin met de opbouw van het select-element
    $input = '<div class="form-group">';
    $input .= ($inline === 'inline') ? '<label for="' . htmlspecialchars($name) . '" class="col-sm-3">' . htmlspecialchars($title) . '</label>' 
                                     : '<label for="' . htmlspecialchars($name) . '">' . htmlspecialchars($title) . '</label>';
    $input .= ($inline === 'inline') ? '<div class="col-sm-9">' : '';
    $input .= '<select name="' . htmlspecialchars($name) . '" id="' . htmlspecialchars($name) . '" ' . $extra . '>';
    $input .= '<option value="">Maak een keuze...</option>';

    // Loop door de opties heen
    foreach ($options as $code => $optionName) {
        // Controleer of $code geen array is
        if (is_array($code)) {
            trigger_error('Fout: array key mag geen array zijn in selectbox()', E_USER_WARNING);
            continue; // Sla deze optie over
        }
        
        // Controleer of $optionName geen array is
        if (is_array($optionName)) {
            trigger_error('Fout: array value mag geen array zijn in selectbox()', E_USER_WARNING);
            $optionName = ''; // Zet terug naar lege string
        }
        
        $input .= '<option value="' . htmlspecialchars($code) . '" ';
        if (strcasecmp(trim($code), trim($value ?? '')) == 0) {
            $input .= 'selected="selected"';
        }
        $input .= '>' . htmlspecialchars($optionName) . '</option>';
    }

    // Sluit het select-element af
    $input .= '</select>';
    $input .= ($inline === 'inline') ? '</div>' : '';
    $input .= '</div>';

    return $input;
}

?>
