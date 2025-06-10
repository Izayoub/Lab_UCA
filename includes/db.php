<?php
require_once 'config.php';

// Database connection class
class Database {
    private $conn;
    private static $instance;
    
    // Private constructor - singleton pattern
    private function __construct() {
        global $db_host, $db_user, $db_pass, $db_name;
        
        try {
            $this->conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8");
        } catch(PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            // Don't expose error details in production
            die("Une erreur est survenue lors de la connexion à la base de données.");
        }
    }
    
    // Get singleton instance
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    // Get database connection
    public function getConnection() {
        return $this->conn;
    }
    
    // Execute a query and return the result
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            error_log("Query failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Fetch all rows from a query
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
    
    // Fetch a single row from a query
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    }
    
    // Insert data into a table
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $stmt = $this->query($sql, array_values($data));
        return $stmt ? $this->conn->lastInsertId() : false;
    }
    
    // Update data in a table
    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "$column = ?";
        }
        $setClause = implode(', ', $set);
        
        $sql = "UPDATE $table SET $setClause WHERE $where";
        
        $params = array_merge(array_values($data), $whereParams);
        $stmt = $this->query($sql, $params);
        
        return $stmt ? $stmt->rowCount() : false;
    }
    
    // Delete data from a table
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->rowCount() : false;
    }
}

// Helper function to get database instance
function db() {
    return Database::getInstance();
}
?>