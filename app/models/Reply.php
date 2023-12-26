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

    public function like($reply_id, $user_id) {
        $query = "INSERT INTO reply_likes (reply_id, user_id) VALUES(?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $reply_id, $user_id);
        if ($run->execute()) {
            echo "Operation successful";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    public function remove_like($reply_id, $user_id) {
        $query = "DELETE FROM reply_likes WHERE reply_id=? AND user_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $reply_id, $user_id);
        if ($run->execute()) {
            echo "Operation successful";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    public function is_liked($reply_id, $user_id) {
        $query = "SELECT 1 FROM reply_likes WHERE reply_id = ? AND user_id = ? LIMIT 1";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $reply_id, $user_id);
        $run->execute();

        $run->store_result();
        $result = $run->num_rows > 0;

        return $result;
    }

    public function get_likes_number($reply_id) {
        $query = "SELECT COUNT(*) AS like_count FROM reply_likes WHERE reply_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $reply_id);
        $run->execute();
    
        $result = $run->get_result();
        $row = $result->fetch_assoc();
    
        return $row['like_count'];
    }

    public function delete_all_likes($reply_id) {
        $query = "DELETE FROM reply_likes WHERE reply_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $reply_id);
        $run->execute();
    }

    public function delete($reply_id) {

        //First delete all likes of the reply
        $reply = new Reply();
        $reply = $reply->delete_all_likes($reply_id);

        //Delete the reply itself
        $query = "DELETE FROM replies WHERE reply_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $reply_id);
        $run->execute();
    }




        
}