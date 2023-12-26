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

    public function has_replies($comment_id) {
        $query = "SELECT * FROM replies WHERE comment_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();

        $result = $run->get_result();
        $results = $result->fetch_all(MYSQLI_ASSOC);

        return count($results);
    }

    public function like($comment_id, $user_id) {
        $query = "INSERT INTO comment_likes (comment_id, user_id) VALUES(?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $comment_id, $user_id);
        if ($run->execute()) {
            echo "Operation successful";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    public function remove_like($comment_id, $user_id) {
        $query = "DELETE FROM comment_likes WHERE comment_id=? AND user_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $comment_id, $user_id);
        if ($run->execute()) {
            echo "Operation successful";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    public function is_liked($comment_id, $user_id) {
        $query = "SELECT 1 FROM comment_likes WHERE comment_id = ? AND user_id = ? LIMIT 1";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $comment_id, $user_id);
        $run->execute();

        $run->store_result();
        $result = $run->num_rows > 0;

        return $result;
    }

    public function get_likes_number($comment_id) {
        $query = "SELECT COUNT(*) AS like_count FROM comment_likes WHERE comment_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();
    
        $result = $run->get_result();
        $row = $result->fetch_assoc();
    
        return $row['like_count'];
    }



}