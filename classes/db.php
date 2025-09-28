<?php
class Database {
    private $host = "localhost";
    private $dbname = "apartment_db";
    private $username = "root"; 
    private $password = "";     
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }

    // Register tenant
    public function registerTenant($name, $email, $password) {
        $sql = "INSERT INTO tenants (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    // Tenant login
    public function loginTenant($email, $password) {
        $sql = "SELECT * FROM tenants WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":email" => $email]);
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

        if($tenant && password_verify($password, $tenant['password'])) {
            return $tenant;
        }
        return false;
    }

    // Admin login
    public function loginAdmin($email, $password) {
        $sql = "SELECT * FROM admins WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":email" => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    // Add new admin
    public function addAdmin($name, $email, $password) {
        $sql = "INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
?>
