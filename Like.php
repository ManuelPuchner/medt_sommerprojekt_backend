<?php

//create table HL_Like (
//                         l_id int not null auto_increment,
//                         l_p_id int not null,
//                         l_u_id int not null,
//                         constraint l_PK primary key (l_id),
//                         constraint l_p_FK foreign key (l_p_id) references HL_Post(p_id),
//                         constraint l_u_FK foreign key (l_u_id) references HL_User(u_id)
//);

class Like{
    private $id;
    private $postId;
    private $userId;

    public function __construct($id, $postId, $userId){
        $this->id = $id;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public function getId(){
        return $this->id;
    }

    public function getPostId(){
        return $this->postId;
    }

    public function getPost(){
        return Post::getById($this->postId);
    }

    public function getUser(){
        return User::getById($this->userId);
    }

    public function getUserId(){
        return $this->userId;
    }

    public static function create($postId, $userId){
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_Like (l_p_id, l_u_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        return new Like($db->getConnection()->insert_id, $postId, $userId);
    }

    public static function delete($id){
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Like WHERE l_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }


    public static function update($id, $postId, $userId){
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("UPDATE HL_Like SET l_p_id = ?, l_u_id = ? WHERE l_id = ?");
        $stmt->bind_param("iii", $postId, $userId, $id);
        $stmt->execute();
    }
}