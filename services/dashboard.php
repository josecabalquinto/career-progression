<?php
require_once __DIR__ . './connection.php';
class DashboardService
{
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = new DatabaseConnection();
    }

    public function countGraduates($batch_id = null)
    {
        $pdo = $this->dbConnection->connect();
        $query = "SELECT COUNT(*) AS total FROM graduates";
        $params = [];

        if ($batch_id && $batch_id !== '*') {
            $query .= " WHERE batch_id = ?";
            $params[] = $batch_id;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function countEmployed($batch_id = null)
    {
        $pdo = $this->dbConnection->connect();
        $query = "
            SELECT COUNT(DISTINCT g.id) AS employed 
            FROM graduates g
            INNER JOIN work_experiences we ON g.id = we.graduate_id
            WHERE we.end_date IS NULL
        ";
        $params = [];

        if ($batch_id && $batch_id !== '*') {
            $query .= " AND g.batch_id = ?";
            $params[] = $batch_id;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['employed'];
    }


    public function countUnemployed()
    {
        return $this->countGraduates() - $this->countEmployed();
    }

    public function getCollegesStatistics($batch_id = null)
    {
        $pdo = $this->dbConnection->connect();

        $query = "
        SELECT c.name AS college,
               c.code AS code, 
               c.color AS color,
               COUNT(g.id) AS total_graduates
        FROM colleges c
        LEFT JOIN graduates g ON c.id = g.college_id
    ";

        $params = [];

        if ($batch_id && $batch_id !== '*') {
            $query .= " WHERE g.batch_id = ?";
            $params[] = $batch_id;
        }

        $query .= " GROUP BY c.name, c.code, c.color";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // public function getCollegesStatistics()
    // {
    //     $pdo = $this->dbConnection->connect();
    //     $stmt = $pdo->prepare("
    //     SELECT c.name AS college,
    //             c.code AS code, 
    //            c.color AS color,
    //            COUNT(g.id) AS total_graduates,
    //            SUM(CASE WHEN we.end_date IS NULL THEN 1 ELSE 0 END) AS employed
    //     FROM colleges c
    //     LEFT JOIN graduates g ON c.id = g.college_id
    //     LEFT JOIN work_experiences we ON g.id = we.graduate_id
    //     GROUP BY c.name, c.code, c.color
    // ");
    //     $stmt->execute();
    //     $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     foreach ($colleges as &$college) {
    //         $college['unemployed'] = $college['total_graduates'] - $college['employed'];
    //     }

    //     return $colleges;
    // }
}
