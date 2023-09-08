<?php

namespace App\Code;

use App\Code\DBConn;

class Employee
{
    private int $id = 0;
    private string $name = '';
    private string $email = '';
    private int $companyID = 0;
    private $salary;

    /**
     * Constructicons unite. Devastator wipe them all!
     *
     * @param string $name
     * @param string $email
     * @param int $companyID
     * @param int $salary
     * @return void
     */
    public function __construct($name = '', $email = '', $companyID = 0, $salary = 0)
    {
        $this->name = $name;
        $this->email = $email;
        $this->salary = $salary;
        $this->companyID = $companyID;
    }

    /**
     * Write the employee record
     * @return int
     */
    public function write(): int
    {
        $db = new DBConn();

        $sql = "INSERT INTO employees (employee_name, email_address, company_id, salary)
            VALUES (:employee_name, :email_address, :company_id, :salary)";

        $id = $db->processStatement(
            $sql,
            [
                'employee_name' => $this->name,
                'email_address' => $this->email,
                'company_id' => $this->companyID,
                'salary' => $this->salary,
            ],
            true
        );

        $this->setId($id);

        return $id;
    }

    /**
     * Update the employee record
     * @return int
     */
    public function updateEmail(): int
    {
        $db = new DBConn();

        $sql = "UPDATE employees SET email_address=:email_address WHERE id=:employee_id";

        $id = $db->processStatement(
            $sql,
            [
                'email_address' => $this->getEmail(),
                'employee_id' => $this->getId(),
            ]
        );

        return $id;
    }

    /**
     * Setter for ID
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
     * Setter for email
     * @param mixed $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Getter for Email
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
}
