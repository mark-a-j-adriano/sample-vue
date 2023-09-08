<?php

namespace App\Test;

use App\Code\Company;
use App\Code\DBConn;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
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

    public function testCompanySaveToDatabase()
    {
        $company = new Company('My Awesome Company');
        $this->assertTrue((bool) $company->write(false));
    }
}
?>
