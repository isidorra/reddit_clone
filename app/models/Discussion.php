<?php 

require_once("app/models/Discussion.php");

class Discussion {
    protected $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function get_all() {
        $query = "SELECT discussions.*, topics.name AS topic_name, users.username AS host_username, users.user_id AS host_id, users.profile_photo
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

    public function get_by_id($discussion_id) {
        $query = "SELECT discussions.*, topics.name AS topic_name, users.username AS host_username, users.user_id AS host_id, users.profile_photo
                    FROM discussions
                    JOIN topics ON discussions.topic_id = topics.topic_id
                    JOIN users ON discussions.host_id = users.user_id
                    WHERE discussion_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $discussion_id);
        $run->execute();

        $result = $run->get_result();
        return $result->fetch_assoc();

    }

    public function get_by_host($user_id) {
        $query = "SELECT discussions.*, topics.name AS topic_name
                FROM discussions
                JOIN topics ON discussions.topic_id = topics.topic_id
                WHERE host_id = ?
                ORDER BY discussions.created_at DESC";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $user_id);
        $run->execute();

        $results = $run->get_result();
        return $results->fetch_all(MYSQLI_ASSOC);
    }

    public function get_all_by_topic($topic_id) {
        $query = "SELECT discussions.*, topics.name AS topic_name, users.username AS host_username, users.user_id AS host_id, users.profile_photo
        FROM discussions
        JOIN topics ON discussions.topic_id = topics.topic_id
        JOIN users ON discussions.host_id = users.user_id
        WHERE discussions.topic_id = ?
        ORDER BY discussions.created_at DESC";

        $run = $this->conn->prepare($query);
        $run->bind_param("i", $topic_id);
        $run->execute();

        $result = $run->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function search($input) {

        $query = "SELECT discussions.*, topics.name AS topic_name, users.username AS host_username, users.user_id AS host_id, users.profile_photo
        FROM discussions
        JOIN topics ON discussions.topic_id = topics.topic_id
        JOIN users ON discussions.host_id = users.user_id
        WHERE subject LIKE ?
        ORDER BY discussions.created_at DESC";

        $run = $this->conn->prepare($query);
        $search_term = "%" . $input . "%";
        $run->bind_param("s", $search_term);
        $run->execute();

        $result = $run->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);

    }





}