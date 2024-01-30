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

// Use appropriate sanitization/validation methods based on your requirements
function inputFormat($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function handleFileUpload($fileInputName, $targetDirectory, $oldFilePath = null, $allowedExtensions = ["jpg", "jpeg", "png", "gif"]) {
    
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $uploadDir = rtrim($targetDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $uploadFile = $uploadDir . basename($_FILES[$fileInputName]['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        
        if (!in_array($imageFileType, $allowedExtensions)) {
            return "Sorry, only " . implode(", ", $allowedExtensions) . " files are allowed.";
        }

        // Move uploaded file to destination directory
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadFile)) {
            // Delete old file if provided
            if ($oldFilePath && file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            return "File uploaded successfully.";
        } else {
            return "Error uploading file.";
        }
    } else {
        return "Error: File not uploaded or has an error.";
    }
}







?>