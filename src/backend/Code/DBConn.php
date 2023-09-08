<?php

namespace App\Code;

use PDO;
use PDOException;

class DBConn
{
    private $pdo;

    /**
     * Constructicons unite. Devastator wipe them all!
     * @return void
     */
    public function __construct()
    {
        $vars = $this->getEnvironmentVars();

        if (empty($vars)) {
            die("Database connection failed: Environment variables not set.");
        }

        // Access the variables
        $host = $vars['DB_SERVER'];
        $port = $vars['DB_LOCAL_PORT'];
        $dbname = $vars['DB_NAME'];
        $username = $vars['DB_USER'];
        $password = $vars['DB_PASSWORD'];

        try {
            $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get PDO connection
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     *  Prepare Employee and Company tables used by Import
     * @return bool
     */
    public function prepareTables()
    {
        $success = false;

        $queries = [
            "CREATE TABLE IF NOT EXISTS `companies` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`)
                )",
            "CREATE TABLE IF NOT EXISTS `employees` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `employee_name` varchar(255) NOT NULL,
                    `email_address` varchar(255) NOT NULL,
                    `company_id` int NOT NULL,
                    `salary` decimal(10,0) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `company_id` (`company_id`),
                    CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
                )",
        ];

        foreach ($queries as $query) {
            try {
                $stmt =  $this->pdo->prepare($query);
                $success = $stmt->execute();
            } catch (PDOException $e) {
                die("Table creation failed: " . $e->getMessage());
                $success = false;
            }
        }

        return $success;
    }

    /**
     *  Destroy Employee and Company tables used by Import
     * @return bool
     */
    public function truncateTables()
    {
        $success = false;
        $queries = [
            "SET FOREIGN_KEY_CHECKS=0",
            "TRUNCATE TABLE `companies`",
            "TRUNCATE TABLE `employees`",
            "SET FOREIGN_KEY_CHECKS=1",
        ];

        foreach ($queries as $query) {
            try {
                $stmt =  $this->pdo->prepare($query);
                $success = $stmt->execute();
            } catch (PDOException $e) {
                die("Table destroy failed: " . $e->getMessage());
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Process an SQL Query
     *
     * @param string $query
     * @param array $values
     * @param bool $retID
     * @return string|false|array|void
     */
    public function processStatement($query = '', $values = [], $retID = false, $retRows = false)
    {
        $success = false;
        $stmt =  $this->pdo->prepare($query);
        try {
            $success = $stmt->execute($values);
        } catch (PDOException $e) {
            die("Statement failed: " . $e->getMessage());
        } finally {
            if ($success) {
                if ($retID) return $this->pdo->lastInsertId();
                if ($retRows) return $stmt->fetchAll();
            }

            return $success;
        }
    }

    /**
     * Get Environment details from `.env` file
     * @return array
     */
    public function getEnvironmentVars()
    {
        $envVariables = [];
        $envContents = file('../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($envContents === false) {
            die('.env file not found or could not be read.');
        }

        foreach ($envContents as $line) {
            list($key, $value) = explode('=', $line, 2);
            $envVariables[$key] = $value;
        }

        return $envVariables;
    }
}
