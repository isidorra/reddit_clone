<?php 

class Reply {

    protected $conn;

    public function __construct(){
        global $conn;
        $this->conn = $conn;
    }

    public function get_all_by_comment_id($comment_id) {
        $query = "SELECT replies.*, users.username, users.user_id, users.profile_photo
            FROM replies
            JOIN users ON replies.user_id = users.user_id
            WHERE comment_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();

        $result = $run->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($comment_id, $user_id, $content) {
        $query = "INSERT INTO replies (comment_id, user_id, content) VALUES (?, ?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("iis", $comment_id, $user_id, $content);
        $run->execute();
    }


        
}