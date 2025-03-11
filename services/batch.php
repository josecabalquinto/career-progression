<?php
require_once '../../services/connection.php';

class BatchService
{
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DatabaseConnection();
    }

    public function list()
    {
        $pdo = $this->dbConnection->connect();
        $stmt = $pdo->prepare("SELECT * FROM batches ORDER BY graduation_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function store($name, $graduation_date)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("INSERT INTO batches (name, graduation_date) VALUES (:name, :graduation_date)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':graduation_date', $graduation_date);
            $stmt->execute();
            return "Batch record added successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("DELETE FROM batches WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return "Batch record deleted successfully.";
            } else {
                return "No record found with the provided ID.";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function update($id, $name, $graduation_date)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("UPDATE batches SET name = :name, graduation_date = :graduation_date WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':graduation_date', $graduation_date);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "Batch record updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function find($id)
    {
        $pdo = $this->dbConnection->connect();
        $stmt = $pdo->prepare("SELECT * FROM batches WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
