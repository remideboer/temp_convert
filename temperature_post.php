<?php
/*
 * Oefen API voor HTTP POST request , 
 * convert celsius naar fahrenheit: c2f
 * convert fahrenheit naar celsius: f2c
 * convert celsius naar Kelvin: c2k
 * convert Kelvin naar celsius: k2c
 */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}
if(isset($_POST['waarde']) && isset($_POST['type']) && is_numeric($_POST['waarde'])){
    $waarde = $_POST['waarde'];
    if($_POST['type'] === 'c2f'){
        echo $waarde * 9/5 + 32;
    } elseif($_POST['type'] === 'f2c') {
        echo ($waarde - 32) * 5/9;
    } elseif($_POST['type'] === 'c2k') {
        echo $waarde + 273.15;
    } elseif($_POST['type'] === 'k2c') {
        echo $waarde - 273.15;
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo 'conversietype niet bekend, toegestaan: c2f, f2c, c2k, k2c';
        exit;
    }
}else {
    header("HTTP/1.1 400 Bad Request");
    echo 'incorrecte data aangeleverd';
    exit;
}

