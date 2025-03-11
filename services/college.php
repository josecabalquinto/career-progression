<?php
require_once '../../services/connection.php';

class CollegeService
{
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DatabaseConnection();
    }

    public function list()
    {
        $pdo = $this->dbConnection->connect();
        $stmt = $pdo->prepare("SELECT * FROM colleges");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function store($name, $code, $color)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("INSERT INTO colleges (name, code, color) VALUES (:name, :code, :color)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':color', $color);
            $stmt->execute();
            return "College record added successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("DELETE FROM colleges WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return "College record deleted successfully.";
            } else {
                return "No record found with the provided ID.";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function update($id, $name, $code, $color)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("UPDATE colleges SET name = :name, code = :code, color = :color WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':color', $color);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "College record updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function find($id)
    {
        $pdo = $this->dbConnection->connect();
        $stmt = $pdo->prepare("SELECT * FROM colleges WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function show_courses($college_id = null)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $sql = "SELECT * FROM courses";

            if (!is_null($college_id)) {
                $sql .= " WHERE college_id = :college_id";
            }

            $stmt = $pdo->prepare($sql);

            if (!is_null($college_id)) {
                $stmt->bindParam(':college_id', $college_id, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function store_course($college_id, $name, $code, $description)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("INSERT INTO courses (college_id, name, code, description) VALUES (:college_id, :name, :code, :description)");
            $stmt->bindParam(':college_id', $college_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            return "Course record added successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function update_course($id, $name, $code, $description)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("UPDATE courses SET name = :name, code = :code, description = :description WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "Course record updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function destroy_course($id)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return "Course record deleted successfully.";
            } else {
                return "No record found with the provided ID.";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
