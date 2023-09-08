<?php

namespace App\Test;

use App\Code\Company;
use App\Code\DBConn;
use App\Code\Employee;
use PDO;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new DBConn();

        // Create necessary tables if they don't exist
        $this->pdo->prepareTables();
    }

    protected function tearDown(): void
    {
        // Clean up the database after each test
        $this->pdo->truncateTables();
    }

    public function testEmployeeSaveToDatabase()
    {
        $company = new Company('My Awesome Company');
        $this->assertTrue((bool) $company->write());

        $employee = new Employee('My Awesome Name', 'i_am@awesome.lol', $company->getID(), '90000');
        $this->assertTrue((bool) $employee->write());
    }
}
?>
