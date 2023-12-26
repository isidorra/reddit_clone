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

    public function get_comments_number($discussion_id) {
        $query = "SELECT
                        c.comment_id,
                        COUNT(DISTINCT c.comment_id) + COUNT(r.reply_id) AS total_count
                    FROM
                        comments c
                    LEFT JOIN
                        replies r ON c.comment_id = r.comment_id
                    WHERE
                        c.discussion_id = ?
                    GROUP BY
                        c.comment_id";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $discussion_id);
        $run->execute();

        $result = $run->get_result();
        return $result->fetch_assoc();

    }

    public function get_participants_number($discussion_id) {
        $query = "SELECT COUNT(DISTINCT p.user_id) AS unique_participants
                    FROM (
                        SELECT c.user_id FROM comments c WHERE c.discussion_id = ?
                        UNION
                        SELECT r.user_id FROM replies r
                        INNER JOIN comments c ON r.comment_id = c.comment_id
                        WHERE c.discussion_id = ?
                    ) AS p";

        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $discussion_id, $discussion_id);
        $run->execute();

        $result = $run->get_result();
        $count = $result->fetch_assoc();

        return $count['unique_participants'];
    }

    public function like($discussion_id, $user_id) {
        $query = "INSERT INTO discussion_likes (discussion_id, user_id) VALUES(?, ?)";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $discussion_id, $user_id);
        if ($run->execute()) {
            echo "Operation successful";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    public function remove_like($discussion_id, $user_id) {
        $query = "DELETE FROM discussion_likes WHERE discussion_id=? AND user_id=?";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $discussion_id, $user_id);
        if ($run->execute()) {
            echo "Operation successful";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    public function is_liked($discussion_id, $user_id) {
        $query = "SELECT 1 FROM discussion_likes WHERE discussion_id = ? AND user_id = ? LIMIT 1";
        $run = $this->conn->prepare($query);
        $run->bind_param("ii", $discussion_id, $user_id);
        $run->execute();

        $run->store_result();
        $result = $run->num_rows > 0;

        return $result;
    }

    public function get_likes_number($discussion_id) {
        $query = "SELECT COUNT(*) AS like_count FROM discussion_likes WHERE discussion_id = ?";
        $run = $this->conn->prepare($query);
        $run->bind_param("i", $discussion_id);
        $run->execute();
    
        $result = $run->get_result();
        $row = $result->fetch_assoc();
    
        return $row['like_count'];
    }
    


}

?>