<?php 

require_once("app/models/Discussion.php");

class Discussion {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function get_all() {
        $query = "SELECT discussions.*, topics.name AS topic_name, users.username AS host_username, users.profile_photo
        FROM discussions
        JOIN topics ON discussions.topic_id = topics.topic_id
        JOIN users ON discussions.host_id = users.user_id
        ORDER BY discussions.created_at DESC";

        $run = $this->conn->query($query);
        return $run->fetch_all(MYSQLI_ASSOC);
    }

    public function create($user_id, $subject, $topic_id) {
        $query = "INSERT INTO discussions (subject, host_id, topic_id) VALUES (?, ?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("sii", $subject, $user_id, $topic_id);
        $run->execute();
    }

}