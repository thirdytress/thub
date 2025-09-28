<?php
class Database {
    private $host = "localhost";
    private $db_name = "apartment_db";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }

    // TENANT FUNCTIONS
    public function registerTenant($fname, $lname, $email, $phone, $password) {
        $sql = "INSERT INTO Tenants (FirstName, LastName, Email, PhoneNumber, TenantPass) VALUES (:fname, :lname, :email, :phone, :password)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":fname", $fname);
        $stmt->bindParam(":lname", $lname);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":password", password_hash($password, PASSWORD_DEFAULT));
        return $stmt->execute();
    }

    public function loginTenant($email, $password) {
        $sql = "SELECT * FROM Tenants WHERE Email = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
        if($tenant && password_verify($password, $tenant['TenantPass'])) {
            return $tenant;
        }
        return false;
    }

    // OWNER FUNCTIONS
    public function loginOwner($email, $password) {
        $sql = "SELECT * FROM Owner WHERE Email = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $owner = $stmt->fetch(PDO::FETCH_ASSOC);
        if($owner && password_verify($password, $owner['OwnerPass'])) {
            return $owner;
        }
        return false;
    }

    // APARTMENTS
    public function getApartments() {
        $sql = "SELECT * FROM Apartments";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addApartment($ownerID, $building, $rent, $bed, $bath, $unit, $street, $city, $prov) {
        $sql = "INSERT INTO Apartments (OwnerID, BuildingName, RentAmount, Bedrooms, Bathrooms, UnitNumber, Apt_Street, Apt_City, Apt_Prov) VALUES (:owner, :building, :rent, :bed, :bath, :unit, :street, :city, :prov)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":owner", $ownerID);
        $stmt->bindParam(":building", $building);
        $stmt->bindParam(":rent", $rent);
        $stmt->bindParam(":bed", $bed);
        $stmt->bindParam(":bath", $bath);
        $stmt->bindParam(":unit", $unit);
        $stmt->bindParam(":street", $street);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":prov", $prov);
        return $stmt->execute();
    }

    // LEASES
    public function createLease($tenantID, $apartmentID, $start, $end, $monthly, $deposit) {
        $sql = "INSERT INTO Leases (TenantID, ApartmentID, StartDate, EndDate, MonthlyRent, DepositAmount) VALUES (:tenant, :apt, :start, :end, :monthly, :deposit)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":tenant", $tenantID);
        $stmt->bindParam(":apt", $apartmentID);
        $stmt->bindParam(":start", $start);
        $stmt->bindParam(":end", $end);
        $stmt->bindParam(":monthly", $monthly);
        $stmt->bindParam(":deposit", $deposit);
        return $stmt->execute();
    }

    // PAYMENTS
    public function recordPayment($leaseID, $pay_date, $amount, $method) {
        $sql = "INSERT INTO Payment (LeasesID, Pay_Date, Amount, Pay_Method) VALUES (:lease, :date, :amount, :method)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":lease", $leaseID);
        $stmt->bindParam(":date", $pay_date);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":method", $method);
        return $stmt->execute();
    }

    // MAINTENANCE REQUEST
    public function addRequest($tenantID, $apartmentID, $requestDate, $desc) {
        $sql = "INSERT INTO MaintenanceRequest (TenantID, ApartmentID, RequestDate, Description) VALUES (:tenant, :apt, :date, :desc)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":tenant", $tenantID);
        $stmt->bindParam(":apt", $apartmentID);
        $stmt->bindParam(":date", $requestDate);
        $stmt->bindParam(":desc", $desc);
        return $stmt->execute();
    }

    // UTILITIES
    public function updateUtilities($aptID, $water, $electric, $internet) {
        $sql = "INSERT INTO Utilities (ApartmentID, WaterBill, ElectricityBill, InternetBill) VALUES (:apt, :water, :electric, :internet) ON DUPLICATE KEY UPDATE WaterBill=:water, ElectricityBill=:electric, InternetBill=:internet";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":apt", $aptID);
        $stmt->bindParam(":water", $water);
        $stmt->bindParam(":electric", $electric);
        $stmt->bindParam(":internet", $internet);
        return $stmt->execute();
    }

    // PARKING
    public function assignParking($tenantID, $spot) {
        $sql = "INSERT INTO ParkingSpaces (TenantID, SpotNumber) VALUES (:tenant, :spot)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":tenant", $tenantID);
        $stmt->bindParam(":spot", $spot);
        return $stmt->execute();
    }

    // APARTMENT AVAILABILITY
    public function setAvailability($aptID, $start, $end, $status) {
        $sql = "INSERT INTO ApartmentAvailability (ApartmentID, Start_Date, End_Date, Status) VALUES (:apt, :start, :end, :status)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":apt", $aptID);
        $stmt->bindParam(":start", $start);
        $stmt->bindParam(":end", $end);
        $stmt->bindParam(":status", $status);
        return $stmt->execute();
    }
}
?>
