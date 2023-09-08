<?php

/* Handle CORS */

// Specify domains from which requests are allowed
header('Access-Control-Allow-Origin: *');

// Specify which request methods are allowed
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

// Additional headers which may be sent along with the CORS request
header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');

// Set the age to 1 day to improve speed/caching.
header('Access-Control-Max-Age: 86400');


// Find autoload.php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    echo "autoload.php not found";
    die;
}

use App\Code\ApiController;

$api = new ApiController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_GET['action'] === 'editEmployee') {
        $data = json_decode(file_get_contents('php://input'), 1);
        $api->editEmployee($data["employeeId"], $data["emailAddress"]);
    } elseif (isset($_FILES["csv"])) {
        $api->uploadCSV($_FILES["csv"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['action'])) {
    if ($_GET['action'] === 'getEmployees') {
        $api->getEmployees($_GET['companyID']);
    } elseif ($_GET['action'] === 'getAverageSalary') {
        $api->getAverageSalary($_GET['companyID']);
    } elseif ($_GET['action'] === 'getCompanies') {
        $api->getCompanies();
    } elseif ($_GET['action'] === 'importCSV') {
        $counter = 0;
        $response = '';

        $fileInfo = [
            'extension' => 'csv'
        ];

        [$counter, $response] =  $api->importFile($fileInfo, '../sample.csv');

        $this->sendOutput(
            json_encode(['data' => $response, 'count' => $counter]),
            ['Content-Type: application/json']
        );
    }
} else {
    echo  $_SERVER["REQUEST_METHOD"];
}
