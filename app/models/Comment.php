<?php 

class Comment {
    protected $conn;

    public function __construct(){
        global $conn;
        $this->conn = $conn;
    }

    public function get_all_by_discussion_id($discussion_id) {
        $query = "SELECT comments.*, users.username, users.user_id, users.profile_photo
                    FROM comments
                    JOIN users ON comments.user_id = users.user_id
                    WHERE discussion_id = ?";

        $run = $this->conn->prepare($query);
        $run->bind_param("i", $discussion_id);
        $run->execute();
        
        $result = $run->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        
    }


    public function create($discussion_id, $user_id, $content) {
        $query = "INSERT INTO comments (discussion_id, user_id, content) VALUES (?, ?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("iis", $discussion_id, $user_id, $content);
        $run->execute();

    }


}