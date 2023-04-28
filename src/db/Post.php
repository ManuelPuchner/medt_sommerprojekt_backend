<?php

namespace db;


use DateTime;
use Exception;
use JsonSerializable;
use mysqli_result;

class Post implements JsonSerializable
{
    private int $id;
    private string $image;
    private string $description;
    private DateTime $date;
    private int $userId;


    private ?bool $isLikedByUser;

    private ?int $likeCount;

    private ?array $likes;

    private ?array $comments;

    private ?User $user;

    private ?bool $isPostedByUser;

    public function __construct(int $id, string $image, string $description, DateTime $date, int $userId)
    {
        $this->id = $id;
        $this->image = $image;
        $this->description = $description;
        $this->date = $date;
        $this->userId = $userId;

        $this->likes = null;
        $this->comments = null;
        $this->user = null;
        $this->isPostedByUser = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param bool|mysqli_result $result
     * @param array $include
     * @return array
     */
    public static function extracted(bool|mysqli_result $result, array $include): array
    {
        $posts = array();
        while ($row = $result->fetch_assoc()) {
            $post = Post::getPostFromRow($row);
            if (in_array("likes", $include)) {
                $post->likes = $post->getLikes();
            }
            if (in_array("likeCount", $include)) {
                $post->likeCount = $post->getLikeCount();
            }
            if (in_array("comments", $include)) {
                $post->comments = $post->getComments();
            }
            if (in_array("user", $include)) {
                $post->user = $post->getUser();
            }

            if (in_array("isLikedByUser", $include)) {
                $post->isLikedByUser = $post->isLikedByUser();
            }

            if (in_array("isPostedByUser", $include)) {
                $post->isPostedByUser = $post->isPostedByUser();
            }

            $posts[] = $post;
        }
        return $posts;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUser(): User
    {
        if ($this->user == null) {
            $this->user = User::getById($this->userId);
        }
        return $this->user;
    }

    public function getComments(): array
    {
        if ($this->comments == null) {
            $includeUser = true;
            $this->comments = Comment::getByPostId($this->id, $includeUser);
        }
        return $this->comments;
    }

    public static function create(string $image, string $description, DateTime $date, int $userId): Post
    {
        $db = DB::getInstance();

        $stmt = $db->getConnection()->prepare("INSERT INTO HL_Post (p_image, p_description, p_date, p_u_id) VALUES (?, ?, ?, ?)");
        $dateFormatted = $date->format("Y-m-d H:i:s");
        $stmt->bind_param("sssi", $image, $description, $dateFormatted, $userId);
        $stmt->execute();
        return new Post($db->getConnection()->insert_id, $image, $description, $date, $userId);
    }

    public static function delete(int $id): void
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Post WHERE p_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function update(int $id, string $image, string $description, DateTime $date, int $userId): Post
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("UPDATE HL_Post SET p_image = ?, p_description = ?, p_date = ?, p_u_id = ? WHERE p_id = ?");
        $stmt->bind_param("sssii", $image, $description, $date, $userId, $id);
        $stmt->execute();
        return new Post($id, $image, $description, $date, $userId);
    }

    public static function getById(int $id, array $include = []): ?Post
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Post WHERE p_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row == null) {
            return null;
        }
        $post = Post::getPostFromRow($row);
        if (in_array("likes", $include)) {
            $post->getLikes();
        }

        if (in_array("comments", $include)) {
            $post->getComments();
        }
        if (in_array("user", $include)) {
            $post->getUser();
        }

        return $post;
    }

    public static function getByUserId(int $userId): array
    {
        echo "\n" . $userId . "\n";
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Post WHERE p_u_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return self::extracted($result, []);
    }

    public static function getAllPaginated(int $page, int $length, array $include = []): array
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Post order by p_date desc LIMIT ? offset ?");
        $endIndex = ($page - 1) * $length;
        $stmt->bind_param("ii", $length, $endIndex);
        $stmt->execute();
        $result = $stmt->get_result();
        return self::extracted($result, $include);
    }

    public static function getAll(array $includeFields = []): array
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Post order by p_date desc");
        $stmt->execute();
        $result = $stmt->get_result();
        return self::extracted($result, $includeFields);
    }

    public function getLikeCount(): int
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT COUNT(*) LIKE_COUNT FROM HL_Like WHERE l_p_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['LIKE_COUNT'];
    }

    public function getLikes(): array
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Like WHERE l_p_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $likes = array();
        while ($row = $result->fetch_assoc()) {
            $likes[] = Like::getLikeFromRow($row);
        }
        $this->likes = $likes;
        return $likes;
    }

    public function toggleLike(int $userId): bool
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Like WHERE l_p_id = ? AND l_u_id = ?");
        $stmt->bind_param("ii", $this->id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row == null) {
            Like::create($this->id, $userId);
            return true;
        } else {
            Like::delete($row['l_id']);
            return false;
        }
    }

    public function jsonSerialize(): array
    {
        $serialized = [
            'id' => $this->id,
            'image' => $this->image,
            'description' => $this->description,
            'date' => $this->date->format("Y-m-d H:i:s"),
            'userId' => $this->userId
        ];

        if (isset($this->likes)) {
            $serialized['likes'] = $this->likes;
        }

        if (isset($this->comments)) {
            $serialized['comments'] = $this->comments;
        }

        if (isset($this->user)) {
            $serialized['user'] = $this->user;
        }

        if (isset($this->likeCount)) {
            $serialized['likeCount'] = $this->likeCount;
        }

        if (isset($this->isLikedByUser)) {
            $serialized['isLikedByUser'] = $this->isLikedByUser;
        }

        if (isset($this->isPostedByUser)) {
            $serialized['isPostedByUser'] = $this->isPostedByUser;
        }

        return $serialized;
    }

    private static function getPostFromRow($row): ?Post
    {
        try {
            return new Post($row['p_id'], $row['p_image'], $row['p_description'], new DateTime($row['p_date']), $row['p_u_id']);
        } catch (Exception $e) {

        }
        return null;
    }

    private function isLikedByUser(): bool
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Like WHERE l_p_id = ? AND l_u_id = ?");

        $userId = unserialize($_SESSION['user'])->getId();
        $stmt->bind_param("ii", $this->id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row == null) {
            return false;
        }
        return true;
    }

    private function isPostedByUser(): bool
    {
        $userId = unserialize($_SESSION['user'])->getId();
        return $this->userId == $userId;
    }
}