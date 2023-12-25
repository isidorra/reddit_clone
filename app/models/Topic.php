<?php 

require_once("app/config/config.php");

class Topic {
    protected $conn;

    public function __construct(){
        global $conn;
        $this->conn = $conn;
        
    }

    public function get_all() {
        $query = "SELECT * FROM topics ORDER BY name ASC";
        $run = $this->conn->query($query);
        return $run->fetch_all(MYSQLI_ASSOC);
    }

    
}