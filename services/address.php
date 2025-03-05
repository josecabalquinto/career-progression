<?php
require_once __DIR__ . './connection.php';

class AddressService
{
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DatabaseConnection();
    }

    public function store($city_code, $city, $barangay_code, $barangay, $address_line1)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("INSERT INTO address (city_code, city, barangay_code, barangay, address_line1) 
                                    VALUES (:city_code, :city, :barangay_code, :barangay, :address_line1)");
            $stmt->bindParam(':city_code', $city_code);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':barangay_code', $barangay_code);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':address_line1', $address_line1);
            $stmt->execute();
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function update($id, $city_code, $city, $barangay_code, $barangay, $address_line1)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("UPDATE address SET city_code = :city_code,city = :city, barangay_code = :barangay_code,barangay = :barangay, address_line1 = :address_line1
                                    WHERE id = :id");
            $stmt->bindParam(':city_code', $city_code);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':barangay_code', $barangay_code);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':address_line1', $address_line1);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
