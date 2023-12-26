<?php 

require_once("app/models/Reply.php");

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


    public function delete_all_likes($comment_id) {
        $query = "DELETE FROM comment_likes WHERE comment_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();
        
    }

    public function delete($comment_id) {
        // Delete comment likes
        $this->delete_all_likes($comment_id);
    
        // Delete replies and their likes
        $this->delete_all_replies($comment_id);
    
        // Delete the comment itself
        $query = "DELETE FROM comments WHERE comment_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();
    }
    
    public function delete_all_replies($comment_id) {
        // Get all reply_ids associated with the comment
        $reply_ids = $this->get_reply_ids_by_comment($comment_id);
    
        // Delete reply likes for each reply
        foreach ($reply_ids as $reply_id) {
            $this->delete_reply_likes($reply_id);
        }
    
        // Delete replies
        $query = "DELETE FROM replies WHERE comment_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();
    }
    
    public function delete_reply_likes($reply_id) {
        $query = "DELETE FROM reply_likes WHERE reply_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $reply_id);
        $run->execute();
    }
    
    public function get_reply_ids_by_comment($comment_id) {
        $query = "SELECT reply_id FROM replies WHERE comment_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $comment_id);
        $run->execute();
        $result = $run->get_result();
        
        $reply_ids = array();
        while ($row = $result->fetch_assoc()) {
            $reply_ids[] = $row['reply_id'];
        }
    
        return $reply_ids;
    }



}