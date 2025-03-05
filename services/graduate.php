<?php
require_once __DIR__ . './connection.php';
require_once __DIR__ . '/address.php';
require_once __DIR__ . '/alert.php';
class GraduatesService
{
    private $dbConnection;
    protected $addressService;
    protected $alertService;

    public function __construct()
    {
        $this->dbConnection = new DatabaseConnection();
        $this->addressService = new AddressService();
        $this->alertService = new AlertService();
    }

    // List all graduates
    public function list($batch_id = null, $college_id = null, $course_id = null, $keyword = null)
    {
        $pdo = $this->dbConnection->connect();
        $sql = "SELECT g.*, 
               b.name AS batch_name, 
               c.name AS college_name, c.code AS college_code, 
               cr.name AS course_name, cr.code AS course_code
        FROM graduates g
        LEFT JOIN batches b ON g.batch_id = b.id
        LEFT JOIN colleges c ON g.college_id = c.id
        LEFT JOIN courses cr ON g.course_id = cr.id";

        $conditions = [];
        $params = [];

        if ($batch_id && $batch_id != '*') {
            $conditions[] = "g.batch_id = :batch_id";
            $params[':batch_id'] = $batch_id;
        }
        if ($college_id && $college_id != '*') {
            $conditions[] = "g.college_id = :college_id";
            $params[':college_id'] = $college_id;
        }
        if ($course_id && $course_id != '*') {
            $conditions[] = "g.course_id = :course_id";
            $params[':course_id'] = $course_id;
        }
        if ($keyword) {
            $conditions[] = "(g.first_name LIKE :keyword OR g.middle_name LIKE :keyword OR g.last_name LIKE :keyword OR g.email LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Store a new graduate
    public function store(
        $batch_id,
        $first_name,
        $last_name,
        $middle_name,
        $email,
        $prefix,
        $suffix,
        $gender,
        $number,
        $address,
        $city_code,
        $city,
        $barangay_code,
        $barangay,
        $address_line1,
        $college_id,
        $course_id,
        $company,
        $position,
        $start_date,
        $end_date
    ) {
        $address_id = $this->addressService->store($city_code, $city, $barangay_code, $barangay, $address_line1);
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("INSERT INTO graduates (batch_id, prefix, suffix, first_name, middle_name, last_name, email,gender,number,address,address_id, college_id, course_id) 
                                    VALUES (:batch_id, :prefix, :suffix, :first_name, :middle_name, :last_name, :email, :gender, :number, :address, :address_id, :college_id, :course_id)");
            $stmt->bindParam(':batch_id', $batch_id);
            $stmt->bindParam(':prefix', $prefix);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':middle_name', $middle_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':number', $number);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':address_id', $address_id);
            $stmt->bindParam(':college_id', $college_id);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $id = $pdo->lastInsertId();

            if ($company != '') {
                $this->add_work_experience($id, $company, $position, $start_date, $end_date);
            }
            $this->alertService->set__("success", "Graduate information has been added successfully.");
            return true;
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }

    public function update(
        $id,
        $address_id,
        $batch_id,
        $first_name,
        $last_name,
        $middle_name,
        $email,
        $prefix,
        $suffix,
        $gender,
        $number,
        $address,
        $city_code,
        $city,
        $barangay_code,
        $barangay,
        $address_line1,
        $college_id,
        $course_id
    ) {
        $this->addressService->update($address_id, $city_code, $city, $barangay_code, $barangay, $address_line1);
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("UPDATE graduates SET batch_id = :batch_id, prefix = :prefix, suffix = :suffix, 
                                    first_name = :first_name, middle_name = :middle_name, last_name = :last_name, email = :email 
                                    , gender = :gender , number = :number , address = :address , college_id = :college_id , course_id = :course_id 
                                    , last_name = :last_name  
                                    WHERE id = :id");
            $stmt->bindParam(':batch_id', $batch_id);
            $stmt->bindParam(':prefix', $prefix);
            $stmt->bindParam(':suffix', $suffix);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':middle_name', $middle_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':number', $number);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':college_id', $college_id);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $this->alertService->set__("success", "Graduate record updated successfully.");
            return true;
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }

