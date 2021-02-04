<?php
function load_options($seleted = null, $data, $value, $text)
{
    $s = '';
    if (empty($data)) {
        return $s .= '<option value=""></option>';
    }
    foreach ($data as $row) {
        if (!empty($seleted) && $seleted === $row[$value]) {
            $s .= "<option value='{$row[$value]}' selected='selected'>";
        } else {
            $s .= "<option value='{$row[$value]}'>";
        }
        $s .= $row[$text];
        $s .= '</option>';
    }
    return $s;
}

function loads_checkbox($nom_elem,$data, $value, $text)
{
    $s = '';
    
    foreach($data as $row){
        $x='';
        $x .= '<div class="form-group">';
        $x .= '<div class="col-sm-8">';
        $x .= '<div class="checkbox">';
        $x .= '<label>';
        $checked = (empty($row['estado']))?'':"checked='checked'";
        $x .= "<input type='checkbox' $checked class='valor-requerido' name='%s' value='%s'>%s</label>";
        $x .= '</div>';
        $x .= '</div>';
        $x .= '</div>';
        $s.= sprintf($x,$nom_elem,$row[$value],$row[$text]);
    }
    
    return $s;
}

?>