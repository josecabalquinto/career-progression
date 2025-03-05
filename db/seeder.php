<?php
require_once __DIR__ . '/../services/connection.php';

class UserSeeder
{
    private $connection;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'password' => password_hash('admin2025', PASSWORD_BCRYPT),
                'fname'    => 'Admin',
                'lname'    => 'User'
            ],
            [
                'username' => 'staff1',
                'password' => password_hash('staff2025', PASSWORD_BCRYPT),
                'fname'    => 'Staff',
                'lname'    => 'User'
            ],
        ];

        foreach ($users as $user) {
            $this->seedUser($user['username'], $user['password'], $user['fname'], $user['lname']);
        }
    }

    private function seedUser($username, $password, $fname, $lname)
    {
        try {
            $stmt = $this->connection->prepare("
                INSERT INTO users (username, password, fname, lname) 
                VALUES (:username, :password, :fname, :lname)
            ");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->execute();
            echo "Seeded user: $username\n";
        } catch (PDOException $e) {
            echo "Failed to seed user $username: " . $e->getMessage() . "\n";
        }
    }
}

// Run the seeder
$seeder = new UserSeeder();
$seeder->run();
