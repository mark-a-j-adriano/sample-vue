<?php

namespace App\Code;

use App\Code\Company;
use App\Code\DBConn;
use App\Code\Employee;

class ApiController
{

    private $pdo;

    public function __construct()
    {
        $this->pdo = new DBConn();

        // Create necessary tables if they don't exist
        $this->pdo->prepareTables();
    }

    public function getCompanies()
    {
        $rows = $this->pdo->processStatement(
            'SELECT id, name from `companies`',
            [],
            false,
            true
        );

        $this->sendOutput(
            json_encode(['data' => $rows]),
            ['Content-Type: application/json']
        );
    }

    public function getEmployees($companyID)
    {
        $company = new Company();
        $company->setID($companyID);

        $this->sendOutput(
            json_encode(['data' => $company->getEmployees()]),
            ['Content-Type: application/json']
        );
    }

    public function getAverageSalary($companyID)
    {
        $company = new Company();
        $company->setID($companyID);

        $data = $company->getAverageSalary();
        $temp = isset($data[0]) ? $data[0] : null;
        $salary = $temp ? $temp['salary'] : 0;

        $this->sendOutput(
            json_encode(['data' => $salary]),
            ['Content-Type: application/json']
        );
    }

    public function editEmployee($id, $email)
    {
        $employee = new Employee();

        $employee->setId($id);

        // PDO prepare statement will clean up the Query to avoid SQL injection
        $employee->setEmail($email);

        $this->sendOutput(
            json_encode(['data' => $employee->updateEmail()]),
            ['Content-Type: application/json']
        );
    }

    public function uploadCSV($file)
    {
        if ($file["error"] === UPLOAD_ERR_OK) {
            $filename = $file["name"];
            $tempPath = $file["tmp_name"];

            // Define your desired upload directory
            $uploadDir = __DIR__ . "/uploads/";

            // Create the directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move the uploaded file to the desired directory
            $destination = $uploadDir . $filename;
            move_uploaded_file($tempPath, $destination);

            $fileInfo = pathinfo($filename);

            $counter = 0;
            $response = '';

            $data = $this->importFile($fileInfo, $destination);
            $response = $data['response'];
            $counter = $data['counter'];

            $this->sendOutput(
                json_encode([
                    'message' => $response,
                    'count' => $counter,
                    'filename' => $filename,
                    'tempPath' => $tempPath,
                    'destination' => $destination,
                    'fileInfo' => $fileInfo,
                ]),
                ['Content-Type: application/json']
            );
        } else {
            echo json_encode(["error" => "Error uploading file"]);
        }
    }

    public function importFile($fileInfo = '', $csvFile = '')
    {
        $counter = 0;
        $response = '';
        // Check if it's a CSV file
        if (strtolower($fileInfo["extension"]) === "csv") {
            // Read and parse the CSV data
            $companies = [];
            $temp = [];
            if (($handle = fopen($csvFile, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Assuming the CSV structure matches the example (Company Name,Employee Name,Email Address,Salary)
                    [$company, $employee, $email, $salary] = $data;

                    if (!is_numeric($salary)) continue;

                    // Store company data in an associative array
                    $companies[] = [
                        'name' => $company,
                        'employee' => $employee,
                        'email' => $email,
                        'salary' => $salary,
                    ];

                    $temp[] = $company;
                }
                fclose($handle);

                $temps = array_unique($temp);

                foreach ($temps as $temp) {
                    $corp = new Company($temp);

                    if (!$corp->exist()) {
                        $corp->write();

                        foreach ($companies as $company) {
                            if ($company['name'] === $temp) {
                                $employee = new Employee(
                                    $company['employee'],
                                    $company['email'],
                                    $corp->getId(),
                                    $company['salary']
                                );

                                $added = $employee->write();

                                if ($added) {
                                    ++$counter;
                                }
                            }
                        }
                    }
                }

                $response = sprintf('Processed %s db records.', $counter);
            } else {
                $response = "Failed to open the uploaded CSV file.";
            }
        } else {
            $response = "Please upload a valid CSV file.";
        }

        return [
            'counter' => $counter,
            'response' => $response,
        ];
    }

    /*
    * Send API output.
    *
    * @param mixed $data
    * @param string $httpHeader
    */
    protected function sendOutput($data, $httpHeaders = array())
    {
        header_remove('Set-Cookie');
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }
}
