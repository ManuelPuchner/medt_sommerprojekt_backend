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

class Comment
{
    private $id;
    private $text;
    private $date;
    private $postId;
    private $userId;

    public function __construct($id, $text, $date, $postId, $userId)
    {
        $this->id = $id;
        $this->text = $text;
        $this->date = $date;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getPost()
    {
        return Post::getById($this->postId);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUser()
    {
        return User::getById($this->userId);
    }

    public static function create($text, $date, $postId, $userId)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_Comment (c_text, c_date, c_p_id, c_u_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $text, $date, $postId, $userId);
        $stmt->execute();
        return new Comment($db->getConnection()->insert_id, $text, $date, $postId, $userId);
    }

    public static function update($id, $text, $date, $postId, $userId)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("UPDATE HL_Comment SET c_text = ?, c_date = ?, c_p_id = ?, c_u_id = ? WHERE c_id = ?");
        $stmt->bind_param("ssiii", $text, $date, $postId, $userId, $id);
        $stmt->execute();
        return new Comment($id, $text, $date, $postId, $userId);
    }

    public static function delete($id)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Comment WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function getById($id)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Comment WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return new Comment($row['c_id'], $row['c_text'], $row['c_date'], $row['c_p_id'], $row['c_u_id']);
    }
}