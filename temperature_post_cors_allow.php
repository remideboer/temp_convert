<?php
$headers = getallheaders();
/*
 * Oefen API voor HTTP POST request , 
 * convert celsius naar fahrenheit: c2f
 * convert fahrenheit naar celsius: f2c
 * convert celsius naar Kelvin: c2k
 * convert Kelvin naar celsius: k2c
 */
// set CORS header to allow all domains
// Allow from any origin
setCORS();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}

// check content type: als application/json krijg { "type" : "c2f", "waarde" : 20 }
// bij text/html in post type=c2f&waarde=20 

$output = "null"; // voor echo 
$waarde = 20; // default
$type = "c2f"; // default
// check voor type om manier parameters uit de request te halen in te stellen
// form

if ($headers['Content-Type'] == 'application/x-www-form-urlencoded') {
    if (isset($_POST['waarde']) && isset($_POST['type'])) {
        $waarde = $_POST['waarde'];
        $type = $_POST['type'];
    } else {
        badRequest('waarde en/of type parameter not set');
    }
} elseif ($headers['Content-Type'] == 'application/json') {
    $jsonString = file_get_contents('php://input'); // get body as string
    $json = json_decode($jsonString); // get as object
    // check if valid json
    if ($json !== null) {
        $waarde = $json->waarde;
        $type = $json->type;
    } else {
        badRequest('invalid json');
    }
}
if (is_numeric($waarde)) {
    if ($type === 'c2f') {
        $output = $waarde * 9 / 5 + 32;
    } elseif ($type === 'f2c') {
        $output = ($waarde - 32) * 5 / 9;
    } elseif ($type === 'c2k') {
        $output = $waarde + 273.15;
    } elseif ($type === 'k2c') {
        $output = $waarde - 273.15;
    } else {
        badRequest('conversietype niet bekend, toegestaan: c2f, f2c, c2k, k2c');
    }
} else {
    badRequest('waarde is niet van het type numeric');
}

if ($headers['Accept'] === 'application/json') {
    header('Content-Type: application/json');
    $output = json_encode(['waarde' => $output]);
} else {
    header('Content-Type: text/html');
}

echo $output;

// check if accept is json dan encoderen als json

function badRequest($message = "") {
    if ($headers['Accept'] === 'application/json') {
        $message = json_encode([ 'error' => $message]);
    }
    header("HTTP/1.1 400 Bad Request");
    echo $message;
    exit;
}

function setCORS() {
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
//        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
// Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
