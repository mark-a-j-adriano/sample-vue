<?php

namespace App\Test;
use App\Code\DBConn;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testPDOConnection()
    {
        try {
            $pdo = new DBConn();
            $this->assertInstanceOf(PDO::class, $pdo->getConnection());
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
