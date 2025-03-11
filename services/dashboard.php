<?php
require_once '../../services/connection.php';
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
        SELECT c.id AS college_id,
               c.name AS college,
               c.code AS code, 
               c.color AS color,
               COUNT(g.id) AS total_graduates
        FROM colleges c
        LEFT JOIN graduates g ON c.id = g.college_id
    ";

        $params = [];

        if ($batch_id && $batch_id !== '*') {
            $query .= " AND g.batch_id = ?";
            $params[] = $batch_id;
        }

        $query .= " GROUP BY c.id, c.name, c.code, c.color";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getEmployedByCollege($batch_id = null)
    {
        $pdo = $this->dbConnection->connect();
        $query = "SELECT id, code, name FROM colleges";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $index => $row) {
            $query = "SELECT COUNT(DISTINCT g.id) AS total
                  FROM graduates g
                  JOIN work_experiences w ON w.graduate_id = g.id
                  WHERE g.college_id = :college_id
                  AND w.end_date IS NULL";

            if ($batch_id !== null) {
                $query .= " AND g.batch_id = :batch_id";
            }

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':college_id', $row['id'], PDO::PARAM_INT);

            if ($batch_id !== null) {
                $stmt->bindParam(':batch_id', $batch_id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC);
            $res[$index]['total'] = $total['total'];
        }
        return $res;
    }


    public function getUnemployedByCollege($batch_id = null)
    {
        $pdo = $this->dbConnection->connect();
        $query = "SELECT id, code, name FROM colleges";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $index => $row) {
            $query = "SELECT COUNT(DISTINCT id) AS total
                  FROM graduates
                  WHERE college_id = :college_id";
            if ($batch_id !== null) {
                $query .= " AND batch_id = :batch_id";
            }
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':college_id', $row['id'], PDO::PARAM_INT);
            if ($batch_id !== null) {
                $stmt->bindParam(':batch_id', $batch_id, PDO::PARAM_INT);
            }
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT COUNT(DISTINCT g.id) AS total
                  FROM graduates g
                  JOIN work_experiences w ON w.graduate_id = g.id
                  WHERE g.college_id = :college_id
                  AND w.end_date IS NULL";

            if ($batch_id !== null) {
                $query .= " AND g.batch_id = :batch_id";
            }

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':college_id', $row['id'], PDO::PARAM_INT);

            if ($batch_id !== null) {
                $stmt->bindParam(':batch_id', $batch_id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $employed = $stmt->fetch(PDO::FETCH_ASSOC);
            $res[$index]['total'] = $total['total'] - $employed['total'];
        }
        return $res;
    }

    public function getUnemployedByCollegeByCourse($batch_id = null)
    {
        $pdo = $this->dbConnection->connect();

        // Fetch all colleges and courses
        $query = "SELECT 
                c.id AS college_id,
                c.name AS college_name,
                c.code AS college_code,
                c.color AS color,
                co.id AS course_id,
                co.name AS course_name,
                co.code AS course_code
              FROM colleges c
              LEFT JOIN courses co ON co.college_id = c.id
              ORDER BY c.name, co.name";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $colleges = [];
        foreach ($res as $row) {
            $collegeId = $row['college_id'];

            if (!isset($colleges[$collegeId])) {
                $colleges[$collegeId] = [
                    'id' => $row['college_id'],
                    'name' => $row['college_name'],
                    'code' => $row['college_code'],
                    'color' => $row['color'],
                    'courses' => []
                ];
            }

            if (!empty($row['course_id'])) {
                $colleges[$collegeId]['courses'][$row['course_id']] = [
                    'id' => $row['course_id'],
                    'name' => $row['course_name'],
                    'code' => $row['course_code'],
                    'total_unemployed' => 0,
                    'total_employed' => 0
                ];
            }
        }

        foreach ($colleges as &$college) {
            foreach ($college['courses'] as &$course) {
                $query = "SELECT COUNT(DISTINCT g.id) AS total
                      FROM graduates g
                      LEFT JOIN work_experiences w ON w.graduate_id = g.id
                      WHERE g.course_id = :course_id
                      AND (w.end_date IS NULL)";
                if ($batch_id !== null) {
                    $query .= " AND g.batch_id = :batch_id";
                }
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':course_id', $course['id'], PDO::PARAM_INT);
                if ($batch_id !== null) {
                    $stmt->bindParam(':batch_id', $batch_id, PDO::PARAM_INT);
                }
                $stmt->execute();
                $employed = $stmt->fetch(PDO::FETCH_ASSOC);

                $query = "SELECT COUNT(DISTINCT g.id) AS total
                      FROM graduates g
                      LEFT JOIN work_experiences w ON w.graduate_id = g.id
                      WHERE g.course_id = :course_id";
                if ($batch_id !== null) {
                    $query .= " AND g.batch_id = :batch_id";
                }
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':course_id', $course['id'], PDO::PARAM_INT);
                if ($batch_id !== null) {
                    $stmt->bindParam(':batch_id', $batch_id, PDO::PARAM_INT);
                }
                $stmt->execute();
                $all = $stmt->fetch(PDO::FETCH_ASSOC);

                $course['total_unemployed'] = $employed['total'] - $all['total'] ?? 0;
                $course['total_employed'] = $employed['total'] ?? 0;
            }
        }

        return array_values($colleges); // Return structured array
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
