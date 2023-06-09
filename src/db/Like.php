<?php

namespace db;

use JsonSerializable;

class Like implements JsonSerializable
{
    private int $id;
    private int $postId;
    private int $userId;

    public function __construct(int $id, int $postId, int $userId)
    {
        $this->id = $id;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public static function getLikeFromRow(array $row): Like
    {
        return new Like($row['l_id'], $row['l_p_id'], $row['l_u_id']);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getPost(): Post
    {
        return Post::getById($this->postId);
    }

    public function getUser(): User
    {
        return User::getById($this->userId);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public static function create(int $postId, int $userId): Like
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("INSERT INTO HL_Like (l_p_id, l_u_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        return new Like($db->getConnection()->insert_id, $postId, $userId);
    }

    public static function delete(int $id): void
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Like WHERE l_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public static function deleteByUserAndPost(int $postId, int $userId): void
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("DELETE FROM HL_Like WHERE l_p_id = ? AND l_u_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
    }


    public static function update(int $id, int $postId, int $userId): Like
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("UPDATE HL_Like SET l_p_id = ?, l_u_id = ? WHERE l_id = ?");
        $stmt->bind_param("iii", $postId, $userId, $id);
        $stmt->execute();
        return new Like($id, $postId, $userId);
    }

    public static function exists(int $postId, int $userId): bool
    {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM HL_Like WHERE l_p_id = ? AND l_u_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "postId" => $this->postId,
            "userId" => $this->userId
        ];
    }
}