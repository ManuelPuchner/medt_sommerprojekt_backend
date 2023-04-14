<?php

//create table HL_Post (
//                         p_id int not null auto_increment,
//                         p_image varchar(255) not null,
//                         p_description varchar(255) not null,
//                         p_date date not null,
//                         p_u_id int not null,
//                         constraint p_PK primary key (p_id),
//                         constraint p_u_FK foreign key (p_u_id) references HL_User(u_id)
//);

class Post
{
    private $id;
    private $image;
    private $description;
    private $date;
    private $userId;

    public function __construct($id, $image, $description, $date, $userId)
    {
        $this->id = $id;
        $this->image = $image;
        $this->description = $description;
        $this->date = $date;
        $this->userId = $userId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUser()
    {
        return User::getById($this->userId);
    }

    public static function create($image, $description, $date, $userId)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_Post (p_image, p_description, p_date, p_u_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $image, $description, $date, $userId);
        $stmt->execute();
        return new Post($db->getConnection()->insert_id, $image, $description, $date, $userId);
    }

    public static function delete($id)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Post WHERE p_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function update($id, $image, $description, $date, $userId)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("UPDATE HL_Post SET p_image = ?, p_description = ?, p_date = ?, p_u_id = ? WHERE p_id = ?");
        $stmt->bind_param("sssii", $image, $description, $date, $userId, $id);
        $stmt->execute();
    }

    public static function getById($id)
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Post WHERE p_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return new Post($row['p_id'], $row['p_image'], $row['p_description'], $row['p_date'], $row['p_u_id']);
    }

    public static function getAll()
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Post");
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = array();
        while($row = $result->fetch_assoc())
        {
            $posts[] = new Post($row['p_id'], $row['p_image'], $row['p_description'], $row['p_date'], $row['p_u_id']);
        }
        return $posts;
    }
}