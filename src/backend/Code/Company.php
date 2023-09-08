<?php

namespace App\Code;

use App\Code\DBConn;

class Company
{
    private int $id = 0;

    private string $name = '';

    /**
     * Constructicons unite. Devastator wipe them all!
     * @param mixed $name
     * @return void
     */
    public function __construct($name = '')
    {
        $this->name = $name;
    }

    /**
     * Write the company record
     * @return int
     */
    public function write($returnID = true): int
    {
        $db = new DBConn();
        $id = $db->processStatement(
            'INSERT INTO companies (name) VALUES (:name)',
            ['name' => $this->name],
            $returnID
        );

        if ($returnID) {
            $this->setId($id);
        }

        return $id;
    }

    /**
     * Setter for the ID
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for ID
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Get the list of Employees
     * @return string|false|array|void
     */
    public function getEmployees()
    {
        $db = new DBConn();
        return $db->processStatement(
            'SELECT * FROM employees WHERE company_id=:companyID',
            ['companyID' => $this->getId()],
            false,
            true
        );
    }

    /**
     * Check if the company already exist
     * @return string|false|array|void
     */
    public function exist()
    {
        $db = new DBConn();
        return $db->processStatement(
            'SELECT * FROM companies WHERE name=:companyName',
            ['companyName' => $this->name],
            true
        );
    }

    /**
     * Get the average salary of Employees
     * @return string|false|array|void
     */
    public function getAverageSalary()
    {
        $db = new DBConn();
        return $db->processStatement(
            'SELECT AVG(salary) as salary FROM employees WHERE company_id=:companyID',
            ['companyID' => $this->getId()],
            false,
            true
        );
    }
}
