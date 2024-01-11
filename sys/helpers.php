<?php
function formatDataPtBr($dataString) {
    $data = new DateTime($dataString);
    return $data->format('d/m/Y H:i:s');
}
function formatDataPtBrNoHours($dataString) {
    $data = new DateTime($dataString);
    return $data->format('d/m/Y');

}



?>