<?php

//create table HL_Comment (
//                            c_id int not null auto_increment,
//                            c_text varchar(255) not null,
//                            c_date date not null,
//                            c_p_id int not null,
//                            c_u_id int not null,
//                            constraint c_PK primary key (c_id),
//                            constraint c_p_FK foreign key (c_p_id) references HL_Post(p_id),
//                            constraint c_u_FK foreign key (c_u_id) references HL_User(u_id)
//);

class Comment implements JsonSerializable
{
    private int $id;
    private string $text;
    private DateTime $date;
    private int $postId;
    private int $userId;

    private ?User $user;

    public function __construct(int $id, string $text, DateTime $date, int $postId, int $userId)
    {
        $this->id = $id;
        $this->text = $text;
        $this->date = $date;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public static function getByPostId(int $id, bool $includeUser): array
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Comment WHERE c_p_id = ? order by c_date desc");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comment = new Comment($row['c_id'], $row['c_text'], new DateTime($row['c_date']), $row['c_p_id'], $row['c_u_id']);
            if($includeUser) {
                $comment->user = User::getById($row['c_u_id']);
            }
            $comments[] = $comment;
        }
        return $comments;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getPost(): Post
    {
        return Post::getById($this->postId);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUser(): User
    {
        return User::getById($this->userId);
    }

    public static function create(string $text, DateTime $date, int $postId, int $userId, bool $includeUser): Comment
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_Comment (c_text, c_date, c_p_id, c_u_id) VALUES (?, ?, ?, ?)");
        $formattedDateTime = $date->format('Y-m-d H:i:s');
        $stmt->bind_param("ssii", $text, $formattedDateTime, $postId, $userId);
        $stmt->execute();
        $comment = new Comment($db->getConnection()->insert_id, $text, $date, $postId, $userId);
        if($includeUser) {
            $comment->user = User::getById($userId);
        }
        return $comment;
    }

    public static function update(int $id, string $text, DateTime $date, int $postId, int $userId): Comment
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("UPDATE HL_Comment SET c_text = ?, c_date = ?, c_p_id = ?, c_u_id = ? WHERE c_id = ?");
        $stmt->bind_param("ssiii", $text, $date, $postId, $userId, $id);
        $stmt->execute();
        return new Comment($id, $text, $date, $postId, $userId);
    }

    public static function delete(int $id): void
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Comment WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function getById(int $id): Comment
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Comment WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return new Comment($row['c_id'], $row['c_text'], $row['c_date'], $row['c_p_id'], $row['c_u_id']);
    }

    public function jsonSerialize(): mixed
    {
        $comment = [
            'id' => $this->id,
            'text' => $this->text,
            'date' => $this->date->format('Y-m-d'),
            'postId' => $this->postId,
            'userId' => $this->userId
        ];

        if(isset($this->user)) {
            $comment['user'] = $this->user;
        }

        return $comment;

    }
}