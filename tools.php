<?php

if (!isset($index_loaded)) {
    die('Direct access to this file is forbidden');
}
/*
 * this file contains misc utility functions
 */

/**
 * Displays any array 1 dimension direct to screen.
 */
function arrayDisplay($arrayName)
{
    echo'<style> td,th{
        border:1px solid black;}</style>';
    echo'<table >';
    echo'<tr>';
    echo'<th>Key </th>';
    echo'<th>Value</th>'.'<br>';
    echo'</tr>';
    foreach ($arrayName as $key => $value) {
        echo'<tr>';
        echo"<td>$key</td>";
        echo"<td>$value</td>";
        echo'</tr>';
    }
    echo'</table>';
}

function array_HTML_Products($arrayName)
{
    $r = '';

    $r .= '<table >';
    $r .= '<tr>';
    $r .= '<th>id </th>';
    $r .= '<th>name</th>';
    $r .= '<th>description</th>';
    $r .= '<th>price</th>';
    $r .= '<th>pic</th>';
    $r .= '<th>stock</th>';
    $r .= '</tr>';
    foreach ($arrayName as $key => $value) {
        $r .= '<tr>';
        $r .= '<td>'.$value['id'].'</td>';
        $r .= '<td>'.$value['name'].'</td>';
        $r .= '<td>'.$value['description'].'</td>';
        $r .= '<td>'.$value['price'].'</td>';
        $r .= '<td>'.$value['pic'].'</td>';
        $r .= '<td>'.$value['qty_in_stock'].'</td>';
        $r .= '</tr>';
    }
    $r .= '</table>';

    return $r;
}

function array_to_HTML_table($array_name)
{
    //alternative for border
    $r = '';
    $r .= '<style> td,th{border:1px solid black;}</style>';
    $r .= '<table border=1>';
    $r .= '<tr>';
    $r .= '<th>index/key</th>';
    $r .= '<th>value</th>';
    $r .= '<tr>';

    foreach ($array_name as $key => $value) {
        $r .= '<tr>';
        $r .= '<td>';
        $r .= "$key <br>";
        $r .= '</td>';
        $r .= '<td>'.$value.'<br>'.'</td>';
        $r .= '</tr>';
    }
    $r .= '</table>';

    return $r;
}

function crash($code, $message)
{
    http_response_code($code);

    //here email to IT admin
    // mail(ADMIN_EMAIL,COMPANY_NAME."Server crashed code=".$code,$message);
    // write in log file
    $file = fopen('logs/errors.log', 'a+');
    $time_info = date('d-M-Y h:i:s');
    fwrite($file, $message.'<br>');
    fclose($file);

    die($message);
}

function tableDisplay($arrayProducts)// display any table
{
    $r = '';
    $r .= '<table class="tab" >';
    if (count($arrayProducts) == 0) {
        return 'table is empty';
    }
    $col_names = array_keys($arrayProducts[0]);
    $r .= '<tr>';
    foreach ($col_names as $col) {
        $r .= '<th>'.$col.'</th>';
    }
    $r .= '</tr>';
    foreach ($arrayProducts as $row) {
        $r .= '<tr>';
        foreach ($col_names as $col) {
            $r .= '<td>'.$row[$col].'</td>';
        }
        $r .= '</tr>';
    }
    $r .= '</table>';

 

    return $r;
}

/**
 * Check that $_FILE (the uploaded file) contains a valid image
 * extension must be: .jpg , .JPG , .gif ou .png.
 *
 * $file_input the file input name on the HTML form
 * $Max_Size maximum file size in Kb, default 500kb
 * returns "OK" or error message
 */
function Photo_Uploaded_Is_Valid($file_input, $Max_Size = 500000)
{
    //Must havein HTML <form enctype="multipart/form-data" .. //otherwise $_FILE is undefined // $file_input is the file
    // input name on the HTML form
    if (!isset($_FILES[$file_input])) {
        return 'No image uploaded';
    }
    if ($_FILES[$file_input]['error'] != UPLOAD_ERR_OK) {
        return 'Error picture upload: code='
        .$_FILES[$file_input]['error'];
    } // Check image size
    if ($_FILES[$file_input]['size'] > $Max_Size) {
        return 'Image too big, max file size is '.$Max_Size.' Kb';
    }
    // Check that file actually contains an image
    $check = getimagesize($_FILES[$file_input]['tmp_name']);
    if ($check === false) {
        return 'This file is not an image';
    }
 
    // Check extension is jpg,JPG,gif,png
    $imageFileType = pathinfo(basename($_FILES[$file_input]['name']), PATHINFO_EXTENSION);
    if ($imageFileType != 'jpg' && $imageFileType != 'JPG' && $imageFileType != 'gif' && $imageFileType != 'png') {
        return 'Invalid image file type, valid extensions are: .jpg .JPG .gif .png';
    }
 
    return 'OK';
}
 
/**
 * Function to save uploaded image in folder
 * (and display image for testing).
 * $file_input is the file input name on the HTML form.
 * */
function Picture_Save_File($file_input, $target_dir)
{
    $message = Photo_Uploaded_Is_Valid($file_input); // voir fonction
    if ($message === 'OK') {
        // Check that there is no file with the same name
        // already exists in the target folder
        // using file_exists()
        $target_file = $target_dir.basename($_FILES[$file_input]['name']);
        if (file_exists($target_file)) {
            die('This file already exists');
        }
 
        // Create the file with move_uploaded_file()
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $target_file)) {
            // ALL OK display image for testing
            echo '<img src="'.$target_file.'">';
        } else {
            echo 'Error in move_upload_file';
        }
    } else {
        // upload error, invalid image or file too big
        echo $message;
    }
}