    public function add_work_experience($id, $company, $position, $start_date, $end_date)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = $end_date ? date('Y-m-d', strtotime($end_date)) : null;
            $stmt = $pdo->prepare("INSERT INTO work_experiences (graduate_id, company, position,start_date, end_date)
                                VALUES (:graduate_id, :company, :position, :start_date, :end_date)");
            $stmt->bindParam(':graduate_id', $id);
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':position', $position);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();

            $this->alertService->set__("success", "Work experience has been added successfully.");
            return true;
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }

    public function update_work_experience($id, $company, $position, $start_date, $end_date)
    {
        try {
            $pdo = $this->dbConnection->connect();

            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = !empty($end_date) ? date('Y-m-d', strtotime($end_date)) : null;

            $stmt = $pdo->prepare("UPDATE work_experiences SET company = :company, position = :position, start_date = :start_date, 
                                end_date = :end_date
                                WHERE id = :id");
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':position', $position);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($end_date === null) {
                $stmt->bindValue(':end_date', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':end_date', $end_date);
            }

            $stmt->execute();

            $this->alertService->set__("success", "Work experience has been updated successfully.");
            return true;
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }


    public function delete_work_experience($id)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("DELETE FROM work_experiences WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->alertService->set__("success", "Work experience deleted successfully.");
                return true;
            } else {
                $this->alertService->set__("error", "No record found with the provided ID.");
                return false;
            }
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }

    // Delete a graduate by ID
    public function destroy($id)
    {
        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("DELETE FROM graduates WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->alertService->set__("success", "Graduate record deleted successfully.");
                return true;
            } else {
                $this->alertService->set__("error", "No record found with the provided ID.");
                return false;
            }
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }

    // Find a graduate by ID
    public function find($id)
    {
        $pdo = $this->dbConnection->connect();

        // Fetch Graduate Details
        $stmt = $pdo->prepare("
            SELECT g.*, 
                b.name AS batch_name, 
                c.name AS college_name, c.code AS college_code, 
                cr.name AS course_name, cr.code AS course_code,
                a.*
            FROM graduates g
            LEFT JOIN batches b ON g.batch_id = b.id
            LEFT JOIN colleges c ON g.college_id = c.id
            LEFT JOIN courses cr ON g.course_id = cr.id
            LEFT JOIN address a ON g.address_id = a.id
            WHERE g.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $graduate = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$graduate) {
            return null; // No graduate found
        }

        // Fetch Work Experiences
        $stmt = $pdo->prepare("
            SELECT *
            FROM work_experiences
            WHERE graduate_id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $workExperiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Attach work experiences to graduate data
        $graduate['work_experiences'] = $workExperiences;

        return $graduate;
    }


    public function upload_profile($graduate_id, $file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return "Error: No file uploaded or file upload failed.";
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            return "Error: Invalid file type. Only JPG and PNG are allowed.";
        }

        if ($file['size'] > 100 * 1024 * 1024) {
            return "Error: File size exceeds 5MB.";
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = 'graduate_' . $graduate_id . '_' . time() . '.' . $ext;

        $uploadDir = __DIR__ . "../../assets/uploads/profiles/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . $newFileName;
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return "Error: Failed to save file.";
        }

        try {
            $pdo = $this->dbConnection->connect();
            $stmt = $pdo->prepare("UPDATE graduates SET profile_path = :profile_path WHERE id = :id");
            $stmt->bindParam(':profile_path', $newFileName);
            $stmt->bindParam(':id', $graduate_id, PDO::PARAM_INT);
            $stmt->execute();
            $this->alertService->set__("success", "User profile has been uploaded.");
            return true;
        } catch (PDOException $e) {
            $this->alertService->set__("error", $e->getMessage());
            return false;
        }
    }
}
