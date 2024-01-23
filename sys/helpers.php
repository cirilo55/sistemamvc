<?php
function formatDataPtBr($dataString) {
    $data = new DateTime($dataString);
    return $data->format('d/m/Y H:i:s');
}
function formatDataPtBrNoHours($dataString) {
    $data = new DateTime($dataString);
    return $data->format('d/m/Y');

}
// @data vai ser o objeto e $fields o campo em valores
function convertArrayOfObjectToArray($data, $value, $label) {
    $result = [];
    $a = null; 
    $b = null;

    foreach($data as $stdclass)
    {
        foreach($stdclass as $v=>$field)
        {
            if($v===$value){
                $a= $field;
            }
            if($v===$label)
            {
                $b= $field;
            }
            $result[$a] = $b;
        }
    }

    return $result;
}







?>